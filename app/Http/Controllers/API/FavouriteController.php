<?php

namespace App\Http\Controllers\API;

use Config;
use JWTAuth;
use Validator;
use Carbon\Carbon;
use App\Models\Consumer;
use App\Models\Professional;
use App\Models\Favourite;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavouriteController extends Controller
{
    
    private $constants;
    private $responseConstants;

    public function __construct()
    {
        $this->constants = Config::get('constants.GENERAL_CONSTANTS');
        $this->responseConstants = Config::get('constants.RESPONSE_CONSTANTS');
    }

    public function addFavourite(Request $request)
    {
        $response = [];
        $rules = [
            $this->constants['KEY_PRODUCT_ID'] => 'required',
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
            $exists = Favourite::where('consumer_id', $user->id)->where('product_id', $request->get($this->constants['KEY_PRODUCT_ID']))->first();
        }else{
            $exists = Favourite::where('professional_id', $user->id)->where('product_id', $request->get($this->constants['KEY_PRODUCT_ID']))->first();
        }
 
        if ($exists != null) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Product Already Added to Favourites',
            ]);
        }
        $data = [
            'product_id' => $request->get($this->constants['KEY_PRODUCT_ID']),
        ];

        if ($user instanceof \App\Models\Consumer) {
           $data["consumer_id"] = $user->id;
        }else{
            $data["professional_id"] = $user->id;
        }
        
        Favourite::create($data);
        
        return response()->json([
            'status' => $this->responseConstants['STATUS_SUCCESS'],
            'message' => 'Success.',
        ]);
    }
    
    
    public function removeFavourite(Request $request)
    {
        $response = [];
        $rules = [
            $this->constants['KEY_PRODUCT_ID'] => 'required',
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
            $favourite = Favourite::where('consumer_id', $user->id)->where('product_id', $request->get($this->constants['KEY_PRODUCT_ID']))->first();
        }else{
            $favourite = Favourite::where('professional_id', $user->id)->where('product_id', $request->get($this->constants['KEY_PRODUCT_ID']))->first();
        }
 
        if ($favourite == null) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'No record found.',
            ]);
        }

        $favourite->delete();

        return response()->json([
            'status' => $this->responseConstants['STATUS_SUCCESS'],
            'message' => 'Record deleted successfully.',
        ]);
    }

    public function getFavourites(Request $request)
    {
        $response = [];

        $user = JWTAuth::toUser($request->token);

        $userStatus = $user->_check();
        if($userStatus != null) {
            return response()->json($userStatus);
        }

        if ($user instanceof \App\Models\Consumer) {
            $favouriteIds = Favourite::select('product_id')->where('consumer_id', $user->id)->pluck('product_id')->toArray();
            
        }else{
            $favouriteIds = Favourite::select('product_id')->where('professional_id', $user->id)->pluck('product_id')->toArray();
        }

        $favourites = Product::select('id', 'title', 'price', 'offer_price','offer_available', 'thumbnail', 'product_type', 'description')->whereIn('id', $favouriteIds)->where('available_quantity', '>', 0)->where('is_deleted', 0)->get();
 
        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['favourites'] = $favourites;
        $response['message'] = 'Success';
        return response()->json($response);
    }
}
