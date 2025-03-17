<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Consumer;
use App\Models\Professional;
use App\Models\ConsumerFilter;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Favourite;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PDF;
use Redirect;
use Validator;
use Mail;
use App\Models\Notification;

class ConsumerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consumers = Consumer::orderBy('created_at', 'DESC')->where('is_deleted', 0)->get();
        return view('admin.consumers.index',
            ['title' => 'Consumers', 'consumers' => $consumers]
        );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deletedConsumers()
    {
        $consumers = Consumer::where('is_deleted', '1')->orderBy('created_at', 'DESC')->get();

        if (count($consumers) == 0) {
            return redirect()->action('ConsumerController@index')->with('error', 'No Deleted Consumer.');
        }

        return view('admin.consumers.deleted',
            ['title' => 'Consumers', 'consumers' => $consumers]
        );
    }

    public function activateConsumer(Request $request)
    {
        $consumer = Consumer::find($request->consumer_id);
        if ($consumer == null) {
            return redirect()->action('ConsumerController@index')->with('error', 'No Record Found.');
        }
        $consumer->update(['is_deleted' => 0]);
        return response()->json(['status' => 1, 'message' => 'Record Updated Successfully.']);
    }


    /**
     * Show the form for creating a new resource for type consumer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.consumers.create', ['title' => 'Consumers']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // consumer info
            'full_name' => 'required|string|max:20',
            'email' => 'required|string|email|max:100|unique:consumers',
            // 'phone' => array(
            //     'required',
            //     'regex:/^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/'),
            'phone' =>'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        }

        $consumerData = [
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
        ];
        $consumer = Consumer::create($consumerData);

        // store consumer profile image
        $consumerImageDirectory = 'consumerImages';
        if ($request->hasFile('profile_image')) {

            $fileName = $request->file('profile_image')->getClientOriginalName();

            if (!Storage::exists($consumerImageDirectory)) {
                Storage::makeDirectory($consumerImageDirectory);
            }

            $profileImageUrl = Storage::putFile($consumerImageDirectory, new File($request->file('profile_image')));
            $consumer->update(['profile_image' => $profileImageUrl]);
        }

        $addressData = [
            'consumer_id' => $consumer->id,
            'professional_id' => Null,
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'country' => 'Pakistan',
            'address_type' => '0',
        ];

        Address::create($addressData);

        $shippingAddressData = [
            'consumer_id' => $consumer->id,
            'professional_id' => Null,
            'address' => $request->input('shipping_address'),
            'city' => $request->input('shipping_city'),
            'counry' => 'Pakistan',
            'address_type' => '1',
        ];

        Address::create($shippingAddressData);

        return redirect()->route('listConsumers')->with('success', 'Record Added Successfully.');
    }

    public function search(Request $request)
    {

        $consumers = ConsumerFilter::apply($request);
        return view('admin.consumers.index',
            ['title' => 'Consumers', 'startDate' => $request->start_date, 'endDate' => $request->end_date, 'consumers' => $consumers]
        );
    }

    public function exportConsumerAsPDF(Request $request)
    {
        $consumers = ConsumerFilter::apply($request);
        $pdf = PDF::loadView('admin.consumers.exportConsumers', ['title' => 'Consumers', 'consumers' => $consumers]);
        return $pdf->download('Consumers.pdf');
    }
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $consumer = Consumer::with('addresses')->where('id', $id)->first();
        if ($consumer == null) {
            return redirect()->route('listConsumers')->with('error', 'Invalid Consumer ID');
        }
        $address = $consumer->addresses->where('address_type', '0')->first();
        $shippingAddress = $consumer->addresses->where('address_type', '1')->first();

        return view('admin.consumers.detail', ['title' => 'Consumer Detail', 'consumer' => $consumer, 'shippingAddress' => $shippingAddress, 'address' => $address]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $consumer = Consumer::find($id);
        if ($consumer == null) {
            return redirect()->action('ConsumerController@index')->with('error', 'No Record Found.');
        }
        
        $billingAddress = $consumer->addresses()->where('address_type', '0')->first();
        $shippingAddress = $consumer->addresses()->where('address_type', '1')->get();
        // dd($billingAddress, $shippingAddress);

        return view('admin.consumers.edit', [
            'title' => 'Consumers',
            'consumer' => $consumer,
            'billingAddress' => $billingAddress, 
            'shippingAddresses' => $shippingAddress, 
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        // dd($request->all());
        $consumer = Consumer::find($request->consumer_id);
       
        if ($consumer == null) {
            return redirect()->action('ConsumerController@index')->with('error', 'No Record Found.');
        }
        
        $rules = [
            // consumer info
            'full_name' => 'required|string|max:150',
            'phone' =>'required'
        ];

        if($request->user_type == '1') {
           $rules =[
                'full_name' => 'required|string|max:50',
                'phone' =>'required',
                'company_name' => 'required|string|max:150',
                'company_address' => 'required|string|max:150',
            ];
        }

        if (!empty($request->password) && !empty($request->password_confirmation)) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }
       
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        }

        
        if($request->user_type == '1') {
            if (Professional::where('email', $consumer->email)->count() > 0) {
                return redirect()->back()->with('error', 'Email Already in use');
            }
            $orderIds = Order::where('consumer_id', $consumer->id)->pluck('id')->toArray();

            if(count($orderIds) > 0){
                $orderDetails = OrderDetail::whereIn('order_id', $orderIds)->get();

                if(count($orderDetails) > 0) {
                    OrderDetail::whereIn('order_id', $orderIds)->delete();
                }
                
                Order::where('consumer_id', $consumer->id)->delete();
                
                $notificationIds = Notification::where('consumer_id', $consumer->id)->pluck('id')->toArray();		
            		
                if(count($notificationIds) > 0){		
                  		
                    Notification::whereIn('id', $notificationIds)->delete();		
                }
            }

            $professionalData = [
                'full_name' => $request->input('full_name'),
                'email' => $consumer->email,
                'phone' => $request->input('phone'),
                'password' => $consumer->password,
                'company_name' => $request->input('company_name'),
                'company_address' => $request->input('company_address'),
                'is_authorized' => $request->input('is_authorized'),
            ];

            if (!empty($request->company_website)) {
                $professionalData['company_website'] = $request->get('company_website');
            }

            if ($request->discount_allowed == "1") {
                $professionalData['discount_allowed'] = $request->get('discount_allowed');
                $professionalData['discount_value'] = $request->get('discount_value');
                $professionalData['min_order_value'] = $request->get('min_order_value');
            }

            $professional = Professional::create($professionalData);

            $professionalImageDirectory = 'professionalImages';
            if ($request->hasFile('profile_image')) {
    
                $fileName = $request->file('profile_image')->getClientOriginalName();
    
                if (!Storage::exists($professionalImageDirectory)) {
                    Storage::makeDirectory($professionalImageDirectory);
                }
                if (isset($professional->porfile_image)) {
                    Storage::delete($professional->profile_image);
                }
    
                $profileImageUrl = Storage::putFile($professionalImageDirectory, new File($request->file('profile_image')));
                $professional->update(['profile_image' => $profileImageUrl]);
            
            }else  if($consumer->profile_image != null && Storage::exists($consumer->profile_image)) {

                $newpath = str_replace('consumerImages', 'professionalImages', $consumer->profile_image);
                Storage::move($consumer->profile_image, $newpath);
                $professional->update(['profile_image' => $newpath]);

            }


            if (Address::where('consumer_id', $consumer->id)->count() > 0) {
                Address::where('consumer_id', $consumer->id)->update(['professional_id' => $professional->id, 'consumer_id' => NULL]);

            }else{
                
                if (!empty($request->get('billing_address')) && !empty($request->get('billing_city')) && !empty($request->get('billing_country'))) {
                    
                    Address::create([
                        'address' => $request->billing_address,
                        'city' => $request->billing_city,
                        'country' => $request->billing_country,
                        'professional_id' => $professional->id,
                        'address_type' => '0'
                    ]);
                }
    
                if(!empty($request->get('shipping_address')) && !empty($request->get('shipping_city')) && !empty($request->get('shipping_country'))){
                    Address::create([
                        'address' => $request->shipping_address,
                        'city' => $request->shipping_city,
                        'country' => $request->shipping_country,
                        'professional_id' => $professional->id,
                        'address_type' => '1'
                    ]);
                }
            }

            // if (Address::where('consumer_id', $consumer->id)->count() > 0) {
            //     Address::where('consumer_id', $consumer->id)->delete();
            // }
            if (Favourite::where('consumer_id', $consumer->id)->count() > 0) {
                Favourite::where('consumer_id', $consumer->id)->update(['professional_id' => $professional->id, 'consumer_id' => NULL]);
            }

            Consumer::where('id', $consumer->id)->delete();

            return redirect()->action('ProfessionalController@index')->with('success', 'Record Updated Successfully.');
        } else {

            $consumerData = [
                'full_name' => $request->input('full_name'),
                'phone' => $request->input('phone'),
                'is_active' => $request->input('is_active'),
                'last_login' => Carbon::now()->toDateTimeString()
            ];
    
            if (!empty($request->password)) {
                $consumerData['password'] = Hash::make($request->password);
            }
    
            // store consumer profile image
            $consumerImageDirectory = 'consumerImages';
            if ($request->hasFile('profile_image')) {
    
                $fileName = $request->file('profile_image')->getClientOriginalName();
    
                if (!Storage::exists($consumerImageDirectory)) {
                    Storage::makeDirectory($consumerImageDirectory);
                }
                if (isset($consumer->porfile_image)) {
                    Storage::delete($consumer->profile_image);
                }
    
                $profileImageUrl = Storage::putFile($consumerImageDirectory, new File($request->file('profile_image')));
                $consumer->update(['profile_image' => $profileImageUrl]);
            } 
    
            $consumer->update($consumerData);
            
            if (!empty($request->billing_id) && !empty($request->get('billing_address')) && !empty($request->get('billing_city')) && !empty($request->get('billing_country'))) {
                
                Address::where('id', $request->billing_id)->update(['address' => $request->billing_address, 'city' => $request->billing_city, 'country' => $request->billing_country]);
            
            }else if (!empty($request->get('billing_address')) && !empty($request->get('billing_city')) && !empty($request->get('billing_country'))) {
                Address::create([
                    'address' => $request->billing_address,
                    'city' => $request->billing_city,
                    'country' => $request->billing_country,
                    'consumer_id' => $consumer->id,
                    'address_type' => '0'
                ]);
            }

            if ($request->shipping_address_count > 0) {

                for($i = 1; $i <= $request->shipping_address_count;  $i++) {
                
                    if(!empty($request->get('shipping_id'.$i)) && !empty($request->get('shipping_city'.$i)) && !empty($request->get('shipping_country'.$i))){
                        Address::where('id', $request->get('shipping_id'.$i))->update([
                            'address' => $request->get('shipping_address'.$i),
                            'city' => $request->get('shipping_city'.$i),
                            'country' => $request->get('shipping_country'.$i)
                        ]);
                    }
                }
            }else if(!empty($request->get('shipping_address')) && !empty($request->get('shipping_city')) && !empty($request->get('shipping_country'))){
                Address::create([
                    'address' => $request->shipping_address,
                    'city' => $request->shipping_city,
                    'country' => $request->shipping_country,
                    'consumer_id' => $consumer->id,
                    'address_type' => '1'
                ]);
            }

            // if (!empty($request->shipping_id)) {
            //     Address::where('id', $request->shipping_id)->update(['address' => $request->shipping_address, 'city' => $request->shipping_city, 'country' => $request->shipping_country]);
            
            // }else  if (!empty($request->shipping_address)) {
            //     Address::create([
            //         'address' => $request->shipping_address,
            //         'city' => $request->shipping_city,
            //         'country' => $request->shipping_country,
            //         'consumer_id' => $consumer->id,
            //         'address_type' => '1'
            //     ]);
            // }

            return redirect()->action('ConsumerController@index')->with('success', 'Record Updated Successfully.');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $consumer = Consumer::find($request->consumer_id);
        if ($consumer == null) {
            return redirect()->action('ConsumerController@index')->with('error', 'No Record Found.');
        }
        $consumer->update(['is_deleted' => 1, 'last_login' => Carbon::now()->toDateTimeString()]);
        return response()->json(['status' => 1, 'message' => 'Record Deleted Successfully.']);
    }

}
