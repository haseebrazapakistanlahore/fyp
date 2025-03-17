@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <a href="{{ url()->previous() }}" class="btn btn-primary pull-right">Back</a>
        </div>
    </div>
    <div class="card productDetail">
        <div class="container-fliud">
            <div class="wrapper row">
                <div class="preview col-md-6">

                    <div class="preview-pic tab-content">
                        <div class="tab-pane active"><img src="{{ asset('storage/app/public/'.$product->thumbnail)}}" /></div>
                        @if (count($product->productImages) > 0)
                        @foreach ($product->productImages as $image)
                        <div class="tab-pane sliderImage" id="{{ $image->id }}"><img src="{{ asset('storage/app/public/'.$image->image_url) }}" /></div>

                        @endforeach
                        @endif
                    </div>
                    <ul class="preview-thumbnail nav nav-tabs">
                        @if (count($product->productImages) > 0)
                        @foreach ($product->productImages as $image)
                        <li><a data-target="#{{ $image->id }}" data-toggle="tab"><img src="{{ asset('storage/app/public/'.$image->image_url) }}" /></a></li>

                        @endforeach
                        @endif
                    </ul>

                </div>
                <div class="details col-md-6">

                    <h3><b>{{ $product->title }}</b></h3>
                    <p><strong>Description: </strong> {{ $product->description }}.</p>

                  
                    <p><strong>Available Quantity: </strong><span>{{ $product->available_quantity }}</span></p>
                    <p><strong>Min. Order Level: </strong><span>{{ $product->min_order_level }}</span></p>

                    @if ($product->product_type == '0')
                    <p><strong>Product Type: </strong><span>Consumer</span></p>
                    <p><strong>Consumer Price: </strong><span>Rs. {{ $product->price }}</span></p>
                    @endif
                    
                    @if ($product->product_type == '1')
                    <p><strong>Product Type: </strong><span>Professional</span></p>
                    <p><strong>Professional Price: </strong> <span>Rs. {{ $product->price }}</span></p>
                    @endif
                    
                    @if ($product->product_type == '2')
                    <p><strong>Product Type: </strong><span>Consumer/Professional</span></p>
                    <p><strong>Consumer Price: </strong> <span>Rs. {{ $product->price }}</span></p>
                    <p><strong>Professional Price: </strong> <span>Rs. {{ $product->price_for_professional }}</span></p>
                    @endif

                    <p><strong> Category:</strong> <span>{{ $product->category_name }}</span></p>

                    @if (isset($product->sub_category_id))
                    <p><strong>Sub Category:</strong> <span>{{ $product->subCategory->title }}</span></p>
                    @endif

                    @if ( count($product->colors) > 0)
                    <p><strong>Colors: </strong>
                        @foreach ($product->colors as $color)
                        <span>{{ $color->name }}</span>,
                        @endforeach
                    </p>
                    @endif

                    @if (isset($product->size))
                    <p><strong>Size: </strong>{{ $product->size }} /ML</p>
                    @endif

                    @if (isset($product->why_its_special))
                    <p><strong>Why Its Special:</strong> <span>{{ $product->why_its_special }}</span></p>
                    @endif
                    @if (isset($product->how_to_use))
                    <p><strong>How To Use:</strong> <span>{{ $product->how_to_use }}</span></p>
                    @endif
                    @if (isset($product->ingredients))
                    <p><strong>Ingredients:</strong> <span>{{ $product->ingredients }}</span></p>
                    @endif


                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default mt-50">
        <div class="panel-heading">
            Product Reviews
        </div>
        <div class="panel-body">

            <div class="profile-feed row">
                @if (count($reviews) > 0)

                @foreach ($reviews as $review)

                @if(isset($review->full_name))
                <div class="col-sm-6">
                    <div class="profile-activity clearfix">
                        <div class="col-sm-2">
                            <img class="pull-left" alt="{{ $review->user->full_name }} avatar" 
                            src="{{ isset($review->user->profile_image) ? url('storage/app/public/'.$review->user->profile_image) : url('storage/app/public/userImages/placeholder.png') }}">
                        </div>
                        <div class="col-sm-10">
                            <a class="user" href="{{ route('userDetail', $review->user->id) }}"> {{ $review->user->full_name }} </a>
                            <p>{{ $review->comment }}</p>
                        </div>
                        <div class="tools action-buttons">
                            @if ($review->is_approved != 1)
                                <a href="{{ route('approveReview', $review->id)}}" class="blue" title="Approve this review">
                                    <i class="ace-icon fa fa-check bigger-125"></i>
                                </a>
                            @endif
                            <a href="javascript:void(0);" onclick="deleteReviewSecond('{{$review->id}}');" class="red" title="Delete this review">
                                <i class="ace-icon fa fa-trash bigger-125"></i>
                            </a>
                        </div>
                    </div>
                </div>
              
                @endif
                
                @endforeach
                
                @else
                <p>No review Available</p>
                @endif
              
            </div><!-- /.row -->
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="{{asset('public/admin/reviews/js/view.js')}}"></script>
@endsection