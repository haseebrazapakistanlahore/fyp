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
                Edit Category
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" action="{{route('updateCategory')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Category Title <span class="required-star">*</span></label>
                                <input type="text" id="title" maxlength="70" class="form-control" name="title" value="{{$category->title}}"
                                    required>
                            </div>
                            
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Category For <span class="required-star">*</span></label>
                                <select name="category_for" id="category_for" class="form-control">
                                    <option value="0" {{ ($category->type == '0')? 'selected':'' }}>Consumer</option>
                                    <option value="1" {{ ($category->type == '1')? 'selected':'' }}>Professional</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Status <span class="required-star">*</span></label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ ($category->is_active == '1')? 'selected':'' }}>Active</option>
                                    <option value="0" {{ ($category->is_active == '0')? 'selected':'' }}>In Active</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12 pt-25">
                                <label class="checkbox-inline">Has Colors? <span class="required-star">*</span></label>
                                @if ($category->has_colors == "0")
                                <input name="has_colors" type="checkbox" class="" data-toggle="toggle">
                                <input type="hidden" name="has_colors" id="has_colors" value="{{$category->has_colors}}">
                                @else
                                <input name="has_colors" type="checkbox" class="" checked data-toggle="toggle">
                                <input type="hidden" name="has_colors" id="has_colors" value="{{$category->has_colors}}">
                                @endif
                                
                                <label class="checkbox-inline">Has Sizes? <span class="required-star">*</span></label>
                                @if ($category->has_sizes == "0")
                                <input name="has_sizes" type="checkbox" class="" data-toggle="toggle">
                                <input type="hidden" name="has_sizes" id="has_sizes" value="{{$category->has_sizes}}">
                                @else
                                <input name="has_sizes" type="checkbox" class="" checked data-toggle="toggle">
                                <input type="hidden" name="has_sizes" id="has_sizes" value="{{$category->has_sizes}}">
                                @endif
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Image</label>
                                <input type="file" id="image_url" class="form-control" name="image_url" >
                                @if ($category->image != null && Storage::exists($category->image))
                                <img src="{{ asset('storage/app/public/'.$category->image) }}" id="image" class="thumbnail-image-100" />
                                @else
                                <img src="{{ asset('storage/app/public/categoryImages/placeholder.png') }}" id="image" width="100px" height="100px" />
                                @endif
                            </div>

                            
                            <div class="form-group col-md-6 col-sm-6 col-xs-12"></div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="checkbox-inline">Has Color No.? <span class="required-star">*</span></label>
                                @if ($category->has_color_no == "0")
                                <input name="has_color_no" type="checkbox" class="" data-toggle="toggle">
                                <input type="hidden" name="has_color_no" id="has_color_no" value="{{$category->has_color_no}}">
                                @else
                                <input name="has_color_no" type="checkbox" class="" checked data-toggle="toggle">
                                <input type="hidden" name="has_color_no" id="has_color_no" value="{{$category->has_color_no}}">
                                @endif

                            </div>

                                
                            <div class="col-lg-12">
                                <input type="hidden" name="category_id" value="{{$category->id}}" />
                                <button type="submit" class="btn btn-primary">Update</button>
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
<script src="{{asset('public/admin/categories/js/view.js')}}"></script>
@endsection
