@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1>{{$title}}</h1>
    </div>
</div>
<div id="list">
    <div class="row pt-10">
        <div class="col-lg-12">
            <a href="{{ route('createSubCategory') }}" class="btn btn-primary pull-right">Add Child Category</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Sub Categories List
                </div>

                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableSubCategories">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Child Category</th>
                                <th>Category</th>
                                <th>Category Type</th>
                                <th>Image</th>
                                <th>No. Of Products</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($subCategories as $subCategory)
                           
                            <tr class="odd gradeX">
                                <td>{{$subCategory->id}}</td>
                                <td>{{$subCategory->title}}</td>
                                <td>{{$subCategory->category->title}}</td>
                                <td>{{ ($subCategory->category->type == '0') ? 'Consumer' : 'Professional'}}</td>
                                @if ($subCategory->image != null && Storage::exists($subCategory->image))
                                <td><img src="{{asset('storage/app/public/'.$subCategory->image)}}" id="image" class="thumbnail-image-50" />
                                </td>
                                @else
                                <td><img class="thumbnail-image-50" src="{{ asset('storage/app/public/categoryImages/placeholder.png') }}" alt=""></td>
                                @endif

                                @if ($subCategory->productCount() > 0)
                                <td><a href="{{ route('productBySC', $subCategory->id)}}">Products ({{$subCategory->productCount()}})</a></td>
                                @else
                                <td>Products ({{$subCategory->productCount()}})</td>
                                @endif
                                
                                <td>{{ ($subCategory->is_active) == 1? 'Active' : 'In Active'}}</td>
                                <td class="text-center">
                                    <a href="{{ route('editSubCategory', $subCategory->id) }}">
                                        <i class="fa fa-pencil-square-o mr-10" aria-hidden="true"></i>
                                    </a>

                                    {{-- <a href="javascript:void(0);" onclick="deactivateSubCategory('{{$subCategory->id}}');">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a> --}}
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
{{-- Module JS file --}}
<script src="{{asset('public/admin/subCategories/js/view.js')}}"></script>
@stop
