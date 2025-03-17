@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('editConsumer', $consumer->id) }}" class="btn btn-primary ml-10 pull-right">Edit</a>
            <a href="{{ route('listConsumers') }}" class="btn btn-primary pull-right">Back</a>
        </div>
    </div>

    <div class="row ">
        <div class="col-lg-12 pt-20">
            <div id="user-profile-2" class="user-profile">
                <div class="tabbable">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 center">
                            <div class="">
                                <span class="profile-picture">
                                    <img class="editable img-responsive" alt=" Avatar" id="avatar2" src="{{($consumer->profile_image !== null)? asset('storage/app/public/'.$consumer->profile_image) : asset('storage/app/public/userImages/placeholder.png')}}">
                                </span>
                            </div>
                        </div><!-- /.col -->

                        <div class="col-xs-12 col-sm-9">

                            <div class="profile-user-info">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Full Name </div>

                                    <div class="profile-info-value">
                                        <span>{{$consumer->full_name}}</span>
                                    </div>
                                </div>
                                
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Email </div>

                                    <div class="profile-info-value">
                                        <i class="fa fa-envelope blue bigger-110"></i>
                                        <span>{{$consumer->email}}</span>
                                    </div>
                                </div>
                               
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Phone </div>

                                    <div class="profile-info-value">
                                        <i class="fa fa-phone blue bigger-110"></i>
                                        <span>{{$consumer->phone}}</span>
                                    </div>
                                </div>
                                
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Status </div>

                                    <div class="profile-info-value">
                                        <span>{{($consumer->is_active == 1) ? 'Active' : 'Suspended'}}</span>
                                    </div>
                                </div>

                                @if($address != null)
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Address </div>

                                    <div class="profile-info-value">
                                        <i class="fa fa-map-marker light-orange bigger-110"></i>
                                        <span>{{$address->city }}</span>
                                        <span>{{$address->address }}</span>
                                    </div>
                                </div>
                                @endif
                               
                                @if($shippingAddress != null)
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Shipping Address </div>

                                    <div class="profile-info-value">
                                        <i class="fa fa-map-marker light-orange bigger-110"></i>
                                        <span>{{$shippingAddress->city }}</span>
                                        <span>{{$shippingAddress->address }}</span>
                                    </div>
                                </div>
                                @endif
                               
                            </div>

                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <div class="space-20"></div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
