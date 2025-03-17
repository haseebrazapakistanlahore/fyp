<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice - Postquam</title>
    {{--
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> --}}
    <style>
        body,
        body p,
        body a,
        body h1,
        body h2,
        body h3,
        body h4,
        body h5,
        body h6 {
            font-family: 'Open Sans', sans-serif;
        }

        body,
        p {
            margin: 0px;
            padding: 0px;
        }

        .container {
            max-width: 900px;
            width: 100%;
            margin: auto;
        }

        .invoice-tabel .table {
            margin-bottom: 30px;
        }

        .invoice-tabel .table thead th {
            border-left: solid 1px #edf5fb;
            background-color: #00e7ff;
            vertical-align: middle;
        }

        .invoice-tabel .table thead tr th:nth-child(1),
        .invoice-tabel .table tbody tr td:nth-child(1),
        .invoice-tabel .table thead tr th:nth-child(4),
        .invoice-tabel .table tbody tr td:nth-child(4),
        .invoice-tabel .table thead tr th:nth-child(5),
        .invoice-tabel .table tbody tr td:nth-child(5),
        .invoice-tabel .table thead tr th:nth-child(6),
        .invoice-tabel .table tbody tr td:nth-child(6) {
            width: 8%;
        }

        .invoice-tabel .table thead tr th:nth-child(2),
        .invoice-tabel .table tbody tr td:nth-child(2),
        .invoice-tabel .table thead tr th:nth-child(7),
        .invoice-tabel .table tbody tr td:nth-child(7) {
            width: 12%;
        }

        .invoice-tabel .table thead tr th:nth-child(3),
        .invoice-tabel .table tbody tr td:nth-child(3) {
            width: 46%;
        }

        .invoice-tabel .table tbody tr td:nth-child(1),
        .invoice-tabel .table tbody tr td:nth-child(2),
        .invoice-tabel .table tbody tr td:nth-child(4),
        .invoice-tabel .table tbody tr td:nth-child(5),
        .invoice-tabel .table tbody tr td:nth-child(6) {
            text-align: right;
        }

        .invoice-tabel .table tbody tr td:nth-child(3) {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .col-md-9 {
            width: 75% !important;
        }

        .col-md-3 {
            width: 25% !important;
        }

        .col-md-4 {
            float: left;
            width: 33%;
        }

        .col-md-6 {
            float: left;
            width: 50%;
        }

        .mb-4,
        .my-4 {
            margin-bottom: 1.5rem !important;
        }

        .mt-4,
        .my-4 {
            margin-top: 1.5rem !important;
        }

        .row {
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
        }

        .h2,
        h2 {
            font-size: 2rem;
        }

        .h5,
        h5 {
            font-size: 1.25rem;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-bottom: .5rem;
            font-family: inherit;
            font-weight: 500;
            line-height: 1.2;
            color: inherit;
            margin-top: 0;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }

        .invoice-tabel .table {
            margin-bottom: 0px;
            border-spacing: 0 0px;
            border-collapse: separate;
        }

        .border {
            border: 1px solid #dee2e6 !important;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }

        table {
            border-collapse: collapse;
        }

        .invoice-tabel .table thead th {
            border-left: solid 4px #fff;
            background-color: #d0d4dd;
            vertical-align: middle;
            font-size: 13px;
        }

        .table thead th {
            vertical-align: bottom;
        }

        .table td,
        .table th {
            padding: 0.30rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        th {
            text-align: inherit;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .05);
        }

        .invoice-tabel .table tbody tr td {
            border-bottom: solid 1px #a0a1a2;
            /* border-left: solid 1px #a0a1a2; */
            border-right: solid 1px #a0a1a2;
            /* border-bottom: solid 1px #a0a1a2; */
            vertical-align: middle;
            font-size: 11px;
        }

        .invoice-tabel .table tbody tr td:first-child {
            border-left: solid 1px #a0a1a2;
        }

        .invoice-tabel .table tbody tr:first-child td {
            border-top: solid 1px #a0a1a2;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        .m-0 {
            margin: 0;
        }

        .col-md-10 {
            width: 83.333333%;
        }

        .col-md-2 {
            width: 16.666667% !important;
        }

        .h6,
        h6 {
            font-size: 1rem;
        }

        .mt-2 {
            margin-top: 6px;
            margin-bottom: 6px;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .mt-3 {
            margin-top: 10px;
        }

        .col-md-9,
        .col-md-3,
        .col-md-10,
        .col-md-2 {
            float: left;
        }

        .f-12 {
            font-size: 12px;
        }

        .f-14 {
            font-size: 14px;
        }

        .f-16 {
            font-size: 16px;
        }

        .f-18 {
            font-size: 18px;
        }

        .f-20 {
            font-size: 20px;
        }

        table td.category-class {
            padding-top: 10px;
        }

        @media (max-width:600px) {
            .right-col {
                text-align: left !important;
            }
        }
    </style>
</head>

<body>

    <div class="container pt-4 pb-4">
        <div class="row">

            <div class="col-md-4">
                <h5 class="text-uppercase f-14"><b>DATED:</b> {{ date('d-m-Y', strtotime($order->created_at))}}</h5>
                <h5 class="text-uppercase f-14"><b>Invoice Id: {{$order->invoice_id}}</b> (Online)</h5>
                <p class="f-12">In Respect of</p>
                <p class="text-uppercase mt-2 f-14"><b>
                        @if($user instanceof \App\Models\Professional)
                        {{$user->company_name}}
                        @else
                        {{$user->full_name}}
                        @endif
                    </b></p>
                <p class="text-uppercase m-0 mt-2 f-12">{{$order->address->city}} / {{$user->phone}}</p>
                <p class="text-uppercase m-0 mt-2 f-12">{{isset($order->address->address) ? $order->address->address :
                    ''}}</p>
            </div>

            <div class="col-md-4 text-center">
                <h3 class=" text-uppercase"><u><b>Invoice</b></u></h3>
            </div>

            <div class="col-md-4 text-right">
                <img class="mb-2 mt-2" style="height:50px;" src="{{ asset('images/postquam-logo.png')}}" alt="postquam">
                <a href="mailto:info@postquam.pk">info@postquam.pk</a><br>
                <a href="http://postquam.pk/" target="_blank">http://postquam.pk/</a>
                <p class="mt-2 f-12">04235712211 / 03348881202</p>
            </div>
        </div>

        {{--
        <div class="row">
            <div class="col-md-6">


            </div>

            <div class="col-md-6 text-right">

            </div>

        </div> --}}

        <div style="clear:both;"></div>

        <br>

        <div class="table-responsive invoice-tabel">
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th style="border-left:0px;">Sr.No</th>
                        <th>PROD-ID</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
            </table>


            @php
            $counter = 1;
            @endphp
            @foreach ($orderCategories as $category)
            @php
            $categoryItems = $orderItems->where('productCategoryId', '=', $category->productCategoryId);
            @endphp
            <table class="table text-center" cellspacing="2">
                <tbody>
                    <tr>
                        <td colspan="7" class="text-uppercase category-class"
                            style="border:0 !important; text-align:center !important; font-size:14px !important;">
                            <b><u>{{$category->productCategoryTitle}}</u></b>
                        </td>
                    </tr>

                    @foreach ($categoryItems as $item)
                    <tr>
                        <td style="text-align:center;">{{$counter}}</td>
                        <td style="text-align:center;">{{$item->productId}}</td>
                        <td class="text-uppercase">{{$item->productTitle}}</td>
                        <td style="text-align:center;">{{$item->productQuantity}}</td>
                        <td style="text-align:center;">{{$item->productPrice}}</td>
                        <td style="text-align:center;">{{$item->productSubTotal}}</td>
                        <td></td>
                    </tr>
                    @php
                    $counter ++;
                    @endphp

                    @endforeach
                </tbody>
            </table>
            @endforeach

            <table class="table text-right">
                <tbody>
                    <tr class="row m-0" style="border:0px !important;">
                        <td class="text-uppercase w-auto text-right"
                            style="width:85%;border:0px !important; background:transparent; font-size:16px;"><b>GROSS
                                TOTAL:</b></td>
                        <td class="text-uppercase w-auto border-0"
                            style="width:15%; text-align:left; border:0px !important; background:transparent; font-size:16px;">
                            <b> Rs. {{$order->gross_total}}</b>
                        </td>
                    </tr>

                    @if($order->delivery_charges != null)
                    <tr class="row m-0" style="border:0px !important;">
                        <td class="text-uppercase w-auto text-right"
                            style="width:85%;border:0px !important; background:transparent; font-size:16px;"><b>DELIVERY
                                CHARGES:</b></td>
                        <td class="text-uppercase w-auto border-0"
                            style="width:15%; text-align:left; border:0px !important; background:transparent; font-size:16px;">
                            <b> Rs. {{$order->delivery_charges}}</b>
                        </td>
                    </tr>
                    @endif

                    {{-- @if($order->discount_amount != null)
                    <tr class="row m-0" style="border:0px !important;">
                        <td class="text-uppercase w-auto text-right"
                            style="width:85%;border:0px !important; background:transparent; font-size:16px;"><b>DISCOUNT
                                AMOUNT:</b></td>
                        <td class="text-uppercase w-auto border-0"
                            style="width:15%; text-align:left; border:0px !important; background:transparent; font-size:16px;">
                            <b> Rs. {{$order->discount_amount}}</b>
                        </td>
                    </tr>
                    @endif --}}

                    @if($order->coupon_discount_amount != null || $order->discount_amount != null || $order->fix_discount != null)
                    <tr class="row m-0" style="border:0px !important;">
                        <td class="text-uppercase w-auto text-right"
                            style="width:85%;border:0px !important; background:transparent; font-size:16px;"><b>DISCOUNT AMOUNT:</b></td>
                        <td class="text-uppercase w-auto border-0"
                            style="width:15%; text-align:left; border:0px !important; background:transparent; font-size:16px;">
                            <b> Rs. {{$order->coupon_discount_amount + $order->discount_amount + $order->fix_discount}}</b>
                        </td>
                    </tr>
                    @endif
                    
                    <tr class="row m-0" style="border:0px !important;">
                        <td class="text-uppercase w-auto text-right"
                            style="width:85%;border:0px !important; background:transparent; font-size:16px;"><b>SALES TAX AMOUNT:</b></td>
                        <td class="text-uppercase w-auto border-0"
                            style="width:15%; text-align:left; border:0px !important; background:transparent; font-size:16px;">
                            <b> Rs. {{$order->net_total - ($order->gross_total +  $order->delivery_charges - $order->coupon_discount_amount - $order->discount_amount - $order->fix_discount )}}</b>
                        </td>
                    </tr>

                    <tr class="row m-0" style="border:0px !important;">
                        <td class="text-uppercase w-auto text-right"
                            style="width:85%;border:0px !important; background:transparent; font-size:16px;"><b>GRAND
                                TOTAL:</b></td>
                        <td class="text-uppercase w-auto border-0"
                            style="width:15%; text-align:left; border:0px !important; background:transparent; font-size:16px;">
                            <b> Rs. {{$order->net_total}}</b>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-4">
                    <h6 class="mt-3"><b><u>Name:</u></b></h6>
                </div>
                <div class="col-md-4">
                    <h6 class="mt-3"><b><u>Signature:</u></b></h6>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
