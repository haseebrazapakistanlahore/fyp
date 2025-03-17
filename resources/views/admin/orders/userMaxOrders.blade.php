@extends('layouts.admin')

@section('content')

<div id="list"> 
    <div class="row">
        <div class="col-lg-12">
            <h1>{{ $title}}</h1>
        </div>
    </div>
    <div class="row pt-10">
        <div class="col-lg-12">
            @if(count($users) > 0)
            <a href="{{ route($url) }}" class="btn btn-success pull-right">Export As PDF</a>
            @endif
        </div>
    </div> 
    <div class="row">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ $tableTitle}}
                </div>
                
                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableMaxOrders">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Full Name</th>
                                <th>User Type</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>No. of Orders</th>
                            </tr>
                        </thead>
                        <tbody>@php $count=1; @endphp
                            @foreach($users as $user)
                          
                            <tr class="odd gradeX">
                                <td>{{$count++}}</td>
                                <td>{{$user->consumer->full_name}}</td>
                                <td>Consumer</td>
                                <td>{{$user->consumer->email}}</td>
                                <td>{{$user->consumer->phone}}</td>
                                <td>{{$user->orderCount}}</td>    
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
 $('#dataTableMaxOrders').DataTable({
        responsive: true,
        "order": [],
        "aoColumnDefs": [{ 
            "bSortable": false,
            "aTargets": [0] 
        },{
            "bSearchable": false, 
            "aTargets": [0] 
        }]
    });
</script>
{{-- Module JS file --}}
{{-- <script src="{{asset('public/admin/user/js/view.js')}}"></script> --}}
@stop
