@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>

@if(count($products) > 0)
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ route('exportTopSellingProducts') }}" class="btn btn-success pull-right">Export As PDF</a>
    </div>
</div>
@endif

<div class="row">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Top Selling Products List
            </div>

            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableProducts">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Product Title</th>
                            <th>Category</th>
                            <th>SubCategory</th>
                            <th>Type</th>
                            <th>Consumer Price</th>
                            <th>Professional Price</th>
                            <th>No. of Orders</th>
                            <th>No. of Products</th>
                        </tr>
                    </thead>
                    <tbody>@php $count = 1; @endphp

                        @foreach($products as $product)
                        <tr class="odd gradeX">
                            <td>{{ $count++ }}</td>
                            <td>{{ $product->product->title }}</td>
                            <td>{{ $product->product->category->title }}</td>
                            <td>{{ isset($product->product->subCategory) ? $product->product->subCategory->title : '- -' }}</td>
                            @if ($product->product->product_type == '0')
                            <td>Consumer</td>
                            @endif
                            @if ($product->product->product_type == '1')
                            <td>Professional</td>
                            @endif
                            @if ($product->product->product_type == '2')
                            <td>Consumer / Professional</td>
                            @endif
                            <td>{{$product->product->price}}</td>
                            <td>{{$product->product->price_for_professional}}</td>
                            <td>{{ $product->orderDetailCount }}</td>
                            <td>{{ $product->totalQuantity }}</td>
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
<script>
    $(document).ready(function () {
        $('#dataTableProducts').DataTable({
            responsive: true, 
            "order": [],
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [0]
            },{
                "bSearchable": false, 
                'aTargets': [0]
            }],
        });
    });
    </script>
@stop