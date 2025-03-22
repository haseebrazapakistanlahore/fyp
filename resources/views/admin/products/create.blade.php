@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ route('listProducts') }}" class="btn btn-primary pull-right">Back</a>
    </div>
</div>

<div class="row" id="form">
    <div class="col-lg-12 pt-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                Add Product
            </div>
            <div class="panel-body">
                <form role="form" class="row" id="addProductForm" action="{{route('storeProduct')}}" method="post"
                    enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="col-lg-12">


                        <fieldset>
                            <legend>Product Info</legend>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Title <span class="required-star">*</span></label>
                                <input type="text" id="title" maxlength="60" class="form-control" name="title" placeholder="Enter Product Title"
                                    value="{{ old('title') }}" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Type <span class="required-star">*</span></label>
                                <select name="product_type" id="product_type" class="form-control" required>
                                    <option value="" selected disabled>Select Product Type</option>
                                    @foreach ($productTypes as $key =>$value)
                                    <option  value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Category <span class="required-star">*</span></label>
                                <select disabled name="category_id" id="category_id" class="form-control" required>
                                    <option value="" selected disabled>Select Category</option>
                                    {{-- @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{ $category->title }}</option>
                                    @endforeach --}}
                                </select>
                            </div>

                            <!-- <div class="form-group col-md-6 col-sm-6 col-xs-12" style="display: none;">
                                <label>Child Category </label>
                                <select name="sub_category_id" id="sub_category_id" disabled class="form-control">
                                    <option value="" selected disabled>Select Sub Category</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-6 col-sm-6 col-xs-12"  style="display: none;">
                                <label>Sub Child Category </label>
                                <select name="sub_child_category_id" id="sub_child_category_id" disabled class="form-control">
                                    <option value="" selected disabled>Select Sub Child Category</option>
                                </select>
                            </div> -->


                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Shipping Cost <span class="required-star">*</span></label>

                                @if ($errors->has('email'))
                                <input type="number" id="shipping_cost" class="form-control" name="shipping_cost"
                                    placeholder="Enter Shipping Cost" value="{{ old('shipping_cost') }}" required>
                                @else
                                <input type="number" id="shipping_cost" class="form-control" name="shipping_cost"
                                    placeholder="Enter Shipping Cost" value="0" required>
                                @endif
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Quantity <span class="required-star">*</span></label>
                                <input type="number" id="quantity" class="form-control" name="quantity" placeholder="Enter Product Quantity"
                                    value="{{ old('quantity') }}" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12 hidden">
                                <label>Min. Order Level <span class="required-star">*</span></label>
                                <input type="number" hidden id="min_order_level" class="form-control" name="min_order_level"
                                    placeholder="Enter Product Min. Order Level" value="1"
                                    required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Offer Available <span class="required-star">*</span></label>
                                <select name="offer_available" id="offer_available" class="form-control">
                                    <option value="" selected disabled>Select Offer Available or Not</option>
                                    <option {{ (old("offer_available") == "0"? "selected":"") }}  value="0" selected>No</option>
                                    <option {{ (old("offer_available") == "1"? "selected":"") }}  value="1">Yes</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12 hidden">
                                <label>Size (ML) <span class="required-star">*</span></label>
                                <input type="text" id="size" disabled class="form-control" name="size"
                                    placeholder="Enter Size In ML" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12 hidden">
                                <label>Color No. <span class="required-star">*</span></label>
                                <input type="text" id="color_no" disabled class="form-control" name="color_no"
                                    placeholder="Enter Color No." required>
                            </div>

                        

                        <div style="border: solid 1px transparent;display: flex;">
                            
                            <div class="form-group col-md-6 col-sm-6 col-xs-12 hidden">
                                <label>Color</label>
                                <input type="text" id="color" disabled class="form-control" name="color[]"
                                    placeholder="Enter Color">
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12 hidden">
                                <label>Image</label>
                                <input type="file" id="colorupload"  class="form-control" name="colorImages[]"
                                    placeholder="Enter Color">
                            </div>
                        </div>

                        

                            <div class="form-group col-md-6 col-sm-6 col-xs-12 hidden">
                                <button type="button" class="btn btn-primary btn-md mt-25" id="add_color"><i class="fa 2x fa-plus"></i> Color</button>
                            </div> 

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="">Is Featured? <span class="required-star">*</span></label>
                                <div>
                                    <input name="is_featured" type="checkbox"  {{ (old("is_featured") == 1? "checked":'') }} data-toggle="toggle">
                                    <input type="hidden" name="is_featured" id="is_featured" value="{{ (old("is_featured") == 1 ? 1 : 0) }}">
                                </div>

                            </div>
                        
                        </fieldset>

                        <fieldset>
                            <legend> Uploads </legend>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Image <span class="required-star">*</span></label>
                                <input type="file" id="thumbnail" class="form-control" name="thumbnail" value="{{ old('thumbnail') }}"
                                    accept=".png, .jpg, .jpeg" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Gallery Images </label>
                                <input type="file" id="images" class="form-control" name="images[]" value="{{ old('images[]') }}"
                                    accept=".png, .jpg, .jpeg" multiple>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <img src="" id="image" hidden class="thumbnail-image-100" />
                            </div>

                        </fieldset>

                        <fieldset>
                            <legend> Additional Info </legend>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label>Description <span class="required-star">*</span></label>
                                <textarea name="description" id="description"  maxlength="500"  class="form-control"
                                    rows="3" placeholder="Enter description" required>{{ old('description') }}</textarea>
                            </div>
                        </fieldset>
                    </div>

                    <div class="col-lg-12">
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Module JS file --}}
<script src="{{asset('public/admin/products/js/view.js?v=1')}}"></script>
@stop
