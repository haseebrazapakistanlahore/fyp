@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ route('listSubChildCategories') }}" class="btn btn-primary pull-right">Back</a>
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
                    <form role="form" action="{{route('updateSubChildCategory')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">
                           
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Sub Child Category <span class="required-star">*</span></label>
                                <input type="text" id="title" maxlength="70" class="form-control" name="title" value="{{$subChildCategory->title}}"
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

                                <select name="category_id" id="category_id" class="form-control">
                                    {{-- <option value="" selected disabled>Select Category</option> --}}
                                    @foreach ($categories as $category)
                                    <option value="{{$category->id}}" @if($subChildCategory->category->id == $category->id)
                                        selected @endif>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                           
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Child Category <span class="required-star">*</span></label>

                                <select name="sub_category_id" id="sub_category_id" class="form-control">
                                    {{-- <option value="" selected disabled>Select Category</option> --}}
                                    @foreach ($subCategories as $subCategory)
                                    <option value="{{$subCategory->id}}" @if($subChildCategory->sub_category_id == $subCategory->id)
                                        selected @endif>{{ $subCategory->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Image</label>
                                <input type="file" id="image_url" class="form-control" name="image_url" >
                                @if ($subChildCategory->image != null && Storage::exists($subChildCategory->image))
                                <img src="{{ asset('storage/app/public/'.$subChildCategory->image) }}" id="image" class="thumbnail-image-100" />
                                @else
                                <img src="{{ asset('storage/app/public/categoryImages/placeholder.png') }}" id="image" class="thumbnail-image-100" class="hidden" />
                                @endif
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Status <span class="required-star">*</span></label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ ($subChildCategory->is_active == '1')? 'selected':'' }}>Active</option>
                                    <option value="0" {{ ($subChildCategory->is_active == '0')? 'selected':'' }}>In Active</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-12">
                                <input type="hidden" name="sub_child_category_id" value="{{$subChildCategory->id}}" />
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
<script src="{{asset('public/admin/subChildCategories/js/view.js')}}"></script>
@endSection