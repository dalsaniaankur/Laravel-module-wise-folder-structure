@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.subscribes.title_single') </h1>
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
       @if(isset($subscribes))
        {!! Form::model($subscribes, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal  validation-form','data-parsley-validate',  'route' => ['administrator.subscribes.save']]) !!}

         <input type="hidden" name="id" value="{{$subscribes->subscriber_id}}" />
      @else
        
	 	      {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'banner_ads_category-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.subscribes.save']]) !!}

         <input type="hidden" name="id" value="" />            
      @endif
        <div class="tab-content ">
         <div class="tab-pane active" id="banner_ads_categoryInfo">

          <div class="form-group"> 
            {!! Form::label('email', trans('quickadmin.subscribes.fields.email').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup']) !!}
                <p class="help-block"></p>
                @if($errors->has('email'))
                <p class="help-block"> {{ $errors->first('email') }} </p>
                @endif 
              </div>
          </div>
          
       <!-- /.box --> 
        <div class="form-navigation">
          <div class="form-group">
           <div class="col-sm-offset-3 col-sm-8"> 
             <a href="{{ route('administrator.subscribes.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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