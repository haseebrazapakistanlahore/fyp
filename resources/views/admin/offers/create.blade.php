@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{route('listOffers')}}" class="btn btn-primary pull-right">Back</a>
    </div>
</div>

<div class="row" id="form">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Add Offer
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" id="addCouponForm" action="{{route('storeOffer')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Offer Name <span class="required-star">*</span></label>
                                <input type="text" id="offer_name" minlength="1" maxlength="50" class="form-control" name="offer_name"
                                    placeholder="Enter Offer Name" value="{{ old('offer_name') }}" required>
                            </div>
                            
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Prefix <span class="required-star">*</span></label>
                                <input type="text" minlength="1" maxlength="3" id="prefix" class="form-control" name="prefix"
                                    placeholder="Enter prefix" value="{{ old('prefix') }}" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>No. of Coupons <span class="required-star">*</span></label>
                                <input type="number" id="no_of_coupons" class="form-control" name="no_of_coupons"
                                    placeholder="Enter No. Of Coupons"  max="8" value="{{ old('no_of_coupons') }}" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Start Date <span class="required-star">*</span></label>
                                <div class="input-group date">
                                    <input class="form-control" type="text" name="start_date" value="{{ old('start_date') }}" placeholder="Enter Start Date" required />
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
                                    <input class="form-control" type="text" name="end_date" value="{{ old('end_date') }}" placeholder="Enter End Date" required />
                                    <span class="input-group-addon">
                                        <button class="btn btn-outline-secondary" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Discount % <span class="required-star">*</span></label>
                                <input type="number" id="discount_percentage" class="form-control" name="discount_percentage"
                                    placeholder="Enter Discount Percentage" value="{{ old('discount_percentage') }}"
                                    required>
                            </div>

                        </div>

                        <div class="col-lg-12">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary">Generate</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('script')
{{-- Module JS file --}}
<script src="{{asset('public/admin/coupons/js/view.js')}}"></script>
@stop
