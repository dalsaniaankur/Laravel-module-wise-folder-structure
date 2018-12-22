@extends('administrator.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> @lang('quickadmin.tournament_organization.title_single') </h1>
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
                            <a href="#teamsInfo" data-toggle="tab">{{trans('quickadmin.tournament_organization-tab.basic-info')}}</a>
                        </li>
                        <li>
                            <a href="#extra_details" data-toggle="tab">{{trans('quickadmin.tournament_organization-tab.extra-details')}}</a>
                        </li>
                    </ul>
                    <!-- form start -->
                    @if(isset($tournamentOrganization))
                        {!! Form::model($tournamentOrganization, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate',
                        'route' => ['administrator.tournament_organization.save']]) !!}
                        <input type="hidden" name="id" value="{{$tournamentOrganization->tournament_organization_id}}" />
                    @else
                        {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'team-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.tournament_organization.save']]) !!}
                        <input type="hidden" name="id" value="" />
                    @endif

                    <div class="tab-content ">
                        <div class="tab-pane active" id="teamsInfo">
                            <div class="form-group"> {!! Form::label('submitted_by_id', trans('quickadmin.tournament_organization.fields.submitted_by_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::select('submitted_by_id', $member, old('submitted_by_id'), ['class' => 'form-control', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('member_id'))
                                        <p class="help-block"> {{ $errors->first('member_id') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('name', trans('quickadmin.tournament_organization.fields.name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('name'))
                                        <p class="help-block"> {{ $errors->first('name') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('contact_name', trans('quickadmin.tournament_organization.fields.contact_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('contact_name', old('contact_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('contact_name'))
                                        <p class="help-block"> {{ $errors->first('contact_name') }} </p>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group">
                                {!! Form::label('address_1', trans('quickadmin.tournament_organization.fields.address_1').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('address_1', old('address_1'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('address_1'))
                                        <p class="help-block"> {{ $errors->first('address_1') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('address_2', trans('quickadmin.tournament_organization.fields.address_2').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('address_2', old('address_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('address_2'))
                                        <p class="help-block"> {{ $errors->first('address_2') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('state_id', trans('quickadmin.tournament_organization.fields.state_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('state_id', $state, old('state_id'), ['class' => 'form-control','onchange' => "getCityDropDown()", 'id' => 'state_id', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('state_id'))
                                        <p class="help-block"> {{ $errors->first('state_id') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('city_id', trans('quickadmin.tournament_organization.fields.city') .': * ', ['class' => 'col-sm-3 control-label']) !!}
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
                                {!! Form::label('url_key', trans('quickadmin.tournament_organization.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
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

                            <div class="form-group"> {!! Form::label('zip', trans('quickadmin.tournament_organization.fields.zip').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::text('zip', old('zip'), ['class' => 'form-control','data-parsley-type'=>'digits', 'data-parsley-trigger' => 'keyup', 'placeholder' => '', 'data-parsley-minlength' => '5', 'data-parsley-maxlength' => '5', 'required' => '']) !!}

                                    <p class="help-block"></p>
                                    @if($errors->has('zip'))
                                        <p class="help-block"> {{ $errors->first('zip') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('phone_number', trans('quickadmin.tournament_organization.fields.phone_number').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::text('phone_number', old('phone_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('phone_number'))
                                        <p class="help-block"> {{ $errors->first('phone_number') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('agree_to_recevie_email_updates', trans('quickadmin.tournament_organization.fields.agree_to_recevie_email_updates').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::checkbox('agree_to_recevie_email_updates', null,  old('agree_to_recevie_email_updates'), array('id' => 'agree_to_recevie_email_updates', 'required' => '')) !!}
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('is_subscribe_newsletter', trans('quickadmin.tournament_organization.fields.is_subscribe_newsletter').': *', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::checkbox('is_subscribe_newsletter', null,  old('is_subscribe_newsletter'), array('id' => 'is_subscribe_newsletter', 'required' => '')) !!}

                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('website_url', trans('quickadmin.tournament_organization.fields.website_url').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('website_url', old('website_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('website_url'))
                                        <p class="help-block"> {{ $errors->first('website_url') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('description', trans('quickadmin.tournament_organization.fields.description').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {{ Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                                    <p class="help-block"></p>
                                    @if($errors->has('description'))
                                        <p class="help-block"> {{ $errors->first('description') }} </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="extra_details">
                            <div class="form-group">
                                {!! Form::label('facebook_url', trans('quickadmin.tournament_organization.fields.facebook_url').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('facebook_url', old('facebook_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('facebook_url'))
                                        <p class="help-block"> {{ $errors->first('facebook_url') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('twitter_url', trans('quickadmin.tournament_organization.fields.twitter_url').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('twitter_url', old('twitter_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('twitter_url'))
                                        <p class="help-block"> {{ $errors->first('twitter_url') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('instagram_url', trans('quickadmin.tournament_organization.fields.instagram_url').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('instagram_url', old('instagram_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('instagram_url'))
                                        <p class="help-block"> {{ $errors->first('instagram_url') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('youtube_video_id_1', trans('quickadmin.tournament_organization.fields.youtube_video_id_1').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('youtube_video_id_1', old('youtube_video_id_1'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('youtube_video_id_1'))
                                        <p class="help-block"> {{ $errors->first('youtube_video_id_1') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('youtube_video_id_2', trans('quickadmin.tournament_organization.fields.youtube_video_id_2').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('youtube_video_id_2', old('youtube_video_id_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('youtube_video_id_2'))
                                        <p class="help-block"> {{ $errors->first('youtube_video_id_2') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('longitude', trans('quickadmin.tournament_organization.fields.longitude').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('longitude', old('longitude'), ['class' => 'form-control', 'id' => 'longitude', 'placeholder' => '','required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('longitude'))
                                        <p class="help-block"> {{ $errors->first('longitude') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('latitude', trans('quickadmin.tournament_organization.fields.latitude').': * ', ['class' => 'col-sm-3 control-label']) !!}
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
                            <div class="form-group"> {!! Form::label('image_id', trans('quickadmin.tournament_organization.fields.select_image') .':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('image_id', $images, old('image_id'), ['class' => 'form-control']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('image_id'))
                                        <p class="help-block"> {{ $errors->first('image_id') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('image_id', trans('quickadmin.tournament_organization.fields.upload_image') .':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::file('tournamentorganization_image', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('current_assigned_image', trans('quickadmin.tournament_organization.fields.current_assigned_image') .':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    @if (!empty($tournamentOrganization->Images->image_path))
                                        {!! Form::image(URL::to('/') .'/'.$tournamentOrganization->Images->image_path, 'Current Assigned Image', array( 'width' => 150, 'height' => 'auto' ),['class' => 'form-control']) !!}
                                    @else
                                        No Image Assigned.
                                    @endif
                                    <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.image') }} </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('is_active', trans('quickadmin.tournament_organization.fields.is_active').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::checkbox('is_active', null,  old('is_active'), array('id' => 'is_active')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('approval_status', trans('quickadmin.tournament_organization.fields.approval_status') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('approval_status', $approvalStatusList, old('approval_status'), ['class' => 'form-control', 'id' => 'approval_status', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('approval_status'))
                                        <p class="help-block"> {{ $errors->first('approval_status') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group"> {!! Form::label('is_send_email_to_user', trans('quickadmin.tournament_organization.fields.is_send_email_to_user').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::checkbox('is_send_email_to_user', null,  old('is_send_email_to_user'), array('id' => 'is_send_email_to_user')) !!}
                                </div>
                            </div>
                        </div>
                        <!-- /.box -->
                        <div class="form-navigation">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <a href="{{ route('administrator.tournament_organizations.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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
@section('javascript')
    <script>
        var _token = "{{ csrf_token() }}";
        var get_google_longitude_latitude_url ="{{ URL::to('administrator/get_google_longitude_latitude') }}";
    </script>
@endsection 