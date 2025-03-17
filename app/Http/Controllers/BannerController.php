<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;

class BannerController extends Controller
{
       
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::orderBy('id','DESC')->get();
 
        return view('admin.banners.index',[ 'banners'=>$banners, 'title'=>'Banners' ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banners = Banner::all();
        return view('admin.banners.create',  ['banners' => $banners, 'title'=>'Banners']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Banner $banner)
    {
        $this->validate($request, [
            'title' => 'required|unique:banners|string|max:50',
            'description' => 'required|string|max:100',
            'image_url' => 'required|mimes:jpeg,bmp,png',
        ]);

        $data = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'is_active' => $request->input('status'),

        ];

        $banner = Banner::create($data);

        $bannerImageDirectory = 'bannerImages';
        if ($request->hasFile('image_url')) {
            
            $fileName = $request->file('image_url')->getClientOriginalName();

            if(!Storage::exists($bannerImageDirectory)){
                Storage::makeDirectory($bannerImageDirectory);
            }
            $imageUrl = Storage::putFile($bannerImageDirectory, new File($request->file('image_url')));
            $banner->update(['image_url'=> $imageUrl]);
        }

        return redirect()->route('listBanners')->with('success', 'Record added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner, $id)
    {
        $banner = Banner::find($id);

        if($banner == null)
        {
            return redirect()->route('listBanners')->with('error', 'No banner found.');
        }
        return view('admin.banners.edit',[ 'banner'=>$banner, 'title'=>'Banners' ] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       
        $banner = Banner::find($request->banner_id);
        if ($banner == null) {
            return redirect()->back()->with('error', 'No Record Found To Update.');
        }
        $rules = [
            'description' => 'required|string|max:100',
            'image_url' => 'mimes:jpeg,bmp,png',
        ];
        if ($banner->title != $request->get('title')) {
           $rules['title'] = 'required|string|unique:banners|max:50';
        
        } else {
            $rules['title'] = 'required|string|max:50';
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(Input::all());
        }

        $data = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'is_active' => $request->input('status'),

        ];

        $bannerImageDirectory = 'bannerImages';
        if ($request->hasFile('image_url')) {
            
            if(!Storage::exists($bannerImageDirectory)){
                Storage::makeDirectory($bannerImageDirectory);
            }
            Storage::delete('/'.$banner->image_url);
            $imageUrl = Storage::putFile($bannerImageDirectory, new File($request->file('image_url')));
            $data['image_url'] = $imageUrl;
        }

        $banner->update($data);
        return redirect()->route('listBanners')->with('success', 'Record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $banner = Banner::find($request->banner_id);
        
        if($banner == null)
        {
            return redirect()->route('listBanners')->with('error', 'No banner found.');
        }
        
        Storage::delete('/'.$banner->image_url);
        $banner = Banner::find($request->banner_id)->delete();
        return response()->json(['status' => 1, 'message' => 'Record deleted successfully.']);
    }
}
