@extends('layouts.app')
@section('content')
    <section class="page-block">
        <div class="container">
            <div class="bg-white float-left w-100">
                <div class="page-block-inner">
                    <div class="page-block-heading bg-primary custom_section">
                        <h1 class="page-block-title">MEMBER REGISTER</h1>
                    </div>
                    <div class="page-block-body d-flex">

                        <div class="form-wrapper">
                            <div class="search-form common-form">

                                <div class="panel-body">
                                    <div class="col-md-12">
                                        @if (Session::has('success'))
                                            <div class="alert alert-success">
                                                <p>{{ Session::get('success') }}</p>
                                            </div>
                                        @endif
                                        @if ($errors->count() > 0)
                                            <div class="alert alert-danger">
                                                <strong>@lang('quickadmin.qa_whoops')</strong> @lang('quickadmin.qa_there_were_problems_with_input')
                                                : <br>
                                                <br>
                                                <ul class="list-unstyled">
                                                    @foreach($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {!! Form::open(['method' => 'POST','name'=>'member-form', 'id' =>'authentication_form', 'class'=>'row','data-parsley-validate','route' => ['member_register']]) !!}

                                {{ csrf_field() }}

                                <div class="form-group col-sm-4 col-xs-12">
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

                                <div class="form-group col-sm-4 col-xs-12">
                                    <div class="label">
                                        {!! Form::label('first_name', trans('quickadmin.member.fields.first_name').': * ') !!}
                                    </div>
                                    <div class="form-input">
                                        {!! Form::text('first_name', old('first_name'), ['placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup', 'data-parsley-maxlength' => '100' ]) !!}
                                        @if($errors->has('first_name'))
                                            <p class="help-block"> {{ $errors->first('first_name') }} </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group col-sm-4 col-xs-12">
                                    <div class="label">
                                        {!! Form::label('last_name', trans('quickadmin.member.fields.last_name').': * ') !!}
                                    </div>
                                    <div class="form-input">
                                        {!! Form::text('last_name', old('last_name'), ['placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup', 'data-parsley-maxlength' => '100']) !!}
                                        @if($errors->has('last_name'))
                                            <p class="help-block"> {{ $errors->first('last_name') }} </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group col-sm-6 col-xs-12">
                                    <div class="label">
                                        {!! Form::label('password', trans('quickadmin.member.fields.password').': *') !!}
                                    </div>
                                    <div class="form-input">
                                        {!! Form::password('password', ['id' => 'password', 'placeholder' => '', 'data-parsley-trigger'=>'keyup', 'required' => '']) !!}
                                        @if($errors->has('password'))
                                            <p class="help-block"> {{ $errors->first('password') }} </p>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group col-sm-6 col-xs-12">
                                    <div class="label">
                                        {!! Form::label('password_confirmation', trans('quickadmin.member.fields.password_confirmation').': *') !!}
                                    </div>
                                    <div class="form-input">
                                        {!! Form::password('password_confirmation', ['placeholder' => '', 'data-parsley-equalto' => '#password', 'data-parsley-trigger' => 'keyup', 'required' => '']) !!}
                                        @if($errors->has('password_confirmation'))
                                            <p class="help-block"> {{ $errors->first('password_confirmation') }} </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group col-sm-6 col-xs-12">
                                    <div class="label align-self-start">
                                        {!! Form::label('description_id', trans('quickadmin.member.fields.description_id').': *') !!}
                                    </div>
                                    <div class="form-input">
                                        <div class="custom-select">
                                        {!! Form::select('description_id[]', $description, old('description_id'), ['id' => 'description_id', 'multiple'  => true, 'required' => '' ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6 col-xs-12">
                                    <div class="label align-self-start">
                                        {!! Form::label('member_module', trans('quickadmin.member.fields.member_module').': *') !!}
                                    </div>
                                    <div class="form-input">
                                        <div class="custom-select">
                                            {!! Form::select('member_module[]', $moduleList, old('member_module'), ['id' => 'member_module', 'multiple' => true ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-sm-4 col-xs-12">
                                    <div class="label">
                                    {!! Form::label('affiliation', trans('quickadmin.member.fields.affiliation').': ') !!}
                                    </div>
                                    <div class="form-input">
                                        {!! Form::text('affiliation', old('affiliation'), ['placeholder' => '']) !!}
                                        @if($errors->has('affiliation'))
                                            <p class="help-block"> {{ $errors->first('affiliation') }} </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group col-sm-4 col-xs-12">
                                    <div class="label">
                                        {!! Form::label('address_1', trans('quickadmin.member.fields.address_1') .': ') !!}
                                    </div>
                                    <div class="form-input">
                                        {!! Form::text('address_1', old('address_1'), ['placeholder' => '']) !!}
                                        @if($errors->has('address_1'))
                                            <p class="help-block"> {{ $errors->first('address_1') }} </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group col-sm-4 col-xs-12">
                                    <div class="label">
                                        {!! Form::label('address_2', trans('quickadmin.member.fields.address_2') .': ') !!}
                                    </div>
                                    <div class="form-input">
                                        {!! Form::text('address_2', old('address_2'), ['placeholder' => '']) !!}
                                        @if($errors->has('address_2'))
                                            <p class="help-block"> {{ $errors->first('address_2') }} </p>
                                        @endif </div>
                                </div>

                                <div class="form-group col-sm-6 col-xs-12">
                                    <div class="label align-self-start">
                                        {!! Form::label('state', trans('quickadmin.member.fields.state') .': *') !!}
                                    </div>
                                    <div class="form-input">
                                        <div class="custom-select">
                                            {!! Form::select('state_id', $state, old('state_id'), ['onchange' => "getCityDropDownForRegistrationPage()", 'id' => 'state_id', 'required' => '']) !!}
                                            @if($errors->has('state_id'))
                                                <p class="help-block"> {{ $errors->first('state_id') }} </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6 col-xs-12">
                                    <div class="label align-self-start">
                                        {!! Form::label('city', trans('quickadmin.member.fields.city') .': *') !!}
                                    </div>
                                    <div class="form-input">
                                        <div class="custom-select">
                                            {!! Form::select('city_id', $cityList, old('city_id'), ['id' => 'city_id', "data-parsley-errors-container" => "#error_message_block", 'required' => '']) !!}
                                            @if($errors->has('city_id'))
                                                <p class="help-block"> {{ $errors->first('city_id') }} </p>
                                            @endif
                                        </div>
                                        <div id="error_message_block"></div>
                                    </div>
                                </div>

                                <div class="form-group col-sm-4 col-xs-12">
                                    <div class="label">
                                        {!! Form::label('url_key', trans('quickadmin.member.fields.url_key').': * ') !!}
                                    </div>
                                    <div class="form-input">
                                        {!! Form::text('url_key', old('url_key'), ['placeholder' => '', 'required' => '' ]) !!}
                                        @if($errors->has('url_key'))
                                            <p class="help-block"> {{ $errors->first('url_key') }} </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group col-sm-4 col-xs-12">
                                    <div class="label">
                                    {!! Form::label('zip', trans('quickadmin.member.fields.zip').': *') !!}
                                    </div>
                                    <div class="form-input">
                                        {!! Form::text('zip', old('zip'), ['data-parsley-type'=>'digits', 'placeholder' => '', 'data-parsley-trigger' => 'keyup', 'data-parsley-minlength' => '5', 'data-parsley-maxlength' => '5', 'required' => '' ]) !!}
                                        @if($errors->has('zip'))
                                            <p class="help-block"> {{ $errors->first('zip') }} </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group col-sm-4 col-xs-12">
                                    <div class="label">
                                    {!! Form::label('phone', trans('quickadmin.member.fields.phone').': ') !!}
                                    </div>
                                    <div class="form-input">
                                        {!! Form::text('phone', old('phone'), ['placeholder' => '']) !!}
                                        @if($errors->has('phone'))
                                            <p class="help-block"> {{ $errors->first('phone') }} </p>
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
                                    {!! Form::submit(trans('quickadmin.qa_submit'), ['class' => 'btn btn-danger']) !!}
                                    <a href="{{ route('member_login') }}"
                                       class="btn btn-primary btn-login">@lang('quickadmin.qa_go_back_to_login')</a>
                                </div>

                                {!! Form::close() !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
