@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
    <div class="row">
        <div class="col-lg-12 pt-10">
            <a href="{{route('listOffers')}}" class="btn btn-primary pull-right">Back</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Offer Coupons
                </div>

                <div class="panel-body">
                    <div class="row pb-30">
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-3 font-bold">Offer Id :</span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-9">{{$offer->id}}</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-3 font-bold">Offer Name :</span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-9">{{$offer->offer_name}}</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-3 font-bold">Prefix :</span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-9">{{$offer->prefix}}</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-3 font-bold">Discount % :</span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-9">{{$offer->discount_percentage}} %</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-3 font-bold">Start Date :</span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-9">{{date('d-m-Y', strtotime($offer->start_date))}}</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-3 font-bold">End Date :</span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-9">{{date('d-m-Y', strtotime($offer->end_date))}}</span>
                        </div>
                        <div>
                            <span class="col-lg-3 col-md-3 col-sm-3 col-xs-3 font-bold">Offer Status :</span>
                            <span class="col-lg-9 col-md-9 col-sm-9 col-xs-9">{{($offer->is_active) == 1? 'Active' : 'In Active'}}</span>
                        </div>
                    </div>
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableOfferDetail">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Coupon Code</th>
                                <th>Coupon Status</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($offer->coupons as $coupon)
                            <tr class="odd gradeX">
                                <td>{{$coupon->id}}</td>
                                <td>{{$coupon->coupon_code}}</td>
                                <td>{{ ($coupon->is_used) == 0? 'Active' : 'In Active'}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('#dataTableOfferDetail').DataTable({
            responsive: true,
            sort: false,
            "order": []
        });
    });

</script>
{{-- Module JS file --}}
<script src="{{asset('public/admin/offers/js/view.js')}}"></script>
@stop
