<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews1 = Review::select(
        DB::raw('reviews.*'))
        ->join('consumers', 'reviews.consumer_id', '=', 'consumers.id')
        ->where('consumers.is_deleted', 0)
        ->get();
        
        $reviews2 = Review::select(
        DB::raw('reviews.*'))
        ->join('professionals', 'reviews.professional_id', '=', 'professionals.id')
        ->where('professionals.is_deleted', 0)
        ->get();
        $reviews = $reviews1->merge($reviews2);
        return view('admin.reviews.index',  ['title' => 'Reviews', 'reviews' => $reviews]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    // Approved the reviews
    public function update($id)
    {
        $review = Review::find($id);
        if($review == null)
        {
            return redirect()->route('listReviews')->with('error', 'No Record Found.');
        }
        if($review->is_approved == 1)
        {
            return redirect()->route('listReviews')->with('error', 'Already approved.');
        }
        $review = Review::where('id', $id)->update(['is_approved' => 1]);
        return redirect()->route('listReviews')->with('success', 'Review Approved Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $review = Review::find($request->review_id)->delete();
        return response()->json(['status' => 1, 'message' => 'Record Deleted Successfully.']);
    }
}
