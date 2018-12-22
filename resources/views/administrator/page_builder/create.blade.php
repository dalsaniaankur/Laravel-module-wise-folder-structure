@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.page_builder.title_single') </h1>
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
          <li class="active"> <a href="#page_builder_info" data-toggle="tab">{{trans('quickadmin.page_builder-tab.basic-info')}}</a> </li>
          <li> <a href="#extra_details" data-toggle="tab">{{trans('quickadmin.page_builder-tab.extra-details')}}</a> </li>
        </ul>
        <!-- form start --> 
        @if(isset($page_builder))

        {!! Form::model($page_builder, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 
        'route' => ['administrator.page_builder.save']]) !!}
        <input type="hidden" name="id" value="{{$page_builder->page_builder_id}}" />
        @else
        
        {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'page_builder-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.page_builder.save']]) !!}
        <input type="hidden" name="id" value="" />
        @endif
        <div class="tab-content ">
          <div class="tab-pane active" id="page_builder_info">
            
            <div class="form-group"> {!! Form::label('page_title', trans('quickadmin.page_builder.fields.page_title').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> {!! Form::text('page_title', old('page_title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('page_title'))
                <p class="help-block"> {{ $errors->first('page_title') }} </p>
                @endif </div>
            </div>
           
            <div class="form-group"> 
              {!! Form::label('image_name', trans('quickadmin.page_builder.fields.image_name') .': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::file('image_name', null, ['class' => 'form-control']) !!}
              </div>
            </div>
            @if (!empty($page_builder->image_path) && $page_builder->image_path != ' ')
            <div class="form-group"> 
              {!! Form::label('current_image', trans('quickadmin.page_builder.fields.current_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::image(URL::to('/') .'/'.$page_builder->image_path, 'Current Image', array( 'width' => 150, 'height' => 'auto' ),['class' => 'form-control']) !!}
                <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.page_builder_image') }} </div>
                 </div>
            </div>
            @endif
            <div class="form-group"> 
              {!! Form::label('content', trans('quickadmin.page_builder.fields.content').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                 
                {{ Form::textarea('content', old('content'), ['id' => 'content', 'class' => 'form-control html_content_editor', 'placeholder' => '']) }}
                 <p class="help-block"></p>
                 @if($errors->has('content'))
                 <p class="help-block"> {{ $errors->first('content') }} </p>
                 @endif 
              </div>
            </div>


             <div class="form-group"> 
              {!! Form::label('short_content', trans('quickadmin.page_builder.fields.short_content').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                 
                {{ Form::textarea('short_content', old('short_content'), ['id' => 'short_content', 'class' => 'form-control html_content_editor', 'placeholder' => '']) }}
                 <p class="help-block"></p>
                 @if($errors->has('short_content'))
                 <p class="help-block"> {{ $errors->first('short_content') }} </p>
                 @endif 
              </div>
            </div>

           <div class="form-group"> 
                {!! Form::label('display_banner_ads', trans('quickadmin.page_builder.fields.display_banner_ads').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    @php ($is_checked = true)
                    @foreach ($displayBannerAds as $key => $value)
                      @if(!empty($page_builder->type) && $page_builder->type == $key)
                        @php ($is_checked = true)
                      @endif
                      {!! Form::radio('display_banner_ads', $key, $is_checked, array('id' => 'display_banner_ads_'.$key)) !!}
                      {!! Form::label('typdisplay_banner_adse'.$key,$value) !!} &nbsp; &nbsp;
                      @php ($is_checked = false)
                    @endforeach
                </div>
              </div>

              <div class="form-group"> 
                {!! Form::label('banner_ads_category_id', trans('quickadmin.page_builder.fields.banner_ads_category') .': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> 
                  {!! Form::select('banner_ads_category_id', $bannerAdsCategory, old('banner_ads_category_id'), ['class' => 'form-control']) !!}
                  <p class="help-block"></p>
                </div>
              </div>

              <div class="form-group"> 
                {!! Form::label('category_id', trans('quickadmin.page_builder.fields.category_id') .': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> 
                  {!! Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control']) !!}
                  <p class="help-block"></p>
                </div>
              </div>

             <div class="form-group"> {!! Form::label('filter_table', trans('quickadmin.page_builder.fields.filter_table') .': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> {!! Form::select('filter_table', $filterTable, old('filter_table'), ['class' => 'form-control', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('filter_table'))
                <p class="help-block"> {{ $errors->first('filter_table') }} </p>
                @endif </div>
            </div>

            <div class="form-group"> {!! Form::label('state_id', trans('quickadmin.page_builder.fields.state_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> {!! Form::select('state_id', $state, old('state_id'), ['class' => 'form-control','onchange' => "getCityDropDown()", 'id' => 'state_id', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('state_id'))
                <p class="help-block"> {{ $errors->first('state_id') }} </p>
                @endif </div>
            </div>
            
            <div class="form-group"> {!! Form::label('city_id', trans('quickadmin.page_builder.fields.city') .': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8 city"> {!! Form::select('city_id', $city, old('city_id'), ['id' => 'city_id', 'class' => 'form-control', 'attr-none' => 'yes']) !!}
                <p class="help-block"></p>
                @if($errors->has('city_id'))
                <p class="help-block"> {{ $errors->first('city_id') }} </p>
                @endif 
              </div>
              <div class="custom-city-error-message"><span>@lang('quickadmin.qa_city_custom_error_msg')</span></div>
            </div>

             <div class="form-group"> {!! Form::label('url_key', trans('quickadmin.page_builder.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
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
                <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick="generateUrlKey(['page_title','city_id','state_id']);">@lang('quickadmin.btn_generate_url_key') </button>
                <p class="help-block"></p>
              </div>
            </div>


            <div class="form-group"> {!! Form::label('redius', trans('quickadmin.page_builder.fields.redius').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> {!! Form::text('redius', old('redius'), ['class' => 'form-control','data-parsley-type'=>'digits', 'data-parsley-trigger' => 'keyup', 'placeholder' => '']) !!}
                <p class="help-block"></p>
              
              </div>
            </div>
          
          
          </div>
          <div class="tab-pane" id="extra_details">
            <div class="form-group"> 
              {!! Form::label('meta_title', trans('quickadmin.page_builder.fields.meta_title').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::text('meta_title', old('meta_title'), ['class' => 'form-control', 'placeholder' => '']) !!}
                <p class="help-block"></p>
              </div>
            </div>
            <div class="form-group"> 
              {!! Form::label('meta_keywords', trans('quickadmin.page_builder.fields.meta_keywords').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::text('meta_keywords', old('meta_keywords'), ['class' => 'form-control', 'placeholder' => '']) !!}
                <p class="help-block"></p>
              </div>
            </div>

            <div class="form-group"> 
              {!! Form::label('meta_description', trans('quickadmin.page_builder.fields.meta_description').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                {{ Form::textarea('meta_description', old('meta_description'), ['id' => 'meta_description', 'class' => 'form-control', 'placeholder' => '']) }}
                 <p class="help-block"></p>
              </div>
            </div>

            <div class="form-group"> 
              {!! Form::label('status', trans('quickadmin.page_builder.fields.status').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::checkbox('status', null,  old('status'), array('id' => 'status')) !!} </div>
            </div>
            
          </div>
          <!-- /.box -->
          <div class="form-navigation">
            <div class="form-group">
              <div class="col-sm-offset-3 col-sm-8"> <a href="{{ route('administrator.page_builder.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a> {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
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