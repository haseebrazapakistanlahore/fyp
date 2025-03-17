@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="pull-right ml-10">
                <form action="{{ route('exportOrder')}}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{$order->id}}">
                    <div class="form-group ">
                        <input type="submit" class="btn btn-success" value="Export Invoice">
                    </div>
                </form>
            </div>

            <a href="{{ route('listOrders') }}" class="btn btn-primary pull-right">Back</a>

        </div>
    </div>
    <!-- Main section-->
    <section class="section-container order">
        <!-- Page content-->
        <div class="content-wrapper">
            <div class="card card-default">
                {{-- <div class="card-header">
                    <h3>Order Information</h3>
                </div> --}}
                <div class="card-body custom-row">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="lead bb">Details</p>
                            <form class="form-horizontal">
                                <div class="form-group m-0">
                                    <div class="col-md-4">Order ID:</div>
                                    <div class="col-md-8">
                                        <strong>{{ $order->id }}</strong>
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <div class="col-md-4">Purchased On:</div>
                                    <div class="col-md-8">
                                        <strong>{{date('d-m-Y', strtotime($order->created_at))}}</strong>
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <div class="col-md-4">User Name:</div>
                                    <div class="col-md-8">

                                        @if ($order->consumer_id != null)
                                        <strong>{{ $order->consumer->full_name }}</strong>
                                        @else
                                        <strong>{{ $order->professional->full_name }}</strong>
                                        @endif

                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <div class="col-md-4">Gross Total:</div>
                                    <div class="col-md-8">
                                        <strong>Rs. {{ $order->gross_total }}</strong>
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <div class="col-md-4">Discount:</div>
                                    <div class="col-md-8">
                                        <strong>Rs. {{ $order->discount_amount }}</strong>
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <div class="col-md-4">User Discount:</div>
                                    <div class="col-md-8">
                                        <strong>Rs. {{ $order->fix_discount }}</strong>
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <div class="col-md-4">Delivery Charges:</div>
                                    <div class="col-md-8">
                                        <strong>Rs. {{ $order->delivery_charges }}</strong>
                                    </div>
                                </div>
                                @if (isset($order->coupon_code))

                                <div class="form-group m-0">
                                    <div class="col-md-4">Coupon Code:</div>
                                    <div class="col-md-8">
                                        <strong>{{ $order->coupon_code }}</strong>
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <div class="col-md-4">Coupon Discount:</div>
                                    <div class="col-md-8">
                                        <strong>Rs. {{ $order->coupon_discount_amount }}</strong>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group m-0">
                                    <div class="col-md-4">Net Total:</div>
                                    <div class="col-md-8">
                                        <strong>Rs. {{ $order->net_total }}</strong>
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <div class="col-md-4">Order Status</div>
                                    <div class="col-md-8">
                                        <div class="badge badge-info">{{ $order->order_status }}</div>
                                    </div>
                                </div>
                                @if ($order->coupon_id != null)
                                <div class="form-group m-0">
                                    <div class="col-md-4">Coupon ID</div>
                                    <div class="col-md-8">
                                        <div class="badge badge-info">{{ $order->coupon_id }}</div>
                                    </div>
                                </div>
                                @endif

                                @if ($order->tracking_id != null)
                                <div class="form-group m-0">
                                    <div class="col-md-4">Tracking ID</div>
                                    <div class="col-md-8">
                                        <strong>{{ $order->tracking_id }}</strong>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group m-0">
                                    <div class="col-md-4">Payment Method</div>
                                    <div class="col-md-8">
                                        <strong>{{ $order->payment_method }}</strong>
                                    </div>
                                </div>

                                <div class="form-group m-0">
                                    <div class="col-md-4">Payment Status</div>
                                    <div class="col-md-8">
                                        <strong>{{ $order->payment_status }}</strong>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="col-md-6">
                            <p class="lead bb">Client</p>
                            <form class="form-horizontal">
                                <div class="form-group m-0">
                                    <div class="col-md-4">Name:</div>
                                    <div class="col-md-8">
                                            @if ($order->consumer_id != null)
                                            <strong>{{ $order->consumer->full_name }}</strong>
                                            @else
                                            <strong>{{ $order->professional->full_name }}</strong>
                                            @endif
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <div class="col-md-4">Email:</div>
                                    <div class="col-md-8">
                                        @if ($order->consumer_id != null)
                                        <strong>{{ $order->consumer->email }}</strong>
                                        @else
                                        <strong>{{ $order->professional->email }}</strong>
                                        @endif
                                    </div>
                                </div>


                                @if ($order->professional_id != null)

                                <div class="form-group m-0">
                                    <div class="col-md-4">Company Name:</div>
                                    <div class="col-md-8">
                                        <strong>{{ $order->professional->company_name }}</strong>
                                    </div>
                                </div>

                                @endif
                                <div class="form-group m-0">
                                    <div class="col-md-4">Phone:</div>
                                    <div class="col-md-8">
                                        @if ($order->consumer_id != null)
                                        <strong>{{ $order->consumer->phone }}</strong>
                                        @else
                                        <strong>{{ $order->professional->phone }}</strong>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <div class="col-md-4">Status</div>
                                    <div class="col-md-8">
                                        <div class="badge badge-success">
                                            @if ($order->consumer_id != null)
                                            {{ ($order->consumer->is_active)? 'Active' : 'Suspended' }}
                                            @else
                                            {{ ($order->professional->is_active)? 'Active' : 'Suspended' }}
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">

                        @if ($billingAddress != null)
                        <div class="col-lg-6">
                            <p class="lead bb">Billing Address</p>
                            <form class="form-horizontal">
                                <div class="form-group m-0">
                                    <div class="col-md-4">Address:</div>
                                    <div class="col-md-8">
                                        <strong>{{ $billingAddress->address }}</strong>
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <div class="col-md-4">City:</div>
                                    <div class="col-md-8">
                                        <strong>{{ $billingAddress->city }}</strong>
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <div class="col-md-4">Country</div>
                                    <div class="col-md-8">
                                        <strong>{{ $billingAddress->country }}</strong>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif

                        @if ($shippingAddress != null)

                        <div class="col-lg-6">
                            <p class="lead bb">Shipping Address</p>
                            <form class="form-horizontal">
                                <div class="form-group m-0">
                                    <div class="col-md-4">Address:</div>
                                    <div class="col-md-8">
                                        <strong>{{ $shippingAddress->address }}</strong>
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <div class="col-md-4">City:</div>
                                    <div class="col-md-8">
                                        <strong>{{ $shippingAddress->city }}</strong>
                                    </div>
                                </div>

                                <div class="form-group m-0">
                                    <div class="col-md-4">Country</div>
                                    <div class="col-md-8">
                                        <strong>{{ $shippingAddress->country }}</strong>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif

                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header">Products in order</div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Title</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Request</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $count=1; @endphp
                            @foreach($orderItems as $item)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td><a href="{{ route('productDetail', $item->product->id) }}">{{ $item->product->title
                                        }}</a> </td>
                                <td>{{ $item->unit_price }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    @if($item->product_request != null)
                                        @php($req = json_decode($item->product_request,true))
                                        <strong>Frame Color : {{ $req['frame_color'] ?? '--' }} </strong> <br>
                                        <strong>Left Engrave : {{ $req['left_engrave'] ?? '--' }} </strong> <br>
                                        <strong>Right Engrave : {{ $req['right_engrave'] ?? '--' }} </strong> <br>
                                        <strong>Size: {{ $req['size'] ?? '--' }}</strong>
                                    @else
                                        --
                                    @endif
                                </td>
                                <td>Rs. {{ $item->sub_total }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>


@endsection
