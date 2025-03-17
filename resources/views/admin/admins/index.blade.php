@extends('layouts.admin')

@section('content')

<div id="list">
    <div class="row">
        <div class="col-lg-12 pt-10">
            <h1>{{ $title }}</h1>
        </div>
    </div>
    <div class="row pt-10">
        <div class="col-lg-12 pull-right">
        <div class="pull-right">
            <a href="{{ route('createAdminUser') }}" class="btn btn-primary">Add New Admin</a>
        </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Admins List
                </div>

                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableAdmins">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Permissions</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>@php $count=1; @endphp

                            @foreach($admins as $user)
                            <tr class="odd gradeX">
                                <td>{{$count++}}</td>
                                <td>{{$user->full_name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->phone}}</td>
                                <td>{{($user->getUserPermissions() == '') ? '--' : $user->getUserPermissions()}}</td>
                                <td>{{($user->is_active == 1) ? 'Active' : 'Suspended'}}</td>

                                <td class="text-center">
                                    {{-- @if ($user->user_type == '1')
                                        
                                    @endif --}}
                                    <a href="{{ route('editAdminUser', $user->id) }}" class="mr-10">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true" ></i>
                                    </a>
                                    {{-- <a href="javascript:void(0);" onclick="deactivateAdminUser('{{$user->id}}');">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a> --}}
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
<script src="{{asset('public/admin/admins/js/view.js')}}"></script>
@stop
