<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subCategories = SubCategory::with('category')->orderBy('id','DESC')->get();
        return view('admin.subCategories.index', ['subCategories' => $subCategories, 'title'=>'Child Categories']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('is_active', '1')->where('type', '0')->get();
        return view('admin.subCategories.create',  ['categories' => $categories, 'title'=>'Child Categories']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:70',
            'category_id' => 'required',
            'image_url' => 'required|mimes:jpeg,bmp,png',
        ];
        
        $validator = Validator::make($request->all(),$rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(Input::all());
        }
        
        $slug = strtolower($request->input('title'));

        $data = [
            'category_id' => $request->input('category_id'),
            'title' => $request->input('title'),
            'slug' => preg_replace('/\s+/', '-', $slug),
            'is_active' => $request->input('status'),
        ];

        $subCategory = SubCategory::create($data);
        $subCategoryImagesDirectory = 'subCategoryImages';
        if ($request->hasFile('image_url')) {
            
            if(!Storage::exists($subCategoryImagesDirectory)){
                Storage::makeDirectory($subCategoryImagesDirectory);
            }
            
            $imageUrl = Storage::putFile($subCategoryImagesDirectory , new File($request->file('image_url')));
            $subCategory->update(['image'=> $imageUrl]);
        }
        return redirect()->route('listSubCategories')->with('success', 'Record added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function show(SubCategory $subCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subCategory = SubCategory::find($id);
        $parentCategory = Category::find($subCategory->category_id);
        $categories = Category::where('is_active', '1')->where('type', $parentCategory->type)->get();

        if($subCategory == null)
        {
            return redirect()->action('SubCategoryController@index')->with('error', 'No Record Found.');
        }
        return view('admin.subCategories.edit', ['subCategory' => $subCategory, 'categories' => $categories, 'parentCategory' => $parentCategory, 'title'=>'Child Categories']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $subCategory = SubCategory::find($request->sub_category_id);

        if($subCategory == null)
        {
            return redirect()->action('SubCategoryController@index')->with('error', 'No Record Found.');
        }
        $rules['category_id'] = 'required';
        $rule['title'] = 'required|string|max:70';
        
        $validator = Validator::make($request->all(),$rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(Input::all());
        }
      
        $slug = strtolower($request->input('title'));

        $data = [
            'title' => $request->input('title'),
            'slug' =>  preg_replace('/\s+/', '-', $slug),
            'category_id' => $request->input('category_id'),
            'is_active' => $request->input('status'),
        ];

        $subCategoryImagesDirectory = 'subCategoryImages';
        if ($request->hasFile('image_url')) {
            
            if(!Storage::exists($subCategoryImagesDirectory)){
                Storage::makeDirectory($subCategoryImagesDirectory);
            }
            if (isset($subCategory->image)) {
                Storage::delete($subCategory->image);
            }
            $imageUrl = Storage::putFile($subCategoryImagesDirectory , new File($request->file('image_url')));
            $data['image'] = $imageUrl;
        }
        $subCategory->update($data);
        return redirect()->action('SubCategoryController@index')->with('success', 'Record Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // SubCategory::where('id', $request->sub_category_id)->update(['is_active' => '0']);
        // return redirect()->route('listSubCategories')->with('success', 'Record status changed successfully.');
    }

    
    // Gets list of subCategories by Category id for dropdowns
    public function getSubCategories(Request $request)
    {
        $category = Category::find($request->category_id);
        $subCategories = SubCategory::where('category_id', $request->category_id)->where('is_active', '1')->get();
        return ['category' => $category, "subCategories" =>$subCategories];
    }

    public function getProductsBySubCatId($id)
    {
        $subCategory = SubCategory::find($id);

        if ($subCategory == null) {
           return redirect()->back()->with('error', 'No Record Found.');
        }

        $products = Product::with('category', 'subCategory', 'subChildCategory')
        ->where('sub_category_id', $subCategory->id)
        ->where('is_deleted', 0)    
        ->orderBy('created_at', 'DESC')
        ->get();
        
        return view('admin.products.productList', ['title' => 'Products', 'products' => $products]);
    }

}
