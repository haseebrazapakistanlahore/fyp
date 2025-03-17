<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
use App\Product;
use App\Category;
use App\SubCategory;
use App\SubChildCategory;
use Validator;

class ProductControllerNew extends Controller
{
    private $productConstants;
    private $responseKeys;
    private $recordsPerPage = 30;

    public function __construct()
    {
        $this->productConstants = Config::get('productConstants.CONSTANTS');
        $this->responseKeys = Config::get('responseConstants.RESPONSE_KEYS');
    }

    public function getAll(Request $request)
    {
        $is_last = 0;
        $offset = 0;
        if($request->filled($this->productConstants['KEY_PAGE_NUMBER'])){
            $offset = $this->recordsPerPage * $request->get($this->productConstants['KEY_PAGE_NUMBER']);
        }

        $products = Product::with('colors', 'reviews')
            ->where('available_quantity', '>', 0)
            ->where('product_type', '1')
            ->where('is_deleted', 0)    
            ->orderBy('id', 'desc')
            ->skip($offset)
            ->take($this->recordsPerPage)
            ->get();
        
        $products->makeHidden(['created_at', 'updated_at', 'is_deleted']);

        $totalRecords = Product::where('product_type', '1')->where('is_deleted', 0)->where('available_quantity', '>', 0)->count();
        $totalPages = ceil($totalRecords / $this->recordsPerPage);
        
        if($request->filled($this->productConstants['KEY_PAGE_NUMBER'])){
            if($totalPages - 1 == $request->get($this->productConstants['KEY_PAGE_NUMBER'])){
                $is_last = 1;
            }
        }
        return response()->json([
            'status' => $this->responseKeys['STATUS_SUCCESS'],
            'message' => 'Success',
            'totalPages' => $totalPages,
            'is_last' => $is_last,
            'products' => $products,
        ]);
    }


}
