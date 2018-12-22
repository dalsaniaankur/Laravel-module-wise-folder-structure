@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.gallery.title_single') </h1>
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
       @if(isset($gallery))
        {!! Form::model($gallery, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 
        'route' => ['administrator.gallery.save']]) !!}
         <input type="hidden" name="id" value="{{$gallery->gallery_id}}" />
      @else
         {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'gallery-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.gallery.save']]) !!}
         <input type="hidden" name="id" value="" />            
      @endif
        <div class="tab-content ">
         <div class="tab-pane active" id="galleryInfo">
          
          <div class="form-group"> 
            {!! Form::label('name', trans('quickadmin.gallery.fields.name').': * ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
               <p class="help-block"></p>
               @if($errors->has('name'))
               <p class="help-block"> {{ $errors->first('name') }} </p>
               @endif 
            </div>
          </div>

          <div class="form-group">
           {!! Form::label('image_id',trans('quickadmin.gallery.fields.select_image') .':  ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
            {!! Form::select('image_id',$images, old('image_id'), ['class' => 'form-control']) !!}
            <p class="help-block"></p>
            @if($errors->has('image_id'))
            <p class="help-block"> {{ $errors->first('image_id') }} </p>
            @endif 
            </div>
          </div>
          <div class="form-group"> 
            {!! Form::label('image_id', trans('quickadmin.gallery.fields.upload_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              {!! Form::file('gallery_image', null, ['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="form-group"> 
            {!! Form::label('current_assigned_image', trans('quickadmin.gallery.fields.current_assigned_image') .': * ', ['class' => 'col-sm-3 control-label']) !!}         <div class="col-sm-8"> 
              @if (!empty($gallery->Images->image_path))
                 {!! Form::image(URL::to('/') .'/'.$gallery->Images->image_path, 'Current Assigned Image', array( 'width' => 150, 'height' => 'auto' ),['class' => 'form-control']) !!}
              @else
                  No Image Assigned.
              @endif
              <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.image') }} </div>
            </div>
         </div>
          <div class="form-group"> 
            {!! Form::label('image_alt_Text', trans('quickadmin.gallery.fields.image_alt_Text').': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               {!! Form::text('image_alt_Text', old('image_alt_Text'), ['class' => 'form-control', 'placeholder' => '']) !!}
               <p class="help-block"></p>
               @if($errors->has('image_alt_Text'))
               <p class="help-block"> {{ $errors->first('image_alt_Text') }} </p>
               @endif 
            </div>
          </div>
         <div class="form-group"> 
            {!! Form::label('sort', trans('quickadmin.gallery.fields.sort').':', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               {!! Form::text('sort', old('name'), ['class' => 'form-control', 'placeholder' => '']) !!}
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
             <a href="{{ route('administrator.gallery.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
             {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!} 
             <button type="button" class="previous btn btn-primary">@lang('quickadmin.previous')</button>
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