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
            <a href="{{ route('createCategory') }}" class="btn btn-primary pull-right">Add Category</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Categories List
                </div>

                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableCategories">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Image</th>
                                <th>colors</th>
                                <th>color No.</th>
                                <th>Sizes</th>
                                <th>No. Of Products</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($categories as $category)
                            <tr class="odd gradeX">
                                <td>{{$category->id}}</td>
                                <td>{{$category->title}}</td>
                                <td>{{ ($category->type == '0')? 'Consumer':'Professional'}}</td>
                                @if ($category->image != null && Storage::exists($category->image))
                                <td>
                                    <img src="{{asset('storage/app/public/'.$category->image)}}" id="image" class="thumbnail-image-50" />
                                </td>
                                @else
                                <td><img class="thumbnail-image-50" src="{{ asset('storage/app/public/categoryImages/placeholder.png') }}" alt=""></td>
                                @endif
                                <td>{{ ($category->has_colors) == 1? 'Yes' : 'No'}}</td>
                                <td>{{ ($category->has_color_no) == 1? 'Yes' : 'No'}}</td>
                                <td>{{ ($category->has_sizes) == 1? 'Yes' : 'No'}}</td>

                                @if ($category->productCount() > 0)
                                <td><a href="{{ route('productByCategory', $category->id)}}">Products ({{$category->productCount()}})</a></td>
                                @else
                                <td>Products ({{$category->productCount()}})</td>
                                @endif


                                <td>{{ ($category->is_active) == 1? 'Active' : 'In Active'}}</td>
                                <td class="text-center">
                                    <a href="{{ route('editCategory', $category->id) }}"><i class="fa fa-pencil-square-o mr-10"
                                            aria-hidden="true"></i></a>
                                    {{-- <a href="javascript:void(0);" onclick="deactivateCategory('{{$category->id}}');">
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
<script src="{{asset('public/admin/categories/js/view.js')}}"></script>
@stop
