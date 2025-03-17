@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ route('listDiscounts') }}" class="btn btn-primary pull-right">Back</a>
    </div>
</div>
<div class="row" id="form">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Edit Discount
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" id="updateDiscountForm" action="{{route('updateDiscount')}}" method="post"
                        enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Min. Order Value <span class="required-star">*</span></label>
                                <input type="number" id="min_amount" min="1" class="form-control" name="min_amount" placeholder="Enter Minimum Order Value"
                                    value="{{ $discount->min_amount }}" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Max. Order Value <span class="required-star">*</span></label>
                                <input type="number" id="max_amount"  min="1" class="form-control" name="max_amount" placeholder="Enter Maximum Order Value"
                                    value="{{ $discount->max_amount }}" required>
                            </div>

                            <div class="col-md-2 col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Start Date <span class="required-star">*</span></label>
                                    <div class="input-group date">
                                        <input class="form-control" type="text" name="start_date" value="{{ date('m/d/Y', strtotime($discount->start_date)) }}"
                                            required />
                                        <span class="input-group-addon">
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">End Date <span class="required-star">*</span></label>
                                    <div class="input-group date">
                                        <input class="form-control" type="text" name="end_date" value="{{ date('m/d/Y', strtotime($discount->end_date)) }}"
                                            placeholder="Enter End Date" required />
                                        <span class="input-group-addon">
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Discount % <span class="required-star">*</span></label>
                                <input type="number" id="discount_percentage" class="form-control" name="discount_percentage"
                                    placeholder="Enter Discount Percentage (%)" value="{{ $discount->discount_percentage }}"
                                    required>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Image</label>
                                <input type="file" id="image_url" class="form-control" name="image_url">
                                @if ($discount->image != null && Storage::exists($discount->image))
                                <img src="{{ asset('storage/app/public/'.$discount->image) }}" id="image" class="thumbnail-image-100" />
                                @else
                                <img src="{{ asset('storage/app/public/discountImages/placeholder.png') }}" id="image" class="thumbnail-image-100" class="hidden" />
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="col-lg-6">
                                <input type="hidden" name="discount_id" value="{{$discount->id}}">
                                <button id="updateDiscountSubmit" type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Module JS file --}}
<script src="{{asset('public/admin/discounts/js/view.js')}}"></script>
@stop
