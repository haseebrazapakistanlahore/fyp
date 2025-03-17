<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
use JWTAuth;
use Validator;
use App\CartItem;
use DB;

class CartItemController extends Controller
{
    private $cartConstants;
    private $responseKeys;
    private $recordsPerPage = 6;


    public function __construct()
    {
        $this->cartConstants = Config::get('cartConstants.CONSTANTS');
        $this->responseKeys = Config::get('responseConstants.RESPONSE_KEYS');
    }

    
    public function addToCart(Request $request)
    {
        $response = [];
        $rules = [
            $this->cartConstants['KEY_PRODUCT_ID'] => 'required',
            $this->cartConstants['KEY_PRODUCT_QUANTITY'] => 'required',
            $this->cartConstants['KEY_COLOR_ID'] => 'nullable',
        ];
        
        $validator = Validator::make($request->all(), $rules);
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
        
        $cartItem = CartItem::where('product_id', $request->get($this->cartConstants['KEY_PRODUCT_ID']))
        ->where('user_id', $user->id)->first();

        if ($cartItem != null) {
            $response['status'] = $this->responseKeys['STATUS_ERROR'];
            $response['message'] = 'Item already added to cart.';
            return response()->json($response);
        }


        // creating cart data
        $cartData = [
            'user_id' => $user->id,
            'product_id' => $request->get($this->cartConstants['KEY_PRODUCT_ID']),
            'quantity' => $request->get($this->cartConstants['KEY_PRODUCT_QUANTITY']),
            'color_id' => $request->get($this->cartConstants['KEY_COLOR_ID']),
        ];
        
        $cart = CartItem::create($cartData);

        // creating response data
        $response['status'] = $this->responseKeys['STATUS_SUCCESS'];
        $response['cartItem'] = $cart;
        $response['message'] = 'Item added to cart successfully.';
        return response()->json($response);
    }
    
    
    public function removeFromCart(Request $request)
    {
        $response = [];

        $rules = [
            $this->cartConstants['KEY_CART_ID'] => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
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

        $cart = CartItem::where('id', $request->get($this->cartConstants['KEY_CART_ID']))->where('user_id', $user->id)->first();

        if ($cart == null) {
            $response['status'] = $this->responseKeys['STATUS_ERROR'];
            $response['message'] = 'Item not found';
            return response()->json($response);
        }

        $cart->delete();
        // creating response data
        $response['status'] = $this->responseKeys['STATUS_SUCCESS'];
        $response['message'] = 'Item deleted from cart successfully.';
        return response()->json($response);
    }
    
    public function getMyCart(Request $request)
    {
        $response = [];

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

        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

        if (count($cartItems) == 0) {
            
            $response['status'] = $this->responseKeys['STATUS_ERROR'];
            $response['message'] = 'Your cart is empty.';
            return response()->json($response);
        }

        $cartItems->makeHidden(['created_at', 'updated_at', 'user_id']);

        // creating response data
        $response['status'] = $this->responseKeys['STATUS_SUCCESS'];
        $response['cart'] = $cartItems;
        $response['message'] = 'Success.';
        return response()->json($response);
    }
}
