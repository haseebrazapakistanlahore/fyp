<html>
<style>
       table {
        border: 1px solid #eee;
        padding: 0px;
        text-align: center;
        font-family: sans-serif;
    }
    h1{
        font-family: sans-serif;
    }
    table th, table td {
        padding: 5px;
    }
    table{
        box-sizing: border-box;
        margin: 0;
    }
    table tr td{
        font-size: 12px;
        text-transform: capitalize;
        border-top: solid 1px #eee;
        border-right: solid 1px #eee;
    }
    table tr td:last-child{
        border-right: 0;
    }
    th {
        color: white;
        background-color: #337ab7;
        font-size: 14px;
    }

</style>

<body>

    <div class="row">
        <div class="col-lg-12">
            <h1>{{$title}}</h1>
        </div>

        <table width="100%" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Order ID</th>
                    <th>Full Name</th>
                    <th>User Type</th>
                    <th>Discount</th>
                    <th>Coupon Discount</th>
                    <th>Order Status</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Net Total</th>
                </tr>
            </thead>
            @if(count($orders) > 0)
            <tbody>@php $count=1; @endphp

                @foreach($orders as $order)

                <tr class="odd gradeX">
                    <td>{{$count++}}</td>
                    <td>{{$order->id}}</td>
                    @if ($order->consumer_id != null)
                    <td>{{$order->consumer->full_name}}</td>
                    <td>Consumer</td>
                    @else
                    <td>{{$order->professional->full_name}}</td>
                    <td>Professional</td>
                    @endif
                    
                    <td>{{$order->discount_amount}}</td>
                    <td>{{$order->coupon_discount_amount}}</td>
                    <td>{{$order->order_status}}</td>
                    <td>{{$order->payment_method}}</td>
                    <td>{{$order->payment_status}}</td>
                    <td>{{$order->net_total}}</td>
                </tr>

                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <td rowspan="1" colspan="7" style="font-weight:bold; text-align:right;">Grand Total</td>
                    <td rowspan="1" colspan="2" style="font-weight:bold">Rs. {{($ordersTotal) > 0 ? $ordersTotal :
                        0}}</td>
                </tr>
            </tfoot>
            @else
            <h3 align="center"> No Record Found. </h3>
            @endif
        </table>
    </div>

</body>

</html>
