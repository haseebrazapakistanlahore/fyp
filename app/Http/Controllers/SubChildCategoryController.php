<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\SubChildCategory;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use App\Models\Product;

class SubChildCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subChildCategories = SubChildCategory::with('subCategory', 'category')->orderBy('id','DESC')->get();
        return view('admin.subChildCategories.index', ['subChildCategories' => $subChildCategories, 'title'=>'Sub Child Categories']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $categories = Category::where('is_active', '1')->get();
        $categories = Category::where('is_active', '1')->where('type', '0')->get();
        return view('admin.subChildCategories.create',  ['categories' => $categories, 'title'=>'Sub Child Categories']);
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
            'sub_category_id' => 'required',
            'status' => 'required',
        ];
        
        $validator = Validator::make($request->all(),$rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(Input::all());
        }
        

        $data = [
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'sub_category_id' => $request->input('sub_category_id'),
            'is_active' => $request->input('status'),
        ];

        $subChildCategory = SubChildCategory::create($data);

        $subChildCategoryDirectory = 'subChildCategoryImages';
        if ($request->hasFile('image_url')) {
            
            if(!Storage::exists($subChildCategoryDirectory)){
                Storage::makeDirectory($subChildCategoryDirectory);
            }
            
            $imageUrl = Storage::putFile($subChildCategoryDirectory , new File($request->file('image_url')));
            $subChildCategory->update(['image'=> $imageUrl]);
        }
        return redirect()->route('listSubChildCategories')->with('success', 'Record added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SubChildCategory  $subChildCategory
     * @return \Illuminate\Http\Response
     */
    public function show(SubChildCategory $subChildCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SubChildCategory  $subChildCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subChildCategory = SubChildCategory::find($id);
        if($subChildCategory == null)
        {
            return redirect()->action('SubChildCategoryController@index')->with('error', 'No Record Found.');
        }

        $parentCategory = Category::find($subChildCategory->category_id);
        $categories = Category::where('is_active', '1')->where('type', $parentCategory->type)->get();
        $subCategories = SubCategory::where('category_id', $parentCategory->id)->get();
        // dd($subCategories);
        return view('admin.subChildCategories.edit', ['subChildCategory' => $subChildCategory, 'categories' => $categories, 'subCategories' => $subCategories, 'parentCategory' => $parentCategory, 'title'=>'Sub Child Categories']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SubChildCategory  $subChildCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $subChildCategory = SubChildCategory::find($request->sub_child_category_id);

        if($subChildCategory == null)
        {
            return redirect()->action('SubChildCategoryController@index')->with('error', 'No Record Found.');
        }

        $rules['category_id'] = 'required';
        $rules['sub_category_id'] = 'required';
        $rule['title'] = 'required|string|max:70';
        $rule['status'] = 'required|string|max:70';
        
        $validator = Validator::make($request->all(),$rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(Input::all());
        }
      
        $data = [
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'sub_category_id' => $request->input('sub_category_id'),
            'is_active' => $request->input('status'),
        ];

        $subChildCategoryDirectory = 'subChildCategoryImages';
        if ($request->hasFile('image_url')) {
            
            if(!Storage::exists($subChildCategoryDirectory)){
                Storage::makeDirectory($subChildCategoryDirectory);
            }
            if (isset($subChildCategory->image)) {
                Storage::delete($subChildCategory->image);
            }
            $imageUrl = Storage::putFile($subChildCategoryDirectory , new File($request->file('image_url')));
            $data['image'] = $imageUrl;
        }
        $subChildCategory->update($data);
        return redirect()->action('SubChildCategoryController@index')->with('success', 'Record Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SubChildCategory  $subChildCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubChildCategory $subChildCategory)
    {
        //
    }

        
    // Gets list of subChildCategories by Category id for dropdowns
    public function getSubChildCategories(Request $request)
    {
        $subChildCategories = SubChildCategory::where('sub_category_id', $request->sub_category_id)->where('is_active', '1')->get();
        return ["subChildCategories" =>$subChildCategories];
    }

    public function getProductsBySubChildCatId($id)
    {
        $subChildCategory = SubChildCategory::find($id);

        if ($subChildCategory == null) {
           return redirect()->back()->with('error', 'No Record Found.');
        }

        $products = Product::with('category', 'subCategory', 'subChildCategory')
        ->where('sub_child_category_id', $subChildCategory->id)
        ->where('is_deleted', 0)    
        ->orderBy('created_at', 'DESC')
        ->get();
        
        return view('admin.products.productList', ['title' => 'Products', 'products' => $products]);
    }
}
