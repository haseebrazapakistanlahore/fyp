<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Category;
use App\Models\OrderDetail;
use Config;
use Validator;
use DB;

class HomeController extends Controller
{
    private $generalConstants;
    private $responseConstants;
    private $topSellingLimit = 20;
    private $featureLimit = 20;

    public function __construct()
    {
        $this->generalConstants = Config::get('constants.GENERAL_CONSTANTS');
        $this->responseConstants = Config::get('constants.RESPONSE_CONSTANTS');
    }

    public function getHomeDataConsumer(Request $request)
    {
        $response = [];

        $banners = Banner::select(['image_url'])
        ->where('is_active', '1')
        ->orderBy('id', 'desc')
        ->get();
        
        $response['banners'] = $banners;

        $categories = Category::select(['id','title', 'image'])
        ->where('is_active', '1')
        ->where('type', '0')
        ->orderBy('id', 'desc')
        ->get();

        $response['categories'] = $categories;

        $topSellingProductsIds = DB::table('order_details')
        ->join('products', 'order_details.product_id', '=', 'products.id')
        ->join('orders', 'order_details.order_id', '=', 'orders.id')
        ->join('consumers', 'orders.consumer_id', '=', 'consumers.id')
        ->select('products.id', 'products.title', 'products.price', 'products.offer_price','products.offer_available', 'products.thumbnail', 'products.product_type', DB::raw('SUM(quantity) as totalQuantity'),'products.id as favorite_product_id')
        ->groupBy('products.id')
        ->where('products.is_deleted', 0)
         ->where('products.available_quantity','>', 0)
        ->where('products.product_type', '0')
        ->take($this->topSellingLimit)
        ->orderBy(DB::raw('RAND()'))
        ->get();
        
        $topIds = $topSellingProductsIds->pluck('favorite_product_id')->toArray();
        
        $topSellingProducts = Product::whereIn('products.id', $topIds)->select('products.id', 'products.title', 'products.price', 'products.offer_price','products.offer_available', 'products.thumbnail', 'products.product_type', DB::raw('SUM(order_details.quantity) as totalQuantity'),'products.id as favorite_product_id')
        ->join('order_details', 'products.id', '=', 'order_details.product_id')
        ->join('orders', 'order_details.order_id', '=', 'orders.id')
        ->groupBy('products.id')
        ->get();

        $response['topSellingProducts'] = $topSellingProducts;
     
        $featuredProducts = Product::select('id', 'title', 'price', 'offer_price','offer_available', 'thumbnail', 'product_type')
        ->where('available_quantity', '>', 0)
        ->where('is_deleted', 0)    
        ->where('is_featured', 1)
        ->where('product_type', '0')
        ->take($this->featureLimit)
        ->orderBy(DB::raw('RAND()'))
        ->get();
        $response['topSellingProducts'] = $topSellingProducts;
        $response['featuredProducts'] = $featuredProducts;
        
        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = 'Success.';

        return response()->json($response);
    }

    public function getPages(Request $request)
    {
        $response = [];

        $pages = CmsPage::all();
        
        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['pages'] = $pages;
        $response['message'] = 'Success.';

        return response()->json($response);
    }

    public function getHomeDataProfessional(Request $request)
    {
        $response = [];
        $categories = Category::select(['id','title', 'image'])
        ->where('is_active', '1')
        ->where('type', '1')
        ->orderBy('category_order')
        ->get();

        $response['categories'] = $categories;
        
        $featuredProducts = Product::select('id', 'title', 'price', 'offer_price','offer_available', 'thumbnail', 'product_type')
        ->where('available_quantity', '>', 0)
        ->where('is_deleted', 0)    
        ->where('is_featured', 1)
        ->where('product_type', '1')
        ->take($this->featureLimit)
        ->orderBy(DB::raw('RAND()'))
        ->get();

        $response['featured'] = $featuredProducts;
      
        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['message'] = 'Success.';


        return response()->json($response);
    }
}
