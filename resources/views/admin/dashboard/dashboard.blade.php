@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">

    @if(Auth::user()->hasPermission('manage-users'))
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $registerConsumerInMonth}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="pr-10 pull-right">Consumers Registered (This Month)</div>
                </div>
            </div>
            <a href="{{ route('listConsumers')}}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    @endif

    @if(Auth::user()->hasPermission('manage-categories'))
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-list-alt fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $categoriesCount }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="pr-10 pull-right">Total Active Categories</div>
                </div>
            </div>
            <a href="{{ route('listCategories')}}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    @endif


</div>

<div class="row">

    @if(Auth::user()->hasPermission('manage-products'))
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-gift fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $productsCount }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="pr-10 pull-right">Total Products</div>
                </div>
            </div>
            <a href="{{ route('listProducts')}}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    @endif

    @if(Auth::user()->hasPermission('manage-orders'))
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-dollar fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $totalCompletedSalesThisMonth }} / {{$totalPendingSalesThisMonth}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="pr-10 pull-right">Total Completed / Pending Sales (This Month)</div>
                </div>
            </div>
            <a href="{{ route('listOrders')}}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    @endif

</div>

<div class="row">

    @if(Auth::user()->hasPermission('manage-offers'))
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-credit-card fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $totalOffers }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="pr-10 pull-right">Total Offers</div>
                </div>
            </div>
            <a href="{{ route('listOffers')}}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    @endif

    @if(Auth::user()->hasPermission('manage-orders'))
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $totalOrdersInMonth->totalOrders }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="pr-10 pull-right">Total Orders (This Month)</div>
                </div>
            </div>
            <a href="{{ route('listOrders')}}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $professionalMaxOrders->totalOrders }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="pr-10 pull-right">Professionals Orders (This Month)</div>
                </div>
            </div>
            <a href="{{ route('listOrders')}}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $consumerMaxOrders->totalOrders }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="pr-10 pull-right">Consumers Orders (This Month)</div>
                </div>
            </div>
            <a href="{{ route('listOrders')}}">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    @endif
</div>
<!-- /.row -->



<div class="row" align="centre">
    <h4 class="ml-20"> Send Notification To All Users: </h4>
    <form role="form" action="{{route('sendNotification')}}" method="post">
        {{csrf_field()}}
        <div class="col-lg-12">
            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                <label>Content <span class="required-star">*</span></label>
                <textarea name="content" maxlength="500" id="content" style="resize:none"
                    class="form-control {{ $errors->has('content') ? ' is-invalid' : '' }}" rows="3"
                    placeholder="Enter Content" required>{{ old('content') }}</textarea>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </div>
    </form>
</div>

<!--
<div class="row">
    <div class="col-md-12">
        <form action="{{route('updateDeliveryCharges')}}" method="POST"> @csrf
            <fieldset class="bordered pb-2">
                <legend class="w-auto"><small>Consumers</small></legend>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">Min order value</label>
                        <input type="number" name="min_order_consumer" id="min_order_consumer" class="form-control"
                            value="{{isset($setting->min_order_consumer) ? $setting->min_order_consumer : 0}}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Delivery charges</label>
                        <input type="number" name="delivery_charges_consumer" id="delivery_charges_consumer"
                            class="form-control"
                            value="{{isset($setting->delivery_charges_consumer) ? $setting->delivery_charges_consumer : 0}}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">VAT <small>(In Percentage)</small></label> <input type="number"
                            name="vat_consumer" id="vat_consumer" class="form-control"
                            value="{{isset($setting->vat_consumer) ? $setting->vat_consumer : 0}}">
                    </div>
                </div>
            </fieldset>

            <fieldset class="bordered pb-2">
                <legend><small>Professionals</small></legend>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">Min Order value</label>
                        <input type="number" name="min_order_professional" id="min_order_professional"
                            class="form-control"
                            value="{{isset($setting->min_order_professional) ? $setting->min_order_professional : 0}}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Delivery charges</label>
                        <input type="number" name="delivery_charges_professional" id="delivery_charges_professional"
                            class="form-control"
                            value="{{isset($setting->delivery_charges_professional) ? $setting->delivery_charges_professional : 0}}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">VAT <small>(In Percentage)</small></label>
                        <input type="number" name="vat_professional" id="vat_professional" class="form-control"
                            value="{{isset($setting->vat_professional) ? $setting->vat_professional : 0}}">
                    </div>
                </div>
            </fieldset>

            <div class="form-group col-md-12">
                <button type="submit" class="btn btn-primary btn-sm"> Save & Update</button>
            </div>
        </form>
    </div>
</div>-->
@endsection
