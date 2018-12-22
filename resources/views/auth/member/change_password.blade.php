@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    section class="page-block">
    <div class="container">
        <div class="bg-white float-left w-100">
            <div class="page-block-inner">
                <div class="page-block-heading bg-primary custom_section">
                    <h1 class="page-block-title">CHANGE PASSWORD</h1>
                </div>
                <div class="page-block-body d-flex">
                    <div class="page-block-left">

                        <div class="row">
                            <div class="col-md-12">
                                @if (Session::has('message'))
                                    <div class="alert alert-info">
                                        <p>{{ Session::get('message') }}</p>
                                    </div>
                                @endif
                                @if ($errors->count() > 0)
                                    <div class="alert alert-danger"> <strong>@lang('quickadmin.qa_whoops')</strong> @lang('quickadmin.qa_there_were_problems_with_input'): <br>
                                        <br>
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
                                    <!-- form start -->
                                    {!! Form::open(['method' => 'PATCH','data-parsley-validate','route' => ['member_change_password']]) !!}
                                    <div class="box-body">
                                        <div class="form-group">
                                            {!! Form::label('current_password', trans('quickadmin.qa_current_password'), ['class' => 'control-label']) !!}
                                            {!! Form::password('current_password', ['class' => 'form-control', 'placeholder' => '','required'=>'']) !!}
                                            <p class="help-block"></p>
                                            @if($errors->has('current_password'))
                                                <p class="help-block">
                                                    {{ $errors->first('current_password') }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('new_password', trans('quickadmin.qa_new_password'), ['class' => 'control-label']) !!}
                                            {!! Form::password('new_password', ['class' => 'form-control', 'placeholder' => '','required'=>'']) !!}
                                            <p class="help-block"></p>
                                            @if($errors->has('new_password'))
                                                <p class="help-block">
                                                    {{ $errors->first('new_password') }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('new_password_confirmation', trans('quickadmin.qa_password_confirm'), ['class' => 'control-label']) !!}
                                            {!! Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => '','required'=>'','data-parsley-equalto'=>'#new_password','data-parsley-equalto-message'=>'New Password and confirm password should be same.']
                                            ) !!}
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


                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
@stop
