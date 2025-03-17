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
            <a href="{{ route('createSubChildCategory') }}" class="btn btn-primary pull-right">Add Sub Child Category</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Sub Child Categories List
                </div>

                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableSubChildCategories">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sub Child Category</th>
                                <th>Child Category</th>
                                <th>Category</th>
                                <th>Category Type</th>
                                <th>No. Of Products</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($subChildCategories as $subChildCategory)

                            <tr class="odd gradeX">
                                <td>{{$subChildCategory->id}}</td>
                                <td>{{$subChildCategory->title}}</td>
                                <td>{{$subChildCategory->subCategory->title}}</td>
                                <td>{{$subChildCategory->category->title}}</td>
                                <td>{{ ($subChildCategory->category->type == '0') ? 'Consumer' : 'Professional'}}</td>
                                
                                @if ($subChildCategory->productCount() > 0)
                                <td><a href="{{ route('productBySCC', $subChildCategory->id)}}">Products ({{$subChildCategory->productCount()}})</a></td>
                                @else
                                <td>Products ({{$subChildCategory->productCount()}})</td>
                                @endif
                                
                                <td>{{ ($subChildCategory->is_active) == 1? 'Active' : 'In Active'}}</td>
                                
                                <td class="text-center">
                                    <a href="{{ route('editSubChildCategory', $subChildCategory->id) }}">
                                        <i class="fa fa-pencil-square-o mr-10" aria-hidden="true"></i>
                                    </a>
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
<script src="{{asset('public/admin/subChildCategories/js/view.js')}}"></script>
@stop
