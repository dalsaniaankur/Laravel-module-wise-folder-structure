@extends('member.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> @lang('quickadmin.teams.title_single') </h1>
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
                            <a href="#teamsInfo" data-toggle="tab">{{trans('quickadmin.teams-tab.basic-info')}}</a>
                        </li>
                        <li>
                            <a href="#extra_details" data-toggle="tab">{{trans('quickadmin.teams-tab.extra-details')}}</a>
                        </li>
                    </ul>
                    <!-- form start -->
                    @if(isset($team))
                        {!! Form::model($team, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate',
                        'route' => ['member.team_save.save']]) !!}
                        <input type="hidden" name="id" value="{{$team->team_id}}" />
                        <input type="hidden" name="approval_status" value="{{ $team->approval_status }}" />

                    @else
                        {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'team-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['member.team_save.save']]) !!}
                        <input type="hidden" name="id" value="" />
                        <input type="hidden" name="approval_status" value="{{ $defaultApprovalStatus }}" />
                    @endif

                    <div class="tab-content ">
                        <div class="tab-pane active" id="teamsInfo">

                            <input type="hidden" name="submitted_by_id" value="{{ $submitted_by_id }}" />

                            <div class="form-group">
                                {!! Form::label('name', trans('quickadmin.teams.fields.name').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('name'))
                                        <p class="help-block"> {{ $errors->first('name') }} </p>
                                    @endif
                                </div>
                            </div>

                            @if(isset($team->team_id))
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-8">
                                        <a href="{{url('member/tryout/'.$team->team_id)}}">
                                            <button type="button" class="btn btn-primary">@lang('quickadmin.teams.add_tryout')</button>
                                        </a>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                {!! Form::label('contact_name', trans('quickadmin.teams.fields.contact_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('contact_name', old('contact_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('contact_name'))
                                        <p class="help-block"> {{ $errors->first('contact_name') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('address_1', trans('quickadmin.teams.fields.address_1').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('address_1', old('address_1'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('address_1'))
                                        <p class="help-block"> {{ $errors->first('address_1') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('address_2', trans('quickadmin.teams.fields.address_2').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('address_2', old('address_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('address_2'))
                                        <p class="help-block"> {{ $errors->first('address_2') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('state_id', trans('quickadmin.teams.fields.state_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::select('state_id', $state, old('state_id'), ['class' => 'form-control','onchange' => "getCityDropDown()", 'id' => 'state_id', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('state_id'))
                                        <p class="help-block"> {{ $errors->first('state_id') }} </p>
                                    @endif </div>
                            </div>
                            <div class="form-group"> {!! Form::label('city_id', trans('quickadmin.teams.fields.city') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8 city"> {!! Form::select('city_id', $city, old('city_id'), ['id' => 'city_id', 'class' => 'form-control', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('city_id'))
                                        <p class="help-block"> {{ $errors->first('city_id') }} </p>
                                    @endif
                                    <div class="custom-city-error-message"><span>@lang('quickadmin.qa_city_custom_error_msg')</span></div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('url_key', trans('quickadmin.teams.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
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
                                    <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick="generateUrlKey(['name','city_id','state_id']);">@lang('quickadmin.btn_generate_url_key') </button>
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('zip', trans('quickadmin.teams.fields.zip').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::text('zip', old('zip'), ['class' => 'form-control','data-parsley-type'=>'digits', 'data-parsley-trigger' => 'keyup', 'placeholder' => '', 'data-parsley-minlength' => '5', 'data-parsley-maxlength' => '5','required' => '']) !!}

                                    <p class="help-block"></p>
                                    @if($errors->has('zip'))
                                        <p class="help-block"> {{ $errors->first('zip') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('phone_number', trans('quickadmin.teams.fields.phone_number').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::text('phone_number', old('phone_number'), ['class' => 'form-control', 'placeholder' => '','required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('phone_number'))
                                        <p class="help-block"> {{ $errors->first('phone_number') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group"> {!! Form::label('email', trans('quickadmin.teams.fields.email').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '','required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('email'))
                                        <p class="help-block"> {{ $errors->first('email') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group"> {!! Form::label('agree_to_recevie_email_updates', trans('quickadmin.teams.fields.agree_to_recevie_email_updates').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::checkbox('agree_to_recevie_email_updates', null,  old('agree_to_recevie_email_updates'), array('id' => 'agree_to_recevie_email_updates')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('website_url', trans('quickadmin.teams.fields.website_url').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('website_url', old('website_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('website_url'))
                                        <p class="help-block"> {{ $errors->first('website_url') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('blog_url', trans('quickadmin.teams.fields.blog_url').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('blog_url', old('blog_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('blog_url'))
                                        <p class="help-block"> {{ $errors->first('blog_url') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('age_group_id', trans('quickadmin.teams.fields.age_group_id').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    @foreach ($age_group as $key => $value)
                                        @php ($is_checked = false)
                                        @if (!empty($team->age_group_id) && in_array($key, $team->age_group_id))
                                            @php ($is_checked = true)
                                        @endif
                                        {!! Form::checkbox('age_group_id[]', $key, $is_checked, array('id' => 'age_group'.$key)) !!}
                                        {!! Form::label('age_group'.$key, $value) !!}<br>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('about', trans('quickadmin.teams.fields.about').':  ', ['class' => 'col-sm-3 control-label']) !!}          <div class="col-sm-8">
                                    {{ Form::textarea('about', old('about'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                                    <p class="help-block"></p>
                                    @if($errors->has('about'))
                                        <p class="help-block"> {{ $errors->first('about') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('general_information', trans('quickadmin.teams.fields.general_information').':  ', ['class' => 'col-sm-3 control-label']) !!}          <div class="col-sm-8">
                                    {{ Form::textarea('general_information', old('general_information'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                                    <p class="help-block"></p>
                                    @if($errors->has('general_information'))
                                        <p class="help-block"> {{ $errors->first('general_information') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('achievements', trans('quickadmin.teams.fields.achievements').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">

                                    {{ Form::textarea('achievements', old('achievements'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                                    <p class="help-block"></p>
                                    @if($errors->has('achievements'))
                                        <p class="help-block"> {{ $errors->first('achievements') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('notable_alumni', trans('quickadmin.teams.fields.notable_alumni').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {{ Form::textarea('notable_alumni', old('notable_alumni'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                                    <p class="help-block"></p>
                                    @if($errors->has('notable_alumni'))
                                        <p class="help-block"> {{ $errors->first('notable_alumni') }} </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="extra_details">
                            <div class="form-group">
                                {!! Form::label('facebook_url', trans('quickadmin.teams.fields.facebook_url').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('facebook_url', old('facebook_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('facebook_url'))
                                        <p class="help-block"> {{ $errors->first('facebook_url') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('twitter_url', trans('quickadmin.teams.fields.twitter_url').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('twitter_url', old('twitter_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('twitter_url'))
                                        <p class="help-block"> {{ $errors->first('twitter_url') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('youtube_video_id_1', trans('quickadmin.teams.fields.youtube_video_id_1').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('youtube_video_id_1', old('youtube_video_id_1'), ['class' => 'form-control', 'placeholder' => '' ]) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('youtube_video_id_1'))
                                        <p class="help-block"> {{ $errors->first('youtube_video_id_1') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('youtube_video_id_2', trans('quickadmin.teams.fields.youtube_video_id_2').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('youtube_video_id_2', old('youtube_video_id_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('youtube_video_id_2'))
                                        <p class="help-block"> {{ $errors->first('youtube_video_id_2') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group"> {!! Form::label('is_show_advertise', trans('quickadmin.teams.fields.is_show_advertise') .':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::select('is_show_advertise', $show_advertise, old('is_show_advertise'), ['class' => 'form-control']) !!}       <p class="help-block"></p>
                                    @if($errors->has('is_show_advertise'))
                                        <p class="help-block"> {{ $errors->first('is_show_advertise') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('longitude', trans('quickadmin.teams.fields.longitude').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('longitude', old('longitude'), ['class' => 'form-control', 'id' => 'longitude', 'placeholder' => '','required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('longitude'))
                                        <p class="help-block"> {{ $errors->first('longitude') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('latitude', trans('quickadmin.teams.fields.latitude').': * ', ['class' => 'col-sm-3 control-label']) !!}
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

                            <div class="form-group"> {!! Form::label('image_id', trans('quickadmin.teams.fields.select_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('image_id', $images, old('image_id'), ['class' => 'form-control']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('image_id'))
                                        <p class="help-block"> {{ $errors->first('image_id') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('image_id', trans('quickadmin.teams.fields.upload_image') .':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::file('teams_image', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('current_assigned_image', trans('quickadmin.teams.fields.current_assigned_image') .':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    @if (!empty($team->Images->image_path))
                                        {!! Form::image(URL::to('/') .'/'.$team->Images->image_path, 'Current Assigned Image', array( 'width' => 150, 'height' => 'auto' ),['class' => 'form-control']) !!}
                                    @else
                                        No Image Assigned.
                                    @endif
                                    <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.teams_image') }} </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('is_active', trans('quickadmin.teams.fields.is_active').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::checkbox('is_active', null,  old('is_active'), array('id' => 'is_active')) !!}
                                </div>
                            </div>
                            <div class="form-group"> {!! Form::label('is_send_email_to_user', trans('quickadmin.teams.fields.is_send_email_to_user').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::checkbox('is_send_email_to_user', null,  old('is_send_email_to_user'), array('id' => 'is_send_email_to_user')) !!}
                                </div>
                            </div>
                        </div>
                        <!-- /.box -->
                        <div class="form-navigation">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <a href="{{ route('member.teams.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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
    <script>

    </script>
@endsection
@section('javascript')
    <script>
        var _token = "{{ csrf_token() }}";
        var get_google_longitude_latitude_url ="{{ URL::to('member/get_google_longitude_latitude') }}";
    </script>
@endsection 