@extends('member.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> @lang('quickadmin.tryout.title_single') </h1>
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
                        <li class="active">
                            <a href="#teamsInfo" data-toggle="tab">{{trans('quickadmin.tryout-tab.basic-info')}}</a>
                        </li>
                        <li>
                            <a href="#extra_details" data-toggle="tab">{{trans('quickadmin.tryout-tab.extra-details')}}</a>
                        </li>
                    </ul>
                    <!-- form start -->
                    @if(isset($tryout))
                        {!! Form::model($tryout, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 'url' => 'member/tryout/save/'.$team_id ]) !!}

                        <input type="hidden" name="id" value="{{$tryout->tryout_id}}" />
                    @else
                        {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'team-form', 'class'=>'form-horizontal validation-form','data-parsley-validate', 'url' => 'member/tryout/save/'.$team_id]) !!}
                        <input type="hidden" name="id" value="" />
                    @endif

                    <div class="tab-content ">
                        <div class="tab-pane active" id="teamsInfo">

                            <input type="hidden" name="submitted_by_id" value="{{ $submitted_by_id }}" />

                            <div class="form-group">
                                {!! Form::label('team_id', trans('quickadmin.tryout.fields.team_name') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('team_id', $team, old('team_id', $team_id), ['class' => 'form-control']) !!}
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('tryout_name', trans('quickadmin.tryout.fields.tryout_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('tryout_name', old('tryout_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('tryout_name'))
                                        <p class="help-block"> {{ $errors->first('tryout_name') }} </p>
                                    @endif
                                </div>
                            </div>



                            <div class="form-group">
                                {!! Form::label('contact_name', trans('quickadmin.tryout.fields.contact_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('contact_name', old('contact_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('contact_name'))
                                        <p class="help-block"> {{ $errors->first('contact_name') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('date', trans('quickadmin.tryout.fields.date').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('dates', $tryoutDate, ['class' => 'form-control' ,'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('address_1', trans('quickadmin.tryout.fields.address_1').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('address_1', old('address_1'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('address_1'))
                                        <p class="help-block"> {{ $errors->first('address_1') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('address_2', trans('quickadmin.tryout.fields.address_2').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('address_2', old('address_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('address_2'))
                                        <p class="help-block"> {{ $errors->first('address_2') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('location', trans('quickadmin.tryout.fields.location').':', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('location', old('location'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('location'))
                                        <p class="help-block"> {{ $errors->first('location') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('state_id', trans('quickadmin.tryout.fields.state_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::select('state_id', $state, old('state_id'), ['class' => 'form-control','onchange' => "getCityDropDown()", 'id' => 'state_id', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('state_id'))
                                        <p class="help-block"> {{ $errors->first('state_id') }} </p>
                                    @endif </div>
                            </div>
                            <div class="form-group"> {!! Form::label('city_id', trans('quickadmin.tryout.fields.city') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8 city"> {!! Form::select('city_id', $city, old('city_id'), ['id' => 'city_id', 'class' => 'form-control', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('city_id'))
                                        <p class="help-block"> {{ $errors->first('city_id') }} </p>
                                    @endif
                                    <div class="custom-city-error-message"><span>@lang('quickadmin.qa_city_custom_error_msg')</span></div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('url_key', trans('quickadmin.tryout.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
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
                                    <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick="generateUrlKey(['tryout_name','city_id','state_id','date_rang']);">@lang('quickadmin.btn_generate_url_key') </button>
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('zip', trans('quickadmin.tryout.fields.zip').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::text('zip', old('zip'), ['class' => 'form-control','data-parsley-type'=>'digits', 'data-parsley-trigger' => 'keyup', 'placeholder' => '', 'data-parsley-minlength' => '5', 'data-parsley-maxlength' => '5', 'required' => '']) !!}

                                    <p class="help-block"></p>
                                    @if($errors->has('zip'))
                                        <p class="help-block"> {{ $errors->first('zip') }} </p>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="extra_details">

                            <div class="form-group">
                                {!! Form::label('phone_number', trans('quickadmin.tryout.fields.phone_number').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::text('phone_number', old('phone_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('phone_number'))
                                        <p class="help-block"> {{ $errors->first('phone_number') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('email', trans('quickadmin.tryout.fields.email').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                </div>
                            </div>


                            <div class="form-group">
                                {!! Form::label('longitude', trans('quickadmin.tryout.fields.longitude').': *', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('longitude', old('longitude'), ['class' => 'form-control', 'id' => 'longitude', 'placeholder' => '','required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('longitude'))
                                        <p class="help-block"> {{ $errors->first('longitude') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('latitude', trans('quickadmin.tryout.fields.latitude').': *', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('latitude', old('latitude'), ['class' => 'form-control', 'id' => 'latitude', 'placeholder' => '','required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('latitude'))
                                        <p class="help-block"> {{ $errors->first('latitude') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick='getGoogleLongitudeLatitude();'>@lang('quickadmin.btn_get_google_longitude_latitude') </button>
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('age_group_id', trans('quickadmin.tryout.fields.age_group_id').': *', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-8">

                                    @foreach ($agegroup as $key => $value)
                                        @php ($is_checked = false)

                                        @if (!empty($tryout->age_group_id) && in_array($key, $tryout->age_group_id))
                                            @php ($is_checked = true)
                                        @endif

                                        <div class="form-group">

                                            <div class="col-sm-3 col-xs-12 col-lg-3 col-md-3">
                                                {!! Form::checkbox('age_group_id[]', $key, $is_checked, array('id' => 'age_group'.$key)) !!}
                                                {!! Form::label('age_group'.$key, $value) !!}
                                            </div>

                                            <div class="col-sm-9 col-xs-12 col-lg-9 col-md-9">
                                                {!! Form::label('age_group_position_'.$key, trans('quickadmin.tryout.fields.position').':') !!}
                                                {!! Form::select('age_group_position_'.$key, $positionList, old('age_group_position_'.$key) ,array('multiple'=>'multiple','name'=>'age_group_position_'.$key.'[]', "size" => count($positionList), 'class' => 'form-control')) !!}
                                            </div>

                                        </div>

                                    @endforeach
                                </div>

                            </div>

                            <div class="form-group">
                                {!! Form::label('information', trans('quickadmin.tryout.fields.information').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {{ Form::textarea('information', old('information'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('attachment_name_1', trans('quickadmin.tryout.fields.attachment_name_1') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::file('attachment_name_1', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            @if (!empty($tryout->attachment_path_1)  && $tryout->attachment_path_1 != ' ')
                                <div class="form-group">
                                    {!! Form::label('current_assigned_image', trans('quickadmin.tryout.fields.current_attachment_name_1') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                    <div class="col-sm-8">
                                        {{ $tryout->attachment_name_1 }}
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                {!! Form::label('attachment_name_2', trans('quickadmin.tryout.fields.attachment_name_2') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::file('attachment_name_2', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            @if (!empty($tryout->attachment_path_2) && $tryout->attachment_path_2 != ' ')
                                <div class="form-group">
                                    {!! Form::label('current_assigned_image', trans('quickadmin.tryout.fields.current_attachment_name_2') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                    <div class="col-sm-8">
                                        {{ $tryout->attachment_name_2 }}
                                    </div>
                                </div>
                            @endif

                        </div>
                        <!-- /.box -->
                        <div class="form-navigation">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <a href="{{ url('member/tryout') }}/{{ $team_id}} " class="btn btn-primary">@lang('quickadmin.cancel')</a>
                                    {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
                                    <button type="button" class="previous btn btn-primary">@lang('quickadmin.previous')</button>
                                    <button type="button" class="next btn btn-primary">@lang('quickadmin.next') </button>
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