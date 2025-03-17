@extends('layouts.admin')

@section('content')

<div id="list">
    <div class="row">
        <div class="col-lg-12 pt-10">
            <h1>{{ $title }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Deleted Professionals List
                </div>

                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableDeleted">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>@php $count=1; @endphp

                            @foreach($professionals as $professional)
                            <tr class="odd gradeX">
                                <td>{{$count++}}</td>
                                <td> 
                                    <a href="{{ route('professionalDetail', $professional->id)}}" class="mr-10">
                                        {{$professional->full_name}}
                                    </a>
                                </td>
                                <td>{{$professional->email}}</td>
                                <td>{{$professional->phone}}</td>
                                <td>Professional</td>
                                <td class="text-center">
                                    
                                    <a href="javascript:void(0);" onclick="activateProfessional('{{$professional->id}}');">
                                        <i class="fa fa-check" aria-hidden="true"></i>
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
<script src="{{asset('public/admin/professionals/js/view.js')}}"></script>
@stop
