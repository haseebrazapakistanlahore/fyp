@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
    <div class="row  pt-10">
        <div class="col-lg-12">
            <a href="{{ route('listOrders')}}" class="btn btn-primary pull-right">Back</a>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Update Order Status
                </div>
                <div class="panel-body">
                    <div class="row">
                        <form role="form" id="order_edit_form" action="{{route('updateOrder')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="col-lg-12">

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Order ID </label>
                                    <input type="text" id="order_disable_id" class="form-control" name="order_disable_id" value="{{ $order->id }}" disabled>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Gross Total </label>
                                    <input type="number" id="gross_total" class="form-control" name="gross_total" value="{{ $order->gross_total }}" disabled>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Total </label>
                                    <input type="number" id="net_total" class="form-control" name="net_total" value="{{ $order->net_total }}" disabled>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Discount Amount</label>
                                    <input type="number" id="discount_amount" class="form-control" name="discount_amount" value="{{ $order->discount_amount }}" disabled>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>User Discount </label>
                                    <input type="number" id="fix_discount" class="form-control" name="fix_discount" value="{{ $order->fix_discount }}" disabled>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Coupon Discount Amount </label>
                                    <input type="number" id="coupon_discount_amount" class="form-control" name="coupon_discount_amount" value="{{ $order->coupon_discount_amount }}" disabled>
                                </div>
                               
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Delivery Charges </label>
                                    <input type="number" id="delivery_charges" class="form-control" name="delivery_charges" value="{{ $order->delivery_charges }}" disabled>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Order Status <span class="required-star">*</span></label>
                                    <select name="order_status" id="order_status" class="form-control" required>

                                        @if (count($orderStatuses))
                                        @foreach ($orderStatuses as $status)
                                            <option {{($order->order_status == $status) ? 'Selected' : '' }} value="{{$status}}">{{$status }}</option>
                                        @endforeach
                                        @endif

                                    </select>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Payment Status <span class="required-star">*</span></label>
                                    <select name="payment_status" id="payment_status" class="form-control" required>
                                        <option value="" disabled>Select Payment Status</option>

                                        @foreach (['Pending', 'Paid', 'Completed'] as $item)
                                        <option {{($order->payment_status == $item) ? 'Selected' : '' }} value="{{$item}}">{{$item}}</option>
                                        @endforeach

                                    </select>
                                </div>
                                
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Tracking ID </label>
                                    <input type="text" id="tracking_id" class="form-control" name="tracking_id" placeholder="Enter Order Tracking Id" @if(isset($order->tracking_id)) value="{{ $order->tracking_id }}" @endif>
                                </div>

                            </div>

                            <div class="col-lg-12">
                                <div class="col-lg-6">
                                    <input type="hidden" name="order_id" id="order_id" value="{{$order->id}}">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    {{-- Module JS file --}}
    {{-- <script src="{{asset('public/admin/orders/js/view.js')}}"></script>     --}}
@endsection