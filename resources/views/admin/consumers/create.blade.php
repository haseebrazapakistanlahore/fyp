@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ route('listConsumers') }}" class="btn btn-primary pull-right">Back</a>
    </div>
</div>

<div class="row" id="form">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Add Consumer
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" action="{{route('storeConsumer')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">

                            <fieldset>
                                <legend>Consumer Info.</legend>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Full Name <span class="required-star">*</span></label>
                                    <input type="text" id="full_name" maxlength="50" class="form-control" name="full_name"
                                        placeholder="Enter Full Name" value="{{ old('full_name') }}" required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Phone No. <span class="required-star">*</span></label>
                                    <input type="text" id="phone" class="form-control " name="phone" placeholder="Enter Phone Number"
                                        value="{{ old('phone') }}" required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Email <span class="required-star">*</span></label>
                                    <input type="email" id="email" class="form-control " name="email" placeholder="Enter Email Address"
                                        value="{{ old('email') }}" required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Password <span class="required-star">*</span></label>
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="Enter Password" required>
                                </div>


                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Confirm Password <span class="required-star">*</span></label>
                                    <input id="password_confirm" type="password" class="form-control" placeholder="Confirm Your Password"
                                        name="password_confirmation" required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Profile Image <span class="required-star">*</span></label>
                                    <input type="file" id="profile_image" class="form-control" name="profile_image"
                                        accept=".png, .jpg, .jpeg" required>
                                    <img class="pt-10 thumbnail-image-100" src="" id="user_profile_image" />
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend>Billing Address </legend>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Address <span class="required-star">*</span></label>
                                    <input type="text" id="address" maxlength="150" class="form-control" name="address"
                                        placeholder="Enter Address" value="{{ old('address') }}" required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>City <span class="required-star">*</span></label>
                                    <input type="text" id="city" class="form-control" maxlength="50" name="city"
                                        placeholder="Enter City" value="{{ old('city') }}" required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Country <span class="required-star">*</span></label>
                                    <input type="text" id="country" class="form-control" name="country" placeholder="Enter Area"
                                        value="Pakistan" disabled>
                                </div>

                            </fieldset>


                            <fieldset>
                                <legend>Shipping Address</legend>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Address <span class="required-star">*</span></label>
                                    <input type="text" id="shipping_address" class="form-control" maxlength="150" name="shipping_address"
                                        placeholder="Enter Address" value="{{ old('shipping_address') }}" required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>City <span class="required-star">*</span></label>
                                    <input type="text" id="shipping_city" class="form-control " maxlength="50" name="shipping_city"
                                        placeholder="Enter City" value="{{ old('shipping_city') }}" required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Country <span class="required-star">*</span></label>
                                    <input type="text" id="shipping_country" class="form-control" name="shipping_country"
                                        placeholder="Enter Area" value="Pakistan" disabled>
                                </div>

                            </fieldset>
                        </div>

                        <div class="col-lg-12">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary">Save</button>
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
<script src="{{asset('public/admin/consumers/js/view.js')}}"></script>
@stop
