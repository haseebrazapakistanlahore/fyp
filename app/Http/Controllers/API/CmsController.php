<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Config;
use Validator;
use DB;

class CmsController extends Controller
{
    private $generalConstants;
    private $responseConstants;
    
    public function __construct()
    {
        $this->generalConstants = Config::get('constants.GENERAL_CONSTANTS');
        $this->responseConstants = Config::get('constants.RESPONSE_CONSTANTS');
    }

    public function getPages(Request $request)
    {
        $response = [];

        $pages = CmsPage::select('id', 'title')->get();
        
        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['pages'] = $pages;
        $response['message'] = 'Success.';

        return response()->json($response);
    }

    public function getPageDetail(Request $request)
    {
        $response = [];

        $page = CmsPage::select('id', 'title', 'description')->where('id', $request->get($this->generalConstants['KEY_PAGE_ID']))->first();
        
        $response['status'] = $this->responseConstants['STATUS_SUCCESS'];
        $response['page'] = $page;
        $response['message'] = 'Success.';

        return response()->json($response);
    }


}
