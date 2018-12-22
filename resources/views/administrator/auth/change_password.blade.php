@extends('administrator.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
   @lang('quickadmin.qa_change_password')
  </h1>
  <!--<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
    <li class="active">Here</li>
  </ol>-->
</section>
<!-- Main content -->
<section class="content">
  <!--------------------------
    | Your Page Content Here |
    -------------------------->
        <div class="row">
            <div class="col-md-12">
                @if (Session::has('message'))
                    <div class="alert alert-info">
                        <p>{{ Session::get('message') }}</p>
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
                @if(session('success'))
                <!-- If password successfully show message -->
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
               @endif
	       </div>
        </div>
        <div class="row">
            	<!-- Page content goes here-->
                <!-- left column -->
                <div class="col-md-9">
                  <!-- general form elements -->
                  <div class="box box-primary">
                    <!-- /.box-header -->
                    <!-- form start -->
                    {!! Form::open(['method' => 'PATCH','data-parsley-validate', 'route' => ['administrator.auth.change_password']]) !!}
                      <div class="box-body">
                        <div class="form-group">
                        {!! Form::label('current_password', trans('quickadmin.qa_current_password') .': * ', ['class' => 'control-label']) !!}
						{!! Form::password('current_password', ['class' => 'form-control', 'placeholder' => '','required'=>'']) !!}
						<p class="help-block"></p>
						@if($errors->has('current_password'))
							<p class="help-block">
								{{ $errors->first('current_password') }}
							</p>
						@endif
                       </div>
                       <div class="form-group">
                        {!! Form::label('new_password', trans('quickadmin.qa_new_password') .': * ', ['class' => 'control-label']) !!}
						{!! Form::password('new_password', ['class' => 'form-control', 'placeholder' => '','required'=>'']) !!}
						<p class="help-block"></p>
						@if($errors->has('new_password'))
							<p class="help-block">
								{{ $errors->first('new_password') }}
							</p>
						@endif
                        </div>
                        <div class="form-group">
                        {!! Form::label('new_password_confirmation', trans('quickadmin.qa_password_confirm') .': * ', ['class' => 'control-label']) !!}
						{!! Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => '','required'=>'','data-parsley-equalto'=>'#new_password','data-parsley-equalto-message'=>'Confirm password and new password should be same.']) !!}
						<p class="help-block"></p>
						@if($errors->has('new_password_confirmation'))
							<p class="help-block">
								{{ $errors->first('new_password_confirmation') }}
							</p>
						@endif
                        </div>
                     </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
                      </div>
                 	  {!! Form::close() !!}
                  </div>
                  <!-- /.box -->
                </div>
               <!-- End page content-->
          </div>
 </section>
<!-- /.content -->
@stop
