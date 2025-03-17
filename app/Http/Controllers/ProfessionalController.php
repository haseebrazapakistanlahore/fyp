<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Professional;
use App\Models\Consumer;
use App\Models\Favourite;
use App\Models\ProfessionalFilter;
use App\Models\Order;
use App\Models\OrderDetail;
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

class ProfessionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $professionals = Professional::where('is_deleted', 0)->orderBy('created_at', 'DESC')->get();
        return view('admin.professionals.index',
            ['title' => 'Professionals', 'professionals' => $professionals]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deletedProfessionals()
    {
        $professionals = Professional::where('is_deleted', 1)->orderBy('created_at', 'DESC')->get();
        if (count($professionals) == 0) {
            return redirect()->action('ProfessionalController@index')->with('error', 'No Deleted Professionals.');
        }
        return view('admin.professionals.deleted',
            ['title' => 'Professionals', 'professionals' => $professionals]
        );
    }

    public function activateProfessional(Request $request)
    {
        $professional = Professional::find($request->professional_id);
        
        if ($professional == null) {
            return redirect()->action('ProfessionalController@index')->with('error', 'No Record Found.');
        }
        $professional->update(['is_deleted' => 0]);
        return response()->json(['status' => 1, 'message' => 'Record Updated Successfully.']);
    }


    /**
     * Show the form for creating a new resource for type professional.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.professionals.create', ['title' => 'Professionals']);
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
            // professional info
            'full_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:100|unique:professionals',
            // 'phone' => array(
            //     'required',
            //     'regex:/^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/'),
            'phone' =>'required',
            'password' => 'required|string|min:6|confirmed',
            'company_name' => 'required|string|max:150',
            'company_address' => 'required|string|max:150',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        }

        $professionalData = [
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
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

        // store professional profile image
        $professionalImageDirectory = 'professionalImages';
        if ($request->hasFile('profile_image')) {

            $fileName = $request->file('profile_image')->getClientOriginalName();

            if (!Storage::exists($professionalImageDirectory)) {
                Storage::makeDirectory($professionalImageDirectory);
            }

            $profileImageUrl = Storage::putFile($professionalImageDirectory, new File($request->file('profile_image')));
            $professional->update(['profile_image' => $profileImageUrl]);
        }

        try {
            $data = [
                'full_name' => $request->input('full_name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ];

            $email = $professional->email;
            Mail::send('mail.professionalEmail', $data, function ($message) use ($email) {
                $message->from('admin@postquam.com', 'Postquam Admin');
                $message->to($email)->subject('Postquam New Registeration');
            });

        } catch (\Exception $e) {
            return redirect()->route('listProfessionals')->with('success', 'Record Added Successfully. Unable to send email.');
        }

        return redirect()->route('listProfessionals')->with('success', 'Record Added Successfully.');
    }

    public function search(Request $request)
    {

        $professionals = ProfessionalFilter::apply($request);
        return view('admin.professionals.index',
            ['title' => 'Professionals', 'startDate' => $request->start_date, 'endDate' => $request->end_date, 'professionals' => $professionals]
        );
    }

    public function exportProfessionalAsPDF(Request $request)
    {
        $professionals = ProfessionalFilter::apply($request);
        $pdf = PDF::loadView('admin.professionals.exportProfessionals', ['title' => 'Professionals', 'professionals' => $professionals]);
        return $pdf->download('Professionals.pdf');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $professional = Professional::with('addresses')->where('id', $id)->first();
        if ($professional == null) {
            return redirect()->route('listProfessionals')->with('error', 'Invalid Professional ID');
        }
        $address = $professional->addresses->where('address_type', '0')->first();
        $shippingAddress = $professional->addresses->where('address_type', '1')->first();

        return view('admin.professionals.detail', ['title' => 'Professional Detail', 'professional' => $professional, 'shippingAddress' => $shippingAddress, 'address' => $address]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $professional = Professional::with('addresses')->where('id', $id)->first();
        if ($professional == null) {
            return redirect()->action('ProfessionalController@index')->with('error', 'No Record Found.');
        }
        
        $billingAddress = $professional->addresses()->where('address_type', '0')->first();
        $shippingAddress = $professional->addresses()->where('address_type', '1')->get();

        return view('admin.professionals.edit', [
            'title' => 'Professionals',
            'professional' => $professional,
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
        $professional = Professional::find($request->professional_id);
        if ($professional == null) {
            return redirect()->action('ProfessionalController@index')->with('error', 'No Record Found.');
        }
        
        $rules = [
            'full_name' => 'required|string|max:150',
            'phone' =>'required'

        ];

        if (!empty($request->password) && !empty($request->password_confirmation)) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }
       
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        }

        if($request->user_type == '0') {
            if(Consumer::where('email', $professional->email)->count() > 0){
                return Redirect::back()->with('error', 'Email already exist as consumer');
            }
            
            $orderIds = Order::where('professional_id', $professional->id)->pluck('id')->toArray();
            
            if(count($orderIds) > 0){
                $orderDetails = OrderDetail::whereIn('order_id', $orderIds)->get();

                if(count($orderDetails) > 0) {
                    OrderDetail::whereIn('order_id', $orderIds)->delete();
                }
                
                Order::where('professional_id', $professional->id)->delete();
                
                $notificationIds = Notification::where('professional_id', $professional->id)->pluck('id')->toArray();		
            		
                if(count($notificationIds) > 0){		
                  		
                    Notification::whereIn('id', $notificationIds)->delete();		
                }
            }

            $consumerData = [
                'full_name' => $request->input('full_name'),
                'phone' => $request->input('phone'),
                'is_active' => $request->input('is_active'),
                'email' => $professional->email,
                'password' => $professional->password,
            ];
    
            if (!empty($request->password)) {
                $consumerData['password'] = Hash::make($request->password);
            }
            
            $consumer = Consumer::create($consumerData);
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
            }else if($professional->profile_image != null) {

                $newpath = str_replace('professionalImages', 'consumerImages', $professional->profile_image);
                Storage::move($professional->profile_image, $newpath);
                $consumer->update(['profile_image' => $newpath]);     
            }

            if (Address::where('professional_id', $professional->id)->count() > 0) {
                Address::where('professional_id', $professional->id)->update(['consumer_id' => $consumer->id, 'professional_id' => NULL]);

            }else{
                
               if (!empty($request->get('billing_address')) && !empty($request->get('billing_city')) && !empty($request->get('billing_country'))) {
                    Address::create([
                        'address' => $request->billing_address,
                        'city' => $request->billing_city,
                        'country' => $request->billing_country,
                        'consumer_id' => $consumer->id,
                        'address_type' => '0'
                    ]);
                }
               
                 if(!empty($request->get('shipping_address')) && !empty($request->get('shipping_city')) && !empty($request->get('shipping_country'))){
                    Address::create([
                        'address' => $request->shipping_address,
                        'city' => $request->shipping_city,
                        'country' => $request->shipping_country,
                        'consumer_id' => $consumer->id,
                        'address_type' => '1'
                    ]);
                }
            }

            if (Favourite::where('professional_id', $professional->id)->count() > 0) {
                Favourite::where('professional_id', $professional->id)->update(['consumer_id' => $consumer->id, 'professional_id' => NULL]);
            }

           
            
            Professional::where('id', $professional->id)->delete();
            return redirect()->action('ConsumerController@index')->with('success', 'Record Updated Successfully.');

        } else {


            $professionalData = [
                'full_name' => $request->input('full_name'),
                'phone' => $request->input('phone'),
                'is_active' => $request->input('is_active'),
                'last_login' => Carbon::now()->toDateTimeString()
            ];

            // company info
            $professionalData['company_name'] = $request->input('company_name');
            $professionalData['company_address'] = $request->input('company_address');
            $professionalData['is_authorized'] = $request->input('is_authorized');
            
            if($request->input('is_authorized') == '0'){
                $professionalData['token'] = Null;
            }
            if (!empty($request->company_website)) {
                $professionalData['company_website'] = $request->get('company_website');
            }
            
            if (!empty($request->password)) {
                $professionalData['password'] = Hash::make($request->password);
            }
    
            if ($request->discount_allowed == "1") {
                $professionalData['discount_allowed'] = 1;
                $professionalData['discount_value'] = $request->get('discount_value');
                $professionalData['min_order_value'] = $request->get('min_order_value');
                
                if($professional->discount_allowed != $request->discount_allowed){		
                    $noti = [];		
                    $noti['professional_id'] = $professional->id;		
                    $noti['content'] = $professional->full_name.' you have been allowed discount of '.$request->get('discount_value'). '% on order greater than '.$request->get('min_order_value');		
                    // dd($noti);		
                    Notification::create($noti);		
                    if (isset($professional->token)) {		
                        app('App\Http\Controllers\FCMController')->sendNotification('Discount allowed by Postquam Admin.', $professional->full_name.' you have been allowed discount of '.$request->get('discount_value'). '% on order greater than '.$request->get('min_order_value'), [], $professional->token);		
            		
                    }		
                }
    
            }else{
                $professionalData['discount_allowed'] = 0;
                $professionalData['discount_value'] = Null;
                $professionalData['min_order_value'] = Null;
            }
    
            // if ($professional->email_sent == 0 && $professional->is_authorized == 0 && $request->input('is_authorized') == '1') {
            //     try {
            //         $data = [
            //             'full_name' => $request->input('full_name'),
            //         ];
    
            //         $email = $professional->email;
            //         Mail::send('mail.authorizationEmail', $data, function ($message) use ($email) {
            //             $message->from('admin@postquam.com', 'Postquam Admin');
            //             $message->to($email)->subject('Congratulations Your Account has been Approved.');
            //         });
            //         $professionalData['email_sent'] = 1;
    
            //     } catch (\Exception $e) {
            //         $response['message'] = 'Registered Successfully.';
            //         return response()->json($response);
            //     }
            // }
            
            // store professional profile image
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
            } 
    
            $professional->update($professionalData);
    
            if (!empty($request->billing_id) && !empty($request->get('billing_address')) && !empty($request->get('billing_city')) && !empty($request->get('billing_country'))) {
                
                Address::where('id', $request->billing_id)->update([
                    'address' => $request->billing_address,
                    'city' => $request->billing_city,
                    'country' => $request->billing_country
                ]);
            
            }else if (!empty($request->get('billing_address')) && !empty($request->get('billing_city')) && !empty($request->get('billing_country'))) {
                Address::create([
                    'address' => $request->billing_address,
                    'city' => $request->billing_city,
                    'country' => $request->billing_country,
                    'professional_id' => $professional->id,
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
                    'professional_id' => $professional->id,
                    'address_type' => '1'
                ]);
            }

            return redirect()->action('ProfessionalController@index')->with('success', 'Record Updated Successfully.');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $professional = Professional::find($request->professional_id);
        if ($professional == null) {
            return redirect()->action('ProfessionalController@index')->with('error', 'No Record Found.');
        }
        $professional->update(['is_deleted' => 1, 'last_login' => Carbon::now()->toDateTimeString()]);
        return response()->json(['status' => 1, 'message' => 'Record Deleted Successfully.']);
    }

}
