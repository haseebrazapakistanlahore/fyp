@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ route('createProduct') }}" class="btn btn-primary pull-right">Add Product</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Products List
            </div>

            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableProduct">
                    <thead>
                        <tr>
                            {{-- <th>Sr. No.</th> --}}
                            <th>ID</th>
                            <th>Title <span class="span-red">(Quantity)</span></th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Sub Child Category</th>
                            <th>Type</th>
                            <th>Price</th>
                            {{-- <th>Featured</th> --}}
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>@php $count=1; @endphp

                        @foreach($products as $product)
                        <tr class="odd gradeX">
                            {{-- <td>{{$count++}}</td> --}}
                            <td>{{$product->prod_id}}  
                                @if($product->is_featured == 1)
                                    <i class="fa fa-star" aria-hidden="true"></i> 
                                    @else 
                                    <i class="fa fa-star-o" aria-hidden="true"></i>
                                    @endif</td>
                            <td>{{$product->title}}  <span class="span-red">({{$product->available_quantity}})<span></td>
                            <td>{{$product->category->title}}</td>
                            <td>{{isset($product->subCategory)? $product->subCategory->title: ''}}</td>
                            <td>{{isset($product->sub_child_category_id)? $product->subChildCategory->title: ''}}</td>
                            @if ($product->product_type == '0')
                            <td>Consumer</td>
                            
                            @endif
                            @if ($product->product_type == '1')
                            <td>Professional</td>
                            @endif
                            <td>{{$product->price}}</td>
                            <td>{{ ($product->available_quantity >= 1) ? 'Avaliable' : 'Out of stock'}} </td>
                            <td>
                                <a href="{{ route('productDetail', $product->id)}}" class="mr-5">
                                    <i class="fa fa-eye" title="View detail" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('editProduct', $product->id) }}" class="mr-5">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>

                                <a href="javascript:void(0);" onclick="deactivateProduct('{{$product->id}}');">
                                    <i class="fa fa-trash" aria-hidden="true" ></i>
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
<script src="{{asset('public/admin/products/js/view.js?v=1')}}"></script>
@stop
