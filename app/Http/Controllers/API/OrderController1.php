<?php

namespace App\Http\Controllers\API;

use Config;
use JWTAuth;
use Validator;
use Carbon\Carbon;
use App\Address;
use App\Coupon;
use App\Offer;
use App\Order;
use App\Product;
use App\OrderDetail;
use App\User;
use App\Discount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Mail;

class OrderController1 extends Controller
{

    private $constants;
    private $responseKeys;

    public function __construct()
    {
        $this->constants = Config::get('orderConstants.CONSTANTS');
        $this->responseKeys = Config::get('responseConstants.RESPONSE_KEYS');
    }

    public function placeOrder(Request $request)
    {
        $response = [];
        $rules = [
            $this->constants['KEY_GROSS_TOTAL'] => 'required',
            $this->constants['KEY_NET_TOTAL'] => 'required',
            $this->constants['KEY_DISCOUNT_ID'] => 'nullable',
            $this->constants['KEY_DISCOUNT_AMOUNT'] => 'nullable',
            $this->constants['KEY_COUPON_CODE'] => 'nullable|string',
            $this->constants['KEY_COUPON_DISCOUNT_AMOUNT'] => 'nullable|string',
            $this->constants['KEY_BILLING_ADDRESS_ID'] => 'required',
            $this->constants['KEY_SHIPPING_ADDRESS_ID'] => 'required',
            $this->constants['KEY_PAYMENT_METHOD'] => 'required',
            // $this->constants['KEY_PRODUCTS'] => 'required|array',
            // $this->constants['KEY_PRODUCTS'] . '.*.' . $this->constants['KEY_PRODUCT_ID'] => 'required',
            // $this->constants['KEY_PRODUCTS'] . '.*.' . $this->constants['KEY_PRODUCT_QUANTITY'] => 'required',
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

        // check user addresses
        if (Address::find($request->get($this->constants['KEY_BILLING_ADDRESS_ID'])) == null && Address::find($request->get($this->constants['KEY_SHIPPING_ADDRESS_ID'])) == null) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => 'Invalid address.',
            ]);
        }

        // creating order data
        $orderData = [
            'user_id' => $user->id,
            'net_total' => $request->get($this->constants['KEY_NET_TOTAL']),
            'gross_total' => $request->get($this->constants['KEY_GROSS_TOTAL']),
            'address_id' => $request->get($this->constants['KEY_SHIPPING_ADDRESS_ID']),
            'payment_method' => $request->get($this->constants['KEY_PAYMENT_METHOD']),
        ];

        $coupon = null;
        // check coupon data
        if ($request->has($this->constants['KEY_COUPON_CODE']) && $request->get($this->constants['KEY_COUPON_CODE']) != null) {
            $coupon = Coupon::where('coupon_code', $request->get($this->constants['KEY_COUPON_CODE']))->first();
            
            if (!$this->_validCoupon($coupon)) {
                return response()->json([
                    'status' => $this->responseKeys['STATUS_INVALID_COUPON_CODE'],
                    'message' => $this->responseKeys['ERROR_INVALID_COUPON_CODE'],
                ]);
            
            } else {
                $orderData['coupon_code'] = $request->get($this->constants['KEY_COUPON_CODE']);
                $orderData['coupon_discount_amount'] = $request->get($this->constants['KEY_COUPON_DISCOUNT_AMOUNT']);
                $coupon->update(['is_used'=> 1]);
            }
        }
        
        // check discount data
        if ($request->has($this->constants['KEY_DISCOUNT_ID']) && $request->get($this->constants['KEY_DISCOUNT_AMOUNT']) != null) {
            $orderData['discount_id'] = $request->get($this->constants['KEY_DISCOUNT_ID']);
            $orderData['discount_amount'] = $request->get($this->constants['KEY_DISCOUNT_AMOUNT']);
        }

        $order = Order::create($orderData);

        // $order = Order::where('id', '9e770692-1eba-4efa-a696-becd24f6be60')->first();
        // if ($request->has($this->constants['KEY_COUPON_CODE']) && $request->get($this->constants['KEY_COUPON_CODE']) != null) {
        //     $coupon->update(['is_used'=> 1]);
        // }

        // foreach ($request->get($this->constants['KEY_PRODUCTS']) as $orderItem) {
        //     $orderItemData = [];
        //     $product = Product::find($orderItem[$this->constants['KEY_PRODUCT_ID']]);
        //     if ($product != null) {
         
        //         $orderItemData['order_id'] = $order->id;
        //         $orderItemData['product_id'] = $product->id;
        //         $orderItemData['quantity'] = $orderItem[$this->constants['KEY_PRODUCT_QUANTITY']];
            
        //         if ($user->user_type == 1) {
        //             $orderItemData['unit_price'] = $product->price_for_professional;
        //             $orderItemData['sub_total'] = $product->price_for_professional * $orderItem[$this->constants['KEY_PRODUCT_QUANTITY']];
                    
        //         } else {
        //             $orderItemData['unit_price'] = $product->price;
        //             $orderItemData['sub_total'] = $product->price * $orderItem[$this->constants['KEY_PRODUCT_QUANTITY']];
        //         }

        //         OrderDetail::create($orderItemData);
        //     }
        // }
       
        
        // creating response data
        $response['status'] = $this->responseKeys['STATUS_SUCCESS'];
        $response['orderId'] = $order->id;
        $response['message'] = 'Order placed successfully.';
        return response()->json($response);
    }

    public function addOrderProduct(Request $request)
    {
        $response = [];
        $rules = [
            $this->constants['KEY_ORDER_ID'] => 'required',
            $this->constants['KEY_PRODUCT_ID'] => 'required',
            $this->constants['KEY_PRODUCT_QUANTITY'] => 'required',
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

        $order = Order::find($request->get($this->constants['KEY_ORDER_ID']));
        if ($order == null) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => 'Invalid Order.',
            ]);
        }

        $product = Product::find($request->get($this->constants['KEY_PRODUCT_ID']));
        if ($product != null) {
        
            $orderItemData['order_id'] = $order->id;
            $orderItemData['product_id'] = $product->id;
            $orderItemData['quantity'] = $request->get($this->constants['KEY_PRODUCT_QUANTITY']);
        
            if ($product->product_type == '1') {

                if($product->offer_available){
                    // offer_price
                    $orderItemData['unit_price'] = $product->offer_price;
                    $orderItemData['sub_total'] = $product->offer_price * $request->get($this->constants['KEY_PRODUCT_QUANTITY']);
                }else{

                    $orderItemData['unit_price'] = $product->price_for_professional;
                    $orderItemData['sub_total'] = $product->price_for_professional * $request->get($this->constants['KEY_PRODUCT_QUANTITY']);
                }
                
            } else {
                if($product->offer_available){
                    // offer_price
                    $orderItemData['unit_price'] = $product->offer_price;
                    $orderItemData['sub_total'] = $product->offer_price * $request->get($this->constants['KEY_PRODUCT_QUANTITY']);
                }else{

                    $orderItemData['unit_price'] = $product->price;
                    $orderItemData['sub_total'] = $product->price * $request->get($this->constants['KEY_PRODUCT_QUANTITY']);
                }
            }

            if (!is_null($request->get($this->constants['KEY_COLOR_ID'])) && !empty($request->get($this->constants['KEY_COLOR_ID']))) {
                $orderItemData['product_color_id'] = $request->get($this->constants['KEY_COLOR_ID']);
            }
            OrderDetail::create($orderItemData);

            $quantity = $product->available_quantity - $request->get($this->constants['KEY_PRODUCT_QUANTITY']);
            $product->update(['available_quantity' => $quantity]);
            

            if($request->get('is_last')  == 1){

                
                $orderItems = OrderDetail::select(
                    DB::raw('order_details.quantity as productQuantity,
                    order_details.sub_total as productSubTotal,
                    order_details.unit_price as productPrice,
                    products.prod_id as productId,
                    products.title as productTitle'))
                    ->join('products', 'order_details.product_id', '=', 'products.id')
                    ->where('order_details.order_id', $order->id)
                    ->get();

                try {
                    $data = [
                        'full_name' => $user->full_name,
                        'order_id' => $order->invoice_id,
                        'gross_total' => $order->gross_total,
                        'discount' => $order->discount_amount,
                        'coupon_discount' => $order->coupon_discount_amount,
                        'net_total' => $order->net_total,
                    ];
        
                    $email = $user->email;
                    Mail::send('mail.adminOrderEmail', $data, function ($message) use ($email) {
                        $message->from('admin@postquam.com', 'Postquam Admin');
                        $message->to('waqas.tektiks@gmail.com')->subject('Postquam New Order.');
                    });
        
                   
        
                    $email = $user->email;
        
                    Mail::send('mail.userOrderEmail', $data, function ($message) use ($email) {
                        $message->from('admin@postquam.com', 'Postquam Admin');
                        $message->to($email)->subject('Postquam Order Summary.');
                    });
        
                } catch (\Exception $e) {
                    $response['message'] = 'Order placed successfully.';
                }
            }

            return response()->json([
                'status' => $this->responseKeys['STATUS_SUCCESS'],
                'message' => 'Item added successfully.',
            ]);

        }else{
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => 'Invalid Product.',
            ]);
        }
    }

    public function _validCoupon($coupon)
    {
        $offer = Offer::find($coupon->offer_id);
        
        if ($offer == null) {
            return false;
        }
        
        if ($offer->is_active == 0) {
            return false;
        }

        if (strtotime($offer->start_date) > strtotime((Carbon::now())->format("Y-m-d H:i:s"))) {
            return false;
        }

        if (strtotime((Carbon::now())->format("Y-m-d H:i:s")) > strtotime($offer->end_date)) {
            return false;
        }

        if ($offer->is_active == 0) {
            return false;
        }

        if ($coupon->is_used == 1) {
            return false;
        }
        return true;
    }

    public function test(Request $request)
    {
        $order = Order::find('6d8ef400-77ca-11e9-9fbe-7d66765a2bdd');
        $user = User::find('0e919640-6a69-11e9-9fb5-09343a453fe0');
         
        $orderItems = OrderDetail::select(
            DB::raw('order_details.quantity as productQuantity,
            order_details.sub_total as productSubTotal,
            order_details.unit_price as productPrice,
            products.title as productTitle'))
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('order_details.order_id', $order->id)
            ->get();
            
        try {
            $data = [
                'full_name' => $user->full_name,
                'order_id' => $order->invoice_id,
                'gross_total' => $order->gross_total,
                'discount' => $order->discount_amount,
                'coupon_discount' => $order->coupon_discount_amount,
                'net_total' => $order->net_total,
                'orderItems' => $orderItems
            ];

            $email = $user->email;
            Mail::send('mail.adminOrderEmail', $data, function ($message) use ($email) {
                $message->from('admin@postquam.com', 'Postquam Admin');
                // $message->to('waqas.tektiks@gmail.com')->subject('Postquam New Order.');
            });

           

            $email = $user->email;

            Mail::send('mail.userOrderEmail', $data, function ($message) use ($email) {
                $message->from('admin@postquam.com', 'Postquam Admin');
                $message->to($email)->subject('Postquam Order Summary.');
            });

        } catch (\Exception $e) {
            $response['message'] = 'Order placed successfully.';
        }
    }


    public function getMyOrders(Request $request)
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

        $user = User::with('orders')->where('id', $user->id)->first();
        $user->makeHidden(['created_at', 'updated_at', 'is_deleted', 'last_login', 'is_authorized', 'user_type', 'is_active']);

        if($user->user_type == 0){
            $user->makeHidden(['contact_person', 'company_name', 'company_website', 'contact_no']);
        }

        // creating response data
        $response['status'] = $this->responseKeys['STATUS_SUCCESS'];
        $response['message'] = 'Success';
        $response['user'] = $user;
        return response()->json($response);
    }
    
    public function getOrderDetail(Request $request)
    {
        $response = [];
        
        $validator = Validator::make($request->all(), [$this->constants['KEY_ORDER_ID'] => 'required']);
        
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

        $order = Order::find($request->get($this->constants['KEY_ORDER_ID']));
        $order = Order::with('orderDetails')->where('id', $request->get($this->constants['KEY_ORDER_ID']))->first();
        
        if ($order == null) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => 'Invalid Order Id.',
            ]);
        }

        if($order->user_id != $user->id){
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => 'Invalid request',
            ]);
        }

        $orderProducts = DB::table('order_details')
        ->join('products', 'order_details.product_id', '=', 'products.id')
        ->join('orders', 'order_details.order_id', '=', 'orders.id')
        ->select('products.id', 'products.title', 'products.description',  'order_details.unit_price as price', 'order_details.quantity as order_quantity',  'products.thumbnail')
        ->where('orders.id', $order->id)
        ->get();


        // creating response data
        $response['status'] = $this->responseKeys['STATUS_SUCCESS'];
        $response['message'] = 'Success';
        $response['orderProducts'] = $orderProducts;
        $response['order'] = $order;
        return response()->json($response);
    }

    public function checkCouponCode(Request $request)
    {

        $response = [];
        $rules = [
            $this->constants['KEY_COUPON_CODE'] => 'required|string',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => $this->responseKeys['INVALID_PARAMETERS'],
            ]);
        }

        $coupon = Coupon::where('coupon_code', $request->get($this->constants['KEY_COUPON_CODE']))->first();
        
        if ($coupon == null || !$this->_validCoupon($coupon) ) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_INVALID_COUPON_CODE'],
                'message' => $this->responseKeys['ERROR_INVALID_COUPON_CODE'],
            ]);
        
        } else {
            $offerPercentage = Offer::find($coupon->offer_id);
            return response()->json([
                'status' => $this->responseKeys['STATUS_SUCCESS'],
                'discount_percentage' => $offerPercentage->discount_percentage,
                'message' => 'Coupon Code is Valid.',
            ]);
        }
    }

    public function getOrderDiscount(Request $request)
    {
        
        $response = [];
        $rules = [
            $this->constants['KEY_NET_TOTAL'] => 'required',
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

        $discount = Discount::where('min_amount', '<=', $request->get($this->constants['KEY_NET_TOTAL']))
        ->where('max_amount', '>=', $request->get($this->constants['KEY_NET_TOTAL']))
        ->where('start_date', '<=',(Carbon::now())->format("Y-m-d"))
        ->where('end_date', '>=',(Carbon::now())->format("Y-m-d"))
        ->first();

        if ($discount == null) {
            return response()->json([
                'status' => $this->responseKeys['STATUS_ERROR'],
                'message' => 'No discount is applicable',
            ]);

        } else {
            return response()->json([
                'status' => $this->responseKeys['STATUS_SUCCESS'],
                'percentage' => $discount->discount_percentage,
                'message' => 'Discount is applicable.',
            ]);

        }
    }
}
