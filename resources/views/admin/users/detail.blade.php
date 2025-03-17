@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('listUsers') }}" class="btn btn-primary pull-right">Back</a>
        </div>
    </div>

    <div class="row ">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    User Detail
                </div>
                <div class="panel-body">

                    <div class="col-lg-6 pt-20">
                        <table class="table custom-table-design table-bordered" align="left">
                            <tbody>
                                <tr>
                                    <th>First Name</th>
                                    <td>{{$user->first_name}}</td>
                                </tr>
                                <tr>
                                    <th>Full Name</th>
                                    <td>{{$user->full_name}}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{$user->phone}}</td>
                                </tr>
                                @if ($user->user_type == '1')
                                <tr>
                                    <th>Contact Person</th>
                                    <td>{{$user->contact_person_name}}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Acccount Status</th>
                                    <td>{{($user->is_active == 1) ? 'Active' : 'Suspended'}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6 pt-20">

                        <table class="table custom-table-design table-bordered">
                            <tbody>
                                <tr>
                                    <th>Last Name</th>
                                    <td>{{$user->last_name}}</td>
                                </tr>

                                <tr>
                                    <th>Email</th>
                                    <td>{{$user->email}}</td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td>{{($user->user_type == 0) ? 'Consumer' : 'Professional'}}</td>
                                </tr>
                                @if ($user->user_type == '1')
                                <tr>
                                    <th>Company Name</th>
                                    <td>{{$user->company_name}}</td>
                                </tr>
                                @endif
                               
                                <tr>
                                    <th>Is Authorized</th>
                                    <td>{{($user->is_authorized == 1) ? 'Yes' : 'No'}}</td>
                                </tr>
                                <tr>
                                    <th>Profile Image</th>
                                    @if($user->profile_image == null)
                                    <td><img id="user_profile_image" src="{{ asset('storage/app/public/userImages/placeholder.png')}}"
                                            alt="User Profile image" width="100px" height="100px"></td>
                                    @else
                                    <td><img src="{{ asset('storage/app/public/'.$user->profile_image) }}" height="100px;"
                                            width="100"></td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
