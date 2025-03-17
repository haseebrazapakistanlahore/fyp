@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>

<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ route('createBanner') }}" class="btn btn-primary pull-right">Add Banner</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Banners List
            </div>

            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableBanners">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Thumbnail</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($banners as $banner)
                        <tr class="odd gradeX">
                            <td>{{$banner->id}}</td>
                            <td>{{$banner->title}}</td>
                            <td><img class="banner-thumbnail-image" src="{{ asset('storage/app/public/'.$banner->image_url) }}" ></td>
                            <td>{{($banner->is_active) ? 'Active' : 'In Active'}}</td>
                            
                            <td>
                                <a href="{{ route('editBanner', $banner->id) }}" class="mr-10">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                                <a href="javascript:void(0);" onclick="deleteBanner('{{$banner->id}}');">
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
@endsection

@section('script')
{{-- Module JS file --}}
 <script src="{{asset('public/admin/banners/js/view.js')}}"></script>
@stop
