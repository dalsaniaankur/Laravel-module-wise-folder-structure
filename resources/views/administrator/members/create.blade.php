@extends('administrator.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> @lang('quickadmin.member.title_single') </h1>
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
                    @if(isset($member))
                        {!! Form::model($member, ['method' => 'POST','class'=>'form-horizontal validation-form','data-parsley-validate',
                        'route' => ['administrator.members.save']]) !!}
                        <input type="hidden" name="id" value="{{$member->member_id}}" />
                    @else
                        {!! Form::open(['method' => 'POST','name'=>'member-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.members.save']]) !!}
                        <input type="hidden" name="id" value="" />
                    @endif
                    <div class="tab-content ">
                        <div class="tab-pane active" id="userInfo">

                            <div class="form-group"> {!! Form::label('email', trans('quickadmin.member.fields.email').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('email'))
                                        <p class="help-block"> {{ $errors->first('email') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('password', trans('quickadmin.member.fields.password').': ', [ 'class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => '', 'data-parsley-trigger'=>'keyup']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('password'))
                                        <p class="help-block"> {{ $errors->first('password') }} </p>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group"> {!! Form::label('password_confirmation', trans('quickadmin.member.fields.password_confirmation').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => '', 'data-parsley-equalto' => '#password', 'data-parsley-trigger' => 'keyup']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('password_confirmation'))
                                        <p class="help-block"> {{ $errors->first('password_confirmation') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('first_name', trans('quickadmin.member.fields.first_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup', 'data-parsley-maxlength' => '100' ]) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('first_name'))
                                        <p class="help-block"> {{ $errors->first('first_name') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('last_name', trans('quickadmin.member.fields.last_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup', 'data-parsley-maxlength' => '100']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('last_name'))
                                        <p class="help-block"> {{ $errors->first('last_name') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('description_id', trans('quickadmin.member.fields.description_id').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    @foreach ($description as $key => $value)
                                        @php ($is_checked = false)
                                        @if (!empty($member->description_id) && in_array($key, $member->description_id))
                                            @php ($is_checked = true)
                                        @endif
                                        {!! Form::checkbox('description_id[]', $key, $is_checked, array('id' => 'description_'.$key)) !!}
                                        {!! Form::label('description_'.$key, $value) !!}<br>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('member_module', trans('quickadmin.member.fields.member_module').': *', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    @foreach ($moduleList as $key => $value)
                                        @php ($is_checked = false)

                                        @if (in_array($key, $memberModuleList))
                                            @php ($is_checked = true)
                                        @endif
                                        {!! Form::checkbox('member_module[]', $key, $is_checked, array('id' => 'member_module_'.$key)) !!}
                                        {!! Form::label('member_module_'.$key, $value) !!}<br>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('affiliation', trans('quickadmin.member.fields.affiliation').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('affiliation', old('affiliation'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('affiliation'))
                                        <p class="help-block"> {{ $errors->first('affiliation') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('address_1', trans('quickadmin.member.fields.address_1') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::text('address_1', old('address_1'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('address_1'))
                                        <p class="help-block"> {{ $errors->first('address_1') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('address_2', trans('quickadmin.member.fields.address_2') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::text('address_2', old('address_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('address_2'))
                                        <p class="help-block"> {{ $errors->first('address_2') }} </p>
                                    @endif </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('state_id', trans('quickadmin.showcase_or_prospect.fields.state_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('state_id', $state, old('state_id'), ['class' => 'form-control','onchange' => "getCityDropDown()", 'id' => 'state_id', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('state_id'))
                                        <p class="help-block"> {{ $errors->first('state_id') }} </p>
                                    @endif
                                    <div class="custom-city-error-message"><span>trans('quickadmin.qa_city_custom_error_msg')</span></div>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('city_id', trans('quickadmin.showcase_or_prospect.fields.city') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8 city">
                                    {!! Form::select('city_id', $city, old('city_id'), ['id' => 'city_id', 'class' => 'form-control', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('city_id'))
                                        <p class="help-block"> {{ $errors->first('city_id') }} </p>
                                    @endif
                                    <div class="custom-city-error-message"><span>@lang('quickadmin.qa_city_custom_error_msg')</span></div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('url_key', trans('quickadmin.member.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
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
                                    <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick="generateUrlKey(['first_name','last_name','city_id','state_id']);">@lang('quickadmin.btn_generate_url_key') </button>
                                    <p class="help-block"></p>
                                </div>
                            </div>


                            <div class="form-group"> {!! Form::label('zip', trans('quickadmin.member.fields.zip').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::text('zip', old('zip'), ['class' => 'form-control','data-parsley-type'=>'digits', 'placeholder' => '', 'data-parsley-trigger' => 'keyup', 'data-parsley-minlength' => '5', 'data-parsley-maxlength' => '5']) !!}

                                    <p class="help-block"></p>
                                    @if($errors->has('zip'))
                                        <p class="help-block"> {{ $errors->first('zip') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('phone', trans('quickadmin.member.fields.phone').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::text('phone', old('phone'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('phone'))
                                        <p class="help-block"> {{ $errors->first('phone') }} </p>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <!-- /.box -->
                        <div class="form-navigation">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <a href="{{ route('administrator.members.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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
