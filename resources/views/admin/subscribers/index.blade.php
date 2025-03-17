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
                    Subscribers List
                </div>

                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableSubscribers">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($subscribers as $subscriber)
                            <tr class="odd gradeX">
                                <td>{{$subscriber->id}}</td>
                                <td>{{$subscriber->email}}</td>
                          
                                <td class="text-center">
                                    <a href="javascript:void(0);" onclick="deleteSubscriber('{{$subscriber->id}}');">
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
<script src="{{asset('public/admin/subscribers/js/view.js')}}"></script>
@stop
