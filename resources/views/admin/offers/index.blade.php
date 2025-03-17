@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
    <div class="row">
        <div class="col-lg-12 pt-10">
            <a href="{{route('createOffer')}}" class="btn btn-primary pull-right">Add Offer</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Offers List
                </div>

                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableOffers">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Offer Name</th>
                                <th>Prefix</th>
                                <th>No. Of Coupons</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Discount %</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($offers as $offer)
                            <tr class="odd gradeX">
                                <td>{{$offer->id}}</td>
                                <td>{{$offer->offer_name}}</td>
                                <td>{{$offer->prefix}}</td>
                                <td><a href="{{ route('showOffer', $offer->id)}}" title="View Detail">{{ count($offer->coupons)}}</a></td>
                                <td>{{date('d-m-Y', strtotime($offer->start_date))}}</td>
                                <td>{{date('d-m-Y', strtotime($offer->end_date))}}</td>
                                <td>{{$offer->discount_percentage}} %</td>
                                <td>{{ ($offer->is_active) == 1? 'Active' : 'In Active'}}</td>

                                <td class="text-center">
                                      @if (strtotime($offer->start_date) > strtotime((Carbon\Carbon::now())->format("Y-m-d H:i:s")))
                                    <a href="{{ route('editOffer', $offer->id)}}" class="mr-10">
                                        <i class="fa fa-pencil-square-o mr-10" title="View detail" aria-hidden="true"></i>
                                    </a>
                                    @endif
                                    <a href="javascript:void(0);" onclick="deleteOffer('{{$offer->id}}')">
                                        <i class="fa fa-trash" aria-hidden="true" ></i>
                                    </a>
                                </td>

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
{{-- Module JS file --}}
<script src="{{asset('public/admin/offers/js/view.js')}}"></script>
@stop
