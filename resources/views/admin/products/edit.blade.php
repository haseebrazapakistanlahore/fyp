@extends('layouts.admin')

@section('content')
<style>
    .product-gallery span{
    position: relative;
}
.delete-icon{
    position: absolute;
    left: 85%;
    color: #ff0000;
    font-size: 15px;
}
</style>
<div class="col-lg-12">
    <h1>{{$title}}</h1>
</div>
<div class="row pt-10">
    <div class="col-lg-12">
        <a href="{{ url()->previous() }}" class="btn btn-primary pull-right">Back</a>
        {{-- <a href="{{ route('listProducts') }}" class="btn btn-primary pull-right">Back</a> --}}
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
                    <form role="form" id="updateProductForm" action="{{route('updateProduct')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-lg-12">

                            <fieldset>
                                <legend>Product Info</legend>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Title <span class="required-star">*</span></label>
                                    <input type="text" id="title" maxlength="60" class="form-control" name="title"
                                        placeholder="Enter Product Title" value="{{ $product->title }}" required>
                                </div>
                                
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Type <span class="required-star">*</span></label>
                                    <select name="product_type" id="product_type" class="form-control" required>
                                        {{-- type 0 for comsumer products -- type 1 for professional products --}}
                                        <option value="" selected disabled>Select Product Type</option>
                                        @foreach ($productTypes as $key =>$value)
                                        <option  {{($key == $product->product_type)? 'Selected': ''}} value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
    
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Category <span class="required-star">*</span></label>
                                    <select name="category_id" id="edit_category_id" class="form-control" required>
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach ($categories as $category)
                                        <option @if($product->category_id == $category->id) selected @endif
                                            value="{{$category->id}}">{{ $category->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>SubCategory </label>
                                    @if($subCategories == null)
                                    <select name="sub_category_id" disabled id="edit_sub_category_id" class="form-control">
                                        <option value="" selected disabled>Select Sub Category</option>
                                    </select>
                                    @else
                                    <select name="sub_category_id" id="edit_sub_category_id" class="form-control">
                                        <option value="" selected disabled>Select Sub Category</option>
                                        @foreach ($subCategories as $subCategory)
                                        <option @if(isset($product->sub_category_id) && ($product->sub_category_id ==
                                            $subCategory->id)) selected @endif value="{{$subCategory->id}}">{{
                                            $subCategory->title }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>
                              
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Sub Child Category </label>

                                    @if($subChildCategories == null)
                                    <select name="sub_child_category_id" id="edit_sub_child_category_id" disabled class="form-control">
                                        <option value="" selected disabled>Select Sub Child Category</option>
                                    </select>
                                    @else
                                    <select name="sub_child_category_id" id="edit_sub_child_category_id" class="form-control">
                                        <option value="" selected disabled>Select Sub Child Category</option>
                                        @foreach ($subChildCategories as $subChildCategory)
                                        <option @if(isset($product->sub_child_category_id) && ($product->sub_child_category_id ==
                                            $subChildCategory->id)) selected @endif value="{{$subChildCategory->id}}">{{
                                            $subChildCategory->title }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                   
                                </div>

                                {{-- <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Type <span class="required-star">*</span></label>
                                    <select name="product_type" id="product_type" class="form-control">
                                        <option value="" selected disabled>Select Product Type</option>

                                        <option @if($product->product_type == 0) selected @endif value="0">Consumers</option>
                                        <option @if($product->product_type == 1) selected @endif
                                            value="1">Professionals</option>
                                        <option @if($product->product_type == 2) selected @endif value="2">Both</option>
                                    </select>
                                </div> --}}
                               
                                @if ($product->product_type == 0)

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Consumer Price <span class="required-star">*</span></label>
                                    <input type="number" id="price_consumer" class="form-control" name="price_consumer" placeholder="Unit Price For Consumers"
                                        value="{{ $product->price }}" required>
                                </div>
                                <input type="hidden" id="old_price_for_consumer" name="old_price_for_consumer" value="{{ $product->price }}">
                                @endif
                                @if ($product->product_type == 1)

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Price For Professionals <span class="required-star">*</span></label>
                                    <input type="number" id="price_professional" class="form-control" name="price_professional"
                                        placeholder="Unit Price For Professionals" value="{{ $product->price }}"
                                        required>
                                </div>
                                <input type="hidden" id="old_price_for_professiona" name="old_price_for_professiona"
                                    value="{{ $product->price }}">
                                @endif

                                @if ($product->product_type == 2)

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Consumer Price <span class="required-star">*</span></label>
                                    <input type="number" id="price_consumer" class="form-control" name="price_consumer" placeholder="Unit Price For Consumers"
                                        value="{{ $product->price }}" required>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Price For Professionals <span class="required-star">*</span></label>
                                    <input type="number" id="price_professional" class="form-control" name="price_professional"
                                        placeholder="Unit Price For Professionals" value="{{ $product->price_for_professional }}"
                                        required>
                                </div>
                                <input type="hidden" id="old_price_for_consumer" name="old_price_for_consumer" value="{{ $product->price }}">
                                <input type="hidden" id="old_price_for_professiona" name="old_price_for_professiona"
                                    value="{{ $product->price_for_professional }}">
                                @endif



                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Shipping Cost <span class="required-star">*</span></label>
                                    <input type="number" id="shipping_cost" class="form-control" name="shipping_cost"
                                        placeholder="Enter Shipping Cost" value="0" value="{{ $product->shipping_cost }}"
                                        required>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Quantity <span class="required-star">*</span></label>
                                    <input type="number" id="quantity" class="form-control" name="quantity" value="{{ $product->available_quantity }}"
                                        placeholder="Enter Product Quantity" required>
                                </div>
                                {{--
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Min. Order Level <span class="required-star">*</span></label>
                                    <input type="number" id="min_order_level" class="form-control" name="min_order_level"
                                        placeholder="Enter Product Min. Order Level" value="{{ $product->min_order_level }}"
                                        required>
                                </div> --}}

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Offer Available <span class="required-star">*</span></label>
                                    <select name="offer_available" id="offer_available" class="form-control">
                                        <option value="" selected disabled>Select Offer Available or Not</option>
                                        <option @if($product->offer_available == '1') selected @endif value="1">Yes</option>
                                        <option @if($product->offer_available == '0') selected @endif value="0">No</option>
                                    </select>
                                </div>

                                @if ($product->offer_available == 1)

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Offer Price <span class="required-star">*</span></label>
                                    <input type="number" id="offer_price" class="form-control" name="offer_price"
                                        placeholder="Enter discounted price" value="{{ $product->offer_price }}"
                                        required>
                                </div>
                                @endif

                                @if (isset($product->size))
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Size (ML) <span class="required-star">*</span></label>
                                    <input type="text" id="size" class="form-control" name="size" placeholder="Enter Size In ML"
                                        value="{{ $product->size}}" required>
                                </div>
                                @else
                                <div class="form-group col-md-6 col-sm-6 col-xs-12 hidden">
                                    <label>Size (ML) <span class="required-star">*</span></label>
                                    <input type="text" id="size" disabled class="form-control" name="size" placeholder="Enter Size In ML"
                                        required>
                                </div>
                                @endif

                                @if (isset($product->color_no))
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Color No <span class="required-star">*</span></label>
                                    <input type="text" id="color_no" class="form-control" name="color_no" placeholder="Enter Color No"
                                        value="{{ $product->color_no}}" required>
                                </div>
                                @else
                                <div class="form-group col-md-6 col-sm-6 col-xs-12 hidden">
                                    <label>Color No. <span class="required-star">*</span></label>
                                    <input type="text" id="color_no" disabled class="form-control" name="color_no"
                                        placeholder="Enter Color No." required>
                                </div>
                                @endif

                                @if (count($product->colors) > 0)
                                <input type="hidden" name="deleted_colors[]" id="deleted_colors">

                                @foreach ($product->colors as $color)
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Color</label>
                                    <div class="d-flex">
                                        <input type="text" class="form-control mr-2" name="old_colors[]" placeholder="Enter Color"
                                            data-colorid="{{ $color->id }}" value="{{ $color->name}}">
                                        <input type="file" id="colorupload"  class="form-control mr-2" name="oldColorImages[]"
                                        placeholder="Enter Color">
                                        <button type="button" onclick="deleteOldColor(this);" class="btn btn-danger"><i
                                                class="fa 2x fa-minus"></i></button>
                                    </div>
                                    <div class="resize-image">
                                    <img src="{{ asset('storage/app/public/'.$color->image_url)}}" alt="">
                                    </div>
                                </div>
                                @endforeach
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <button type="button" class="btn btn-primary btn-md mt-25" id="edit_add_color"><i
                                            class="fa 2x fa-plus"></i> Color</button>
                                </div>
                                @else

                                  @if ($product->categoryHasColors($product->category_id))
                                    
                                        <div class="form-group col-md-3 col-sm-3 col-xs-6">
                                            <label>Color</label>
                                            <input type="text" id="color" class="form-control" name="color[]"
                                                placeholder="Enter Color">
                                        </div>
                                        <div class="form-group col-md-3 col-sm-3 col-xs-6">
                                            <label>Image</label>
                                            <input type="file" id="colorupload"  class="form-control" name="colorImages[]"
                                    placeholder="Enter Color">
                                        </div>
                                    
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <button type="button" class="btn btn-primary btn-md mt-25" id="edit_add_color"><i
                                                    class="fa 2x fa-plus"></i> Color</button>
                                        </div>
                                    @else
                                    
                                        <div class="form-group col-md-3 col-sm-3 col-xs-6 hidden">
                                            <label>Color</label>
                                            <input type="text" id="color" class="form-control" name="color[]"
                                                placeholder="Enter Color">
                                        </div>
                                        <div class="form-group col-md-3 col-sm-3 col-xs-6 hidden">
                                            <label>Image</label>
                                            <input type="file" id="colorupload"  class="form-control" name="colorImages[]"
                                    placeholder="Enter Color">
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12 hidden">
                                            <button type="button" class="btn btn-primary btn-md mt-25" id="edit_add_color"><i
                                                    class="fa 2x fa-plus"></i> Color</button>
                                        </div>
                                    @endif
                                @endif
                               

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Is Featured? <span class="required-star">*</span></label>
                                    @if ($product->is_featured == 0)
                                    <input name="is_featured" type="checkbox" class="" data-toggle="toggle">
                                    <input type="hidden" name="is_featured" id="is_featured" value="{{$product->is_featured}}">
                                    @else
                                    <input name="is_featured" type="checkbox" class="" checked data-toggle="toggle">
                                    <input type="hidden" name="is_featured" id="is_featured" value="{{$product->is_featured}}">
                                    @endif

                                </div>
                            </fieldset>

                            <fieldset>
                                <legend> Uploads </legend>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Image </label>
                                    <input type="file" id="thumbnail" class="form-control" name="thumbnail" accept=".png, .jpg, .jpeg">
                                    <img id="image" src="{{ asset('storage/app/public/'.$product->thumbnail) }}" class="thumbnail-image-100">
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Gallery Images </label>
                                    <input type="file" id="images" class="form-control" name="images[]" accept=".png, .jpg, .jpeg"
                                        multiple>
                                    @if (count($product->productImages) > 0)
                                    <div class="product-gallery">

                                        @foreach ($product->productImages as $image)
                                        <span class="prdouct-image {{$image->id}}">
                                            <img src="{{ asset('storage/app/public/'.$image->image_url) }}" class="thumbnail-image-100">
                                            <a href="javascript:void(0);" onclick="deleteProductImage({{$image->id}});">
                                                <i class="fa fa-times delete-icon" aria-hidden="true"></i>
                                            </a>
                                        </span>
                                        @endforeach
                                        <input type="hidden" name="previousImageCount" id="previousImageCount" value="{{ count($product->productImages)}}">
                                    </div>
                                    @endif
                                </div>

                            </fieldset>

                            <fieldset>
                                <legend> Additional Info </legend>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Description <span class="required-star">*</span></label>
                                    <textarea name="description" id="description" maxlength="500" class="form-control"
                                        rows="3" placeholder="Enter description" required>{{ $product->description }}</textarea>
                                </div>
                            </fieldset>

                            <div class="col-lg-12">
                                <div class="col-lg-6">
                                    <input type="hidden" name="old_url" value="{{ url()->previous() }}">
                                    <input type="hidden" name="product_id" value="{{$product->id}}">
                                    <button id="updatePropertySubmit" type="submit" class="btn btn-primary">Update</button>
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

<script>
    $(function () {

    });

</script>
{{-- Module JS file --}}
<script src="{{asset('public/admin/products/js/edit.js?v=1')}}"></script>
@stop
