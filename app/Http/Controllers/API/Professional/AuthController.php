<?php

namespace App\Http\Controllers\API\Professional;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Config;
use JWTAuth;
use App\Models\Professional;
use Hash;
use Carbon\Carbon;
use App\Models\Notification;

class AuthController extends Controller
{
    private $generalConstants;
    private $responseConstants;
    private $constants;

    public function __construct() {
        $this->constants = Config::get('constants.PROFESSIONAL_CONSTANTS');
        $this->generalConstants = Config::get('constants.GENERAL_CONSTANTS');
        $this->responseConstants = Config::get('constants.RESPONSE_CONSTANTS');
    }

    
    public function professionalSignUp(Request $request)
    {
        $response = [];

        $rules = [
            $this->constants['KEY_FULL_NAME'] => 'required',
            $this->constants['KEY_EMAIL'] => 'required|email',
            $this->constants['KEY_PASSWORD'] => 'required|min:6',
            $this->constants['KEY_PHONE'] => 'required',
            $this->constants['KEY_COMPANY_NAME'] => 'required',
            $this->constants['KEY_COMPANY_ADDRESS'] => 'required',
            $this->constants['KEY_COMPANY_WEBSITE'] => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['INVALID_PARAMETERS'],
            ]);
        }

               $emailExists = Professional::where('email', '=', $request->get($this->constants['KEY_EMAIL']))->first();
        
        if ($emailExists !== null) {

            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['ERROR_EMAIL_EXIST'],
            ]);
        }

        $data = [
            'full_name' => $request->get($this->constants['KEY_FULL_NAME']),
            'phone' =>$request->get($this->constants['KEY_PHONE']),
            'email' =>  $request->get($this->constants['KEY_EMAIL']),
            'password' => Hash::make($request->get($this->constants['KEY_PASSWORD'])),
            'company_name' => $request->get($this->constants['KEY_COMPANY_NAME']),
            'company_address' => $request->get($this->constants['KEY_COMPANY_ADDRESS']),
            'last_login' => Carbon::now()->toDateTimeString()
        ];

        Professional::create($data);
        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = $this->responseConstants['MSG_REGISTERED_PROFESSIONAL'];
        return response()->json($response);
    }

    public function professionalSignIn(Request $request)
    {
        $response = [];
        $validator = Validator::make($request->all(), [
            $this->constants['KEY_EMAIL'] => 'required|string|email|max:255',
            $this->constants['KEY_PASSWORD'] => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['INVALID_PARAMETERS'],
            ]);
        }

        $credentials = $request->only($this->constants['KEY_EMAIL'], $this->constants['KEY_PASSWORD']);
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => $this->responseConstants['STATUS_ERROR'],
                    'message' => $this->responseConstants['ERROR_INVALID_CREDENTIALS'],
                ]);
            }

        } catch (JWTException $e) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Cant create token.',
            ]);
        }

        $user = Professional::find(JWTAuth::setToken($token)->getClaim('sub'));
        $user->update(['last_login' => Carbon::now()->toDateTimeString()]);
        $user->makeHidden(['created_at', 'updated_at', 'is_deleted', 'is_active', 'remember_token', 'is_authorized', 'email_sent']);
        
        $token = JWTAuth::fromUser($user);
        $userStatus = $user->_check();
        if($userStatus != null) {
            return response()->json($userStatus);
        }

        $user = Professional::with(['addresses' => function($query) {
            $query->select(['id', 'professional_id',  'address_type', 'address', 'city', 'country'])->where('address_type', '1');
        }])->where('id', $user->id)->first();

        if ($request->has($this->constants['KEY_DEVICE_ID']) && !empty($request->get($this->constants['KEY_DEVICE_ID']))) {
            $user->update(['token' => $request->get($this->constants['KEY_DEVICE_ID'])]);
        }
        if ($request->has($this->constants['KEY_DEVICE_TYPE']) && !empty($request->get($this->constants['KEY_DEVICE_TYPE']))) {
            $user->update(['device_type' => $request->get($this->constants['KEY_DEVICE_TYPE'])]);
        }
        $user->makeHidden(['is_authorized', 'is_active', 'is_deleted', 'email_sent', 'created_at', 'updated_at']);
        $notificationCount = Notification::where('professional_id', $user->id)->where('is_read', 0)->count();
        
        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = $this->responseConstants['MSG_LOGGED_IN'];
        $response['access_token'] = $token;
        $response['professional'] = $user;
        $response['notificationsCount'] = $notificationCount;
        return response()->json($response);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $this->constants['KEY_EMAIL'] => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['INVALID_PARAMETERS'],
            ]);
        }

        $professional = Professional::where('email', $request->get($this->constants['KEY_EMAIL']))->first();

        if ($professional == null) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['ERROR_INVALID_EMAIL'],
            ]);
        }

        $response = app('App\Http\Controllers\API\ProfessionalForgotPasswordController')->sendResetLinkEmail($request);
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
    
    
    public function logout(Request $request)
    {
        $response = [];
        
        $professional = JWTAuth::toUser($request->token);
        $professionalStatus = $professional->_check();
        if($professionalStatus != null) {
            return response()->json($professionalStatus);
        }

        $professional = Professional::find($professional->id);
        $professional->update([
            'last_login' => Carbon::now()->toDateTimeString(),
            'token' => Null
        ]);

        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = $this->responseConstants['MSG_LOGGED_OUT'];
        return response()->json($response);
       
    }
}
