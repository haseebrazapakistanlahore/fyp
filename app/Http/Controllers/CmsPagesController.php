<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\Http\Request;

class CmsPagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getPages = CmsPage::all();
        return view('admin.cmsPages.index',[
            'title' => 'CMS Pages',
            'getPages' => $getPages
        ]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $getEditPage = CmsPage::where('id',$id)->get();
        if ($getEditPage == null) {
            return redirect()->back()->with('errror', 'No Record Found');
        }
        return view('admin.cmsPages.edit',[
            'title' => 'Edit Page',
            'getEditPage' => $getEditPage
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:40',
            'description' => 'required'
        ]);

        $data = [
            'title' => $request->input('title'),
            'description' =>$request->input('description')
        ];

        CmsPage::where('id', $request->input('page_id'))->update($data);
        return redirect()->route('listPages')->with('success', 'Record updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
