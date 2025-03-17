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
                Edit Consumer
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" id="updateConsumerForm" action="{{route('updateConsumer')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">

                            <fieldset>
                                <legend>Consumer Info</legend>
                                
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Full Name <span class="required-star">*</span></label>
                                    <input type="text" id="full_name" class="form-control" name="full_name" value="{{$consumer->full_name}}"
                                        required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Email <span class="required-star">*</span></label>
                                    <input type="text" id="email" class="form-control" name="email" value="{{$consumer->email}}"
                                        disabled required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Phone <span class="required-star">*</span></label>
                                    <input type="text" id="phone" class="form-control" name="phone" value="{{$consumer->phone}}"
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

                                    @if($consumer->profile_image != null && Storage::exists($consumer->profile_image))
                                    <img id="user_profile_image" src="{{ asset('storage/app/public/'.$consumer->profile_image)}}"
                                        alt="Consumer Profile image" class="thumbnail-image-100">
                                    @else
                                    <img id="user_profile_image" src="{{ asset('storage/app/public/userImages/placeholder.png')}}"
                                            alt="Consumer Profile image" class="thumbnail-image-100">
                                    @endif

                                </div>

                                <div  id="professional-fields2" style="display:none" >
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label>Discount Allowed</label>
                                        <select id="discount_allowed" class="form-control" name="discount_allowed">
                                            <option selected value="0">Not Allowed</option>
                                            <option value="1">Allowed</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12 discount_fields">
                                        <label>Min. Order Value <span class="required-star">*</span></label>
                                        <input id="min_order_value" name="min_order_value" type="number" min="1" class="form-control" placeholder="Enter Min. Order Value">
                                    </div>
                                    
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12 discount_fields" >
                                        <label>Discount Value (%)  <span class="required-star">*</span></label>
                                        <input id="discount_value" type="number" min="0" max="100" class="form-control" placeholder="Enter Discount Percentage" name="discount_value">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset id="professional-fields" style="display:none;">
                                <legend>Salon/Company Info</legend>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Salon/Company Name <span class="required-star">*</span></label>
                                    <input type="text" id="company_name" class="form-control" name="company_name"
                                        placeholder="Enter Salon/Company Name" >
                                </div>
                                
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Salon/Company Address <span class="required-star">*</span></label>
                                    <input type="text" id="company_address" class="form-control" name="company_address"
                                        placeholder="Enter Salon/Company Adress" >
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Company Website</label>
                                    <input type="text" id="company_website" class="form-control" name="company_website"
                                        placeholder="Enter Comapany Website" >
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
                            
                            <div id="professional-fields3" class="form-group" style="display:none;">
                                <label class="checkbox-inline col-md-4">Authorized <span class="required-star">*</span></label>
                                <input name="is_authorized" type="checkbox" class="" data-toggle="toggle">
                                <input type="hidden" name="is_authorized" id="is_authorized" value="0">
                            </div>

                            <div class="form-group">
                                <label class="checkbox-inline col-md-4">Suspend Account <span class="required-star">*</span></label>
                                @if ($consumer->is_active == "0")
                                <input name="is_active" type="checkbox" class="" checked data-toggle="toggle">
                                <input type="hidden" name="is_active" id="is_active" value="{{$consumer->is_active}}">
                                @else
                                <input name="is_active" type="checkbox" class="" data-toggle="toggle">
                                <input type="hidden" name="is_active" id="is_active" value="{{$consumer->is_active}}">
                                @endif
                            </div>
                           

                            <div class="form-group">
                                <label class="checkbox-inline col-md-4">Make Professional <span class="required-star">*</span></label>
                                <input name="user_type" type="checkbox" class="" data-toggle="toggle">
                                <input type="hidden" name="user_type" id="user_type" value="0">
                            </div>
                            

                            <input type="hidden" name="consumer_id" value="{{$consumer->id}}" />
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
<script src="{{asset('public/admin/consumers/js/view.js')}}"></script>
@stop
