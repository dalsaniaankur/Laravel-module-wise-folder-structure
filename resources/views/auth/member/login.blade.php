@extends('layouts.app')
@section('content')
    <section class="page-block">
        <div class="container">
            <div class="bg-white float-left w-100">
                <div class="page-block-inner">
                    <div class="page-block-heading bg-primary custom_section">
                        <h1 class="page-block-title">MEMBER LOGIN</h1>
                    </div>
                    <div class="page-block-body d-flex">

                        <div class="form-wrapper">
                            <div class="search-form common-form" style="max-width: 500px;">

                                <div class="login-box-body">
                                    @if (count($errors) > 0)
                                        <div class="alert alert-danger">
                                            <strong>@lang('quickadmin.qa_whoops')</strong> @lang('quickadmin.qa_there_were_problems_with_input')
                                            : <br>
                                            <br>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    {!! Form::open(['method' => 'POST','name'=>'member-form', 'id' =>'authentication_form', 'class'=>'row','data-parsley-validate','route' => ['member_login']]) !!}
                                    {{ csrf_field() }}
                                    <div class="form-group col-sm-12 col-xs-12">
                                        <div class="label">
                                            {!! Form::label('email', trans('quickadmin.member.fields.email').': * ') !!}
                                        </div>

                                        <div class="form-input">
                                            {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                            @if($errors->has('email'))
                                                <p class="help-block"> {{ $errors->first('email') }} </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12 col-xs-12">
                                        <div class="label">
                                            {!! Form::label('password', trans('quickadmin.member.fields.password').': * ') !!}
                                        </div>
                                        <div class="form-input">
                                            {!! Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => '', 'data-parsley-trigger'=>'keyup', 'required' => '']) !!}
                                            @if($errors->has('password'))
                                                <p class="help-block"> {{ $errors->first('password') }} </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12 col-xs-12">
                                        <div class="label">
                                            {!! Form::label('google_captcha', trans('quickadmin.google_captcha').': ') !!}
                                        </div>
                                        <div class="form-input">
                                            {!! app('captcha')->display() !!}
                                            @if($errors->has('g-recaptcha-response'))
                                                <p class="help-block"> {{ $errors->first('g-recaptcha-response') }} </p>
                                            @endif
                                            <p class="help-block"></p>
                                            <!--Google Captcha Validation Message-->
                                            <div class="clear-both google-captcha-error-msg"></div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12 col-xs-12">
                                        <div class="form-input">
                                            <label class="ct-input">
                                                {!! Form::checkbox('remember', false ,false , array('id' => 'remember', 'class'=>'ct-input')) !!}
                                                <div class="ct-label">Remember me</div>
                                            </label>
                                        </div>
                                        <a class="control-label"
                                           href="{{ route('member_password.reset') }}">@lang('quickadmin.qa_forgot_password')</a>
                                    </div>

                                        <div class="form-group col-sm-12 col-xs-12">
                                            {!! Form::Submit(trans('quickadmin.qa_login'), ['class' => 'btn btn-danger btn-flat']) !!}
                                            <a href="{{ route('member_register') }}"
                                               class="btn btn-primary btn-flat">@lang('quickadmin.qa_registration')</a>
                                        </div>

                                        {!! Form::close() !!}
                                        <!-- <a href="{{ route('administrator.password.reset')}}">@lang('quickadmin.qa_forgot_password')</a> -->
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection