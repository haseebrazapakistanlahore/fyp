@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1>{{$title}}</h1>
        </div>
        <div class="row  pt-10">
            <div class="col-lg-12">
                <a href="{{ route('listPages')}}" class="btn btn-primary pull-right">Back</a>
            </div>
        </div>
        <div class="row ">
            <div class="col-lg-12 pt-20">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Update Page
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <form role="form" id="order_edit_form" action="{{route('updatePage')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="col-lg-12">

                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label>Title <span class="required-star">*</span></label>
                                        <input type="text" class="form-control" name="title" value="{{ $getEditPage[0]->title }}" >
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label>Description <span class="required-star">*</span></label>
                                        <textarea rows="3" id="editor1" class="form-control" name="description">{!! $getEditPage[0]->description !!}</textarea>
                                    </div>

                                </div>

                                <div class="col-lg-12">
                                    <div class="col-lg-6">
                                        <input type="hidden" name="page_id" id="order_id" value="{{$getEditPage[0]->id}}">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
     {{--Module JS file--}}
    <script type="text/javascript" src="{{asset('public/ckeditor/ckeditor.js')}}"></script>
     <script> CKEDITOR.replace('editor1')</script>
@endsection