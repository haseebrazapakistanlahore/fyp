<?php

namespace App\Http\Controllers\API;

use App\Address;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Config;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use JWTAuth;
use Redirect;
use Validator;
use Mail;

class AuthenticationController extends Controller
{
    private $constants;
    private $responseKeys;
    private $accountType;
    private $addressConstants;

    public function __construct()
    {
        $this->accountType = Config::get('authConstants.ACCOUNT_TYPE');
        $this->constants = Config::get('authConstants.CONSTANTS');
        $this->responseKeys = Config::get('responseConstants.RESPONSE_KEYS');
        $this->addressConstants = Config::get('addressConstants.CONSTANTS');
    }

    public function register(Request $request)
    {

        $response = [];
        $constraints = $this->_getCommonConstraints();

        $professionalConstraints = [
            $this->constants['KEY_COMPANY_NAME'] => 'required',
            // $this->constants['KEY_COMPANY_WEBSITE'] => 'required',
            // $this->constants['KEY_CONTACT_PERSON'] => 'required',
            // $this->constants['KEY_CONTACT_PHONE'] => 'required',
            $this->addressConstants['KEY_ADDRESS'] => 'required',
            $this->addressConstants['KEY_CITY'] => 'required',
            $this->addressConstants['KEY_STATE'] => 'required',
            $this->addressConstants['KEY_COUNTRY'] => 'required',
        ];

        if ($request->user_type == 1) {

            $constraints = array_merge($professionalConstraints, $constraints);
        }

        $userValidator = Validator::make($request->all(), $constraints);

        if ($userValidator->fails()) {

            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['INVALID_PARAMETERS'],
            ]);
        }

        // check if email already exist
        $emailExists = User::where('email', '=', $request->get($this->constants['KEY_EMAIL']))->first();
        if ($emailExists !== null) {

            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['ERROR_EMAIL_EXIST'],
            ]);
        }

        // check if user_name already exist
        $userNameExists = User::where('user_name', '=', $request->get($this->constants['KEY_USER_NAME']))->first();
        if ($userNameExists !== null) {

            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['ERROR_USER_NAME_EXIST'],
            ]);
        }

        // create user form request data
        $user = User::create([
            'email' => $request->get($this->constants['KEY_EMAIL']),
            'user_name' => strtolower($request->get($this->constants['KEY_USER_NAME'])),
            'user_type' => $this->_getAccountType($request),
            'phone' => $request->get($this->constants['KEY_PHONE']),
            'password' => bcrypt($request->get($this->constants['KEY_PASSWORD'])),
            'first_name' => $request->get($this->constants['KEY_FIRST_NAME']),
            'last_name' => $request->get($this->constants['KEY_LAST_NAME']),
            'last_login' => Carbon::now(),
        ]);

        // get jwt token for newly created user
        $token = JWTAuth::fromUser($user);
        $user->makeHidden(['created_at', 'updated_at', 'is_deleted', 'last_login', 'is_authorized']);

        if ($user->user_type == '0') {
            $user->makeHidden(['contact_person', 'company_name', 'company_website', 'contact_no']);
        }
        $response['status'] = $this->responseKeys['STATUS_SUCCESS'];
        $response['user'] = $user;

        // if user type is professional update info
        if ($request->get($this->constants['KEY_USER_TYPE']) == 1) {

            User::where('id', $user->id)->update([
                // 'contact_person' => $request->get($this->constants['KEY_CONTACT_PERSON']),
                'company_name' => $request->get($this->constants['KEY_COMPANY_NAME']),
                'company_website' => $request->get($this->constants['KEY_COMPANY_WEBSITE']),
                // 'contact_no' => $request->get($this->constants['KEY_CONTACT_PHONE']),
            ]);

            $data = [
                'user_id' => $user->id,
                'address' => $request->get($this->addressConstants['KEY_ADDRESS']),
                'city' => $request->get($this->addressConstants['KEY_CITY']),
                'state' => $request->get($this->addressConstants['KEY_STATE']),
                // 'zip_code' => $request->get($this->addressConstants['KEY_ZIP_CODE']),
                'country' => $request->get($this->addressConstants['KEY_COUNTRY']),
                'address_type' => 0,
            ];
            if ($request->has($this->addressConstants['KEY_ZIP_CODE']) && !empty($request->get($this->addressConstants['KEY_ZIP_CODE']))) {
                $data['zip_code'] = $request->get($this->addressConstants['KEY_ZIP_CODE']);
            }
            Address::create($data);
        } else {
            $response['access_token'] = $token;
        }
        
        $response['message'] = 'Registered Successfully.';
        return response()->json($response);
    }
    public function registerV2(Request $request)
    {

        $response = [];
        $constraints = $this->_getCommonConstraints2();

        $professionalConstraints = [
            $this->constants['KEY_COMPANY_NAME'] => 'required',
            $this->constants['KEY_COMPANY_ADDRESS'] => 'required',
        ];

        if ($request->user_type == 1) {
            $constraints = array_merge($professionalConstraints, $constraints);
        }

        $userValidator = Validator::make($request->all(), $constraints);

        if ($userValidator->fails()) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['INVALID_PARAMETERS'],
            ]);
        }

        // check if email already exist
        $emailExists = User::where('email', '=', $request->get($this->constants['KEY_EMAIL']))->first();
        if ($emailExists !== null) {

            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['ERROR_EMAIL_EXIST'],
            ]);
        }

        // create user form request data
        $user = User::create([
            'email' => $request->get($this->constants['KEY_EMAIL']),
            'full_name' => strtolower($request->get($this->constants['KEY_FULL_NAME'])),
            'user_type' => $this->_getAccountType($request),
            'phone' => $request->get($this->constants['KEY_PHONE']),
            'password' => bcrypt($request->get($this->constants['KEY_PASSWORD'])),
            'last_login' => Carbon::now(),
        ]);

        // get jwt token for newly created user
        $token = JWTAuth::fromUser($user);
        $user->makeHidden(['created_at', 'updated_at', 'is_deleted', 'last_login', 'is_authorized']);

        if ($user->user_type == '0') {
            $user->makeHidden(['contact_person', 'company_name', 'company_website', 'contact_no']);
        }
        $response['status'] = $this->responseKeys['STATUS_SUCCESS'];
        $response['user'] = $user;

        // if user type is professional update info
        if ($request->get($this->constants['KEY_USER_TYPE']) == 1) {

            User::where('id', $user->id)->update([
                'company_name' => $request->get($this->constants['KEY_COMPANY_NAME']),
                'company_address' => $request->get($this->constants['KEY_COMPANY_ADDRESS']),
            ]);
          
        } else {
            $response['access_token'] = $token;
        }
        
        if($user->user_type == '1') {
            try {
                $data = [
                    'full_name' => $request->input('full_name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'company_name' => $request->input('company_name'),
                    'company_address' => $request->input('company_address'),
                ];

                $email = $request->input('email');
                Mail::send('mail.professionalRegisterationEmail', $data, function ($message) use ($email) {
                    $message->from('admin@postquam.com', 'Postquam Admin');
                    $message->cc('salmanmustafa@gmail.com', 'Mr. Salman');
                    $message->to('kabeer@live.hk')->subject('Request for professional account approval.');
                });
                Mail::send('mail.registerEmail', $data, function ($message) use ($email) {
                    $message->from('admin@postquam.com', 'Postquam Admin');
                    $message->to($email)->subject('Request for professional account.');
                });

            } catch (\Exception $e) {
                $response['message'] = 'Registered Successfully.';
                return response()->json($response);
            }
        }

        $response['message'] = 'Registered Successfully.';
        return response()->json($response);
    }


    // login api for consumer and professionals
    public function loginProfessional(Request $request)
    {
        $response = [];
        $validator = Validator::make($request->all(), [
            $this->constants['KEY_EMAIL'] => 'required|string|email|max:255',
            $this->constants['KEY_PASSWORD'] => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['INVALID_PARAMETERS'],
            ]);
        }

        $credentials = $request->only($this->constants['KEY_EMAIL'], $this->constants['KEY_PASSWORD']);
        try {
            if (!$token = JWTAuth::attempt($credentials)) {

                return response()->json([
                    'status' => $this->responseKeys['STATUS_ERROR'],
                    'message' => $this->responseKeys['ERROR_INVALID_CREDENTIALS'],
                ]);
            }

        } catch (JWTException $e) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => 'Cant create token.',
            ]);
        }

        $userId = JWTAuth::getPayload($token)->get('sub');
        $user = User::find($userId);

        // check if account is deleted or supended
        if ($user->is_deleted == 1) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['ERROR_INVALID_CREDENTIALS'],
            ]);
        }

        // check if account is for consumer or not
        if ($user->user_type != '1') {
            return response()->json([
                'status' => $this->responseKeys['STATUS_INVALID_USER_TYPE'],
                'message' => $this->responseKeys['ERROR_INVALID_USER_TYPE'],
            ]);
        }

        // check if account is active or supended
        if ($user->is_active == 0) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ACCOUNT_SUSPENDED'],
                'message' => $this->responseKeys['ERROR_ACCOUNT_SUSPENDED'],
            ]);
        }

        // check if professional user is authorized or not
        if ($user->user_type == 1 && $user->is_authorized == 0) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ACCOUNT_UNAUTHORIZED'],
                'message' => $this->responseKeys['ERROR_ACCOUNT_UNAUTHORIZED'],
            ]);
        }

        // creating response data
        $response['status'] = $this->responseKeys['STATUS_SUCCESS'];
        $response['user'] = User::find($userId);

        $response['accessToken'] = $token;
        $response['message'] = 'Logged in Successfully.';
        return response()->json($response);
    }

    // login api for consumer and professionals
    public function loginConsumer(Request $request)
    {
        $response = [];
        $validator = Validator::make($request->all(), [
            $this->constants['KEY_EMAIL'] => 'required|string|email|max:255',
            $this->constants['KEY_PASSWORD'] => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['INVALID_PARAMETERS'],
            ]);
        }

        $credentials = $request->only($this->constants['KEY_EMAIL'], $this->constants['KEY_PASSWORD']);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {

                return response()->json([
                    'status' => $this->responseKeys['STATUS_ERROR'],
                    'message' => $this->responseKeys['ERROR_INVALID_CREDENTIALS'],
                ]);
            }

        } catch (JWTException $e) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => 'Cant create token.',
            ]);
        }

        $userId = JWTAuth::getPayload($token)->get('sub');
        $user = User::find($userId);

        // check if account is deleted or supended
        if ($user->is_deleted == 1) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['ERROR_INVALID_CREDENTIALS'],
            ]);
        }

        // check if account is for consumer or not
        if ($user->user_type != '0') {
            return response()->json([
                'status' => $this->responseKeys['STATUS_INVALID_USER_TYPE'],
                'message' => $this->responseKeys['ERROR_INVALID_USER_TYPE'],
            ]);
        }

        // check if account is active or supended
        if ($user->is_active == 0) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ACCOUNT_SUSPENDED'],
                'message' => $this->responseKeys['ERROR_ACCOUNT_SUSPENDED'],
            ]);
        }

        // check if professional user is authorized or not
        if ($user->user_type == '1' && $user->is_authorized == '0') {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ACCOUNT_UNAUTHORIZED'],
                'message' => $this->responseKeys['ERROR_ACCOUNT_UNAUTHORIZED'],
            ]);
        }
        $user = User::find($userId);
        $user->makeHidden(['created_at', 'updated_at', 'is_deleted', 'last_login', 'is_authorized']);

        if ($user->user_type == '0') {
            $user->makeHidden(['contact_person', 'company_name', 'company_website', 'contact_no']);
        }
        // creating response data
        $response['status'] = $this->responseKeys['STATUS_SUCCESS'];
        $response['user'] = $user;

        $response['accessToken'] = $token;
        $response['message'] = 'Logged in Successfully.';
        return response()->json($response);
    }

    // get common constraints for user register
    public function _getCommonConstraints()
    {
        return [
            $this->constants['KEY_EMAIL'] => 'required|string|email|max:255',
            $this->constants['KEY_PASSWORD'] => 'required',
            $this->constants['KEY_USER_TYPE'] => 'required',
            // $this->constants['KEY_PHONE'] => array(
            //     'required',
            //     'regex:/^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/'),
            $this->constants['KEY_PHONE'] => 'required',
            $this->constants['KEY_FIRST_NAME'] => 'required',
            $this->constants['KEY_LAST_NAME'] => 'required',
            $this->constants['KEY_USER_NAME'] => 'required',
        ];
    }
    public function _getCommonConstraints2()
    {
        return [
            $this->constants['KEY_EMAIL'] => 'required|string|email|max:255',
            $this->constants['KEY_PASSWORD'] => 'required',
            $this->constants['KEY_USER_TYPE'] => 'required',
            // $this->constants['KEY_PHONE'] => array(
            //     'required',
            //     'regex:/^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/'),
            $this->constants['KEY_PHONE'] => 'required',
            $this->constants['KEY_FULL_NAME'] => 'required',
        ];
    }

    // helper fnction to get acccount type of new user registered
    public function _getAccountType(Request $request)
    {

        if ($request->get($this->constants['KEY_USER_TYPE']) == 0) {
            return $this->accountType['CONSUMER'];
        } else {
            return $this->accountType['PROFESSIONAL'];
        }
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $this->constants['KEY_EMAIL'] => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['INVALID_PARAMETERS'],
            ]);
        }

        $user = User::where('email', $request->get($this->constants['KEY_EMAIL']))->where('user_type', '!=', '2')->first();

        
        // check if account is deleted or supended
        // if ($user->is_deleted == 1) {
        //     return response()->json([
        //         'status' => $this->responseKeys['STATUS_ERROR'],
        //         'message' => 'Invalid Email Address.',
        //     ]);
        // }

        if ($user == null) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['ERROR_INVALID_EMAIL'],
            ]);
        }

        $response = app('App\Http\Controllers\API\ForgotPasswordController')->sendResetLinkEmail($request);
        return $response;
    }

    // reset user password form
    public function resetPassword($id)
    {
        $userId = base64_decode($id);

        if (User::find($userId) == null) {
            return redirect('/')->with('error', 'Invalid Token');
        }
        return view('user.restPasswordForm', ['userId' => $userId]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|alpha_num',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        }
        $user = User::find($request->user_id);

        // check if account is deleted or supended
        if ($user->is_deleted == 1) {
            return Redirect::back()->with('Error', 'Your account was deleted.');
        }
        
        if ($user == null) {
            return Redirect::back()->with('error', 'No user found');
        }

        $data = [
            'password' => Hash::make($request->get('password')),
        ];

        $user->update($data);

        return Redirect::back()->with('success', 'Password updated successfuly.');
    }

    public function updateProfile(Request $request)
    {

        $response = [];
        $rules = [
            $this->constants['KEY_USER_TYPE'] => 'required',
            // $this->constants['KEY_PHONE'] => array(
            //     'required',
            //     'regex:/^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/'),
            $this->constants['KEY_PHONE'] => 'required',
            $this->constants['KEY_FULL_NAME'] => 'required',
            // $this->constants['KEY_FIRST_NAME'] => 'required',
            // $this->constants['KEY_LAST_NAME'] => 'required',
            // $this->constants['KEY_USER_NAME'] => 'required',
        ];

        if ($request->get($this->constants['KEY_USER_TYPE']) == 1) {
            $rules[$this->constants['KEY_COMPANY_NAME']] = 'required';
            $rules[$this->constants['KEY_COMPANY_ADDRESS']] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['INVALID_PARAMETERS'],
            ]);
        }

        $user = JWTAuth::toUser($request->token);

        // check if account is deleted or supended
        if ($user->is_deleted == 1) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['ERROR_INVALID_CREDENTIALS'],
            ]);
        }

        if ($user->user_type != $request->get($this->constants['KEY_USER_TYPE'])) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_INVALID_USER_TYPE'],
                'message' => $this->responseKeys['ERROR_INVALID_USER_TYPE'],
            ]);
        }

        // check if account is active or supended
        if ($user->is_active == 0) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ACCOUNT_SUSPENDED'],
                'message' => $this->responseKeys['ERROR_ACCOUNT_SUSPENDED'],
            ]);
        }

        // check if professional user is authorized or not
        if ($user->user_type == 1 && $user->is_authorized == 0) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ACCOUNT_UNAUTHORIZED'],
                'message' => $this->responseKeys['ERROR_ACCOUNT_UNAUTHORIZED'],
            ]);
        }

        $data = [
            'phone' => $request->get($this->constants['KEY_PHONE']),
            'full_name' => $request->get($this->constants['KEY_FULL_NAME']),
            // 'last_name' => $request->get($this->constants['KEY_LAST_NAME']),
            'last_login' => Carbon::now(),
        ];

        // $username = $request->get($this->constants['KEY_USER_NAME']);
        
        // if (($user->user_name != $username) && (User::where('user_name', $username)->where('id', '!=', $user->id)->count())) {
        //     return response()->json([
        //         'status' => $this->responseKeys['STATUS_ERROR'],
        //         'message' => 'User Name Is Already Taken.',
        //     ]); 
        // }else if($user->user_name != $username){
        //     $data['user_name'] = strtolower($request->get($this->constants['KEY_USER_NAME']));
        // }

        // if user type is professional update info
        if ($request->get($this->constants['KEY_USER_TYPE']) == 1) {

            $temp = [
                'company_name' => $request->get($this->constants['KEY_COMPANY_NAME']),
                'company_address' => $request->get($this->constants['KEY_COMPANY_ADDRESS']),
                'company_website' => $request->get($this->constants['KEY_COMPANY_WEBSITE']),
            ];
            $data = array_merge($data, $temp);

        }

        // if($request->has('profile_image') && !is_null($request->get('profile_image'))){
        //     $data['profile_image'] = $request->get('profile_image');
        // }
       

        User::where('id', $user->id)->update($data);
       
        // creating response data
        $response['status'] = $this->responseKeys['STATUS_SUCCESS'];
        $response['message'] = 'Profile Updated Successfully.';
        return response()->json($response);
    }

    public function getProfile(Request $request)
    {
        $response = [];

        $user = JWTAuth::toUser($request->token);
        
        // check if account is deleted or supended
        if ($user->is_deleted == 1) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['ERROR_INVALID_CREDENTIALS'],
            ]);
        }
        
        // check if account is active or supended
        if ($user->is_active == 0) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ACCOUNT_SUSPENDED'],
                'message' => $this->responseKeys['ERROR_ACCOUNT_SUSPENDED'],
            ]);
        }

        // check if professional user is authorized or not
        if ($user->user_type == 1 && $user->is_authorized == 0) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ACCOUNT_UNAUTHORIZED'],
                'message' => $this->responseKeys['ERROR_ACCOUNT_UNAUTHORIZED'],
            ]);
        }

        $user = User::with('addresses')->where('id', $user->id)->first();
        $user->makeHidden(['created_at', 'updated_at', 'is_deleted', 'last_login', 'is_authorized', 'user_type', 'is_active']);

        if ($user->user_type == 0) {
            $user->makeHidden(['contact_person', 'company_name', 'company_website', 'contact_no']);
        }

        // creating response data
        $response['status'] = $this->responseKeys['STATUS_SUCCESS'];
        $response['messge'] = 'Success';
        $response['user'] = $user;
        $response['billing_address'] = Address::where('address_type', '0')->where('user_id', $user->id)->get();
        return response()->json($response);
    }

    public function uploadProfileImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['INVALID_PARAMETERS'],
            ]);
        }

        $user = JWTAuth::toUser($request->token);

        // check if account is active or supended
        if ($user->is_active == 0) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ACCOUNT_SUSPENDED'],
                'message' => $this->responseKeys['ERROR_ACCOUNT_SUSPENDED'],
            ]);
        }

        // check if professional user is authorized or not
        if ($user->user_type == 1 && $user->is_authorized == 0) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ACCOUNT_UNAUTHORIZED'],
                'message' => $this->responseKeys['ERROR_ACCOUNT_UNAUTHORIZED'],
            ]);
        }

        // store user profile image
        $userImageDirectory = 'userImages';
        if ($request->has('profile_image')) {

            if (!Storage::exists($userImageDirectory)) {
                Storage::makeDirectory($userImageDirectory);
            }
            
            $base64_image = $request->get('profile_image');

            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                $data = substr($base64_image, strpos($base64_image, ',') + 1);
            
                $data = base64_decode($data);
                $profileImageUrl =  $userImageDirectory.'/'.$user->id.'.png';
                Storage::put($profileImageUrl, $data);
                
                User::where('id', $user->id)->update(['profile_image' => $profileImageUrl]);
                return response()->json([
                    'status' => $this->responseKeys['STATUS_SUCCESS'],
                    'message' => 'Image Uploaded Successfully.',
                ]);
            }
        }

        return response()->json([
            'status' => $this->responseKeys['STATUS_ERROR'],
            'message' => $this->responseKeys['INVALID_PARAMETERS'],
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $this->constants['KEY_PASSWORD'] => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['INVALID_PARAMETERS'],
            ]);
        }

        $user = JWTAuth::toUser($request->token);

        // check if account is deleted or supended
        if ($user->is_deleted == 1) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['ERROR_INVALID_CREDENTIALS'],
            ]);
        }
        
        // check if account is active or supended
        if ($user->is_active == 0) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ACCOUNT_SUSPENDED'],
                'message' => $this->responseKeys['ERROR_ACCOUNT_SUSPENDED'],
            ]);
        }

        // check if professional user is authorized or not
        if ($user->user_type == 1 && $user->is_authorized == 0) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ACCOUNT_UNAUTHORIZED'],
                'message' => $this->responseKeys['ERROR_ACCOUNT_UNAUTHORIZED'],
            ]);
        }

        $user->update(['password' => bcrypt($request->get($this->constants['KEY_PASSWORD'])) ]);
        
        return response()->json([
            'status' => $this->responseKeys['STATUS_SUCCESS'],
            'message' => 'Password updated successfully.'
        ]);
    }

    public function checkAndGet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $this->constants['KEY_EMAIL'] => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['INVALID_PARAMETERS'],
            ]);
        }

        $user = User::where('email', $request->get($this->constants['KEY_EMAIL']))->first();
        
        // check if account is deleted or supended
       
        
        if ($user == null) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => 'No user found.',
            ]);
        } 
        
        if ($user->is_deleted == 1) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => 'Your account has been deleted.',
            ]);
        }

        $token = JwtAuth::fromUser($user);

        $user->makeHidden(['created_at', 'updated_at', 'is_deleted', 'last_login', 'is_authorized', 'email_sent']);

        if ($user->user_type == '0') {
            $user->makeHidden(['contact_person', 'company_name', 'company_website', 'contact_no']);
        }
        $response = [];
        $response['status'] = $this->responseKeys['STATUS_SUCCESS'];
        $response['user'] = $user;
        $response['accessToken'] = $token;
        $response['message'] = 'Logged in Successfully.';
        return response()->json($response);

    }
}
