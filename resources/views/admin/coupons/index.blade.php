@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
    <div class="row">
        <div class="col-lg-12 pt-10">
        <a href="{{route('createCoupon')}}" class="btn btn-primary pull-right">Add Coupon</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Coupons List
                </div>

                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Coupon Code</th>
                                <th>Prefix</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Discount</th>
                                <th>Is Used</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>@php $count=1; @endphp

                            @foreach($coupons as $coupon)
                            <tr class="odd gradeX">
                                <td>{{$count++}}</td>
                                <td>{{$coupon->coupon_code}}</td>
                                <td>{{$coupon->prefix}}</td>
                                <td>{{date('d-m-Y', strtotime($coupon->start_date))}}</td>
                                <td>{{date('d-m-Y', strtotime($coupon->end_date))}}</td>
                                <td>{{$coupon->discount_percentage}} %</td>
                                <td>{{ ($coupon->is_used) == 0? 'Not used' : 'Used'}}</td>
                                <td>{{ ($coupon->is_active) == 1? 'Active' : 'In Active'}}</td>

                                <td>
                                    <a href="javascript:void(0);" onclick="deleteCoupon('{{$coupon->id}}')">
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
<script>
$(document).ready(function () {
    $('#dataTables-example').DataTable({
        responsive: true, 
        "order": [],
        "aoColumnDefs": [{ 
            "bSortable": false,
            "aTargets": [0,1,4,6,7,8 ] 
        },{
            "bSearchable": false, 
            "aTargets": [ 0,1,4,6,7,8 ] 
        }]

    });
});
</script>
{{-- Module JS file --}}
<script src="{{asset('public/admin/coupons/js/view.js')}}"></script>
@stop
