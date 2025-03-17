<?php

namespace App\Http\Controllers\API\Professional;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Professional;
use Auth;
use Config;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use JWTAuth;
use Validator;
use Hash;
use App\Models\Notification;

class UserController extends Controller
{
    private $generalConstants;
    private $responseConstants;
    private $constants;

    public function __construct() {
        $this->constants = Config::get('constants.PROFESSIONAL_CONSTANTS');
        $this->generalConstants = Config::get('constants.GENERAL_CONSTANTS');
        $this->responseConstants = Config::get('constants.RESPONSE_CONSTANTS');
    }

    public function getMyProfile(Request $request)
    {
        $response = [];

        $professional = JWTAuth::toUser($request->token);
        
        $professionalStatus = $professional->_check();
        if($professionalStatus != null) {
            return response()->json($professionalStatus);
        }
        
        
        $professional = Professional::with(['addresses' => function($query) {
            $query->select(['id', 'professional_id',  'address_type', 'address', 'city', 'country'])->where('address_type', '1');
        }])->where('id', $professional->id)->first();

        $professional->makeHidden(['is_authorized', 'is_active', 'is_deleted', 'email_sent', 'created_at', 'updated_at']);
        
        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = "Success";
        $response['professional'] = $professional;
       
        return response()->json($response);
    }
    
    public function updteProfile(Request $request)
    {
        $response = [];
        $rules = [
            $this->constants['KEY_FULL_NAME'] => 'required',
            $this->constants['KEY_PHONE'] => 'required',
            $this->constants['KEY_COMPANY_NAME'] => 'required',
            $this->constants['KEY_COMPANY_ADDRESS'] => 'required',
            $this->constants['KEY_COMPANY_WEBSITE'] => 'nullable',
            $this->constants['KEY_PROFILE_IMAGE'] => 'nullable'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['INVALID_PARAMETERS'],
            ]);
        }

        $professional = JWTAuth::toUser($request->token);
        
        $professionalStatus = $professional->_check();
        if($professionalStatus != null) {
            return response()->json($professionalStatus);
        }

        $data = [
            'full_name' => $request->get($this->constants['KEY_FULL_NAME']),
            'phone' => $request->get($this->constants['KEY_PHONE']),
            'company_name' => $request->get($this->constants['KEY_COMPANY_NAME']),
            'company_address' => $request->get($this->constants['KEY_COMPANY_ADDRESS']),
        ];
        
        if ($request->has($this->constants['KEY_COMPANY_WEBSITE']) && !empty($request->get($this->constants['KEY_COMPANY_WEBSITE']))) {
            $data['company_website'] = $request->get($this->constants['KEY_COMPANY_WEBSITE']);
        }

        if ($request->hasFile($this->constants['KEY_PROFILE_IMAGE'])) {
            if (isset($user->profile_image) && Storage::exists($user->profile_image)) {
                Storage::delete($user->profile_image);
            }

            if (!Storage::exists('professionalImages')) {
                Storage::makeDirectory('professionalImages');
            }

            $data['profile_image'] = Storage::putFile('professionalImages', new File($request->file($this->constants['KEY_PROFILE_IMAGE'])));
        }
        
        Professional::where('id', $professional->id)->update($data);
        // $professional = Professional::find($professional->id);

        $professional = Professional::with(['addresses' => function($query) {
            $query->select(['id', 'professional_id',  'address_type', 'address', 'city', 'country'])->where('address_type', '1');
        }])->where('id', $professional->id)->first();
        
        $professional->makeHidden(['is_authorized', 'is_active', 'is_deleted', 'email_sent', 'created_at', 'updated_at']);

        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = "Success";
        $response['professional'] = $professional;
        
        return response()->json($response);

    }
    
      public function getMyNotifications(Request $request)
    {
        $response = [];

        $professional = JWTAuth::toUser($request->token);
        
        $professionalStatus = $professional->_check();
        if($professionalStatus != null) {
            return response()->json($professionalStatus);
        }
        
         $notifications = Notification::where('professional_id', $professional->id)->orderBy('created_at', 'DESC')->get();
         
         Notification::where('professional_id', $professional->id)->update(['is_read' => 1]);

        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = "Success";
        // $response['consumer'] = $consumer;
        $response['notifications'] = $notifications;

        return response()->json($response);
    }
}
