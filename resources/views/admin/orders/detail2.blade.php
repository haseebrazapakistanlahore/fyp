@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('listOrders') }}" class="btn btn-primary pull-right">Back</a>
        </div>
    </div>

    <div class="row ">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Order Detail
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-5 font-bold">ID </span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-7">{{$order->id}}</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-5 font-bold">Order By </span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-7">{{$order->user->full_name}}</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-5 font-bold">Total </span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-7">Rs. {{$order->order_total}}</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-5 font-bold">Discount </span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-7">Rs. {{$order->total_discount}}</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-5 font-bold">Status </span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-7">{{$order->order_status}}</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-5 font-bold">Payment Method </span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-7">{{$order->payment_method}}</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-5 font-bold">Payment Status </span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-7">{{$order->payment_status}}</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-5 font-bold">Shipping Address </span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-7">{{$order->address->address }}</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-5 font-bold">Order Date </span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-7">{{date('F d, Y', strtotime($order->created_at))}}</span>
                        </div>
                    </div>

                    <div class="row order-detail">
                        <div class="col-lg-12">

                            <h4 class="pt-10 pb-10">Order Item's Detail</h4>

                            <table width="100%" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Title</th>
                                        <th>Quanity</th>
                                        <th>Unit Price</th>
                                        <th>Discount</th>
                                        <th>Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>@php $count=1; @endphp

                                    @foreach($orderItems as $item)

                                    <tr class="odd gradeX">
                                        <td class="center">{{ $count++ }}</td>
                                        <td class="center">{{$item->product->title}}</td>
                                        <td class="center">{{$item->quantity}}</td>
                                        <td class="center">{{$item->unit_price}}</td>
                                        <td class="center">{{$item->discount}}</td>
                                        <td class="center">{{$item->sub_total}}</td>
                                    </tr>

                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
