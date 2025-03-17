<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Validator;
use Redirect;
use Illuminate\Support\Facades\Input;
use Storage;
use Illuminate\Http\File;
use Carbon\Carbon;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::orderBy('id', 'DESC')->get();
        return view('admin.discounts.index', ['title' => 'Discounts', 'discounts' => $discounts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.discounts.create', ['title' => 'Discounts']);
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
            'min_amount' => 'required|numeric|gt:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'discount_percentage' => 'required|numeric|gt:0|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        }
        
        if($request->discount_percentage == 0){
            
            return Redirect::back()->with('error', 'Discount percentage must be greater then 0.')->withInput(Input::all());
        }

        $data = [
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
            'discount_percentage' => $request->input('discount_percentage'),
            'start_date' => $start_date,
            'end_date' => $end_date,     
        ];

        $discount = Discount::create($data);

        $discountImages = 'discountImages';
        if ($request->hasFile('image_url')) {
            
            if(!Storage::exists($discountImages)){
                Storage::makeDirectory($discountImages);
            }
            $imageUrl = Storage::putFile($discountImages, new File($request->file('image_url')));
            $discount->update(['image'=> $imageUrl]);
        }

        return redirect()->route('listDiscounts')->with('success', 'Record added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // return redirect()->action('DiscountController@index');
        $discount = Discount::find($id);

        if ($discount == null) {
            return redirect()->back()->with('error', 'Record Not Found.');
        }
        
        if (strtotime($discount->start_date) <= strtotime((Carbon::now())->format("Y-m-d H:i:s"))) {
            return redirect()->back()->with('error', 'Invalid Request.');
        }
        return view('admin.discounts.edit', ['discount' => $discount, 'title' => 'Discounts']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $discount = Discount::find($request->discount_id);

        if ($discount == null) {
            return redirect()->back()->with('error', 'Record Not Found.');
        }
        
        if (strtotime($discount->start_date) <= strtotime((Carbon::now())->format("Y-m-d H:i:s"))) {
            return redirect()->back()->with('error', 'Invalid Request.');
        }

        // date convert to mysql format
        $start_date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $request->input('start_date'))));
        $end_date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $request->input('end_date'))));

        $validator = Validator::make($request->all(),[
            'min_amount' => 'required|numeric|gt:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'discount_percentage' => 'required|numeric|gt:0',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        }


        $data = [
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
            'discount_percentage' => $request->input('discount_percentage'),
            'start_date' => $start_date,
            'end_date' => $end_date,  
        ];
        $discountImages = 'discountImages';
        if ($request->hasFile('image_url')) {
            
            if(!Storage::exists($discountImages)){
                Storage::makeDirectory($discountImages);
            }
            
            if ($discount->image != null && Storage::exists($discount->image)) {
                Storage::delete($discount->image);
            }
            
            $imageUrl = Storage::putFile($discountImages, new File($request->file('image_url')));
            $discount->update(['image'=> $imageUrl]);
        }

        $discount = Discount::find($request->discount_id);
        $discount->update($data);
        return redirect()->route('listDiscounts')->with('success', 'Record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Discount::where('id', $request->discount_id)->delete();
        return response()->json(['status' => 1, 'message' => 'Record deleted successfully.']);
    }
}
