<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Category;
use App\Models\Consumer;
use App\Models\Coupon;
use App\Models\Discount;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Setting;
use Carbon\Carbon;
use Config;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use JWTAuth;
use Mail;
use PDF;
use Validator;
use App\Models\Notification;

use App\Models\User;

class OrderController extends Controller
{

    private $constants;
    private $responseConstants;

    public function __construct()
    {
        $this->constants = Config::get('constants.ORDER_CONSTANTS');
        $this->responseConstants = Config::get('constants.RESPONSE_CONSTANTS');
    }

    public function placeOrder(Request $request)
    {

        $response = [];
        $rules = [
            $this->constants['KEY_GROSS_TOTAL'] => 'required',
            $this->constants['KEY_NET_TOTAL'] => 'required',
            $this->constants['KEY_DISCOUNT_SLAB_ID'] => 'nullable',
            $this->constants['KEY_DISCOUNT_AMOUNT'] => 'nullable',
            $this->constants['KEY_COUPON_CODE'] => 'nullable|string',
            $this->constants['KEY_COUPON_DISCOUNT_AMOUNT'] => 'nullable|string',
            $this->constants['KEY_SHIPPING_ADDRESS_ID'] => 'required',
            $this->constants['KEY_PAYMENT_METHOD'] => 'required',
            $this->constants['KEY_FIX_DISCOUNT'] => 'required',
            $this->constants['KEY_PRODUCTS'] => 'required|array',
            $this->constants['KEY_PRODUCTS'] . '.*.' . $this->constants['KEY_PRODUCT_ID'] => 'required',
            $this->constants['KEY_PRODUCTS'] . '.*.' . $this->constants['KEY_PRODUCT_QUANTITY'] => 'required',
            $this->constants['KEY_PRODUCTS'] . '.*.' . $this->constants['KEY_PRODUCT_FRAME_COLOR'] => 'required',
            $this->constants['KEY_PRODUCTS'] . '.*.' . $this->constants['KEY_PRODUCT_LEFT_ENGRAVE'] => 'required',
            $this->constants['KEY_PRODUCTS'] . '.*.' . $this->constants['KEY_PRODUCT_RIGHT_ENGRAVE'] => 'required',
            $this->constants['KEY_PRODUCTS'] . '.*.' . $this->constants['KEY_PRODUCT_SIZE'] => 'required',
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['INVALID_PARAMETERS'],
            ]);
        }
        // if( $_SERVER['REMOTE_ADDR'] == "103.62.235.89") {
        //     dd($request->all());
        // }
        if($request->get($this->constants['KEY_GROSS_TOTAL']) == 0 || $request->get($this->constants['KEY_NET_TOTAL']) == 0) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => "we are unable to place your order due to some technical reasons. Please try again later",
            ]);
        }
        $user = JWTAuth::toUser($request->token);

        $userStatus = $user->_check();
        if ($userStatus != null) {
            return response()->json($userStatus);
        }

        // check user addresses
        if (Address::where('id', $request->get($this->constants['KEY_SHIPPING_ADDRESS_ID']))->where('address_type', '1')->first() == null) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Invalid address.',
            ]);
        }

        if( !$request->has($this->constants['KEY_PRODUCTS']) || count($request->get($this->constants['KEY_PRODUCTS'])) <= 0) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Order is empty.',
            ]);
        }

        foreach ($request->get($this->constants['KEY_PRODUCTS']) as $orderItem) {
            $product = Product::find($orderItem[$this->constants['KEY_PRODUCT_ID']]);

            if (empty($orderItem[$this->constants['KEY_PRODUCT_QUANTITY']])) {
                return response()->json([
                    'status' => $this->responseConstants['STATUS_ERROR'],
                    'message' => 'Invalid product qunatity.',
                ]);
            }

            if ($product->available_quantity < $orderItem[$this->constants['KEY_PRODUCT_QUANTITY']]) {
                return response()->json([
                    'status' => $this->responseConstants['STATUS_INVALID_PRODUCT_QUANTITY'],

                    'message' => 'Invalid product quantity.',
                ]);
            }
        }


        // creating order data
        $orderData = [
            'net_total' => $request->get($this->constants['KEY_NET_TOTAL']),
            'gross_total' => $request->get($this->constants['KEY_GROSS_TOTAL']),
            'address_id' => $request->get($this->constants['KEY_SHIPPING_ADDRESS_ID']),
            'payment_method' => $request->get($this->constants['KEY_PAYMENT_METHOD']),
            // 'fix_discount' => $request->get($this->constants['KEY_FIX_DISCOUNT']),

        ];

        if ($user instanceof \App\Models\Consumer) {
            $orderData["consumer_id"] = $user->id;
        } else {
            $orderData["professional_id"] = $user->id;
        }

        $coupon = null;
        $couponDiscount = 0;
        $slabDiscount = 0;
        $fixUserDiscount = 0;

        // check coupon data
        if ($request->has($this->constants['KEY_COUPON_CODE']) && $request->get($this->constants['KEY_COUPON_CODE']) != null) {
            $coupon = Coupon::where('coupon_code', $request->get($this->constants['KEY_COUPON_CODE']))->first();
            
            if (!$this->_validCoupon($coupon)) {
                return response()->json([
                    'status' => $this->responseConstants['STATUS_INVALID_COUPON_CODE'],
                    'message' => $this->responseConstants['ERROR_INVALID_COUPON_CODE'],
                ]);

            } else {
                $couponDiscount = $request->get($this->constants['KEY_COUPON_DISCOUNT_AMOUNT']);
                $orderData['coupon_code'] = $request->get($this->constants['KEY_COUPON_CODE']);
                $orderData['coupon_discount_amount'] = $request->get($this->constants['KEY_COUPON_DISCOUNT_AMOUNT']);
                $coupon->update(['is_used' => 1]);
            }
        }

        if ($request->has($this->constants['KEY_DISCOUNT_SLAB_ID']) && $request->get($this->constants['KEY_DISCOUNT_AMOUNT']) != null) {
            $orderData['discount_id'] = $request->get($this->constants['KEY_DISCOUNT_SLAB_ID']);
            $orderData['discount_amount'] = $request->get($this->constants['KEY_DISCOUNT_AMOUNT']);
            $slabDiscount = $request->get($this->constants['KEY_DISCOUNT_AMOUNT']);
        }

        if ($request->has($this->constants['KEY_FIX_DISCOUNT']) && $request->get($this->constants['KEY_FIX_DISCOUNT']) != null) {
            $orderData['fix_discount'] = $request->get($this->constants['KEY_FIX_DISCOUNT']);
            $fixUserDiscount = $request->get($this->constants['KEY_FIX_DISCOUNT']);
        }

        if (!empty($couponDiscount) && $couponDiscount > $slabDiscount && $couponDiscount > $fixUserDiscount) {
            $orderData['discount_id'] = NULL;
            $orderData['discount_amount'] = 0.00;
            $orderData['fix_discount'] = 0.00;

        } else if (!empty($slabDiscount) && $slabDiscount > $couponDiscount && $slabDiscount > $fixUserDiscount) {
            $orderData['coupon_code'] = NULL;
            $orderData['coupon_discount_amount'] = 0.00;
            $orderData['fix_discount'] = 0.00;

        }else if(!empty($fixUserDiscount) && $fixUserDiscount > $couponDiscount && $fixUserDiscount > $slabDiscount){
            $orderData['coupon_code'] = NULL;
            $orderData['coupon_discount_amount'] = 0.00;
            $orderData['discount_id'] = NULL;
            $orderData['discount_amount'] = 0.00;
        }

        if ($request->has($this->constants['KEY_DELIVERY_CHARGES']) && $request->get($this->constants['KEY_DELIVERY_CHARGES']) != null) {
            $orderData['delivery_charges'] = $request->get($this->constants['KEY_DELIVERY_CHARGES']);
        }

        $order = Order::create($orderData);

        foreach ($request->get($this->constants['KEY_PRODUCTS']) as $orderItem) {
            $orderItemData = [];
            $product = Product::find($orderItem[$this->constants['KEY_PRODUCT_ID']]);

            if ($product != null && $product->available_quantity >= $orderItem[$this->constants['KEY_PRODUCT_QUANTITY']]) {

                $orderItemData['order_id'] = $order->id;
                $orderItemData['product_id'] = $product->id;
                $orderItemData['quantity'] = $orderItem[$this->constants['KEY_PRODUCT_QUANTITY']];

                if ($product->product_type == '1') {

                    if ($product->offer_available && $product->offer_price != 0) {
                        // offer_price
                        $orderItemData['unit_price'] = $product->offer_price;
                        $orderItemData['sub_total'] = $product->offer_price * $orderItem[$this->constants['KEY_PRODUCT_QUANTITY']];
                    } else {

                        $orderItemData['unit_price'] = $product->price;
                        $orderItemData['sub_total'] = $product->price * $orderItem[$this->constants['KEY_PRODUCT_QUANTITY']];
                    }

                } else {

                    if ($user instanceof \App\Models\Professional) {
                        if ($product->offer_available && $product->offer_price != 0) {
                            // offer_price
                            $orderItemData['unit_price'] = $product->offer_price - ($product->offer_price * 0.10);
                            $orderItemData['sub_total'] = ($product->offer_price - ($product->offer_price * 0.10)) * $orderItem[$this->constants['KEY_PRODUCT_QUANTITY']];
                        } else {

                            $orderItemData['unit_price'] = $product->price - ($product->price * 0.10);
                            $orderItemData['sub_total'] = ($product->price - ($product->price * 0.10)) * $orderItem[$this->constants['KEY_PRODUCT_QUANTITY']];
                        }
                    } else {
                        if ($product->offer_available && $product->offer_price != 0) {
                            // offer_price

                            $orderItemData['unit_price'] = $product->offer_price;
                            $orderItemData['sub_total'] = $product->offer_price * $orderItem[$this->constants['KEY_PRODUCT_QUANTITY']];
                        } else {

                            $orderItemData['unit_price'] = $product->price;
                            $orderItemData['sub_total'] = $product->price * $orderItem[$this->constants['KEY_PRODUCT_QUANTITY']];
                        }
                    }

                }
                if (!is_null($request->get($this->constants['KEY_COLOR_ID'])) && !empty($request->get($this->constants['KEY_COLOR_ID']))) {
                    $orderItemData['product_color_id'] = $request->get($this->constants['KEY_COLOR_ID']);
                }

                if (!empty($orderItem[$this->constants['KEY_PRODUCT_FRAME_COLOR']]) || !empty($orderItem[$this->constants['KEY_PRODUCT_LEFT_ENGRAVE']]) || !empty($orderItem[$this->constants['KEY_PRODUCT_RIGHT_ENGRAVE']]) || !empty($orderItem[$this->constants['KEY_PRODUCT_SIZE']])) {
                Log::info("check here");
                    $orderItemData['product_request'] = json_encode([
                        'frame_color' => $orderItem[$this->constants['KEY_PRODUCT_FRAME_COLOR']] ?? null,
                        'left_engrave' => $orderItem[$this->constants['KEY_PRODUCT_LEFT_ENGRAVE']] ?? null,
                        'right_engrave' => $orderItem[$this->constants['KEY_PRODUCT_RIGHT_ENGRAVE']] ?? null,
                        'size' => $orderItem[$this->constants['KEY_PRODUCT_SIZE']] ?? null,
                    ]);
                }

                OrderDetail::create($orderItemData);

                $quantity = $product->available_quantity - $orderItem[$this->constants['KEY_PRODUCT_QUANTITY']];
                $product->update(['available_quantity' => $quantity]);
            }
        }

        $order = Order::with('address')->where('id', $order->id)->first();

        $orderItems = OrderDetail::select(
            DB::raw('order_details.quantity as productQuantity,
            order_details.sub_total as productSubTotal,
            order_details.unit_price as productPrice,
            products.prod_id as productId,
            products.title as productTitle,
            categories.id as productCategoryId'))
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('order_details.order_id', $order->id)
            ->get();

        $orderCategories = Category::select(
            DB::raw('categories.id as productCategoryId, categories.title as productCategoryTitle'))
            ->join('products', 'products.category_id', '=', 'categories.id')
            ->join('order_details', 'order_details.product_id', '=', 'products.id')
            ->where('order_details.order_id', $order->id)
            ->groupBy('categories.id')
            ->get();

        /*try{
            $pdf = PDF::loadView('admin.orders.exportOrder', ['order' => $order, 'orderItems' => $orderItems, 'user' => $user, 'orderCategories' => $orderCategories])->setPaper('a4');
            Storage::put($order->id . '.pdf', $pdf->output());
        }catch (\Exception $e) {
        }*/
        /*try {
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
                $message->to('kabeer@live.hk')->subject('Postquam New Order.');
            });

            $email = $user->email;
            $path = Storage::path($order->id . '.pdf');
            Mail::send('mail.userOrderEmail', $data, function ($message) use ($email, $path, $order) {
                $message->from('admin@postquam.com', 'Postquam Admin');
                $message->to($email)->subject('Postquam Order Summary.');
                $message->attach($path, [
                    'as' => 'Order ' . $order->invoice_id . '.pdf',
                    'mime' => 'application/pdf',
                ]);
            });

        } catch (\Exception $e) {
        }*/

        if (isset($user->token)) {
            app('App\Http\Controllers\FCMController')->sendNotification('Order Placed Successfully.', $user->full_name.' your order has been placed successfully.', [], $user->token);

        }

         if ($user instanceof \App\Models\Consumer) {
            $orderData["consumer_id"] = $user->id;
        } else {
            $orderData["professional_id"] = $user->id;
        }


         $noti = [];
        if ($user instanceof \App\Models\Consumer) {
            $noti['consumer_id'] = $user->id;
        } else {
            $noti['professional_id'] = $user->id;
        }

        $noti['content'] = $user->full_name.' your order has been placed successfully.';
        Notification::create($noti);


        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = 'Order placed successfully.';
        return response()->json($response);
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

    public function getMyOrders(Request $request)
    {
        $response = [];

        $user = JWTAuth::toUser($request->token);


        $userStatus = $user->_check();
        if ($userStatus != null) {
            return response()->json($userStatus);
        }

        if ($user instanceof \App\Models\Consumer) {
            $orders = Order::select('id', 'invoice_id', 'gross_total', 'net_total', 'order_status', 'created_at')->where('consumer_id', $user->id)->get();

        } else {
            $orders = Order::select('id', 'invoice_id', 'gross_total', 'net_total', 'order_status', 'created_at')->where('professional_id', $user->id)->get();

        }

        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = 'Success';
        $response['orders'] = $orders;
        return response()->json($response);
    }

    public function getOrderDetail(Request $request)
    {
        $response = [];

        $validator = Validator::make($request->all(), [$this->constants['KEY_ORDER_ID'] => 'required']);

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

        if ($user instanceof \App\Models\Consumer) {
            $order = Order::where('id',$request->get($this->constants['KEY_ORDER_ID']))->where('consumer_id', $user->id)->first();

        } else {
            $order = Order::where('id',$request->get($this->constants['KEY_ORDER_ID']))->where('professional_id', $user->id)->first();

        }

        if($order == null){
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Invalid Order Id.',
            ]);
        }

        $orderProducts = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->select('products.id', 'products.title', 'order_details.unit_price as price', 'order_details.quantity as order_quantity', 'products.thumbnail')
            ->where('orders.id', $order->id)
            ->get();

        // creating response data
        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = 'Success';
        $response['order'] = $order;
        $response['orderProducts'] = $orderProducts;
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
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['INVALID_PARAMETERS'],
            ]);
        }

        $coupon = Coupon::where('coupon_code', $request->get($this->constants['KEY_COUPON_CODE']))->first();

        if ($coupon == null || !$this->_validCoupon($coupon)) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_INVALID_COUPON_CODE'],
                'message' => $this->responseConstants['ERROR_INVALID_COUPON_CODE'],
            ]);

        } else {
            $offerPercentage = Offer::find($coupon->offer_id);
            return response()->json([
                'status' => $this->responseConstants['STATUS_SUCCESS'],
                'discount_percentage' => $offerPercentage->discount_percentage,
                'message' => 'Coupon Code is Valid.',
            ]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $response = [];
        $rules = [
            $this->constants['KEY_ORDER_ID'] => 'required',
            $this->constants['KEY_ORDER_STATUS'] => 'required',
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

        $noti = [];

        if ($user instanceof \App\Models\Consumer) {
            $order = Order::where('id',$request->get($this->constants['KEY_ORDER_ID']))->where('consumer_id', $user->id)->first();
            $noti['consumer_id'] = $user->id;
        } else {
            $order = Order::where('id',$request->get($this->constants['KEY_ORDER_ID']))->where('professional_id', $user->id)->first();
            $noti['professional_id'] = $user->id;
        }

        $noti['content'] = $user->full_name.' your order '.$order->invoice_id.' has been updated to '.$request->input('order_status');
        Notification::create($noti);

        if ($order == null) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Invalid order ID.',
            ]);
        }
        if (isset($user->token)) {
            $order->update(['order_status' => 'Delivered']);
            app('App\Http\Controllers\FCMController')->sendNotification('Order Placed Successfully.', $user->full_name.' your order '.$order->invoice_id.' has been updated to Delivered.', [], $user->token);

        }
        $order->update(['order_status' => 'Delivered']);


        return response()->json([
            'status' => $this->responseConstants['STATUS_SUCCESS'],
            'message' => 'Order updated successfully.',
        ]);
    }

    public function listOrdersForAdmin(Request $request){
        $response = [];
        $rules = [
            $this->constants['KEY_ADMIN_EMAIL'] => 'required',
            $this->responseConstants['KEY_PAGE_NO'] => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['INVALID_PARAMETERS'],
            ]);
        }

        $user = User::where('email', $request->get($this->constants['KEY_ADMIN_EMAIL']))
        ->first();

        if(empty($user)){
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Invalid User.',
            ]);
        }

        $orders1Ids = Order::select(
            DB::raw('orders.*'))
            ->join('consumers', 'orders.consumer_id', '=', 'consumers.id')
            ->where('consumers.is_deleted', 0)->pluck('orders.id')->toArray();

        $orders2Ids = Order::select(
            DB::raw('orders.*'))
            ->join('professionals', 'orders.professional_id', '=', 'professionals.id')
            ->where('professionals.is_deleted', 0)->pluck('orders.id')->toArray();

        $ordersIds = array_unique(array_merge($orders1Ids, $orders2Ids));

        $orders = Order::select(
            DB::raw('orders.*, orders.created_at as order_date'))
            ->whereIn('orders.id', $ordersIds);

        $totalPages = $orders->count() / $this->responseConstants['KEY_RECORD_PER_PAGE'];

        $totalPages = ceil($totalPages);

        $pages = $request->get($this->responseConstants['KEY_PAGE_NO']) * $this->responseConstants['KEY_RECORD_PER_PAGE'];

        $orders = $orders
        // ->offset($pages)
        // ->limit($this->responseConstants['KEY_RECORD_PER_PAGE'])
        ->orderBy('orders.created_at', 'DESC')
        ->get();


        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = 'Success';
        $response['orders'] = $orders;
        return response()->json($response);

    }

    public function orderDetailForAdmin(Request $request){
        $response = [];
        $rules = [
            $this->constants['KEY_ADMIN_EMAIL'] => 'required',
            $this->constants['KEY_ORDER_ID'] => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['INVALID_PARAMETERS'],
            ]);
        }

        $user = User::where('email', $request->get($this->constants['KEY_ADMIN_EMAIL']))
        ->first();

        if(empty($user)){
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Invalid User.',
            ]);
        }

        $order = Order::where('id',$request->get($this->constants['KEY_ORDER_ID']))
        ->first();

        if(empty($order)){
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Invalid Order Id.',
            ]);
        }

        $orderProducts = DB::table('order_details')
        ->join('products', 'order_details.product_id', '=', 'products.id')
        ->join('orders', 'order_details.order_id', '=', 'orders.id')
        ->select('products.id', 'products.title', 'order_details.unit_price as price', 'order_details.quantity as order_quantity', 'products.thumbnail')
        ->where('orders.id', $order->id)
        ->get();

        // creating response data
        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = 'Success';
        $response['order'] = $order;
        $response['orderProducts'] = $orderProducts;
        return response()->json($response);

    }

    public function orderStatusUpdateForAdmin(Request $request){
        $response = [];
        $rules = [
            $this->constants['KEY_ADMIN_EMAIL'] => 'required',
            $this->constants['KEY_ORDER_ID'] => 'required',
            $this->constants['KEY_ORDER_STATUS'] => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['INVALID_PARAMETERS'],
            ]);
        }

        $user = User::where('email', $request->get($this->constants['KEY_ADMIN_EMAIL']))
        ->first();

        if(empty($user)){
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Invalid User.',
            ]);
        }

        $order = Order::where('id',$request->get($this->constants['KEY_ORDER_ID']))
        ->first();

        if(empty($order)){
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Invalid Order Id.',
            ]);
        }

        $order->update(['order_status' => $request->get($this->constants['KEY_ORDER_STATUS'])]);


        // creating response data
        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = 'Updated Order Status.';
        return response()->json($response);

    }

public function orderPaymentStatusUpdateForAdmin(Request $request){
    $response = [];
    $rules = [
        $this->constants['KEY_ADMIN_EMAIL'] => 'required',
        $this->constants['KEY_ORDER_ID'] => 'required',
        $this->constants['KEY_ORDER_PAYMENT_STATUS'] => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'status' => $this->responseConstants['STATUS_ERROR'],
            'message' => $this->responseConstants['INVALID_PARAMETERS'],
        ]);
    }

    $user = User::where('email', $request->get($this->constants['KEY_ADMIN_EMAIL']))
    ->first();

    if(empty($user)){
        return response()->json([
            'status' => $this->responseConstants['STATUS_ERROR'],
            'message' => 'Invalid User.',
        ]);
    }

    $order = Order::where('id',$request->get($this->constants['KEY_ORDER_ID']))
    ->first();

    if(empty($order)){
        return response()->json([
            'status' => $this->responseConstants['STATUS_ERROR'],
            'message' => 'Invalid Order Id.',
        ]);
    }

    $order->update(['payment_status' => $request->get($this->constants['KEY_ORDER_PAYMENT_STATUS'])]);


    // creating response data
    $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
    $response['message'] = 'Updated Order Payment Status.';
    return response()->json($response);

}

    public function searchOrder(Request $request)
    {
        $response = [];
        $rules = [
            // $this->constants['KEY_DATE_FROM'] => 'required',
            // $this->constants['KEY_DATE_TO'] => 'required',
            // $this->constants['KEY_ORDER_STATUS'] => 'required',
            // $this->constants['KEY_ORDER_PAYMENT_STATUS'] => 'required',
            $this->constants['KEY_ADMIN_EMAIL'] => 'required',
            $this->responseConstants['KEY_PAGE_NO'] => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => $this->responseConstants['INVALID_PARAMETERS'],
            ]);
        }

        $user = User::where('email', $request->get($this->constants['KEY_ADMIN_EMAIL']))
        ->first();

        if(empty($user)){
            return response()->json([
                'status' => $this->responseConstants['STATUS_ERROR'],
                'message' => 'Invalid User.',
            ]);
        }

        $orders1Ids = Order::select(
            DB::raw('orders.*'))
            ->join('consumers', 'orders.consumer_id', '=', 'consumers.id')
            ->where('consumers.is_deleted', 0)->pluck('orders.id')->toArray();

        $orders2Ids = Order::select(
            DB::raw('orders.*'))
            ->join('professionals', 'orders.professional_id', '=', 'professionals.id')
            ->where('professionals.is_deleted', 0)->pluck('orders.id')->toArray();

        $ordersIds = array_unique(array_merge($orders1Ids, $orders2Ids));

        $orders = Order::select(
            DB::raw('orders.*, orders.created_at as order_date'))
            ->whereIn('orders.id', $ordersIds);

        if ($request->has($this->constants['KEY_DATE_FROM']) && !empty($request->get($this->constants['KEY_DATE_FROM'])))
        {
            $orders->where('orders.created_at', '>=', date('Y-m-d 00:00:00', strtotime($request->get($this->constants['KEY_DATE_FROM']))));
        }

        if ($request->has($this->constants['KEY_DATE_TO']) && !empty($request->get($this->constants['KEY_DATE_TO'])))
        {
            $orders->where('orders.created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->get($this->constants['KEY_DATE_TO']))));
        }

        if ($request->has($this->constants['KEY_ORDER_STATUS']) && !empty($request->get($this->constants['KEY_ORDER_STATUS'])))
        {
            $orders->where('orders.order_status', $request->get($this->constants['KEY_ORDER_STATUS']));
        }

        if ($request->has($this->constants['KEY_ORDER_PAYMENT_STATUS']) && !empty($request->get($this->constants['KEY_ORDER_PAYMENT_STATUS'])))
        {
            $orders->where('orders.payment_status', $request->get($this->constants['KEY_ORDER_PAYMENT_STATUS']));
        }

        $totalPages = $orders->count() / $this->responseConstants['KEY_RECORD_PER_PAGE'];

        $totalPages = ceil($totalPages);

        $pages = $request->get($this->responseConstants['KEY_PAGE_NO']) * $this->responseConstants['KEY_RECORD_PER_PAGE'];

        $orders = $orders
        ->offset($pages)
        ->limit($this->responseConstants['KEY_RECORD_PER_PAGE'])
        ->orderBy('orders.created_at', 'DESC')
        ->get();

        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = 'Success';
        $response['orders'] = $orders;
        return response()->json($response);
    }

    public function getMiscellaneousData(Request $request)
    {
        $setting = Setting::first();
        $user = JWTAuth::toUser($request->token);

        $data = [];

        if (isset($user) && $request->net_total > 0) {
            if ($user instanceof \App\Models\Consumer) {
                $data["consumer_id"] = $user->id;
                $data['min_order'] = $setting->min_order_consumer;
                $data['vat'] = $setting->vat_consumer;

                if ($request->get('net_total') < $setting->min_order_consumer) {
                    $data['delivery_charges'] = $setting->delivery_charges_consumer;
                    $vat_amount = $request->get('net_total') * $setting->vat_consumer / 100;
                    $net_total = $request->get('net_total') + $data['delivery_charges'] + $vat_amount;
                    $data['net_total'] = number_format((float)$net_total, 2, '.', '');
                } else {
                    $data['delivery_charges'] = '0.0';
                    $vat_amount = $request->get('net_total') * $setting->vat_consumer / 100;
                    $net_total = $request->get('net_total') + $data['delivery_charges'] + $vat_amount;
                    $data['net_total'] = number_format((float)$net_total, 2, '.', '');
                }

            } else {
                $data["professional_id"] = $user->id;
                $data['min_order'] = $setting->min_order_professional;
                $data['vat'] = $setting->vat_professional;

                if ($request->get('net_total') < $setting->min_order_professional) {
                    $data['delivery_charges'] = $setting->delivery_charges_professional;
                    $vat_amount = $request->get('net_total') * $setting->vat_professional / 100;
                    $net_total = $request->get('net_total') + $data['delivery_charges'] + $vat_amount;
                    $data['net_total'] = number_format((float)$net_total, 2, '.', '');
                } else {
                    $data['delivery_charges'] = '0.0';
                    $vat_amount = $request->get('net_total') * $setting->vat_professional / 100;
                    $net_total = $request->get('net_total') + $data['delivery_charges'] + $vat_amount;
                    $data['net_total'] = number_format((float)$net_total, 2, '.', '');
                }
            }
        }

        return response()->json([
            'status' => $this->responseConstants['STATUS_SUCCESS'],
            'message' => 'Success',
            'data' => $data,
        ]);
    }
}
