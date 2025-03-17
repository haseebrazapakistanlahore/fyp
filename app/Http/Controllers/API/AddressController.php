<?php

namespace App\Http\Controllers\API;

use App\Models\Address;
use App\Http\Controllers\Controller;
use Config;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class AddressController extends Controller
{
    private $responseConstants;
    private $constants;

    public function __construct()
    {
        $this->constants = Config::get('constants.ADDRESS_CONSTANTS');
        $this->responseConstants = Config::get('constants.RESPONSE_CONSTANTS');
    }

    public function addAddress(Request $request)
    {
        $response = [];
        $rules = [
            $this->constants['KEY_ADDRESS_TYPE'] => 'required',
            $this->constants['KEY_ADDRESS'] => 'required',
            $this->constants['KEY_CITY'] => 'required',
            $this->constants['KEY_COUNTRY'] => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['INVALID_PARAMETERS'],
            ]);
        }

        $user = JWTAuth::toUser($request->token);

        $userStatus = $user->_check();
        if ($userStatus != null) {
            return response()->json($userStatus);
        }

        if ($request->get($this->constants['KEY_ADDRESS_TYPE']) == 1) {

            $shippingAddressCount = $user->addresses()->where('address_type', '1')->count();

            if ($shippingAddressCount >= 5) {
                return response()->json([
                    'status' => $this->responseConstants['STATUS_ERROR'],
                    'message' => 'Maximum No Of Addresses Already Added.',
                ]);
            }
        } else {

            if ($user->addresses()->where('address_type', '0')->count()) {
                return response()->json([
                    'status' => $this->responseConstants['STATUS_ERROR'],
                    'message' => 'Multiple billing addresses not allowed.',
                ]);
            }

        }

        $data = [
            'address' => $request->get($this->constants['KEY_ADDRESS']),
            'city' => $request->get($this->constants['KEY_CITY']),
            'country' => $request->get($this->constants['KEY_COUNTRY']),
            'address_type' => $request->get($this->constants['KEY_ADDRESS_TYPE']),
        ];

        if ($user instanceof \App\Models\Consumer) {
            $data["consumer_id"] = $user->id;
        } else {
            $data["professional_id"] = $user->id;
        }

        Address::create($data);
        $addresses = $user->addresses;
        $addresses->makeHidden(['created_at', 'updated_at']);

        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['addresses'] = $addresses;
        $response['message'] = 'Address Added Successfully';
        return response()->json($response);
    }
  
    public function updateAddress(Request $request)
    {
        $response = [];
        $rules = [
            $this->constants['KEY_ADDRESS_ID'] => 'required',
            $this->constants['KEY_ADDRESS_TYPE'] => 'required',
            $this->constants['KEY_ADDRESS'] => 'required',
            $this->constants['KEY_CITY'] => 'required',
            $this->constants['KEY_COUNTRY'] => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['INVALID_PARAMETERS'],
            ]);
        }

        $user = JWTAuth::toUser($request->token);

        $userStatus = $user->_check();
        if ($userStatus != null) {
            return response()->json($userStatus);
        }

        $address = $user->addresses()->where('id', $request->get($this->constants['KEY_ADDRESS_ID']))->first();
        // dd(empty($address));

        if(empty($address)){
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Invalid Address Id.'
            ]);
        }
        
        $data = [
            'address' => $request->get($this->constants['KEY_ADDRESS']),
            'city' => $request->get($this->constants['KEY_CITY']),
            'country' => $request->get($this->constants['KEY_COUNTRY']),
        ];

        $address->update($data);

        $addresses = $user->addresses;
        $addresses->makeHidden(['created_at', 'updated_at']);

        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['addresses'] = $addresses;
        $response['message'] = 'Address Updated Successfully';
        return response()->json($response);
    }


    public function getAllAddress(Request $request)
    {
        $response = [];

        $user = JWTAuth::toUser($request->token);

        $userStatus = $user->_check();
        if ($userStatus != null) {
            return response()->json($userStatus);
        }

        $addresses = $user->addresses;
        $addresses->makeHidden(['created_at', 'updated_at']);

        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['addresses'] = $addresses;
        $response['message'] = 'Success';
        return response()->json($response);
    }

}
