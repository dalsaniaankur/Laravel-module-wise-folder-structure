@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.team_group.title_single') </h1>
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
        <ul class="nav nav-tabs">
        </ul>
        <!-- form start --> 
       @if(isset($teamgroup))
        {!! Form::model($teamgroup, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate',
        'route' => ['administrator.team_group.save']]) !!}
         <input type="hidden" name="id" value="{{$teamgroup->team_group_id}}" />
      @else
         {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'gallery-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.team_group.save']]) !!}
         <input type="hidden" name="id" value="" />            
      @endif
        <div class="tab-content ">
         <div class="tab-pane active" id="galleryInfo">
          <div class="form-group"> 
            {!! Form::label('name', trans('quickadmin.team_group.fields.name').': * ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
               <p class="help-block"></p>
               @if($errors->has('name'))
               <p class="help-block"> {{ $errors->first('name') }} </p>
               @endif 
            </div>
          </div>
          <div class="form-group">
           {!! Form::label('image_id',trans('quickadmin.team_group.fields.select_image') .':  ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
            {!! Form::select('image_id',$images, old('image_id'), ['class' => 'form-control']) !!}
            <p class="help-block"></p>
            @if($errors->has('image_id'))
            <p class="help-block"> {{ $errors->first('image_id') }} </p>
            @endif 
          </div>
        </div>
          <div class="form-group"> 
            {!! Form::label('image_id', trans('quickadmin.team_group.fields.upload_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              {!! Form::file('gallery_image', null, ['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="form-group"> 
            {!! Form::label('current_assigned_image', trans('quickadmin.team_group.fields.current_assigned_image') .': * ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              @if (!empty($teamgroup->Images->image_path))
                 {!! Form::image(URL::to('/') .'/'.$teamgroup->Images->image_path, 'Current Assigned Image', array( 'width' => 150, 'height' => 'auto' ),['class' => 'form-control']) !!}
              @else
                  No Image Assigned.
              @endif
              <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.image') }} </div>
            </div>
         </div>
         
         @if(isset($teamgroup))
         <div class="form-group"> 
          {!! Form::label('team_id', trans('quickadmin.team_group.fields.team_id') .':', ['class' => 'col-sm-3 control-label']) !!}
          <div class="col-sm-8"> {!! Form::select('team_id',$teams, old('team_id'), ['class' => 'form-control']) !!}
         <p class="help-block"></p>
            @if($errors->has('team_id'))
              <p class="help-block"> {{ $errors->first('member_id') }} </p>
            @endif 
          </div>
        </div>
        <div class="form-group"> 
            {!! Form::label('sort', trans('quickadmin.team_group.fields.sort').':', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               {!! Form::text('sort', old('name'), ['class' => 'form-control', 'placeholder' => '']) !!}
               <p class="help-block"></p>
               @if($errors->has('sort'))
               <p class="help-block"> {{ $errors->first('sort') }} </p>
               @endif 
            </div>
        </div>
       @if(isset($teamgroup))
        @if(count($teamgroup->teams) > 0)
        	@php($count=0)
           @foreach ($teamgroup->teams as $team)
          <div class="form-group"> 
          	@if($count==0)
          	  {!! Form::label('teams', trans('quickadmin.team_group.fields.teams').':', ['class' => 'col-sm-3 control-label']) !!}
            @else
             <div class="col-sm-3">
             </div>
            @endif
            @php($count++)
             <div class="col-sm-4">
          	  {{ $team->name }} <br/>
             </div>
             <div class="col-sm-2">
              <a href="{{ url('administrator/team_group_unlink/'.$teamgroup->team_group_id.'/'.$team->team_id)}}"
              class="btn btn-xs btn-danger" title="">
               Unlink
              </a>
             </div>
           </div>    
           @endforeach 
          @endif 
        @endif 
        @endif          
         <!-- /.box --> 
         <div class="form-navigation">
          <div class="form-group">
           <div class="col-sm-offset-3 col-sm-8"> 
             <a href="{{ route('administrator.team_group.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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