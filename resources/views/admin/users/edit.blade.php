@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ route('listUsers') }}" class="btn btn-primary pull-right">Back</a>
    </div>
</div>
<div class="row" id="form">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Edit User
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" id="updateUserForm" action="{{route('updateUser')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">

                            <fieldset>
                                <legend>User Info</legend>
                                
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Full Name <span class="required-star">*</span></label>
                                    <input type="text" id="full_name" class="form-control" name="full_name" value="{{$user->full_name}}"
                                        required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Email <span class="required-star">*</span></label>
                                    <input type="text" id="email" class="form-control" name="email" value="{{$user->email}}"
                                        disabled required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Phone <span class="required-star">*</span></label>
                                    <input type="text" id="phone" class="form-control" name="phone" value="{{$user->phone}}"
                                        required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Password </label>
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="********" >
                                </div>


                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Confirm Password </label>
                                    <input id="password_confirm" type="password" class="form-control" placeholder="********"
                                        name="password_confirmation" >
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Profile Image </label>
                                    <input type="file" id="profile_image" class="form-control" name="profile_image"
                                        accept=".png, .jpg, .jpeg">

                                    @if($user->profile_image != null && Storage::exists($user->profile_image))
                                    <img id="user_profile_image" src="{{ asset('storage/app/public/'.$user->profile_image)}}"
                                        alt="User Profile image" class="thumbnail-image-100">
                                    @else
                                    <img id="user_profile_image" src="{{ asset('storage/app/public/userImages/placeholder.png')}}"
                                            alt="User Profile image" class="thumbnail-image-100">
                                    @endif

                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Discount Allowed</label>
                                    <select id="edit_discount_allowed" class="form-control" name="discount_allowed">
                                        <option {{( $user->discount_allowed == "0")?'selected':'' }} value="0">Not Allowed</option>
                                        <option {{( $user->discount_allowed == "1") ?'selected':'' }} value="1">Allowed</option>
                                    </select>
                                </div>
                                
                                <div class="form-group col-md-6 col-sm-6 col-xs-12 edit_discount_fields" {{( $user->discount_allowed == '0')?'style=display:none':'' }}>
                                    <label>Min. Order Value <span class="required-star">*</span></label>
                                    <input id="min_order_value" name="min_order_value" type="text" class="form-control" placeholder="Enter Min. Order Value" value="{{$user->min_order_value}}">
                                </div>
                                
                                <div class="form-group col-md-6 col-sm-6 col-xs-12 edit_discount_fields" {{( $user->discount_allowed == '0')?'style=display:none':'' }}>
                                    <label>Discount Value <span class="required-star">*</span></label>
                                    <input id="discount_value" type="text" class="form-control" placeholder="Enter Discount Amount (Rs.)" name="discount_value" value="{{$user->discount_value}}">
                                </div>
                            </fieldset>
          
                           @if ($billingAddress != null)
                                
                            <fieldset>
                                <legend>Billing Addresses</legend>
                               
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Address</label>
                                    <input type="text" id="billing_address" class="form-control" name="billing_address" value="{{$billingAddress->address}}">
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>City</label>
                                    <input type="text" id="billing_city" class="form-control" name="billing_city" value="{{$billingAddress->city}}">
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Country</label>
                                    <input type="text" id="billing_country" class="form-control" name="billing_country" value="{{$billingAddress->country}}">
                                </div>
                                <input type="hidden" name="billing_id" value="{{$billingAddress->id}}">

                            </fieldset>
                            @endif
                           @if ($shippingAddress != null)
                                
                            <fieldset>
                                <legend>Shipping Addresses</legend>
                               
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Address</label>
                                    <input type="text" id="shipping_address" class="form-control" name="shipping_address" value="{{$shippingAddress->address}}">
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>City</label>
                                    <input type="text" id="shipping_city" class="form-control" name="shipping_city" value="{{$shippingAddress->city}}">
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Country</label>
                                    <input type="text" id="shipping_country" class="form-control" name="shipping_country" value="{{$shippingAddress->country}}">
                                </div>
                                <input type="hidden" name="shipping_id" value="{{$shippingAddress->id}}">
                            </fieldset>
                            @endif

                            @if ($user->user_type == '1')
                            <fieldset id="professional-fields">
                                <legend>Salon/Company Info</legend>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Salon/Company Name <span class="required-star">*</span></label>
                                    <input type="text" id="company_name" class="form-control" name="company_name"
                                        placeholder="Enter Salon/Company Name" value="{{ $user->company_name }}" required>
                                </div>
                                
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Salon/Company Address <span class="required-star">*</span></label>
                                    <input type="text" id="company_address" class="form-control" name="company_address"
                                        placeholder="Enter Salon/Company Adress" value="{{ $user->company_address }}" required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Company Website</label>
                                    <input type="text" id="company_website" class="form-control" name="company_website"
                                        placeholder="Enter Comapany Website" value="{{ $user->company_website }}"
                                        >
                                </div>
                            </fieldset>
                            
                            @else
                            
                            <fieldset id="professional-fields" style="display:none;">
                                <legend>Salon/Company Info</legend>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Salon/Company Name <span class="required-star">*</span></label>
                                    <input type="text" id="company_name" class="form-control" name="company_name"
                                        placeholder="Enter Salon/Company Name" value="{{ $user->company_name }}" >
                                </div>
                                
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Salon/Company Address <span class="required-star">*</span></label>
                                    <input type="text" id="company_address" class="form-control" name="company_address"
                                        placeholder="Enter Salon/Company Adress" value="{{ $user->company_address }}" >
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Company Website</label>
                                    <input type="text" id="company_website" class="form-control" name="company_website"
                                        placeholder="Enter Comapany Website" value="{{ $user->company_website }}"
                                        >
                                </div>
                            </fieldset>

                            @endif

                            @if ($user->user_type == '1')
                            
                            <div class="form-group">
                                <label class="checkbox-inline col-md-4">Authorized <span class="required-star">*</span></label>
                                @if ($user->is_authorized == "1")
                                <input name="is_authorized" type="checkbox" class="" checked data-toggle="toggle">
                                <input type="hidden" name="is_authorized" id="is_authorized" value="{{$user->is_authorized}}">
                                @else
                                <input name="is_authorized" type="checkbox" class="" data-toggle="toggle">
                                <input type="hidden" name="is_authorized" id="is_authorized" value="{{$user->is_authorized}}">
                                @endif
                            </div>
                            @endif
                            
                           
                            <div class="form-group">
                                <label class="checkbox-inline col-md-4">Suspend Account <span class="required-star">*</span></label>
                                @if ($user->is_active == "0")
                                <input name="is_active" type="checkbox" class="" checked data-toggle="toggle">
                                <input type="hidden" name="is_active" id="is_active" value="{{$user->is_active}}">
                                @else
                                <input name="is_active" type="checkbox" class="" data-toggle="toggle">
                                <input type="hidden" name="is_active" id="is_active" value="{{$user->is_active}}">
                                @endif
                            </div>
                           
                            @if ($user->user_type == '0')
                                <div class="form-group">
                                    <label class="checkbox-inline col-md-4">Make Professional <span class="required-star">*</span></label>
                                    <input name="user_type" type="checkbox" class="" data-toggle="toggle">
                                    <input type="hidden" name="user_type" id="user_type" value="{{$user->user_type}}">
                                </div>
                            
                            @else

                                <div class="form-group">
                                    <label class="checkbox-inline col-md-4">Make Consumer <span class="required-star">*</span></label>
                                    <input name="user_type" type="checkbox" class="" data-toggle="toggle">
                                    <input type="hidden" name="user_type" id="user_type" value="{{$user->user_type}}">
                                </div>
                            @endif

                            
                            
                            
                            <input type="hidden" name="user_id" value="{{$user->id}}" />
                            {{-- <input type="hidden" name="user_type" value="{{$user->user_type}}" /> --}}
                            <button type="submit" class="btn btn-primary ml-20">Update</button>

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
<script src="{{asset('public/admin/users/js/view.js')}}"></script>
@stop
