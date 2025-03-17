@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ route('listProfessionals') }}" class="btn btn-primary pull-right">Back</a>
    </div>
</div>
<div class="row" id="form">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Edit Professional
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" id="updateProfessionalForm" action="{{route('updateProfessional')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">

                            <fieldset>
                                <legend>Professional Info</legend>
                                
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Full Name <span class="required-star">*</span></label>
                                    <input type="text" id="full_name" class="form-control" name="full_name" value="{{$professional->full_name}}"
                                        required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Email <span class="required-star">*</span></label>
                                    <input type="text" id="email" class="form-control" name="email" value="{{$professional->email}}"
                                        disabled required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Phone <span class="required-star">*</span></label>
                                    <input type="text" id="phone" class="form-control" name="phone" value="{{$professional->phone}}"
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

                                    @if($professional->profile_image != null && Storage::exists($professional->profile_image))
                                    <img id="user_profile_image" src="{{ asset('storage/app/public/'.$professional->profile_image)}}"
                                        alt="Professional Profile image" class="thumbnail-image-100">
                                    @else
                                    <img id="user_profile_image" src="{{ asset('storage/app/public/userImages/placeholder.png')}}"
                                            alt="Professional Profile image" class="thumbnail-image-100">
                                    @endif

                                </div>
                                <div id="professional-fields2">
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label>Discount Allowed</label>
                                        <select id="edit_discount_allowed" class="form-control" name="discount_allowed">
                                            <option {{( $professional->discount_allowed == "0")?'selected':'' }} value="0">Not Allowed</option>
                                            <option {{( $professional->discount_allowed == "1") ?'selected':'' }} value="1">Allowed</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12 edit_discount_fields" {{( $professional->discount_allowed == '0')?'style=display:none':'' }}>
                                        <label>Min. Order Value <span class="required-star">*</span></label>
                                        <input id="min_order_value" name="min_order_value" min="0" type="number" class="form-control" placeholder="Enter Min. Order Value" value="{{$professional->min_order_value}}">
                                    </div>
                                    
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12 edit_discount_fields" {{( $professional->discount_allowed == '0')?'style=display:none':'' }}>
                                        <label>Discount Value (%) <span class="required-star">*</span></label>
                                        <input id="discount_value" type="number" max="100" min="0" class="form-control" placeholder="Enter Discount Percentage" name="discount_value" value="{{$professional->discount_value}}">
                                    </div>
                                </div>
                            </fieldset>
          
                            <fieldset id="professional-fields">
                                <legend>Salon/Company Info</legend>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Salon/Company Name <span class="required-star">*</span></label>
                                    <input type="text" id="company_name" class="form-control" name="company_name"
                                        placeholder="Enter Salon/Company Name" value="{{ $professional->company_name }}" required>
                                </div>
                                
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Salon/Company Address <span class="required-star">*</span></label>
                                    <input type="text" id="company_address" class="form-control" name="company_address"
                                        placeholder="Enter Salon/Company Adress" value="{{ $professional->company_address }}" required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Company Website</label>
                                    <input type="text" id="company_website" class="form-control" name="company_website"
                                        placeholder="Enter Comapany Website" value="{{ $professional->company_website }}"
                                        >
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
                            @else
                            <fieldset>
                                <legend>Billing Addresses</legend>
                                
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Address</label>
                                    <input type="text" id="billing_address" class="form-control" name="billing_address">
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>City</label>
                                    <input type="text" id="billing_city" class="form-control" name="billing_city" >
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Country</label>
                                    <input type="text" id="billing_country" class="form-control" name="billing_country" >
                                </div>

                            </fieldset>
                            @endif

                            {{-- @if ($shippingAddress != null)
                                
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
                            @else
                            <fieldset>
                                <legend>Shipping Addresses</legend>
                               
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Address</label>
                                    <input type="text" id="shipping_address" class="form-control" name="shipping_address">
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>City</label>
                                    <input type="text" id="shipping_city" class="form-control" name="shipping_city">
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Country</label>
                                    <input type="text" id="shipping_country" class="form-control" name="shipping_country">
                                </div>
                            </fieldset>
                            @endif --}}

                            @php
                                $counter = 1;
                            @endphp
                            @if (count($shippingAddresses) > 0)
                            <fieldset>
                                <legend>Shipping Addresses</legend>
                               
                                @foreach ($shippingAddresses as $shippingAddress)
                                   <div class="col-md-12 p-0 pt-20">
                                       <div class="col-md-12">
                                        <h4>Shipping Address {{$counter}}</h4>   
                                        </div>
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label>Address</label>
                                    <input type="text" id="shipping_address{{$counter}}" class="form-control" name="shipping_address{{$counter}}" value="{{$shippingAddress->address}}">
                                    </div>

                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label>City</label>
                                        <input type="text" id="shipping_city{{$counter}}" class="form-control" name="shipping_city{{$counter}}" value="{{$shippingAddress->city}}">
                                    </div>

                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label>Country</label>
                                        <input type="text" id="shipping_country{{$counter}}" class="form-control" name="shipping_country{{$counter}}" value="{{$shippingAddress->country}}">
                                    </div>
                                    <input type="hidden" name="shipping_id{{$counter}}" value="{{$shippingAddress->id}}">
                                    @php
                                        $counter++;
                                    @endphp
                                    </div>
                                @endforeach
                            </fieldset>
                            @else
                            <fieldset>
                                <legend>Shipping Addresses</legend>
                               
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Address</label>
                                    <input type="text" id="shipping_address" class="form-control" name="shipping_address">
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>City</label>
                                    <input type="text" id="shipping_city" class="form-control" name="shipping_city">
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Country</label>
                                    <input type="text" id="shipping_country" class="form-control" name="shipping_country">
                                </div>
                            </fieldset>
                            @endif
                            <input type="hidden" name="shipping_address_count" value="{{count($shippingAddresses)}}">
                            
                           
                            
                            <div id="professional-fields3" class="form-group">
                                <label class="checkbox-inline col-md-4">Authorized <span class="required-star">*</span></label>
                                @if ($professional->is_authorized == "1")
                                <input name="is_authorized" type="checkbox" class="" checked data-toggle="toggle">
                                <input type="hidden" name="is_authorized" id="is_authorized" value="{{$professional->is_authorized}}">
                                @else
                                <input name="is_authorized" type="checkbox" class="" data-toggle="toggle">
                                <input type="hidden" name="is_authorized" id="is_authorized" value="{{$professional->is_authorized}}">
                                @endif
                            </div>
                           
                            <div class="form-group">
                                <label class="checkbox-inline col-md-4">Suspend Account <span class="required-star">*</span></label>
                                @if ($professional->is_active == "0")
                                <input name="is_active" type="checkbox" class="" checked data-toggle="toggle">
                                <input type="hidden" name="is_active" id="is_active" value="{{$professional->is_active}}">
                                @else
                                <input name="is_active" type="checkbox" class="" data-toggle="toggle">
                                <input type="hidden" name="is_active" id="is_active" value="{{$professional->is_active}}">
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="checkbox-inline col-md-4">Make Consumer <span class="required-star">*</span></label>
                                <input name="user_type" type="checkbox" class="" data-toggle="toggle">
                                <input type="hidden" name="user_type" id="user_type" value="1">
                            </div>
                          
                            <input type="hidden" name="professional_id" value="{{$professional->id}}" />
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
<script src="{{asset('public/admin/professionals/js/view.js?v=1')}}"></script>
@stop
