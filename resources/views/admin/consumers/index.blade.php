@extends('layouts.admin')

@section('content')

<div id="list">
    <div class="row">
        <div class="col-lg-12 pt-10">
            <h1>{{ $title }}</h1>
        </div>
    </div>

    <div class="row pt-10">

        <form id="filter_user_form" action="{{ route('searchConsumers') }}" method="POST" enctype="multipart/form-data"
            class="form-inline">
            @csrf
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    <label class="control-label">Start Date</label>
                    <div class="input-group date previous">
                        <input class="form-control" type="text" name="start_date" required value="{{isset($startDate)? $startDate:''}}" />
                        <span class="input-group-addon">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fa fa-calendar"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    <label class="control-label">End Date</label>
                    <div class="input-group date previous">
                        <input class="form-control" type="text" name="end_date" required  value="{{isset($endDate)? $endDate:''}}"/>
                        <span class="input-group-addon">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fa fa-calendar"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-2 col-lg-1">
                <div class="form-group pt-20">
                    <input type="submit" class="btn btn-primary" value="Filter">
                </div>
            </div>
        </form>
        @if (isset($startDate) && isset($endDate) && count($consumers) > 0)
        <form action="{{ route('exportConsumers')}}" method="POST">
            @csrf
            <input type="hidden" name="start_date_second" value="{{isset($startDate)? $startDate:''}}">
            <input type="hidden" name="end_date_second" value="{{isset($endDate)? $endDate:''}}">
            <div class="col-md-2 col-lg-3">
                <div class="form-group pt-20">
                    <input type="submit" class="btn btn-success" value="Export These Consumers">
                </div>
            </div>
        </form>
        @endif
      
    </div>

    <div class="row">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Consumers List
                </div>

                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableConsumers">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Is Active</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>@php $count=1; @endphp

                            @foreach($consumers as $consumer)
                            <tr class="odd gradeX">
                                <td>{{$count++}}</td>
                                <td> 
                                    <a href="{{ route('consumerDetail', $consumer->id)}}" class="mr-10">
                                        {{$consumer->full_name}}
                                    </a>
                                </td>
                                <td>{{$consumer->email}}</td>
                                <td>{{$consumer->phone}}</td>
                                <td>Consumer</td>
                                <td>{{($consumer->is_active == 1) ? 'Active' : 'Suspended'}}</td>

                                <td class="text-center">
                                    <a href="{{ route('editConsumer', $consumer->id) }}" class="mr-10">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true" ></i>
                                    </a>
                                    <a href="javascript:void(0);" onclick="deactivateConsumer('{{$consumer->id}}');">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
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
<script src="{{asset('public/admin/consumers/js/view.js')}}"></script>
@stop
