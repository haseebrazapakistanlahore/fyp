<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Redirect;
use Uuid;
use Illuminate\Support\Facades\Input;

class CouponController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $coupons = Coupon::all();
        // return view('admin.coupons.index', ['title' => 'Coupons', 'coupons' => $coupons]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('admin.coupons.create', ['title' => 'Coupons']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // date convert to mysql format
        // $start_date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $request->input('start_date'))));
        // $end_date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $request->input('end_date'))));

        // $validator = Validator::make($request->all(),[
        //     'prefix' => 'required|max:3',
        //     'start_date' => 'required|date|after_or_equal:today',
        //     'end_date' => 'required|date|after_or_equal:start_date',
        //     'discount_percentage' => 'required|numeric|gt:0',
        //     'no_of_coupons' => 'required|numeric|gt:0',
        //     'offer_name' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return Redirect::back()->withErrors($validator)->withInput(Input::all());
        // }

        // if($request->discount_percentage == 0 ){
        //     return Redirect::back()->with('error', 'Discount percentage must be greater then 0.');
        // }

        // if( $request->no_of_coupons == 0){
        //     return Redirect::back()->with('error', 'No of coupons must be greater then 0.');
        // }

        // for($i=1; $i<=$request->input('no_of_coupons'); $i++)
        // {
         
            // generating coupong code 
            // adding timestamp at start and end of random string
            // $string = Str::random(10);
            // $first = substr(time(), 0, 5);
            // $last = substr(time(), 5, 10);
            // $coupon = $request->input('prefix').$last.$string.$first;

            // Generate a version 4, truly random, UUID coupon with prefix
        //     $coupon = $request->input('prefix')."-".Uuid::generate(4);
        //     $data = [
        //         'coupon_code' => $coupon,
        //         'prefix' => $request->input('prefix'),
        //         'start_date' => $start_date,
        //         'end_date' => $end_date,
        //         'discount_percentage' => $request->input('discount_percentage'),
        //         'offer_name' => $request->input('offer_name'),
        //     ];

        //     $coupon = Coupon::create($data);
        // }
        // return redirect()->route('listCoupons')->with('success', 'Record added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // Coupon::find($request->coupon_id)->delete();
        // return response()->json(['status' => 1, 'message' => 'Record deleted successfully.']);
    }
}
