<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\OrderFilter;
use App\Models\Category;
use App\Models\Consumer;
use App\Models\Professional;
use PDF;
use DB;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Mail;
use App\Models\Notification;
use Illuminate\Support\Collection;
class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders1 = Order::select(
            DB::raw('orders.*'))
            ->join('consumers', 'orders.consumer_id', '=', 'consumers.id')
            ->where('consumers.is_deleted', 0)
            ->where('orders.is_deleted', 0)
            ->get();
            
        $orders2 = Order::select(
            DB::raw('orders.*'))
            ->join('professionals', 'orders.professional_id', '=', 'professionals.id')
            ->where('professionals.is_deleted', 0)
            ->where('orders.is_deleted', 0)
            ->get();
            $orders = $orders1->merge($orders2);
        
        
        
        // $clientIP = \Request::getClientIp(true);
        
        // if($clientIP == '103.12.199.43') {
        //     $orders = $orders->sortByDesc(function($order){
        //         return $order->created_at;
        //     });
        // }
            
        $orders = $orders->sortByDesc(function($order){
            return $order->created_at;
        });
        
        // $orders = Order::orderBy('created_at', 'DESC')->get();
        // dd($orders);
        $orderStatuses = ['Pending', 'Confirmed', 'In Process',  'Completed', 'Dispatched', 'Delivered'];
        $ordersTotal = $orders->sum('net_total');
        return view('admin.orders.index', ['title' => 'Orders', 'orders' => $orders, 'ordersTotal' => $ordersTotal, 'orderStatuses' => $orderStatuses]);
    }
    public function deleted()
    {
        $orders1 = Order::select(
            DB::raw('orders.*'))
            ->join('consumers', 'orders.consumer_id', '=', 'consumers.id')
            ->where('consumers.is_deleted', 0)
            ->where('orders.is_deleted', 1)
            ->get();
            
        $orders2 = Order::select(
            DB::raw('orders.*'))
            ->join('professionals', 'orders.professional_id', '=', 'professionals.id')
            ->where('professionals.is_deleted', 0)
            ->where('orders.is_deleted', 1)
            ->get();
            $orders = $orders1->merge($orders2);
        
        
        
        // $clientIP = \Request::getClientIp(true);
        
        // if($clientIP == '103.12.199.43') {
        //     $orders = $orders->sortByDesc(function($order){
        //         return $order->created_at;
        //     });
        // }
            
        $orders = $orders->sortByDesc(function($order){
            return $order->created_at;
        });
        
        // $orders = Order::orderBy('created_at', 'DESC')->get();
        // dd($orders);
        $orderStatuses = ['Pending', 'Confirmed', 'In Process',  'Completed', 'Dispatched', 'Delivered'];
        $ordersTotal = $orders->sum('net_total');
        return view('admin.orders.deleted', ['title' => 'Deleted Orders', 'orders' => $orders, 'ordersTotal' => $ordersTotal, 'orderStatuses' => $orderStatuses]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with('address')->where('id', $id)->first();

        if ($order == null) {
            return redirect()->route('listOrders')->with('error', 'Invalid order id');
        }
        $orderItems = OrderDetail::where('order_id', $id)->get();
        if ($order->consumer_id != null) {
            $billingAddress = $order->consumer->addresses()->where('address_type', '0')->first();
            $shippingAddress = $order->consumer->addresses()->where('address_type', '1')->first();
            
        }else{
            $billingAddress = $order->professional->addresses()->where('address_type', '0')->first();
            $shippingAddress = $order->professional->addresses()->where('address_type', '1')->first();

        }
        return view('admin.orders.detail', [
            'title' => 'Order Information',
            'order' => $order,
            'orderItems' => $orderItems,
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        if ($order == null) {
            return redirect()->route('listOrders')->with('error', 'No Record Found.');
        }
        $orderStatuses = ['Pending', 'Confirmed', 'In Process',   'Completed', 'Dispatched', 'Delivered'];

        return view('admin.orders.edit', ['title' => 'Orders', 'order' => $order, 'orderStatuses' => $orderStatuses]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $this->validate($request, [
            'order_status' => 'required',
            'payment_status' => 'required',
            'order_id' => 'required',
        ]);

        $order = Order::where('id', $request->order_id)->first();		
        $user = null;		
        $noti = [];		
        if($order->consumer_id != null) {		
            $user = Consumer::where('id', $order->consumer_id)->first();		
            $noti['consumer_id'] = $user->id;		
        }else{		
            $user = Professional::where('id', $order->professional_id)->first();		
            $noti['professional_id'] = $user->id;		
        }
        
        $data = [
            'order_status' => $request->input('order_status'),
            'payment_status' => $request->input('payment_status'),
        ];

        if ($request->input('tracking_id') != null && $request->input('tracking_id') != "") {
            $data["tracking_id"] = $request->input('tracking_id');
            
            // if ($order->tracking_id != $request->input('tracking_id')) {		
            //     if (isset($user->token)) {		
            //         app('App\Http\Controllers\FCMController')->sendNotification('Order Updated Successfully.', $user->full_name.' your tracking id for order '.$order->invoice_id.' has been updated to '.$request->input('tracking_id'), [], $user->token);		
                    		
            //         $noti['content'] = $user->full_name.' your tracking id for order '.$order->invoice_id.' has been updated to '.$request->input('tracking_id');		
        		
            //         Notification::create($noti);		
            //     }		
            // }
        }
        
        // if ($order->order_status != $request->input('order_status')) {		
        //     if (isset($user->token)) {		
        //         app('App\Http\Controllers\FCMController')->sendNotification('Order Updated Successfully.', $user->full_name.' your order '.$order->invoice_id.' has been updated to '.$request->input('order_status'), [], $user->token);		
                		
        //         $noti['content'] = $user->full_name.' your order '.$order->invoice_id.' has been updated to '.$request->input('order_status');		
        		
        //         Notification::create($noti);		
        //     }		
        // }		
        		
        // if ($order->payment_status != $request->input('payment_status')) {		
        //     if (isset($user->token)) {		
        //         app('App\Http\Controllers\FCMController')->sendNotification('Order Updated Successfully.', $user->full_name.' your payment status for order '.$order->invoice_id.' has been updated to '.$request->input('payment_status'), [], $user->token);		
                		
        //         $noti['content'] = $user->full_name.' your payment status for order '.$order->invoice_id.' has been updated to '.$request->input('payment_status');		
        		
        //         Notification::create($noti);		
        //     }		
        // }
        

        Order::where('id', $request->order_id)->update($data);
        return redirect()->route('listOrders')->with('success', 'Record Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    // filter orders based on time of placement
    public function filter(Request $request)
    {

        // dd($request->all());
        $orders = OrderFilter::apply($request);
        $ordersTotal = $orders->sum('order_total');
        $orderStatuses = ['Pending', 'Confirmed', 'In Process', 'Completed', 'Dispatched', 'Delivered'];
        return view('admin.orders.index',
            ['title' => 'Orders', 'ordersTotal' => $ordersTotal, 'startDate' => $request->start_date, 'endDate' => $request->end_date, 'orderStatus' => $request->order_status, 'orders' => $orders, 'orderStatuses' => $orderStatuses]
        );
    }

    // export order's filtered on the basis of time
    public function exportOrderAsPDF(Request $request)
    {
        $orders = OrderFilter::apply($request);
        $ordersTotal = $orders->sum('net_total');

        $pdf = PDF::loadView('admin.orders.exportOrders', ['title' => 'Orders', 'ordersTotal' => $ordersTotal, 'orders' => $orders]);
        return $pdf->download('Orders.pdf');
    }

    // export order's filtered on the basis of time
    public function exportOrder(Request $request)
    {
        $order = Order::with('address')->where('id', $request->order_id)->first();
        $user;
        if($order->consumer_id != null){

            $user = Consumer::find($order->consumer_id);
        } else{
            
            $user = Professional::find($order->professional_id);
        }

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
        // $total = OrderDetail::select(
        //     DB::raw('sum(order_details.sub_total) as orderTotal'))
        //     ->where('order_details.order_id', $order->id)
        //     ->first();

        $orderCategories = Category::select(
            DB::raw('categories.id as productCategoryId, categories.title as productCategoryTitle'))
            ->join('products', 'products.category_id', '=', 'categories.id')
            ->join('order_details', 'order_details.product_id', '=', 'products.id')
            ->where('order_details.order_id', $order->id)
            ->groupBy('categories.id')
            ->get();

            $pdf = PDF::loadView('admin.orders.exportOrder', ['order' => $order, 'orderItems' => $orderItems, 'user' => $user, 'orderCategories' => $orderCategories]);
            return $pdf->download('Order.pdf');
            // dd($order, $user, $orderCategories, $orderItems);
        // return view('admin.orders.exportOrder', ['title' => 'Orders', 'order' => $order, 'orderItems' => $orderItems, 'user' => $user, 'orderCategories' => $orderCategories]);
        }

    // get professional max orders
    public function showProfessionalMaxOrders()
    {
        // dd("here");
        // $professionalMaxOrders = Order::select(DB::raw('orders.professional_id, count(orders.id) as orderCount'))->with('professional')->where('professional_id', '!=', NULL)->groupBy('orders.professional_id')->get();
        $professionalMaxOrders = Order::select(		
        DB::raw('orders.professional_id, count(orders.id) as orderCount'))		
        ->join('professionals', 'orders.professional_id', '=', 'professionals.id')		
        ->where('professionals.is_deleted', 0)		
        ->where('orders.professional_id', '!=', NULL)		
        ->groupBy('orders.professional_id')		
        ->get();
        return view('admin.orders.professionalMaxOrders',
            ['title' => 'Professional Max. Orders Report', 'url' => 'exportPdfProfessional', 'tableTitle' => 'Professionals with maximum orders', 'users' => $professionalMaxOrders]
        );
    }

    // export professional max orders report
    public function exportPdfProfessional()
    {
        // $professionalMaxOrders = Order::select(DB::raw('orders.professional_id, count(orders.id) as orderCount'))->with('professional')->where('professional_id', '!=', NULL)->groupBy('orders.professional_id')->get();
        $professionalMaxOrders = Order::select(		
        DB::raw('orders.professional_id, count(orders.id) as orderCount'))		
        ->join('professionals', 'orders.professional_id', '=', 'professionals.id')		
        ->where('professionals.is_deleted', 0)		
        ->where('orders.professional_id', '!=', NULL)		
        ->groupBy('orders.professional_id')		
        ->get();
        $pdf = PDF::loadView('admin.orders.exportProfessioanlMaxOrders',
            ['title' => 'Professional Max. Orders Report', 'users' => $professionalMaxOrders]
        );
        return $pdf->download('Professional Maximum Orders Report.pdf');
    }

    // get consumer max orders
    public function showConsumerMaxOrders()
    {
        // $consumerMaxOrders = Order::select(DB::raw('orders.consumer_id, count(orders.id) as orderCount'))->with('consumer')->where('consumer_id', '!=', NULL)->groupBy('orders.consumer_id')->get();
        // dd($consumerMaxOrders);
        $consumerMaxOrders = Order::select(		
            DB::raw('orders.consumer_id, count(orders.id) as orderCount'))		
            ->join('consumers', 'orders.consumer_id', '=', 'consumers.id')		
            ->where('consumers.is_deleted', 0)		
            ->where('orders.consumer_id', '!=', NULL)		
            ->groupBy('orders.consumer_id')		
            ->get();
        return view('admin.orders.userMaxOrders',
            ['title' => 'Consumer Max. Orders Report', 'url' => 'exportPdfConsumer', 'tableTitle' => 'Consumers with maximum orders', 'users' => $consumerMaxOrders]
        );
    }

    // export consumer max orders report
    public function exportPdfConsumer()
    {
        // $consumerMaxOrders = Order::select(DB::raw('orders.consumer_id, count(orders.id) as orderCount'))->with('consumer')->where('consumer_id', '!=', NULL)->groupBy('orders.consumer_id')->get();
        $consumerMaxOrders = Order::select(		
            DB::raw('orders.consumer_id, count(orders.id) as orderCount'))		
            ->join('consumers', 'orders.consumer_id', '=', 'consumers.id')		
            ->where('consumers.is_deleted', 0)		
            ->where('orders.consumer_id', '!=', NULL)		
            ->groupBy('orders.consumer_id')		
            ->get();
        $pdf = PDF::loadView('admin.orders.exportUsersMaxOrders',
            ['title' => 'Consumer Max. Orders Report', 'users' => $consumerMaxOrders]
        );
        return $pdf->download('Consumer Maximum OrdersReport.pdf');
    }

    // get top selling products
    public function getTopSellingProducts()
    {
        $topSellingProducts = OrderDetail::select(DB::raw('order_details.*, count(order_details.id) as orderDetailCount, SUM(quantity) as totalQuantity'))->groupBy('product_id')->orderBy('totalQuantity', 'desc')->get();
        return view('admin.products.topSellingProducts', ['title' => 'Products', 'products' => $topSellingProducts]);
    }

    // export top selling products report
    public function exportTopSellingProducts()
    {
        $topSellingProducts = OrderDetail::select(DB::raw('order_details.*, count(order_details.id) as orderDetailCount, SUM(quantity) as totalQuantity'))->groupBy('product_id')->orderBy('totalQuantity', 'desc')->get();
        $pdf = PDF::loadView('admin.products.exportTopSellingProducts', ['title' => 'Top Selling Products', 'products' => $topSellingProducts]);
        return $pdf->download('Top Selling Products.pdf');
    }

    // export order's filtered on the basis of time
    // public function exportOrderTest(Request $request)
    // {

    //     $order = Order::with('address')->where('id', '66642e60-669e-11e9-b3af-3596811ff954')->first();
    //     $user = User::find($order->user_id);

    //     $orderItems = OrderDetail::select(
    //         DB::raw('order_details.quantity as productQuantity,
    //         order_details.sub_total as productSubTotal,
    //         order_details.unit_price as productPrice,
    //         products.prod_id as productId,
    //         products.title as productTitle,
    //         categories.id as productCategoryId'))
    //         ->join('products', 'order_details.product_id', '=', 'products.id')
    //         ->join('categories', 'products.category_id', '=', 'categories.id')
    //         ->where('order_details.order_id', $order->id)
    //         ->get();

    //     $orderCategories = Category::select(
    //         DB::raw('categories.id as productCategoryId, categories.title as productCategoryTitle'))
    //         ->join('products', 'products.category_id', '=', 'categories.id')
    //         ->join('order_details', 'order_details.product_id', '=', 'products.id')
    //         ->where('order_details.order_id', $order->id)
    //         ->groupBy('categories.id')
    //         ->get();

    //     $pdf = PDF::loadView('admin.orders.exportOrder', ['order' => $order, 'orderItems' => $orderItems, 'user' => $user, 'orderCategories' => $orderCategories])->setPaper('a4');
 
    //     $location = '/pdf/66642e60-669e-11e9-b3af-3596811ff954.pdf';
    //     Storage::put($location, $pdf->output());
    //     $data = [
    //         'full_name' => 'waqas',
    //         'order_id' => $order->invoice_id,
    //         'gross_total' => $order->gross_total,
    //         'discount' => $order->discount_amount,
    //         'coupon_discount' => $order->coupon_discount_amount,
    //         'net_total' => $order->net_total,
    //         'orderItems' => $orderItems
    //     ];
    //     $email = 'weqesince1993@gmail.com';
    //     Mail::send('mail.userOrderEmail', $data, function ($message) use ($email) {
    //         $message->from('admin@postquam.com', 'Postquam Admin');
    //         $message->to('waqas.tektiks@gmail.com')->subject('Postquam Test Order Summary.');
    //         $message->attach(Storage::path('/pdf/66642e60-669e-11e9-b3af-3596811ff954.pdf'), [
    //             'as' => 'Order.pdf', 
    //             'mime' => 'application/pdf'
    //         ]);
    //     });

    //     return "email sent";

    //     // return $pdf->download('invoice.pdf');
    // }
    
    public function delOrder(Request $request){

        $order = Order::where('id', $request->orderID)->first();
        if(!empty($order)){
            $order->update([
                'is_deleted' => 1,
                ]);
        
        return ['status' => 1, 'message' => 'Order Deleted Successfully'];
        }
        return ['status' => 0, 'message' => 'Order Not Found'];
    }
}
