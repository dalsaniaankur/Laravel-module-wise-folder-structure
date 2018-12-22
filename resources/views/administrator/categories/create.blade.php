@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.categories.title_single') </h1>
</section>
<!-- Main content -->
<section class="content"> 
  <!--------------------------
    | Your Page Content Here |
    -------------------------->
  <div class="row">
    <div class="col-md-12"> @if (Session::has('success'))
      <div class="alert alert-success">
        <p>{{ Session::get('success') }}</p>
      </div>
      @endif
      @if ($errors->count() > 0)
      <div class="alert alert-danger">
        <ul class="list-unstyled">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif </div>
  </div>
  <div class="row"> 
    <!-- left column -->
    <div class="col-md-9">
      <div class="nav-tabs-custom validation-tab" >
        <ul class="nav nav-tabs">
          <li class="active"> <a href="#page_builder_info" data-toggle="tab">{{trans('quickadmin.categories.basic-info')}}</a> </li>
          <li> <a href="#extra_details" data-toggle="tab">{{trans('quickadmin.categories.extra-details')}}</a> </li>
        </ul>
        <!-- form start --> 
        @if(isset($categories))

        {!! Form::model($categories, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 
        'route' => ['administrator.categories.save']]) !!}
        <input type="hidden" name="id" value="{{$categories->category_id}}" />
        @else
        
        {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'page_builder-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.categories.save']]) !!}
        <input type="hidden" name="id" value="" />
        @endif
        <div class="tab-content ">
          <div class="tab-pane active" id="page_builder_info">
            
            <div class="form-group"> {!! Form::label('title', trans('quickadmin.categories.fields.title').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('title'))
                <p class="help-block"> {{ $errors->first('title') }} </p>
                @endif </div>
            </div>

            <div class="form-group"> {!! Form::label('url_key', trans('quickadmin.categories.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> {!! Form::text('url_key', old('url_key'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-pattern' => '^[a-z0-9-]*$']) !!}
                <p class="help-block"></p>
                @if($errors->has('url_key'))
                <p class="help-block"> {{ $errors->first('url_key') }} </p>
                @endif 
                <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.url_key') }} </div>
              </div>
            </div>

             <div class="form-group"> 
              <div class="col-sm-offset-3 col-sm-8">
                <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick="generateUrlKey(['title']);">@lang('quickadmin.btn_generate_url_key') </button>
                <p class="help-block"></p>
              </div>
            </div>

            <div class="form-group"> 
              {!! Form::label('image_name', trans('quickadmin.categories.fields.image_name') .': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::file('image_name', null, ['class' => 'form-control']) !!}
              </div>
            </div>
            @if (!empty($categories->image_path) && $categories->image_path != ' ')
            <div class="form-group"> 
              {!! Form::label('current_image', trans('quickadmin.categories.fields.current_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::image(URL::to('/') .'/'.$categories->image_path, 'Current Image', array( 'width' => 150, 'height' => 'auto' ),['class' => 'form-control']) !!}
                <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.image') }} </div>
                 </div>

            </div>

            @endif

            <div class="form-group"> 
              {!! Form::label('parent_category_id', trans('quickadmin.categories.fields.parent_category_id') .': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::select('parent_category_id', $parentCategories, old('parent_category_id'), ['class' => 'form-control']) !!}
                <p class="help-block"></p>
              </div>
            </div>

             <div class="form-group"> 
              {!! Form::label('banner_ads_category_id', trans('quickadmin.categories.fields.banner_ads_category_id') .': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::select('banner_ads_category_id', $bannerAdsCategory, old('banner_ads_category_id'), ['class' => 'form-control']) !!}
                <p class="help-block"></p>
              </div>
            </div>

            <div class="form-group"> 
              {!! Form::label('description', trans('quickadmin.categories.fields.description').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                 
                {{ Form::textarea('description', old('description'), ['id' => 'description', 'class' => 'form-control html_content_editor', 'placeholder' => '']) }}
                 <p class="help-block"></p>
                 @if($errors->has('description'))
                 <p class="help-block"> {{ $errors->first('description') }} </p>
                 @endif 
              </div>
            </div>

            <div class="form-group"> 
              {!! Form::label('sort', trans('quickadmin.categories.fields.sort').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                 {!! Form::text('sort', old('sort'), ['class' => 'form-control', 'placeholder' => '', 'data-parsley-type'=>'digits', 'data-parsley-trigger' => 'keyup','required' => '']) !!}
                 <p class="help-block"></p>
                 @if($errors->has('sort'))
                 <p class="help-block"> {{ $errors->first('sort') }} </p>
                 @endif 
              </div>
            </div>

          </div>
          <div class="tab-pane" id="extra_details">
            <div class="form-group"> 
              {!! Form::label('meta_title', trans('quickadmin.categories.fields.meta_title').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::text('meta_title', old('meta_title'), ['class' => 'form-control', 'placeholder' => '']) !!}
                <p class="help-block"></p>
              </div>
            </div>
            <div class="form-group"> 
              {!! Form::label('meta_keyword', trans('quickadmin.categories.fields.meta_keyword').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::text('meta_keyword', old('meta_keyword'), ['class' => 'form-control', 'placeholder' => '']) !!}
                <p class="help-block"></p>
              </div>
            </div>

            <div class="form-group"> 
              {!! Form::label('meta_description', trans('quickadmin.categories.fields.meta_description').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                {{ Form::textarea('meta_description', old('meta_description'), ['id' => 'meta_description', 'class' => 'form-control', 'placeholder' => '']) }}
                 <p class="help-block"></p>
              </div>
            </div>

            <div class="form-group"> 
              {!! Form::label('short_description', trans('quickadmin.categories.fields.short_description').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                {{ Form::textarea('short_description', old('short_description'), ['id' => 'short_description', 'class' => 'form-control', 'placeholder' => '']) }}
                 <p class="help-block"></p>
              </div>
            </div>

            <div class="form-group"> 
              {!! Form::label('status', trans('quickadmin.categories.fields.status').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::checkbox('status', null,  old('status'), array('id' => 'status')) !!} </div>
            </div>
            
          </div>
          <!-- /.box -->
          <div class="form-navigation">
            <div class="form-group">
              <div class="col-sm-offset-3 col-sm-8"> <a href="{{ route('administrator.categories.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a> {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
                <button type="button" class="previous btn btn-primary">@lang('quickadmin.previous')</button>
                <button type="button" class="next btn btn-primary">@lang('quickadmin.next') </button>
                <span class="clearfix"></span> </div>
            </div>
          </div>
        </div>
        {!! Form::close() !!} 
        <!-- End page content--> 
      </div>
    </div>
  </div>
</section>
<!-- /.content --> 
<script type="text/javascript">
tinymceInit();
</script>
@endsection 