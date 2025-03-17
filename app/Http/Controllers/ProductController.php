<?php

namespace App\Http\Controllers;

use App;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductColor;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubChildCategory;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Redirect;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('is_deleted', 0)->with('category', 'subCategory', 'subChildCategory')->orderBy('created_at', 'DESC')->get();
        return view('admin.products.index', ['title' => 'Products', 'products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('is_active', '1')->get();
        $subCategories = SubCategory::where('is_active', '1')->get();
        $productTypes = [0 => 'Consumer', 1 => 'Professional'];
        return view('admin.products.create', ['title' => 'Products', 'categories' => $categories, 'subCategories' => $subCategories, 'productTypes' => $productTypes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request->all());
        $rules = [
            'title' => 'required|string|max:60',
            'description' => 'required|string|max:500',
            'shipping_cost' => 'required|numeric',
            'category_id' => 'required',
            'min_order_level' => 'required|numeric|gt:0',
            'quantity' => 'required|numeric|gt:0',
            'offer_available' => 'required',
            'product_type' => 'required',
            'thumbnail' => 'required|mimes:jpeg,bmp,png',
            'is_featured' => 'required',
        ];

        if ($request->product_type == 0) {
            $rules['price_consumer'] = 'required|numeric|gt:0';

        } else if ($request->product_type == 1) {
            $rules['price_professional'] = 'required|numeric|gt:0';

        } else {
            $rules['price'] = 'required|numeric|gt:0';
            $rules['price_professional'] = 'required|numeric|gt:0';
        }

        if ($request->offer_available == 1) {
            $rules['offer_price'] = 'required|numeric';

        }

        if ($request->has('size')) {
            $rules['size'] = 'required';
        }

        // if ($request->has('color')) {
        //     $rules['color'] = 'required|array';
        // }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput(request()->all());
        }
        
         if($request->product_type == 0) {
            $finalPrice = $request->price_consumer;
        }
        
        if($request->product_type == 1) {
            $finalPrice = $request->price_professional;
        }
        
        if ($request->has('color') && count($request->get('color')) > 0  && $request->get('color')[0] != null) {
            $files = $request->file('colorImages');
            
            if($files == null || count($files)  != count($request->get('color'))) {
                return redirect()->back()->withInput(request()->all())->with('error', 'Please upload images with every color.');
            }
        }

        $data = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'shipping_cost' => $request->input('shipping_cost'),
            // 'price' => $request->input('price') + $request->input('shipping_cost'),
            'price' => $finalPrice + $request->input('shipping_cost'),
            'category_id' => $request->input('category_id'),
            'sub_category_id' => $request->input('sub_category_id'),
            'sub_child_category_id' => $request->input('sub_child_category_id'),
            'offer_price' => $request->input('offer_price'),
            'min_order_level' => $request->input('min_order_level'),
            'available_quantity' => $request->input('quantity'),
            'offer_available' => $request->input('offer_available'),
            'product_type' => $request->input('product_type'),
            'is_featured' => $request->input('is_featured'),
        ];

        if ($request->has('size')) {
            $data['size'] = $request->input('size');
        }

        if ($request->has('color_no')) {
            $data['color_no'] = $request->input('color_no');
        }

        $product = Product::create($data);

        if ($request->has('color') && count($request->get('color')) > 0) {
            $files = $request->file('colorImages');
            foreach ($request->get('color') as $key => $color) {
            
                if ($color != '' && $color != null) {
                    $productImageDirectory = 'productImages';
                    if (!Storage::exists($productImageDirectory . '/' . $product->id)) {
                        Storage::makeDirectory($productImageDirectory . '/' . $product->id);
                    }
            
                    $colorImage = Storage::putFile($productImageDirectory . '/' . $product->id, new File($files[$key]));
                  
                    ProductColor::create(['product_id' => $product->id, 'name' => $color, 'image_url' => $colorImage]);
                }
            }
        }

        $productImageDirectory = 'productImages';
        if ($request->hasFile('thumbnail')) {

            if (!Storage::exists($productImageDirectory . '/' . $product->id)) {
                Storage::makeDirectory($productImageDirectory . '/' . $product->id);
            }

            $thumanilUrl = Storage::putFile($productImageDirectory . '/' . $product->id, new File($request->file('thumbnail')));
            $product->update(['thumbnail' => $thumanilUrl]);
        }

        if ($request->hasFile('images')) {

            $files = $request->file('images');
            foreach ($files as $file) {
                if (!Storage::exists($productImageDirectory . '/' . $product->id)) {
                    Storage::makeDirectory($productImageDirectory . '/' . $product->id);
                }

                $imageUrl = Storage::putFile($productImageDirectory . '/' . $product->id, new File($file));
                ProductImage::create(['product_id' => $product->id, 'image_url' => $imageUrl]);
            }
        }

        return redirect()->route('listProducts')->with('success', 'Record Added Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('productImages', 'colors')->where('id', $id)->first();
        if ($product == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }
        $reviews = $product->reviews()->get();
        return view('admin.products.detail', ['title' => 'Product Detail', 'product' => $product, 'reviews' => $reviews]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with('productImages', 'colors')->find($id);
        if($product == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }
        $categories = Category::where('is_active', '1')->where('type', $product->product_type)->get();

        if ($product->sub_category_id != null) {
            $subCategories = SubCategory::where('is_active', '1')->where('category_id', $product->category_id)->get();
        } else {
            $subCategories = null;
        }
        
        if ($product->sub_child_category_id != null) {
            $subChildCategories = SubChildCategory::where('is_active', '1')->where('sub_category_id', $product->sub_category_id)->get();
        } else {
            $subChildCategories = null;
        }
        $productTypes = [0 => 'Consumer', 1 => 'Professional'];
        return view('admin.products.edit', ['title' => 'Products', 'product' => $product, 'productTypes' => $productTypes, 'categories' => $categories, 'subCategories' => $subCategories, 'subChildCategories' => $subChildCategories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $rules = [
            'title' => 'required|string|max:60',
            'description' => 'required|string|max:500',
            'shipping_cost' => 'required|numeric',
            'category_id' => 'required',
            // 'min_order_level' => 'required|numeric|gt:0',
            'quantity' => 'required|numeric',
            'offer_available' => 'required',
            'product_type' => 'required',
        ];

        if ($request->product_type == 0) {
            $rules['price_consumer'] = 'required|numeric|gt:0';

        } else if ($request->product_type == 1) {
            $rules['price_professional'] = 'required|numeric|gt:0';

        } else {
            $rules['price'] = 'required|numeric|gt:0';
            $rules['price_professional'] = 'required|numeric|gt:0';
        }

        if ($C->offer_available == 1) {
            $rules['offer_price'] = 'required|numeric';
        }

        if ($request->has('size')) {
            $rules['size'] = 'required';
        }

        // if ($request->has('color')) {
        //     $rules['color'] = 'required|array';
        // }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput(request()->all());
        }
        
        if($request->product_type == 0) {
            $finalPrice = $request->price_consumer;
        }
        
        if($request->product_type == 1) {
            $finalPrice = $request->price_professional;
        }
        
        if ($request->has('color') && count($request->get('color')) > 0 && $request->get('color')[0] != null) {
            $files = $request->file('colorImages');
            
            if($files == null || count($files)  != count($request->get('color'))) {
                return  Redirect::back()->withInput(request()->all())->with('error', 'Please upload images with every color.');
            }
        }

        $data = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'shipping_cost' => $request->input('shipping_cost'),
            // 'price' => $request->input('price') + $request->input('shipping_cost'),
            'price' => $finalPrice + $request->input('shipping_cost'),
            'category_id' => $request->input('category_id'),
            'sub_category_id' => $request->input('sub_category_id'),
            'sub_child_category_id' => $request->input('sub_child_category_id'),
            'offer_price' => $request->input('offer_price'),
            // 'min_order_level' => $request->input('min_order_level'),
            'available_quantity' => $request->input('quantity'),
            'offer_available' => $request->input('offer_available'),
            'product_type' => $request->input('product_type'),
            'is_featured' => $request->input('is_featured'),
        ];

        if ($request->has('size')) {
            $data['size'] = $request->input('size');
        } else {
            $data['size'] = NULL;
        }

        if ($request->has('color_no')) {
            $data['color_no'] = $request->input('color_no');

        } else {
            $data['color_no'] = NULL;
        }

        $product = Product::find($request->product_id);
        $product->update($data);

        if ($request->has('deleted_colors') && count($request->get('deleted_colors')) > 0 && $request->get('deleted_colors')[0] != null) {
            $deletedColors = $request->get('deleted_colors')[0];
            $deletedColors = str_replace('[', '', $deletedColors);
            $deletedColors = str_replace(']', '', $deletedColors);
            $deletedColors = explode(',',$deletedColors);
            foreach ($deletedColors as $colorId) {
                ProductColor::find($colorId)->delete();
            }
        }

        if (!$product->categoryHasColors($request->category_id)) {
            ProductColor::where('product_id', $product->id)->delete();
        }

        if ($request->has('color') && count($request->get('color')) > 0) {
            $files = $request->file('colorImages');
            // dd($files);
            foreach ($request->get('color') as $key => $color) {
                if ($color != '' && $color != null) {
                    $productImageDirectory = 'productImages';
                    if (!Storage::exists($productImageDirectory . '/' . $product->id)) {
                        Storage::makeDirectory($productImageDirectory . '/' . $product->id);
                    }
            
                    $colorImage = Storage::putFile($productImageDirectory . '/' . $product->id, new File($files[$key]));
                  
                    ProductColor::create(['product_id' => $product->id, 'name' => $color, 'image_url' => $colorImage]);
                }
            }
        }

        $productImageDirectory = 'productImages';
        if ($request->hasFile('thumbnail')) {

            if (!Storage::exists($productImageDirectory . '/' . $product->id)) {
                Storage::makeDirectory($productImageDirectory . '/' . $product->id);
            }

            if (isset($product->thumbnail)) {
                Storage::delete('/' . $product->thumbnail);
            }

            $thumanilUrl = Storage::putFile($productImageDirectory . '/' . $product->id, new File($request->file('thumbnail')));
            $product->update(['thumbnail' => $thumanilUrl]);
        }

        if ($request->hasFile('images')) {
            $files = $request->file('images');

            foreach ($files as $file) {
                if (!Storage::exists($productImageDirectory . '/' . $product->id)) {
                    Storage::makeDirectory($productImageDirectory . '/' . $product->id);
                }

                $imageUrl = Storage::putFile($productImageDirectory . '/' . $product->id, new File($file));
                ProductImage::create(['product_id' => $product->id, 'image_url' => $imageUrl]);
            }
        }

        return redirect($request->old_url)->with('success', 'Record Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Product::where('id', $request->product_id)->update(['is_deleted' => 1]);
        return response()->json(['status' => 1, 'message' => 'Record Deleted Successfully.']);
    }

}
