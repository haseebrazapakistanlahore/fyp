@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ route('listAdmins') }}" class="btn btn-primary pull-right">Back</a>
    </div>
</div>
<div class="row" id="form">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Edit Admin
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" id="updateUserForm" action="{{route('udpateAdminUser')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">

                            <fieldset>
                              
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Full Name <span class="required-star">*</span></label>
                                    <input type="text" id="full_name" class="form-control" name="full_name" value="{{$admin->full_name}}"
                                        required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Email <span class="required-star">*</span></label>
                                    <input type="text" id="email" class="form-control" name="email" value="{{$admin->email}}"
                                        disabled required>
                                </div>

                                {{-- <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>First Name <span class="required-star">*</span></label>
                                    <input type="text" id="first_name" class="form-control" name="first_name" value="{{$admin->first_name}}"
                                        required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Last Name <span class="required-star">*</span></label>
                                    <input type="text" id="last_name" class="form-control" name="last_name" value="{{$admin->last_name}}"
                                        required>
                                </div> --}}

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Phone <span class="required-star">*</span></label>
                                    <input type="text" id="phone" class="form-control" name="phone" value="{{$admin->phone}}"
                                        required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Password </label>
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="********" >
                                </div>


                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Confirm Password </label>
                                    <input id="password_confirm" type="password" class="form-control" placeholder="********"
                                        name="password_confirmation" >
                                </div>
                               
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Permissions</label>
                                    <select name="permissions[]" id="permissions" multiple data-live-search="true" class="selectpicker form-control {{ $errors->has('permissions') ? ' is-invalid' : '' }}">
                                        @foreach ($permissions as $permission)
                                            {{-- <option value="{{$permission->id}}">{{$permission->name}}</option> --}}
                                            <option @foreach ($admin->permissions as $perm) 
                                                @if ($perm->id == $permission->id) selected @endif @endforeach 
                                                value="{{$permission->id}}">{{$permission->name}}
                                            </option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('permissions'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('permissions') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </fieldset>

                            <div class="form-group">
                                <label class="checkbox-inline">Suspend Account <span class="required-star">*</span></label>
                                @if ($admin->is_active == "0")
                                <input name="is_active" type="checkbox" class="" checked data-toggle="toggle">
                                <input type="hidden" name="is_active" id="is_active" value="{{$admin->is_active}}">
                                @else
                                <input name="is_active" type="checkbox" class="" data-toggle="toggle">
                                <input type="hidden" name="is_active" id="is_active" value="{{$admin->is_active}}">
                                @endif
                            </div>

                            <input type="hidden" name="admin_id" value="{{$admin->id}}" />
                            <button type="submit" class="btn btn-primary">Update</button>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Module JS file --}}
<script src="{{asset('public/admin/users/js/view.js')}}"></script>
@stop
