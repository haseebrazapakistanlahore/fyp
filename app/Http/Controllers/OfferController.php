<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Redirect;
use Uuid;
use Illuminate\Support\Facades\Input;

class OfferController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::where('is_active', 1)->orderBy('id', 'DESC')->get();
        return view('admin.offers.index', ['title' => 'Offers', 'offers' => $offers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.offers.create', ['title' => 'Offers']);
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
        $start_date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $request->input('start_date'))));
        $end_date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $request->input('end_date'))));

        $validator = Validator::make($request->all(),[
            'offer_name' => 'required|string|max:50',
            'prefix' => 'required|string|max:3',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'discount_percentage' => 'required|numeric|gt:0',
            'no_of_coupons' => 'required|numeric|between:1,8',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        }

        if($request->discount_percentage == 0 ){
            return Redirect::back()->with('error', 'Discount percentage must be greater then 0.');
        }

        if( $request->no_of_coupons == 0){
            return Redirect::back()->with('error', 'No. of coupons must be greater then 0.');
        }

        $data = [
            'offer_name' => $request->input('offer_name'),
            'prefix' => $request->input('prefix'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'discount_percentage' => $request->input('discount_percentage'),
        ];

        $offer = Offer::create($data);

        $prefix = $request->input('prefix');  
        for($i=1; $i<=$request->input('no_of_coupons'); $i++)
        {
         
            $coupon = $prefix.'-'.str_random(3);
            if(Coupon::where('coupon_code', $coupon)->count() == 0) {
                // Generate a version 4, truly random, UUID coupon with prefix
                Coupon::create([
                    // 'coupon_code' => $request->input('prefix')."-".Uuid::generate(4),
                    'coupon_code' => $coupon,
                    'offer_id' => $offer->id,
                ]);

            }
            
            // Generate a version 4, truly random, UUID coupon with prefix
            // Coupon::create([
            //     'coupon_code' => $request->input('prefix')."-".Uuid::generate(4),
            //     'offer_id' => $offer->id,
            // ]);
        }
        return redirect()->route('listOffers')->with('success', 'Record Added Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offer = Offer::find($id);
        if($offer == null) {
            return Redirect::back()->with('error', 'No Record Found.');
        }
        return view('admin.offers.view',['title' => 'Offers', 'offer' => $offer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $offer = Offer::find($id);
        if($offer == null) {
            return Redirect()->back()->with('error', 'No record found.');
        }

        return view('admin.offers.edit', ['title' => 'Offers', 'offer' => $offer]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offer $offer)
    {
         // date convert to mysql format
        $start_date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $request->input('start_date'))));
        $end_date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $request->input('end_date'))));
 
        $validator = Validator::make($request->all(),[
            'offer_name' => 'required|string|max:50',
            'prefix' => 'required|string|max:3',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'discount_percentage' => 'required|numeric|gt:0',
        ]);
 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        }
 
        if($request->discount_percentage == 0 ){
            return Redirect::back()->with('error', 'Discount percentage must be greater then 0.');
        }
 
       
        $data = [
            'offer_name' => $request->input('offer_name'),
            'prefix' => $request->input('prefix'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'discount_percentage' => $request->input('discount_percentage'),
        ];
 
        Offer::find($request->input('offer_id'))->update($data);
        return redirect()->route('listOffers')->with('success', 'Record Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Offer::find($request->offer_id)->update(['is_active' =>  0]);
        return response()->json(['status' => 1, 'message' => 'Record Deleted Successfully.']);
    }
}
