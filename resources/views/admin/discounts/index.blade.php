@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{route('createDiscount')}}" class="btn btn-primary pull-right">Add Discount Slab</a>
        {{-- <button type="button" id="add_discount_slab" class="btn btn-primary pull-right">Add Discount Slab</button>
        --}}
    </div>
</div>

<div class="row">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Discounts List
            </div>

            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableDiscounts">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Min. Order Value</th>
                            <th>Max. Order Value</th>
                            <th>Thumbnail</th>
                            <th>Discount %</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($discounts as $discount)
                        <tr class="odd gradeX">
                            <td>{{$discount->id}}</td>
                            <td>{{$discount->min_amount}}</td>
                            <td>{{$discount->max_amount}}</td>
                            @if ($discount->image != null && Storage::exists($discount->image))
                            <td><img src="{{asset('storage/app/public/'.$discount->image)}}" id="image" class="thumbnail-image-50" /> </td>
                            @else
                            <td> <img src="{{ asset('storage/app/public/discountImages/placeholder.png') }}" class="thumbnail-image-50" alt="Discount Image"></td>
                            @endif
                            <td class="text-center">{{$discount->discount_percentage}} %</td>
                            <td>{{date('d-m-Y', strtotime($discount->start_date))}}</td>
                            <td>{{date('d-m-Y', strtotime($discount->end_date))}}</td>
                            <td>{{ ($discount->is_active) == 1? 'Active' : 'In Active'}}</td>
                            <td class="text-center">
                                @if (strtotime($discount->start_date) > strtotime((Carbon\Carbon::now())->format("Y-m-d H:i:s")))
                                <a href="{{ route('editDiscount', $discount->id)}}" class="mr-5">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                                @endif
                                <a href="javascript:void(0);" onclick="deleteDiscount({{ $discount->id}})">
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

@endsection

@section('script')
{{-- Module JS file --}}
<script src="{{asset('public/admin/discounts/js/view.js')}}"></script>
@stop
