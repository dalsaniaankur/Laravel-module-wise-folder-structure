@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.banner_tracking.title_single') </h1>
</section>
<!-- Main content -->
<section class="content"> 
  <!--------------------------
    | Your Page Content Here |
    -------------------------->
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
        <ul class="nav nav-tabs">
        </ul>

        <!-- form start --> 
       @if(isset($bannerTracking))
        {!! Form::model($bannerTracking, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal  validation-form','data-parsley-validate',  'route' => ['administrator.banner_tracking.save']]) !!}

         <input type="hidden" name="id" value="{{$bannerTracking->banner_tracking_id}}" />
      @else
        
	 	      {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'banner_ads_category-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.banner_tracking.save']]) !!}

         <input type="hidden" name="id" value="" />            
      @endif
        <div class="tab-content ">
         <div class="tab-pane active" id="banner_ads_categoryInfo">

          <div class="form-group"> 
              {!! Form::label('banner_ads_id', trans('quickadmin.banner_tracking.fields.banner_ads_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::select('banner_ads_id', $bannerAdsList, old('banner_ads_id'), ['class' => 'form-control','onchange' => "getCityDropDown()", 'id' => 'banner_ads_id', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('banner_ads_id'))
                <p class="help-block"> {{ $errors->first('banner_ads_id') }} </p>
                @endif 
              </div>
            </div>


          <div class="form-group"> 
            {!! Form::label('page_url', trans('quickadmin.banner_tracking.fields.page_url').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::text('page_url', old('page_url'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup']) !!}
                <p class="help-block"></p>
                @if($errors->has('page_url'))
                <p class="help-block"> {{ $errors->first('page_url') }} </p>
                @endif 
              </div>
          </div>

          <div class="form-group"> 
            {!! Form::label('ip_address', trans('quickadmin.banner_tracking.fields.ip_address').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::text('ip_address', old('ip_address'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup']) !!}
                <p class="help-block"></p>
                @if($errors->has('ip_address'))
                <p class="help-block"> {{ $errors->first('ip_address') }} </p>
                @endif 
              </div>
          </div>

          <div class="form-group"> 
            {!! Form::label('banner_redirect_link', trans('quickadmin.banner_tracking.fields.banner_redirect_link').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::text('banner_redirect_link', old('banner_redirect_link'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup']) !!}
                <p class="help-block"></p>
                @if($errors->has('banner_redirect_link'))
                <p class="help-block"> {{ $errors->first('banner_redirect_link') }} </p>
                @endif 
              </div>
          </div>
          
       <!-- /.box --> 
        <div class="form-navigation">
          <div class="form-group">
           <div class="col-sm-offset-3 col-sm-8"> 
             <a href="{{ route('administrator.banner_tracking.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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