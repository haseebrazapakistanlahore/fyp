<?php

namespace App\Http\Controllers\API;

use Config;
use Validator;
use App\Banner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    private $responseKeys;

    public function __construct()
    {
        $this->responseKeys = Config::get('responseConstants.RESPONSE_KEYS');
    }

    public function getAllBanners(Request $request)
    {
        $banners = Banner::select(['id','description', 'image_url'])
        ->where('is_active', '1')
        ->orderBy('id', 'desc')
        ->get();
        
        return response()->json([
            'status' => $this->responseKeys['STATUS_SUCCESS'],
            'message' => 'Success',
            'banners' => $banners,
        ]);
    }
}
