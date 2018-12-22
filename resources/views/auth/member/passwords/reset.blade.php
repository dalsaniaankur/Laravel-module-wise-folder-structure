@extends('layouts.app')
@section('content')
    <section class="page-block">
        <div class="container">
            <div class="bg-white float-left w-100">
                <div class="page-block-inner">
                    <div class="page-block-heading bg-primary custom_section">
                        <h1 class="page-block-title">RESET PASSWORD</h1>
                    </div>
                    <div class="page-block-body d-flex">

                        <div class="form-wrapper">
                            <div class="search-form common-form" style="max-width: 500px;">

                                <div class="login-box-body">
                                    @if (session('status'))
                                        <div class="alert alert-success"> {{ session('status') }} </div>
                                    @endif

                                    @if (count($errors) > 0)
                                        <div class="alert alert-danger"> <strong>@lang('quickadmin.qa_whoops')</strong> @lang('quickadmin.qa_there_were_problems_with_input'): <br>
                                            <br>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    {!! Form::open(['method' => 'POST','name'=>'reset-form', 'id' =>'authentication_form', 'class'=>'row','data-parsley-validate','route' => ['member_password.reset']]) !!}

                                    {{ csrf_field() }}
                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="form-group col-sm-12 col-xs-12">
                                        <div class="label">
                                            {!! Form::label('email', trans('quickadmin.email').': * ') !!}
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
                                            {!! Form::label('password_confirmation', trans('quickadmin.member.fields.password_confirmation').': * ') !!}
                                        </div>

                                        <div class="form-input">
                                            {!! Form::password('password_confirmation', ['id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => '', 'data-parsley-equalto' => '#password', 'data-parsley-trigger' => 'keyup', 'required' => '']) !!}
                                            @if($errors->has('password_confirmation'))
                                                <p class="help-block"> {{ $errors->first('password_confirmation') }} </p>
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
                                        {!! Form::Submit('Reset Password', ['class' => 'btn btn-danger btn-flat']) !!}
                                        <a href="{{ route('member_login') }}" class="btn btn-primary btn-flat">@lang('quickadmin.qa_login')</a>
                                    </div>

                                {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection