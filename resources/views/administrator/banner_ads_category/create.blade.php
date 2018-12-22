@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.banner_ads_category.title_single') </h1>
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
       @if(isset($bannerAdsCategory))
        {!! Form::model($bannerAdsCategory, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 
        'route' => ['administrator.banner_ads_category.save']]) !!}

         <input type="hidden" name="id" value="{{$bannerAdsCategory->banner_ads_category_id}}" />
      @else
        
	 	      {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'banner_ads_category-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.banner_ads_category.save']]) !!}

         <input type="hidden" name="id" value="" />            
      @endif
        <div class="tab-content ">
         <div class="tab-pane active" id="banner_ads_categoryInfo">

          <div class="form-group"> 
            {!! Form::label('name', trans('quickadmin.banner_ads_category.fields.name').': * ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
               <p class="help-block"></p>
               @if($errors->has('name'))
               <p class="help-block"> {{ $errors->first('name') }} </p>
               @endif 
            </div>
          </div>

           <div class="form-group"> 
                {!! Form::label('reservation_category_for', trans('quickadmin.banner_ads_category.fields.reservation_category_for') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> 
                  {!! Form::select('reservation_category_for', $reservationCategoryFor, old('reservation_category_for'), ['class' => 'form-control']) !!}
                  <p class="help-block"></p>
                </div>
              </div>

          <div class="form-group"> 
            {!! Form::label('sort', trans('quickadmin.banner_ads_category.fields.sort').': * ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               {!! Form::text('sort', old('sort'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
               <p class="help-block"></p>
               @if($errors->has('sort'))
               <p class="help-block"> {{ $errors->first('sort') }} </p>
               @endif 
            </div>
          </div>
       <!-- /.box --> 
        <div class="form-navigation">
          <div class="form-group">
           <div class="col-sm-offset-3 col-sm-8"> 
             <a href="{{ route('administrator.banner_ads_category.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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