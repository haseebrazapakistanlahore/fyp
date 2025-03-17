<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use App\Models\Product;

class CategoryController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('id','DESC')->get();
        return view('admin.categories.index',[ 'categories'=>$categories, 'title'=>'Categories'] );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create', ['title'=>'Categories']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:70',
            'has_sizes' => 'required',
            'has_colors' => 'required',
            'has_color_no' => 'required',
            'category_for' => 'required',
        ]);
    
        $slug = strtolower($request->input('title'));

        $data = [
            'title' => $request->input('title'),
            'slug' =>  preg_replace('/\s+/', '-', $slug),
            'is_active' => $request->input('status'),
            'has_sizes' => $request->input('has_sizes'),
            'has_colors' => $request->input('has_colors'),
            'has_color_no' => $request->input('has_color_no'),
            'type' => $request->input('category_for'),
        ];

        $category = Category::create($data);
        $categoryImagesDirectory = 'categoryImages';
        if ($request->hasFile('image_url')) {
            
            if(!Storage::exists($categoryImagesDirectory)){
                Storage::makeDirectory($categoryImagesDirectory);
            }
            
            $imageUrl = Storage::putFile($categoryImagesDirectory , new File($request->file('image_url')));
            $category->update(['image'=> $imageUrl]);
        }
        return redirect()->route('listCategories')->with('success', 'Record added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        if($category == null)
        {
            return redirect()->route('listCategories')->with('error', 'No Record Found.');
        }
        return view('admin.categories.edit', ['category' => $category, 'title'=>'Categories']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $category = Category::find($request->category_id);
        if ($category == null) {
            return redirect()->back()->with('error', 'No Record Found To Update.');
        }
        $rules = [
            'category_id' => 'required',
            'has_sizes' => 'required',
            'has_colors' => 'required',
            'has_color_no' => 'required',
            'category_for' => 'required',
            'title' => 'required|string|max:70',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(Input::all());
        }
        
        $slug = strtolower($request->input('title'));

        $data = [
            'title' => $request->input('title'),
            'slug' =>  preg_replace('/\s+/', '-', $slug),
            'is_active' => $request->input('status'),
            'has_sizes' => $request->input('has_sizes'),
            'has_colors' => $request->input('has_colors'),
            'has_color_no' => $request->input('has_color_no'),
            'type' => $request->input('category_for'),
        ];

        $categoryImagesDirectory = 'categoryImages';
        if ($request->hasFile('image_url')) {
            
            if(!Storage::exists($categoryImagesDirectory)){
                Storage::makeDirectory($categoryImagesDirectory);
            }
            Storage::delete('/'.$category->image_url);
            $imageUrl = Storage::putFile($categoryImagesDirectory , new File($request->file('image_url')));
            $data['image'] = $imageUrl;
        }

        $category->update($data);
        return redirect()->route('listCategories')->with('success', 'Record updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // $category = Category::where('id', $request->category_id)->update(['is_active' => '0']);
        // return redirect()->route('listCategories')->with('success', 'Record status changed successfully.');
    }

    // Gets list of subCategories by Category id for dropdowns
    public function getCategories(Request $request)
    {
        $categories = Category::where('is_active', '1')->where('type', $request->category_type)->get();
        return ['categories' => $categories];
    }

    public function getProductsByCategoryId($id)
    {
        $category = Category::find($id);

        if ($category == null) {
           return redirect()->back()->with('error', 'No Record Found.');
        }

        $products = Product::with('category', 'subCategory', 'subChildCategory')
        ->where('category_id', $category->id)
        ->where('is_deleted', 0)    
        ->orderBy('created_at', 'DESC')
        ->get();
        
        return view('admin.products.productList', ['title' => 'Products', 'products' => $products]);
    }
}
