@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.event.title_single') </h1>
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
       @if(isset($event))
        {!! Form::model($event, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 
        'route' => ['administrator.event.save']]) !!}

         <input type="hidden" name="id" value="{{$event->event_id}}" />
      @else
        
      {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'event-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.event.save']]) !!}

         <input type="hidden" name="id" value="" />            
      @endif
        <div class="tab-content ">
         <div class="tab-pane active" id="eventInfo">

          <div class="form-group"> 
            {!! Form::label('title', trans('quickadmin.event.fields.title').': * ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
               <p class="help-block"></p>
               @if($errors->has('title'))
               <p class="help-block"> {{ $errors->first('title') }} </p>
               @endif 
            </div>
          </div>

           <div class="form-group"> 
            {!! Form::label('event_date', trans('quickadmin.event.fields.event_date').': * ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               {!! Form::text('event_date', old('event_date'), ['class' => 'form-control', 'id'=>'event_date' ,'placeholder' => '', 'required' => '']) !!}
               <p class="help-block"></p>
               @if($errors->has('event_date'))
               <p class="help-block"> {{ $errors->first('event_date') }} </p>
               @endif 
            </div>
          </div>

          <div class="form-group"> 
              {!! Form::label('url_key', trans('quickadmin.event.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::text('url_key', old('url_key'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-pattern' => '^[a-z0-9-]*$' ]) !!}
                <p class="help-block"></p>
                @if($errors->has('url_key'))
                <p class="help-block"> {{ $errors->first('url_key') }} </p>
                @endif 
                <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.url_key') }} </div>
               </div>
          </div>

           <div class="form-group"> 
              <div class="col-sm-offset-3 col-sm-8">
                <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick="generateUrlKey(['title','event_date']);">@lang('quickadmin.btn_generate_url_key') </button>
                <p class="help-block"></p>
              </div>
            </div>

           <div class="form-group"> 
            {!! Form::label('content', trans('quickadmin.event.fields.content').': * ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               
              {{ Form::textarea('content', old('content'), ['id' => 'content', 'class' => 'form-control html_content_editor', 'placeholder' => '', 'required' => '']) }}
               <p class="help-block"></p>
               @if($errors->has('content'))
               <p class="help-block"> {{ $errors->first('content') }} </p>
               @endif 
            </div>
          </div>

          <div class="form-group"> {!! Form::label('image_id', trans('quickadmin.event.fields.select_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
          <div class="col-sm-8"> {!! Form::select('image_id', $images, old('image_id'), ['class' => 'form-control']) !!}
            <p class="help-block"></p>
          </div>
        </div>

          <div class="form-group"> 
            {!! Form::label('image_id', trans('quickadmin.event.fields.upload_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              {!! Form::file('event_image', null, ['class' => 'form-control']) !!}
            </div>
          </div>
          
          <div class="form-group"> 
            {!! Form::label('current_assigned_image', trans('quickadmin.event.fields.current_assigned_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              @if (!empty($event->Images->image_path))
                 {!! Form::image(URL::to('/') .'/'.$event->Images->image_path, 'Current Assigned Image', array( 'width' => 150, 'height' => 'auto' ),['class' => 'form-control']) !!}
              @else
                  No Image Assigned.
              @endif
              <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.image') }} </div>
            </div>
          </div>
          
       <!-- /.box --> 
        <div class="form-navigation">
          <div class="form-group">
           <div class="col-sm-offset-3 col-sm-8"> 
             <a href="{{ route('administrator.events.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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
<script type="text/javascript">
tinymceInit();
</script>
@endsection 