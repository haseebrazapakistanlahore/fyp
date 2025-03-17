<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Config;
use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use App\Models\Review;
use App\Models\Product;
use App\Models\Consumer;
use App\Models\Professional;

class ReviewController extends Controller
{
    private $constants;
    private $responseConstants;
    private $recordsPerPage = 10;

    public function __construct()
    {
        $this->constants = Config::get('constants.GENERAL_CONSTANTS');
        $this->responseConstants = Config::get('constants.RESPONSE_CONSTANTS');
    }

    public function addReviewConsumer(Request $request)
    {
        $response = [];
        $rules = [
            $this->constants['KEY_PRODUCT_ID'] => 'required',
            $this->constants['KEY_COMMENT'] => 'nullable',
            $this->constants['KEY_RATING'] => 'required',
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
        if($userStatus != null) {
            return response()->json($userStatus);
        }
       
        if (Product::find($request->get($this->constants['KEY_PRODUCT_ID'])) == null) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Invalid Product id. ',
            ]);
        }

        if ($user instanceof \App\Models\Consumer) {
            if (Review::where('consumer_id', $user->id)->where('product_id', $request->get($this->constants['KEY_PRODUCT_ID']))->first() != null) {
                return response()->json([
                    'status' => $this->responseConstants['STATUS_ERROR'],
                    'message' => 'You have already rated this product. ',
                ]);
            }
        }else{
            if (Review::where('professional_id', $user->id)->where('product_id', $request->get($this->constants['KEY_PRODUCT_ID']))->first() != null) {
                return response()->json([
                    'status' => $this->responseConstants['STATUS_ERROR'],
                    'message' => 'You have already rated this product. ',
                ]);
            }
        }
        
        $data = [
            'rating' => $request->get($this->constants['KEY_RATING']),
            'comment' => $request->get($this->constants['KEY_COMMENT']),
            'product_id' => $request->get($this->constants['KEY_PRODUCT_ID']),
        ];

        if ($user instanceof \App\Models\Consumer) {
           $data["consumer_id"] = $user->id;
        }else{
            $data["professional_id"] = $user->id;
        }

        Review::create($data);

        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = 'Review Added Successfully';
        return response()->json($response);
    }

}
