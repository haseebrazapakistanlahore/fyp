<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Config;
use Validator;

class CategoryController extends Controller
{
    private $constants;
    private $responseConstants;
    private $recordsPerPage = 20;

    public function __construct()
    {
        $this->constants = Config::get('constants.CATEGORY_CONSTANTS');
        $this->responseConstants = Config::get('constants.RESPONSE_CONSTANTS');
    }

    public function getAllCategories(Request $request)
    {     
        $categories = Category::select(['id','title', 'image', 'type'])
        ->where('is_active', '1')
        ->orderBy('category_order')
        ->get();

        return response()->json([
            'status' => $this->responseConstants['STATUS_SUCCESS'],
            'message' => 'Success',
            'categories' => $categories,
        ]);
    }
}
