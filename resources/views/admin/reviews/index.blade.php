@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
    <div class="row">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Reviews List
                </div>

                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableReviews">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>Image</th>
                                <th>Product Title</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($reviews as $review)
                            <tr class="odd gradeX">
                                <td>{{$review->id}}</td>
                                @php
                                    if($review->consumer != null){
                                        $user = $review->consumer;
                                    }else{
                                        $user = $review->professional;
                                    }
                                @endphp
                                @if($review->consumer != null)
                                    <td><a href="{{route('consumerDetail', $user->id)}}">{{$user->full_name}}</a></td>
                                @else
                                    <td><a href="{{route('professionalDetail', $user->id)}}">{{$user->full_name}}</a></td>
                                @endif
                                <td>
                                    <img src="{{ isset($user->profile_image) ? url('storage/app/public/'.$user->profile_image) : url('storage/app/public/userImages/placeholder.png') }}"
                                        class="image-60" alt="User Profile Image">
                                </td>
                                <td><a href="{{ route('productDetail', $review->product->id) }}">{{$review->product->title}}</a></td>
                                <td>{{$review->rating}}</td>
                                <td>{{$review->comment}}</td>
                                <td>{{($review->is_approved == 1) ? 'Approved' : 'Not Approved' }}</td>

                                <td class="text-center">
                                    @if ( $review->is_approved != 1)
                                    <a href="{{ route('approveReview', $review->id)}}" class="mr-10">
                                        <i class="fa fa-check" title="Approve Review" aria-hidden="true"></i>
                                    </a>
                                    @endif
                                    
                                    <a href="javascript:void(0);" onclick="deleteReview('{{$review->id}}');">
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
    $('#dataTableReviews').DataTable({
        responsive: true, 
        "order": [],
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [2,4,5,7]
        },{
            "bSearchable": false, 
            'aTargets': [2,4,5,7]
        }],
    });
});
</script>
{{-- Module JS file --}}
<script src="{{asset('public/admin/reviews/js/view.js')}}"></script>
@stop
