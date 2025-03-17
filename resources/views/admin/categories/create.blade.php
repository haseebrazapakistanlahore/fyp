@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ route('listCategories') }}" class="btn btn-primary pull-right">Back</a>
    </div>
</div>
<div class="row" id="form">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Add Category
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" action="{{route('storeCategory')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Title <span class="required-star">*</span></label>
                                <input type="text" id="title" maxlength="70" class="form-control" name="title" placeholder="Enter Category Title"
                                    value="{{ old('title') }}" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Category For <span class="required-star">*</span></label>
                                <select name="category_for" id="category_for" class="form-control">
                                    <option {{ (old("category_for") == "0"? "selected":"") }} value="0">Consumer</option>
                                    <option {{ (old("category_for") == "1"? "selected":"") }} value="1">Professional</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Status <span class="required-star">*</span></label>
                                <select name="status" id="status" class="form-control">
                                    <option {{ (old("status") == "1"? "selected":"") }} value="1">Active</option>
                                    <option {{ (old("status") == "0"? "selected":"") }} value="0">In Active</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Image <span class="required-star">*</span></label>
                                <input type="file" id="image_url" class="form-control" name="image_url" required>
                                <img src="" id="image" width="100px" height="100px" class="hidden" />
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12 pt-25">
                                
                                <label class="checkbox-inline">Has Colors? <span class="required-star">*</span></label>
                                <input name="has_colors" type="checkbox"  {{ (old("has_colors") == 1? "checked":'') }} data-toggle="toggle">
                                <input type="hidden" name="has_colors" id="has_colors" value="{{ (old("has_colors") == 1 ? 1 : 0) }}">

                                <label class="checkbox-inline">Has Sizes? <span class="required-star">*</span></label>
                                <input name="has_sizes" type="checkbox"  {{ (old("has_sizes") == 1? "checked":'') }} data-toggle="toggle">
                                <input type="hidden" name="has_sizes" id="has_sizes" value="{{ (old("has_sizes") == 1 ? 1 : 0) }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12"></div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="checkbox-inline">Has Color No.? <span class="required-star">*</span></label>
                                <input name="has_color_no" type="checkbox"  {{ (old("has_color_no") == 1? "checked":'') }} data-toggle="toggle">
                                <input type="hidden" name="has_color_no" id="has_color_no" value="{{ (old("has_color_no") == 1 ? 1 : 0) }}">

                            </div>

                        </div>
                        <div class="col-lg-12">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary">Save</button>
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
<script src="{{asset('public/admin/categories/js/view.js')}}"></script>
@stop
