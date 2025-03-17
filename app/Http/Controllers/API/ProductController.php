<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubChildCategory;
use App\Models\Review;
use Validator;
use DB;

class ProductController extends Controller
{
    private $constants;
    private $generalConstants;
    private $responseConstants;
    private $recordsPerPage = 20;

    public function __construct()
    {
        $this->constants = Config::get('constants.PRODUCT_CONSTANTS');
        $this->generalConstants = Config::get('constants.GENERAL_CONSTANTS');
        $this->responseConstants = Config::get('constants.RESPONSE_CONSTANTS');
    }

    public function getAllConsumer(Request $request)
    {
        // $offset = 0;
        // if($request->filled($this->generalConstants['KEY_PAGE_NO'])){
        //     $offset = $this->recordsPerPage * $request->get($this->generalConstants['KEY_PAGE_NO']);
        // }

        $products = Product::select('id', 'title', 'price', 'offer_price', 'offer_available', 'thumbnail', 'product_type')
        ->where('available_quantity', '>', 0)
        ->where('product_type', '0')
        ->where('is_deleted', 0)
        ->orderBy(DB::raw('RAND()'))
        ->get()
        ->map(function ($product) {
            $product->thumbnail = asset('storage/app/public/' . $product->thumbnail);
            return $product;
        });



        // $totalRecords = Product::where('available_quantity', '>', 0)
        // ->where('product_type', '0')
        // ->where('is_deleted', 0)
        // ->count();

        // $totalPages = ceil($totalRecords / $this->recordsPerPage);

        return response()->json([
            'status' => $this->responseConstants['STATUS_SUCCESS'],
            'message' => 'Success',
            // 'totalPages' => $totalPages,
            'products' => $products,
        ]);
    }
    
    public function getAllProfessional(Request $request)
    {
        // $offset = 0;
        // if($request->filled($this->generalConstants['KEY_PAGE_NO'])){
        //     $offset = $this->recordsPerPage * $request->get($this->generalConstants['KEY_PAGE_NO']);
        // }

        $products = Product::select('id', 'title', 'price', 'offer_price','offer_available', 'thumbnail', 'product_type')
        ->where('available_quantity', '>', 0)
        ->where('product_type', '1')
        ->where('is_deleted', 0)
        ->orderBy(DB::raw('RAND()'))
        // ->skip($offset)
        // ->take($this->recordsPerPage)
        ->get()
        ->map(function ($product) {
            $product->thumbnail = asset('storage/app/public/' . $product->thumbnail);
            return $product;
        });

        // $totalRecords = Product::where('available_quantity', '>', 0)
        // ->where('product_type', '1')
        // ->where('is_deleted', 0)
        // ->count();

        // $totalPages = ceil($totalRecords / $this->recordsPerPage);

        return response()->json([
            'status' => $this->responseConstants['STATUS_SUCCESS'],
            'message' => 'Success',
            // 'totalPages' => $totalPages,
            'products' => $products,
        ]);
    }

    public function getProductsByCategoryId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $this->constants['KEY_CATEGORY_ID'] => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' =>  $this->responseConstants['STATUS_ERROR'], 
                'message' => $this->responseConstants['INVALID_PARAMETERS']
            ]);
        }
        $category = Category::select(['id','title', 'image'])
        ->where('id', $request->get($this->constants['KEY_CATEGORY_ID']))
        ->with(['subCategories' => function($query) {
            $query->select(['id', 'title', 'category_id', 'image'])->where('is_active', '1');
        }])
        ->first();

        if ($category != null) {
            $category->subCategories->makeHidden(['category_id']);
        }

        if (count($category->subCategories) > 0) {
            
            $products = Product::select('id', 'title', 'price', 'offer_price','offer_available', 'thumbnail', 'product_type')
            ->where('category_id', $request->get($this->constants['KEY_CATEGORY_ID']))
            ->where('is_featured', 1)
            ->where('available_quantity', '>', 0)
            ->where('is_deleted', 0)    
            ->orderBy('id', 'desc')
            ->get()->map(function ($product) {
                $product->thumbnail = asset('storage/app/public/' . $product->thumbnail);
                return $product;
            });
            
        } else {

            $products = Product::select('id', 'title', 'price', 'offer_price','offer_available', 'thumbnail', 'product_type')
            ->where('category_id', $request->get($this->constants['KEY_CATEGORY_ID']))
            ->where('sub_category_id', NULL)
            ->where('sub_child_category_id', NULL)
            ->where('available_quantity', '>', 0)
            ->where('is_deleted', 0)    
            ->orderBy('id', 'desc')
            ->get()->map(function ($product) {
                $product->thumbnail = asset('storage/app/public/' . $product->thumbnail);
                return $product;
            });

        }
        
        return response()->json([
            'status' => $this->responseConstants['STATUS_SUCCESS'],
            'message' => 'Success',
            'products' => $products,
            'category' => $category,
        ]);
    }

    public function getProductsBySubCategoryId(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            $this->constants['KEY_SUB_CATEGORY_ID'] => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' =>  $this->responseConstants['STATUS_ERROR'], 
                'message' => $this->responseConstants['INVALID_PARAMETERS']
            ]);
        }

        $subCateogory = SubCategory::select(['id','title', 'image'])
        ->with(['subChildCategories' => function($query) {
            $query->select(['id', 'title', 'category_id','sub_category_id', 'image'])->where('is_active', '1');
        }])
        ->where('id', $request->get($this->constants['KEY_SUB_CATEGORY_ID']))
        ->first();

        
        if ($subCateogory != null) {
            $subCateogory->subChildCategories->makeHidden(['sub_category_id']);
        }

        if (count($subCateogory->subChildCategories) > 0) {

            $products = Product::select('id', 'title', 'price', 'offer_price','offer_available', 'thumbnail', 'product_type')
            ->where('sub_category_id', $request->get($this->constants['KEY_SUB_CATEGORY_ID']))
            ->where('available_quantity', '>', 0)
            ->where('is_featured', 1)
            ->where('is_deleted', 0)    
            ->orderBy('id', 'desc')
            ->get();
            
        } else {
            $products = Product::select('id', 'title', 'price', 'offer_price','offer_available', 'thumbnail', 'product_type')
            ->where('sub_category_id', $request->get($this->constants['KEY_SUB_CATEGORY_ID']))
            ->where('available_quantity', '>', 0)
            ->where('sub_child_category_id', NULL)
            ->where('is_deleted', 0)    
            ->orderBy('id', 'desc')
            ->get();
        }

        return response()->json([
            'status' => $this->responseConstants['STATUS_SUCCESS'],
            'message' => 'Success',
            'products' => $products,
            'sub_category' => $subCateogory,
        ]);
    }

    public function getProductsBySubChildCategoryId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $this->constants['KEY_SUB_CHILD_CATEGORY_ID'] => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' =>  $this->responseConstants['STATUS_ERROR'], 
                'message' => $this->responseConstants['INVALID_PARAMETERS']
            ]);
        }

        $products = Product::select('id', 'title', 'price', 'offer_price','offer_available', 'available_quantity', 'thumbnail', 'product_type')
            ->where('sub_child_category_id', $request->get($this->constants['KEY_SUB_CHILD_CATEGORY_ID']))
            ->where('available_quantity', '>', 0)
            ->where('is_deleted', 0)    
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => $this->responseConstants['STATUS_SUCCESS'],
            'message' => 'Success',
            'products' => $products,
        ]);
    }

    public function getProductDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $this->generalConstants['KEY_PRODUCT_ID'] => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' =>  $this->responseConstants['STATUS_ERROR'], 
                'message' => $this->responseConstants['INVALID_PARAMETERS']
            ]);
        }

        $product = Product::select('id','title','description', 'category_id', 'sub_category_id', 'sub_child_category_id', 'product_type', 'available_quantity', 'min_order_level', 'price', 'offer_available', 'offer_price', 'size', 'color_no', 'thumbnail')->with('colors', 'reviews')
        ->with(['productimages' => function($query) {
            $query->select(['product_id', 'image_url']);
        }])
        ->where('id', $request->get($this->generalConstants['KEY_PRODUCT_ID']))
        ->where('is_deleted', 0)
        ->first();
        if ($product) {
            $product->thumbnail = asset('storage/app/public/' . $product->thumbnail);
        
            $product->productimages->transform(function ($image) {
                $image->image_url = asset('storage/app/public/' . $image->image_url);
                return $image;
            });
            
        }
        if ($product == null) {
            return response()->json([
                'status' =>  $this->responseConstants['STATUS_ERROR'],
                'message' => 'Invalid Product Id.'
            ]);
        }
        if ($product->available_quantity <= 0) {
            return response()->json([
                'status' =>  $this->responseConstants['STATUS_INVALID_AVAILABLE_QUANTITY'],
                'message' => 'Invalid Product Quantity.'
            ]);
        }

        if ($product->category_id != null)
        $product->setAttribute('category_name', Category::find($product->category_id)->title);
        if ($product->sub_category_id != null)
        $product->setAttribute('sub_category_name', SubCategory::find($product->sub_category_id)->title);
        if ($product->sub_child_category_id != null)
        $product->setAttribute('sub_child_category_name', SubChildCategory::find($product->sub_child_category_id)->title);
        

        if($product->productImages != null){
            $product->productImages->makeHidden(['created_at', 'updated_at', 'product_id']);
        }
        if($product->colors != null){
            $product->colors->makeHidden(['created_at', 'updated_at', 'product_id']);
        }
        
        if($product->reviews != null){
            $product->reviews->makeHidden(['user_id', 'product_id']);
        }

        $rating = Review::select(DB::raw('count(id) as total, sum(rating) as sum'))->where('product_id', $product->id)->first();
        if ($rating->total > 0) {
            $rating =  ceil($rating->sum/$rating->total);
        }else{
            $rating = 0;
        }
        
        $noOfReviews = Review::where('product_id', $product->id)->count();
        
        return response()->json([
            'status' => $this->responseConstants['STATUS_SUCCESS'],
            'message' => 'Success',
            'product' => $product,
            'rating' => $rating,
            'noOfReviews' => $noOfReviews,
        ]);
    }
}
