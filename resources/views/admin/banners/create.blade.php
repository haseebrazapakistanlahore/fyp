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
            <div class="panel-heading">
                Add Banner
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" action="{{route('storeBanner')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Title <span class="required-star">*</span></label>
                                <input type="text" id="title" maxlength="50" class="form-control" name="title" value="{{old('title')}}"
                                    placeholder="Enter Banner Title" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Status <span class="required-star">*</span></label>
                                <select class="form-control" name="status" id="status">
                                    <option value="1" {{ old('status') == "1" ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == "0" ? 'selected' : '' }}>In Active</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Description <span class="required-star">*</span></label>
                                <textarea name="description" maxlength="100" id="description" style="resize:none" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                    rows="3" placeholder="Enter banner description" required>{{ old('description') }}</textarea>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Thumbnail <span class="required-star">*</span></label>
                                <input type="file" id="image_url" class="form-control" name="image_url" required>
                                <img src="" id="image" width="500px" height="200px" class="hidden" />
                            </div>

                        </div>
                        <div class="col-lg-12">
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
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
<script src="{{asset('public/admin/banners/js/view.js')}}"></script>
@stop
