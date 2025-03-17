@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1>{{$title}}</h1>
        </div>
    </div>
    <div class="row pt-10">
        <div class="col-lg-12">

            {{-- <button type="button" id="add_discount_slab" class="btn btn-primary pull-right">Add Discount Slab</button>
            --}}
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 pt-20">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Pages List
                </div>

                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableDiscounts">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>@php $count=1; @endphp

                        @foreach($getPages as $page)
                            <tr class="odd gradeX">
                                <td>{{$count++}}</td>
                                <td>{{$page->title}}</td>
                                <td>{!!str_limit($page->description,100)!!}</td>
                                <td class="text-center">
                                    <a href="{{ route('editPage', $page->id) }}" class="mr-10">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size:18px;"></i>
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

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#dataTableDiscounts').DataTable({
                responsive: true,

                "aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": [1,2,3]
                }, {
                    "bSearchable": false,
                    "aTargets": [0]
                }]

            });
        });

    </script>
    {{-- Module JS file --}}
    <script src="{{asset('public/admin/cmsPages/js/view.js')}}"></script>
@stop
