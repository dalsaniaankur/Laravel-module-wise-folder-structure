@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.user.title_single') </h1>
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
       @if(isset($user))
        {!! Form::model($user, ['method' => 'POST','class'=>'form-horizontal validation-form','data-parsley-validate', 
        'route' => ['administrator.user.save']]) !!}

           <input type="hidden" name="id" value="{{$user->user_id}}" />
      @else
        
	 	      {!! Form::open(['method' => 'POST','name'=>'user-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.user.save']]) !!}

         <input type="hidden" name="id" value="" />            
      @endif
        <div class="tab-content ">
         <div class="tab-pane active" id="userInfo">

          <div class="form-group"> 
            {!! Form::label('first_name', trans('quickadmin.user.fields.first_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '','data-parsley-trigger' => 'keyup', 'data-parsley-maxlength' => '100']) !!}
               <p class="help-block"></p>
               @if($errors->has('first_name'))
               <p class="help-block"> {{ $errors->first('first_name') }} </p>
               @endif 
            </div>
          </div>
          
          <div class="form-group"> 
            {!! Form::label('last_name', trans('quickadmin.user.fields.last_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               {!! Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup', 'data-parsley-maxlength' => '100']) !!}
               <p class="help-block"></p>
               @if($errors->has('last_name'))
               <p class="help-block"> {{ $errors->first('last_name') }} </p>
               @endif 
            </div>
          </div>
		  <div class="form-group"> 
            {!! Form::label('email', trans('quickadmin.user.fields.email').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup', 'data-parsley-maxlength' => '100']) !!}
                <p class="help-block"></p>
                @if($errors->has('email'))
                <p class="help-block"> {{ $errors->first('email') }} </p>
                @endif 
              </div>
          </div>
          <div class="form-group"> {!! Form::label('password', trans('quickadmin.user.fields.password').': * ', [ 'class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => '', 'data-parsley-trigger'=>'keyup']) !!}
                <p class="help-block"></p>
                @if($errors->has('password'))
                <p class="help-block"> {{ $errors->first('password') }} </p>
                @endif 
              </div>
          </div>
          <div class="form-group"> {!! Form::label('password_confirmation', trans('quickadmin.user.fields.password_confirmation').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => '', 'data-parsley-equalto' => '#password', 'data-parsley-trigger' => 'keyup']) !!}
                <p class="help-block"></p>
                @if($errors->has('password_confirmation'))
                <p class="help-block"> {{ $errors->first('password_confirmation') }} </p>
                @endif 
              </div>
          </div>
           <div class="form-group"> 
            {!! Form::label('link_id', trans('quickadmin.user.fields.link_id').': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
                @foreach ($securitymodule as $key => $value)
                   @php ($is_checked = false)
                   @if (!empty($user_links) && in_array($key,$user_links))
                      @php ($is_checked = true)
                   @endif
                  {!! Form::checkbox('link_id[]', $key, $is_checked, array('id' => 'link_id_'.$key)) !!}
                  {!! Form::label('link_id_'.$key, $value) !!}<br>
                @endforeach
            </div>
          </div>
        
       <!-- /.box --> 
        <div class="form-navigation">
          <div class="form-group">
           <div class="col-sm-offset-3 col-sm-8"> 
             <a href="{{ route('administrator.users.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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