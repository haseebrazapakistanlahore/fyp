<?php

namespace App\Http\Controllers\API\Consumer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Consumer;
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
        $this->constants = Config::get('constants.CONSUMER_CONSTANTS');
        $this->generalConstants = Config::get('constants.GENERAL_CONSTANTS');
        $this->responseConstants = Config::get('constants.RESPONSE_CONSTANTS');
    }

    public function getMyProfile(Request $request)
    {
        $response = [];

        $consumer = JWTAuth::toUser($request->token);
        $consumerStatus = $consumer->_check();
        if($consumerStatus != null) {
            return response()->json($consumerStatus);
        }

        $consumer = Consumer::with(['addresses' => function($query) {
            $query->select(['id', 'consumer_id',  'address_type', 'address', 'city', 'country'])->where('address_type', '1');
        }])->where('id', $consumer->id)->first();

        $consumer->makeHidden(['is_active', 'is_deleted', 'created_at', 'updated_at']);

        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = "Success";
        $response['consumer'] = $consumer;

        return response()->json($response);
    }

    
    public function updteProfile(Request $request)
    {
        $response = [];
        $rules = [
            $this->constants['KEY_FULL_NAME'] => 'required',
            $this->constants['KEY_PHONE'] => 'required',
            $this->constants['KEY_PROFILE_IMAGE'] => 'nullable'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['INVALID_PARAMETERS'],
            ]);
        }

        $consumer = JWTAuth::toUser($request->token);
        
        $consumerStatus = $consumer->_check();
        if($consumerStatus != null) {
            return response()->json($consumerStatus);
        }

        $data = [
            'full_name' => $request->get($this->constants['KEY_FULL_NAME']),
            'phone' => $request->get($this->constants['KEY_PHONE']),
        ];
        

        if ($request->hasFile($this->constants['KEY_PROFILE_IMAGE'])) {
            if (isset($consumer->profile_image) && Storage::exists($consumer->profile_image)) {
                Storage::delete($consumer->profile_image);
            }

            if (!Storage::exists('consumerImages')) {
                Storage::makeDirectory('consumerImages');
            }

            $data['profile_image'] = Storage::putFile('consumerImages', new File($request->file($this->constants['KEY_PROFILE_IMAGE'])));
        }

        Consumer::where('id', $consumer->id)->update($data);
        $consumer = Consumer::with(['addresses' => function($query) {
            $query->select(['id', 'consumer_id',  'address_type', 'address', 'city', 'country'])->where('address_type', '1');
        }])->where('id', $consumer->id)->first();
        
        $consumer->makeHidden(['is_active', 'is_deleted', 'created_at', 'updated_at']);

        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = "Success";
        $response['consumer'] = $consumer;
        
        return response()->json($response);

    }
    
    
    public function getMyNotifications(Request $request)
    {
        $response = [];

        $consumer = JWTAuth::toUser($request->token);
        $consumerStatus = $consumer->_check();
        if($consumerStatus != null) {
            return response()->json($consumerStatus);
        }
        
         $notifications = Notification::where('consumer_id', $consumer->id)->orderBy('created_at', 'DESC')->get();
         
         Notification::where('consumer_id', $consumer->id)->update(['is_read' => 1]);

        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = "Success";
        $response['notifications'] = $notifications;

        return response()->json($response);
    }

}
