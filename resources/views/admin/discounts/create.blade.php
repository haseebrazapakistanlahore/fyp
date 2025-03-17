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

<div class="row">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Add Discount
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" id="add_discount_form" action="{{route('storeDiscount')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Min. Order Value <span class="required-star">*</span></label>
                                <input type="number" id="min_amount" min="1" class="form-control" name="min_amount"
                                    value="{{ old('min_amount') }}" placeholder="Enter Minimmum Order Value" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Max. Order Value <span class="required-star">*</span></label>
                                <input type="number" id="max_amount" min="1" class="form-control" name="max_amount"
                                    value="{{ old('max_amount') }}" placeholder="Enter Maximum Order Amount" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Start Date <span class="required-star">*</span></label>
                                <div class="input-group date">
                                    <input class="form-control" type="text" name="start_date" value="{{ old('start_date') }}"
                                        placeholder="Enter Start Date" required />
                                    <span class="input-group-addon">
                                        <button class="btn btn-outline-secondary" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">End Date <span class="required-star">*</span></label>
                                <div class="input-group date">
                                    <input class="form-control" type="text" name="end_date" value="{{ old('end_date') }}"
                                        placeholder="Enter End Date" required />
                                    <span class="input-group-addon">
                                        <button class="btn btn-outline-secondary" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Discount %<span class="required-star">*</span></label>
                                <input type="number" id="discount_percentage" min="1" class="form-control" value="{{ old('discount_percentage') }}"
                                    name="discount_percentage" placeholder="Enter Discount Percentage (%)" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Image <span class="required-star">*</span></label>
                                <input type="file" id="image_url" class="form-control" name="image_url" required>
                                <img src="" id="image" class="thumbnail-image-200 hidden" />
                            </div>
                            <div class="col-lg-12">
                                <button id="add_discount_submit" type="submit" class="btn btn-primary">Save</button>
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
