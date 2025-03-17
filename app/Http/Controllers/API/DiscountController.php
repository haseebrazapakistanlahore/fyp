<?php

namespace App\Http\Controllers\API;

use Config;
use Validator;
use App\Models\Discount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
    private $responseConstants;

    public function __construct()
    {
        $this->responseConstants = Config::get('constants.RESPONSE_CONSTANTS');
    }

    public function getAllDiscounts(Request $request)
    {
        $dt = Carbon::now();
        $discounts = Discount::select(['id','min_amount', 'max_amount', 'start_date', 'end_date', 'discount_percentage', 'image'])
        // ->where('start_date', '<=', Carbon::now())
        // ->where('end_date', '>=', Carbon::now())
        ->where('start_date', '<=', $dt->toDateString())
        ->where('end_date', '>=', $dt->toDateString())
        ->where('is_active', '1')
        ->orderBy('id', 'desc')
        ->get();

        return response()->json([
            'status' => $this->responseConstants['STATUS_SUCCESS'],
            'message' => 'Success',
            'discounts' => $discounts,
        ]);
    }
}
