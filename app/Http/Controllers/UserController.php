<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use App\Models\UserFilter;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use PDF;
use Redirect;
use Validator;
use Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::all();
        $users = User::orderBy('created_at', 'DESC')->get();
        return view('admin.users.index',
            ['title' => 'Users', 'users' => $users]
        );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deletedUsers()
    {
        $users = User::orderBy('created_at', 'DESC')->get();

        return view('admin.users.deleted',
            ['title' => 'Users', 'users' => $users]
        );
    }

    public function activateUser(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user == null) {
            return redirect()->action('UserController@index')->with('error', 'No Record Found.');
        }
        $user->update(['is_active' => 1]);
        return response()->json(['status' => 1, 'message' => 'Record Updated Successfully.']);
    }

    // public function listConsumers()
    // {
    //     $users = User::orderBy('created_at', 'DESC')->get();
    //     return view('admin.users.listConsumers',
    //         ['title' => 'Users', 'users' => $users]
    //     );
    // }

    // public function listProfessionals()
    // {
    //     $users = User::orderBy('created_at', 'DESC')->get();
    //     return view('admin.users.listProfessionals',
    //         ['title' => 'Users', 'users' => $users]
    //     );
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function createProfessional()
    // {
    //     return view('admin.users.createProfessional', ['title' => 'Users']);
    // }

    /**
     * Show the form for creating a new resource for type consumer.
     *
     * @return \Illuminate\Http\Response
     */
    // public function createConsumer()
    // {
    //     return view('admin.users.createConsumer', ['title' => 'Users']);
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function storeProfessional(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         // user info
    //         'full_name' => 'required|string|max:150',
    //         'email' => 'required|string|email|max:150|unique:users',
    //         // 'phone' => array(
    //         //     'required',
    //         //     'regex:/^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/'),
    //         'phone' =>'required',
    //         'password' => 'required|string|min:6|confirmed',

    //         // company validations
    //         'company_name' => 'required|string|max:150',
    //         'company_address' => 'required|string|max:150',
    //     ]);
 
    //     if ($validator->fails()) {
    //         return Redirect::back()->withErrors($validator)->withInput(Input::all());
    //     }

    //     $userData = [
    //         'full_name' => $request->input('full_name'),
    //         'email' => $request->input('email'),
    //         'phone' => $request->input('phone'),
    //         'password' => Hash::make($request->input('password')),
    //         'user_type' => '1',
    //         'company_name' => $request->input('company_name'),
    //         'company_address' => $request->input('company_address'),
    //         'is_authorized' => $request->input('is_authorized'),
    //     ];

    //     if (!empty($request->company_website)) {
    //         $userData['company_website'] = $request->get('company_website');
    //     }
       
    //     if ($request->discount_available == "1") {
    //         $userData['discount_allowed'] = $request->get('discount_allowed');
    //         $userData['discount_value'] = $request->get('discount_value');
    //         $userData['min_order_value'] = $request->get('min_order_value');
    //     }

    //     $user = User::create($userData);

    //     // store user profile image
    //     $userImageDirectory = 'userImages';
    //     if ($request->hasFile('profile_image')) {

    //         $fileName = $request->file('profile_image')->getClientOriginalName();

    //         if (!Storage::exists($userImageDirectory)) {
    //             Storage::makeDirectory($userImageDirectory);
    //         }

    //         $profileImageUrl = Storage::putFile($userImageDirectory, new File($request->file('profile_image')));
    //         $user->update(['profile_image' => $profileImageUrl]);
    //     }

    //     try {
    //         $data = [
    //             'full_name' => $request->input('full_name'),
    //             'email' => $request->input('email'),
    //             'password' => $request->input('password'),
    //         ];

    //         $email = $user->email;
    //         Mail::send('mail.professionalEmail', $data, function ($message) use ($email) {
    //             $message->from('admin@postquam.com', 'Postquam Admin');
    //             $message->to($email)->subject('Postquam New Registeration');
    //         });

    //     } catch (\Exception $e) {
    //         return redirect()->route('listProfessionals')->with('success', 'Record Added Successfully. Unable to send email.');
    //     }
    //     return redirect()->route('listProfessionals')->with('success', 'Record Added Successfully.');
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function storeConsumer(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         // user info
    //         'user_name' => 'required|string|max:50|unique:users',
    //         'first_name' => 'required|string|max:20',
    //         'last_name' => 'required|string|max:20',
    //         'email' => 'required|string|email|max:100|unique:users',
    //         // 'phone' => array(
    //         //     'required',
    //         //     'regex:/^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/'),
    //         'phone' =>'required',
    //         'password' => 'required|string|min:6|confirmed',
    //         // address
    //         'city' => 'required|string|max:50',
    //         'state' => 'required|string|max:50',
    //         'address' => 'required|string|max:191',
    //         // shipping address
    //         'shipping_city' => 'required|string|max:50',
    //         'shipping_state' => 'required|string|max:50',
    //         'shipping_address' => 'required|string|max:191',
    //         'shipping_zip_code' => 'required|string|max:10',
    //     ]);
    //     if ($validator->fails()) {
    //         return Redirect::back()->withErrors($validator)->withInput(Input::all());
    //     }

    //     $userData = [
    //         'user_name' => strtolower($request->input('user_name')),
    //         'first_name' => $request->input('first_name'),
    //         'last_name' => $request->input('last_name'),
    //         'email' => $request->input('email'),
    //         'phone' => $request->input('phone'),
    //         'password' => Hash::make($request->input('password')),
    //         'user_type' => '0',
    //     ];
    //     $user = User::create($userData);

    //     // store user profile image
    //     $userImageDirectory = 'userImages';
    //     if ($request->hasFile('profile_image')) {

    //         $fileName = $request->file('profile_image')->getClientOriginalName();

    //         if (!Storage::exists($userImageDirectory)) {
    //             Storage::makeDirectory($userImageDirectory);
    //         }

    //         $profileImageUrl = Storage::putFile($userImageDirectory, new File($request->file('profile_image')));
    //         $user->update(['profile_image' => $profileImageUrl]);
    //     }

    //     $addressData = [
    //         'user_id' => $user->id,
    //         'address' => $request->input('address'),
    //         'city' => $request->input('city'),
    //         'state' => $request->input('state'),
    //         'zip_code' => $request->input('zip_code'),
    //         'counry' => 'Pakistan',
    //         'address_type' => '0',
    //     ];

    //     Address::create($addressData);

    //     $shippingAddressData = [
    //         'user_id' => $user->id,
    //         'address' => $request->input('shipping_address'),
    //         'city' => $request->input('shipping_city'),
    //         'state' => $request->input('shipping_state'),
    //         'zip_code' => $request->input('shipping_zip_code'),
    //         'counry' => 'Pakistan',
    //         'address_type' => '1',
    //     ];

    //     Address::create($shippingAddressData);

    //     return redirect()->route('listConsumers')->with('success', 'Record Added Successfully.');
    // }

    public function search(Request $request)
    {

        $users = UserFilter::apply($request);
        return view('admin.users.index',
            ['title' => 'Users', 'startDate' => $request->start_date, 'endDate' => $request->end_date, 'users' => $users]
        );
    }

    public function exportUserAsPDF(Request $request)
    {
        $users = UserFilter::apply($request);
        $pdf = PDF::loadView('admin.users.exportUsers', ['title' => 'Users', 'users' => $users]);
        return $pdf->download('Users.pdf');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('addresses')->where('id', $id)->first();
        if ($user == null) {
            return redirect()->route('listUsers')->with('error', 'Invalid user id');
        }
        $address = $user->addresses->where('address_type', '0')->first();
        $shippingAddress = $user->addresses->where('address_type', '1')->first();

        return view('admin.users.detail2', ['title' => 'User Detail', 'user' => $user, 'shippingAddress' => $shippingAddress, 'address' => $address]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with('addresses')->where('id', $id)->first();
        if ($user == null) {
            return redirect()->action('UserController@index')->with('error', 'No Record Found.');
        }
        // $billingAddress = $user->addresses()->where('address_type', '0')->first();
        // $shippingAddress = $user->addresses()->where('address_type', '1')->first();
        
        $billingAddress = $user->addresses()->where('address_type', '0')->first();
        $shippingAddress = $user->addresses()->where('address_type', '1')->orderBy('created_at', 'DESC')->first();

        return view('admin.users.edit', [
            'title' => 'Users',
            'user' => $user,
            'billingAddress' => $billingAddress, 
            'shippingAddress' => $shippingAddress, 
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        // dd($request->all());
        $user = User::find($request->user_id);
        if ($user == null) {
            return redirect()->action('UserController@index')->with('error', 'No Record Found.');
        }
        // $userType = $user->user_type;
        
        $rules = [
            // user info
            'full_name' => 'required|string|max:150',
            // 'phone' => array(
            //     'required',
            //     'regex:/^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/'),
            'phone' =>'required'

        ];

        if (!empty($request->password) && !empty($request->password_confirmation)) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        // if ($request->get('user_type') == 1) {
        //     // company info
        //     $rules['company_name'] = 'required|string|max:150';
        //     $rules['company_address'] = 'required|string|max:150';
        // }
       
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        }

        $userData = [
            'full_name' => $request->input('full_name'),
            // 'first_name' => $request->input('first_name'),
            // 'last_name' => $request->input('last_name'),
            'phone' => $request->input('phone'),
            'is_active' => $request->input('is_active'),
        ];
        // if($userType != $request->user_type) {
        //     $orderIds = Order::where('user_id', $user->id)->pluck('id')->toArray();
        //     if(count($orderIds) > 0){
        //         $orderDetails = OrderDetail::whereIn('order_id', $orderIds)->get();
                
        //         if(count($orderDetails) > 0) {
        //             OrderDetail::whereIn('order_id', $orderIds)->delete();
        //         }
                
        //         Order::where('user_id', $user->id)->delete();
        //     }
        //     $userData['user_type'] = $request->get('user_type');
        // }

        // if ($request->get('user_type') == 1) {
        //     // company info
        //     $userData['company_name'] = $request->input('company_name');
        //     $userData['company_address'] = $request->input('company_address');
        //     $userData['is_authorized'] = $request->input('is_authorized');
        //     if (!empty($request->company_website)) {
        //         $userData['company_website'] = $request->get('company_website');
        //     }
        // }else{
        //     $userData['company_name'] = Null;
        //     $userData['company_address'] = Null;
        //     $userData['is_authorized'] = 0;
        //     $userData['company_website'] = Null;
            
        // }
        
        if (!empty($request->password)) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->discount_allowed == "1") {
            $userData['discount_allowed'] = 1;
            $userData['discount_value'] = $request->get('discount_value');
            $userData['min_order_value'] = $request->get('min_order_value');

        }else{
            $userData['discount_allowed'] = 0;
            $userData['discount_value'] = Null;
            $userData['min_order_value'] = Null;
        }

        // if ($user->email_sent == 0 && $user->is_authorized == 0 && $request->input('is_authorized') == '1') {
        //     try {
        //         $data = [
        //             'full_name' => $request->input('full_name'),
        //         ];

        //         $email = $user->email;
        //         Mail::send('mail.authorizationEmail', $data, function ($message) use ($email) {
        //             $message->from('admin@postquam.com', 'Postquam Admin');
        //             $message->to($email)->subject('Congratulations Your Account has been Approved.');
        //         });
        //         $userData['email_sent'] = 1;

        //     } catch (\Exception $e) {
        //         $response['message'] = 'Registered Successfully.';
        //         return response()->json($response);
        //     }
        // }
        
        // store user profile image
        $userImageDirectory = 'userImages';
        if ($request->hasFile('profile_image')) {

            $fileName = $request->file('profile_image')->getClientOriginalName();

            if (!Storage::exists($userImageDirectory)) {
                Storage::makeDirectory($userImageDirectory);
            }
            if (isset($user->porfile_image)) {
                Storage::delete($user->profile_image);
            }

            $profileImageUrl = Storage::putFile($userImageDirectory, new File($request->file('profile_image')));
            $user->update(['profile_image' => $profileImageUrl]);
        } 

        $user->update($userData);

        if (!empty($request->billing_id)) {
            // $billingAddress = Address::find($request->billing_id);
            
            // dd($billingAddress);
            Address::where('id', $request->billing_id)->update(['address' => $request->billing_address, 'city' => $request->billing_city, 'country' => $request->billing_country]);
        }
        if (!empty($request->shipping_id)) {
            Address::where('id', $request->shipping_id)->update(['address' => $request->shipping_address, 'city' => $request->shipping_city, 'country' => $request->shipping_country]);
        }

        return redirect()->action('UserController@index')->with('success', 'Record Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user == null) {
            return redirect()->action('UserController@index')->with('error', 'No Record Found.');
        }
        $user->update(['is_deleted' => 1]);
        return response()->json(['status' => 1, 'message' => 'Record Deleted Successfully.']);
    }

}
