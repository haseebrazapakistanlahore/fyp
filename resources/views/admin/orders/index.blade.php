@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>

    <form id="filter_user_form" action="{{ route('searchOrders') }}" method="POST" enctype="multipart/form-data" class="form-inline">
        @csrf
        <div class="col-md-3 col-lg-3">
            <div class="form-group">
                <label class="control-label">Start Date</label>
                <div class="input-group date previous">
                    <input class="form-control" type="text" name="start_date"  value="{{isset($startDate)? $startDate:''}}"/>
                    <span class="input-group-addon">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fa fa-calendar"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-lg-3">
            <div class="form-group">
                <label class="control-label">End Date</label>
                <div class="input-group date previous">
                    <input class="form-control" type="text" name="end_date" value="{{isset($endDate)? $endDate:''}}" />
                    <span class="input-group-addon">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fa fa-calendar"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg-3 ">
            <div class="form-group">
                <label class="control-label">Order Status</label>
                <select class="form-control w-100" name="order_status" id="order_status">
                    <option value="" selected disabled>Select Order Status</option>
                    @if (count($orderStatuses))
                    @foreach ($orderStatuses as $status)
                    <option {{ (isset($orderStatus) && ($status == $orderStatus) ) ? "selected":"" }}  value="{{$status}}">{{$status }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
        </div>

        <div class="col-md-2 col-lg-1">
            <div class="form-group pt-20">
                <input type="submit" class="btn btn-primary" value="Filter">
            </div>
        </div>
    </form>
    @if (((isset($startDate) && isset($endDate)) || (isset($orderStatus))) &&count($orders) > 0)
    <form action="{{ route('exportOrders')}}" method="POST">
        @csrf
        @if (isset($startDate) && isset($endDate))
        <input type="hidden" name="start_date_second" value="{{isset($startDate)? $startDate:''}}">
        <input type="hidden" name="end_date_second" value="{{isset($endDate)? $endDate:''}}">
        @endif
        @if (isset($orderStatus))
        <input type="hidden" name="order_status_second" value="{{isset($orderStatus)? $orderStatus:''}}">

        @endif
        <div class="col-md-3 col-lg-2">
            <div class="form-group pt-20">
                <input type="submit" class="btn btn-success" value="Export PDF">
            </div>
        </div>
    </form>
    @endif
</div>
<!-- /.col-lg-12 -->
<div class="row">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Orders List
            </div>

            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableOrders">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Order ID</th>
                            <th>Full Name</th>
                            <th>User Type</th>
                            <th>Payment Method</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Net Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>@php $count=1; @endphp
                        @foreach($orders as $order)

                        <tr class="odd gradeX">
                            <td>{{$count++}}</td>
                            {{-- <td>{{$order->id}}</td> --}}
                            <td><a href="{{route('orderDetail', $order->id)}}">{{$order->invoice_id}}</a></td>
                            @if ($order->consumer_id != null)
                            <td><a href="{{route('consumerDetail', $order->consumer->id)}}">{{$order->consumer->full_name}}</a></td>
                            <td>Consumer</td>
                            @else
                            <td><a href="{{route('professionalDetail', $order->professional->id)}}">{{$order->professional->full_name}}</a></td>
                            <td>Professional</td>
                            @endif
                            {{-- <td>{{ ($order->user->user_type == '1')? 'Professional': 'Consumer'}}</td> --}}
                            <td>{{$order->payment_method}}</td>
                            <td>{{date('d-m-Y', strtotime($order->created_at))}}</td>
                            <td>{{$order->order_status}}</td>
                            <td>{{$order->payment_status}}</td>
                            <td>{{$order->net_total}}</td>

                            <td>
                                {{-- <a href="{{ route('orderDetail', $order->id)}}" class="mr-10">
                                    <i class="fa fa-eye" title="View detail" aria-hidden="true" style="font-size:18px;"></i>
                                </a> --}}
                                <a href="{{ route('editOrder', $order->id)}}" class="btn btn-success">
                                    {{-- <i class="fa fa-refresh" title="Update Order" aria-hidden="true" style="font-size:18px;"></i>
                                    --}}
                                    Update
                                </a>
                                <br>
                                <br>
                                <a href="javascript:void(0);" onclick="delOrders('{{$order->id}}')" class="btn btn-danger">
                                    Delete
                                </a>
                            </td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
{{-- Module JS file --}}
<script src="{{asset('public/admin/orders/js/view.js')}}"></script>
<script>
    function delOrders(orderID) {
    var result = window.confirm('Are you sure you want to delete this order?');
    if (result == false) {
        event.preventDefault();
    } else {
        $.ajax({
            method: "POST",
            url: '{{route('delOrder')}}',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'orderID': orderID,
            },
            success: function (response) {

                window.location.reload();
            }
        });
    }
}
    
</script>
@stop
