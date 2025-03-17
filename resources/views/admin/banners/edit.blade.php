@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>

<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ route('listBanners') }}" class="btn btn-primary pull-right">Back</a>
    </div>
</div>
<div class="row" id="form">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading ">
                Edit Banner
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" action="{{route('updateBanner')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Title <span class="required-star">*</span></label>
                                <input type="text" id="title"  maxlength="50" class="form-control" name="title" value="{{ $banner->title }}"
                                    placeholder="Enter Banner Title" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Status <span class="required-star">*</span></label>
                                <select class="form-control" name="status" id="status" required>\
                                    <option {{ ($banner->is_active == "1"? "selected":"") }} value="1">Active</option>
                                    <option {{ ($banner->is_active == "0"? "selected":"") }} value="0">In Active</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Description <span class="required-star">*</span></label>
                                <textarea name="description" maxlength="300" id="description" style="resize:none" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                    rows="3" placeholder="Enter banner description" required>{{ $banner->description }}</textarea>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Thumbnail </label>
                                <input type="file" id="image_url" class="form-control" name="image_url" value="{{ $banner->image_url }}">
                                <img src="{{ asset('storage/app/public/'.$banner->image_url) }}" id="image" class="banner-thumbnail-image pt-10" />
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <input type="hidden" name="banner_id" value="{{$banner->id}}" />
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                    <div class="col-lg-12">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Module JS file --}}
<script src="{{asset('public/admin/banners/js/view.js')}}"></script>
@stop
