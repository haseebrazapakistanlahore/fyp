<?php

namespace App\Http\Controllers;
use App;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Models\ProductImage;
use Validator;
Use Redirect;
use Illuminate\Support\Facades\Input;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.test');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $products = App\Product::all();

        foreach ($products as $product) {

            $productImageDirectory = 'productImages';
            if ($request->hasFile('thumbnail')) {
                
                $fileName = $request->file('thumbnail')->getClientOriginalName();

                if(!Storage::exists($productImageDirectory.'/'.$product->id)){
                    Storage::makeDirectory($productImageDirectory.'/'.$product->id);
                }
                
                Storage::putFileAs($productImageDirectory.'/'.$product->id , new File($request->file('thumbnail')), $fileName);
                $thumanilUrl = $productImageDirectory.'/'.$product->id.'/'.$fileName;

                $product->update(['thumbnail'=> $thumanilUrl]);
            }
        
            
            if ($request->hasFile('images')) {

                $files=$request->file('images');
                
                foreach($files as $file){
                    $fileName =  $file->getClientOriginalName();
                    if(!Storage::exists($productImageDirectory.'/'.$product->id)){
                        Storage::makeDirectory($productImageDirectory.'/'.$product->id);
                    }
                    Storage::putFileAs($productImageDirectory.'/'.$product->id , new File($file), $fileName);
                    $imageUrl = $productImageDirectory.'/'.$product->id.'/'.$fileName; 
                    ProductImage::create(['product_id'=> $product->id, 'image_url' => $imageUrl]);
                }

            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function show(ProductImage $productImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductImage $productImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductImage $productImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $productImage = ProductImage::find($request->imageId);
        Storage::delete('/'.$productImage->image_url);
        ProductImage::where('id', $request->imageId)->delete();
        return ["status" => 1, "message" => "Image deleted successfully."];
    }
}
