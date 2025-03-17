@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ route('listSubCategories') }}" class="btn btn-primary pull-right">Back</a>
    </div>
</div>
<div class="row" id="form">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Edit Child Category
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" action="{{route('updateSubCategory')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">
                           
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Child Category <span class="required-star">*</span></label>
                                <input type="text" id="title" maxlength="70" class="form-control" name="title" value="{{$subCategory->title}}"
                                    required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Parent Category Type <span class="required-star">*</span></label>
                                <select name="category_type" id="edit_category_type" class="form-control">
                                    <option {{ ($parentCategory->type == "0"? "selected":"") }} value="0">Consumer</option>
                                    <option {{ ($parentCategory->type== "1"? "selected":"") }} value="1">Professional</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Category <span class="required-star">*</span></label>

                                <select name="category_id" id="edit_category_id" class="form-control">
                                    {{-- <option value="" selected disabled>Select Category</option> --}}
                                    @foreach ($categories as $category)
                                    <option value="{{$category->id}}" @if($subCategory->category->id == $category->id)
                                        selected @endif>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Status <span class="required-star">*</span></label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ ($subCategory->is_active == '1')? 'selected':'' }}>Active</option>
                                    <option value="0" {{ ($subCategory->is_active == '0')? 'selected':'' }}>In Active</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Image</label>
                                <input type="file" id="image_url" class="form-control" name="image_url" >
                                @if ($subCategory->image != null && Storage::exists($subCategory->image))
                                <img src="{{ asset('storage/app/public/'.$subCategory->image) }}" id="image" class="thumbnail-image-100" />
                                @else
                                <img src="{{ asset('storage/app/public/categoryImages/placeholder.png') }}" id="image" class="thumbnail-image-100" class="hidden" />
                                @endif
                            </div>
                            
                            <div class="col-lg-12">
                                <input type="hidden" name="sub_category_id" value="{{$subCategory->id}}" />
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
<script src="{{asset('public/admin/subCategories/js/view.js')}}"></script>
@endSection