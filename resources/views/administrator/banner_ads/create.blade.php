@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.banner_ads.title_single') </h1>
</section>
<!-- Main content -->
<section class="content"> 
  <div class="row">
    <div class="col-md-12">
     @if (Session::has('success'))
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
      @endif
     </div>
  </div>
  <div class="row"> 
    <!-- left column -->
    <div class="col-md-9">
      <div class="nav-tabs-custom validation-tab" >
        
       <!-- form start --> 
      @if(isset($bannerAds))
        {!! Form::model($bannerAds, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 'url' => 'administrator/banner_ads/save/'.$banner_ads_category_id ]) !!}
        
         <input type="hidden" name="id" value="{{$bannerAds->banner_ads_id}}" />
      @else
          {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'team-form', 'class'=>'form-horizontal validation-form','data-parsley-validate', 'url' => 'administrator/banner_ads/save/'.$banner_ads_category_id]) !!}
         <input type="hidden" name="id" value="" />
      @endif
      
        <div class="tab-content ">
         <div class="tab-pane active" id="teamsInfo">

              <div class="form-group"> 
                {!! Form::label('title', trans('quickadmin.banner_ads.fields.title').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('title'))
                   <p class="help-block"> {{ $errors->first('title') }} </p>
                   @endif 
                </div>
              </div>

              <div class="form-group"> 
                {!! Form::label('banner_ads_category_id', trans('quickadmin.banner_ads.fields.banner_ads_category') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> 
                  {!! Form::select('banner_ads_category_id', $bannerAdsCategory, old('banner_ads_category_id', $banner_ads_category_id), ['class' => 'form-control']) !!}
                  <p class="help-block"></p>
                </div>
              </div>

              <div class="form-group"> 
                  {!! Form::label('type', trans('quickadmin.banner_ads.fields.type') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> 
                  {!! Form::select('type', $type, old('type'), ['class' => 'form-control', 'required' => '']) !!}
                  <p class="help-block"></p>
                  @if($errors->has('type'))
                  <p class="help-block"> {{ $errors->first('type') }} </p>
                  @endif 
                </div>
              </div>

              <div class="form-group"> 
                  {!! Form::label('position', trans('quickadmin.banner_ads.fields.position') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> 
                  {!! Form::select('position', $position, old('position'), ['class' => 'form-control', 'required' => '']) !!}
                  <p class="help-block"></p>
                  @if($errors->has('position'))
                  <p class="help-block"> {{ $errors->first('position') }} </p>
                  @endif 
                </div>
              </div>

              <div class="form-group"> 
                {!! Form::label('sort', trans('quickadmin.banner_ads.fields.sort').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('sort', old('sort'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('sort'))
                   <p class="help-block"> {{ $errors->first('sort') }} </p>
                   @endif 
                </div>
              </div>

              <div class="form-group"> 
                {!! Form::label('alt_image_text', trans('quickadmin.banner_ads.fields.alt_image_text').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('alt_image_text', old('alt_image_text'), ['class' => 'form-control', 'placeholder' => '']) !!}
                   <p class="help-block"></p>
                </div>
              </div>

              <div class="form-group"> 
                {!! Form::label('forward_url', trans('quickadmin.banner_ads.fields.forward_url').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('forward_url', old('forward_url'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('forward_url'))
                   <p class="help-block"> {{ $errors->first('forward_url') }} </p>
                   @endif 
                </div>
              </div>

               <div class="form-group"> 
                  {!! Form::label('image_id', trans('quickadmin.banner_ads.fields.select_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> 
                  {!! Form::select('image_id', $images, old('image_id'), ['class' => 'form-control']) !!}
                  <p class="help-block"></p>
                </div>
              </div>

                <div class="form-group"> 
                  {!! Form::label('image_id', trans('quickadmin.banner_ads.fields.upload_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-8"> 
                    {!! Form::file('image', null, ['class' => 'form-control']) !!}
                  </div>
                </div>
                
                <div class="form-group"> 
                  {!! Form::label('current_assigned_image', trans('quickadmin.banner_ads.fields.current_assigned_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-8"> 
                    @if (!empty($bannerAds->Images->image_path))
                       {!! Form::image(URL::to('/') .'/'.$bannerAds->Images->image_path, 'Current Assigned Image', array( 'width' => 510  ),['class' => 'form-control']) !!}
                    @else
                        No Image Assigned.
                    @endif
                    <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.image') }}</div> 
                  </div>
                </div>
             
         </div>
         <!-- /.box --> 
         <div class="form-navigation">
          	 <div class="form-group">
               <div class="col-sm-offset-3 col-sm-8"> 
               <a href="{{ url('administrator/banner_ads') }}/{{ $banner_ads_category_id}} " class="btn btn-primary">@lang('quickadmin.cancel')</a>
                {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!} 
                <span class="clearfix"></span>
               </div>
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
@endsection 
