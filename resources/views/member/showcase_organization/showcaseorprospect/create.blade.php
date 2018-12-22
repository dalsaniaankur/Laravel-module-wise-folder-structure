@extends('member.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> @lang('quickadmin.showcase_or_prospect.title_single') </h1>
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
                            <a href="#teamsInfo" data-toggle="tab">{{trans('quickadmin.showcase_or_prospect-tab.basic-info')}}</a>
                        </li>
                        <li>
                            <a href="#extra_details" data-toggle="tab">{{trans('quickadmin.showcase_or_prospect-tab.extra-details')}}</a>
                        </li>
                    </ul>

                    <!-- form start -->
                    @if(isset($showcaseOrProspect->showcase_or_prospect_id))
                        {!! Form::model($showcaseOrProspect, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate',
                        'route' => ['member.showcase_or_prospect.save']]) !!}
                        <input type="hidden" name="id" value="{{ $showcaseOrProspect->showcase_or_prospect_id }}" />
                        <input type="hidden" name="approval_status" value="{{ $showcaseOrProspect->approval_status }}" />

                    @else
                        {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'team-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['member.showcase_or_prospect.save']]) !!}
                        <input type="hidden" name="id" value="" />
                        <input type="hidden" name="approval_status" value="{{ $defaultApprovalStatus }}" />
                    @endif
                    <div class="tab-content ">
                        <div class="tab-pane active" id="teamsInfo">

                            <input type="hidden" name="submitted_by_id" value="{{ $submitted_by_id }}" />

                            <div class="form-group">
                                {!! Form::label('type', trans('quickadmin.showcase_or_prospect.fields.type').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    @php ($is_checked = false)
                                    @foreach ($types as $key => $value)
                                        @if($showcaseOrProspect->type == $key)
                                            @php ($is_checked = true)
                                        @endif
                                        {!! Form::radio('type', $key, $is_checked, array('id' => 'type'.$key)) !!}
                                        {!! Form::label('type'.$key,$value) !!} &nbsp; &nbsp;
                                        @php ($is_checked = false)
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('showcase_organization_id', trans('quickadmin.showcase_or_prospect.fields.showcase_organization_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::select('showcase_organization_id', $showcase_organizations, old('showcase_organization_id'), ['class' => 'form-control', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('showcase_organization_id'))
                                        <p class="help-block"> {{ $errors->first('showcase_organization_id') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('name', trans('quickadmin.showcase_or_prospect.fields.name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('name'))
                                        <p class="help-block"> {{ $errors->first('name') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('date', trans('quickadmin.showcase_or_prospect.fields.dates').':', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('dates', $showcaseDate, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('dates'))
                                        <p class="help-block"> {{ $errors->first('dates') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('address_1', trans('quickadmin.showcase_or_prospect.fields.address_1').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('address_1', old('address_1'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('address_1'))
                                        <p class="help-block"> {{ $errors->first('address_1') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('address_2', trans('quickadmin.showcase_or_prospect.fields.address_2').':', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('address_2', old('address_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('address_2'))
                                        <p class="help-block"> {{ $errors->first('address_2') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('location', trans('quickadmin.showcase_or_prospect.fields.location').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('location', old('location'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('location'))
                                        <p class="help-block"> {{ $errors->first('location') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('state_id', trans('quickadmin.showcase_or_prospect.fields.state_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('state_id', $state, old('state_id'), ['class' => 'form-control','onchange' => "getCityDropDown()", 'id' => 'state_id', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('state_id'))
                                        <p class="help-block"> {{ $errors->first('state_id') }} </p>
                                    @endif
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
                                {!! Form::label('url_key', trans('quickadmin.showcase_or_prospect.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
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
                                    <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick="generateUrlKey(['name','city_id','state_id','date']);">@lang('quickadmin.btn_generate_url_key') </button>
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('zip', trans('quickadmin.showcase_or_prospect.fields.zip').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::text('zip', old('zip'), ['class' => 'form-control','required' => '','data-parsley-type'=>'digits', 'data-parsley-trigger' => 'keyup', 'placeholder' => '', 'data-parsley-minlength' => '5', 'data-parsley-maxlength' => '5']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('zip'))
                                        <p class="help-block"> {{ $errors->first('zip') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('phone_number', trans('quickadmin.showcase_or_prospect.fields.phone_number').': * ', ['class' => 'col-sm-3 control-label']) !!}             <div class="col-sm-8"> {!! Form::text('phone_number', old('phone_number'), ['class' => 'form-control', 'placeholder' => '','required' => ''])!!}
                                    <p class="help-block"></p>
                                    @if($errors->has('phone_number'))
                                        <p class="help-block"> {{ $errors->first('phone_number') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('email', trans('quickadmin.showcase_or_prospect.fields.email').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '','required' => '']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('website_url', trans('quickadmin.showcase_or_prospect.fields.website_url').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('website_url', old('website_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('website_url'))
                                        <p class="help-block"> {{ $errors->first('website_url') }} </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="extra_details">
                            <div class="form-group">
                                {!! Form::label('age_group_id', trans('quickadmin.showcase_or_prospect.fields.age_group_id').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    @foreach ($agegroup as $key => $value)
                                        @php ($is_checked = false)
                                        @if (!empty($showcaseOrProspect->age_group_id) && in_array($key, $showcaseOrProspect->age_group_id))
                                            @php ($is_checked = true)
                                        @endif
                                        {!! Form::checkbox('age_group_id[]', $key, $is_checked, array('id' => 'agegroup'.$key)) !!}
                                        {!! Form::label('agegroup'.$key, $value) !!}<br>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('position_id', trans('quickadmin.showcase_or_prospect.fields.position_id').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    @foreach ($position as $key => $value)
                                        @php ($is_checked = false)
                                        @if (!empty($showcaseOrProspect->position_id) && in_array($key, $showcaseOrProspect->position_id))
                                            @php ($is_checked = true)
                                        @endif
                                        {!! Form::checkbox('position_id[]', $key, $is_checked, array('id' => 'position_id'.$key)) !!}                 {!! Form::label('position_id'.$key, $value) !!}<br>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('open_or_invite', trans('quickadmin.showcase_or_prospect.fields.open_or_invite').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    @foreach ($open_or_invites as $key => $value)
                                        @php ($is_checked = false)
                                        <?php
                                        if (empty($showcaseOrProspect->open_or_invite))
                                            $is_checked = true;
                                        else if($key==$showcaseOrProspect->open_or_invite)
                                            ($is_checked = true);
                                        ?>
                                        {!! Form::radio('open_or_invite', $key,$is_checked, array('id' => 'open_or_invite'.$key)) !!}
                                        {!! Form::label('open_or_invite'.$key,$value) !!} &nbsp; &nbsp;
                                    @endforeach
                                </div>
                            </div>


                            <div class="form-group">
                                {!! Form::label('longitude', trans('quickadmin.showcase_or_prospect.fields.longitude').': *', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('longitude', old('longitude'), ['class' => 'form-control', 'id' => 'longitude', 'placeholder' => '','required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('longitude'))
                                        <p class="help-block"> {{ $errors->first('longitude') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('latitude', trans('quickadmin.showcase_or_prospect.fields.latitude').': *', ['class' => 'col-sm-3 control-label']) !!}
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
                                {!! Form::label('cost_or_notes', trans('quickadmin.showcase_or_prospect.fields.cost_or_notes').': ', ['class' => 'col-sm-3 control-label']) !!}              <div class="col-sm-8">
                                    {{ Form::textarea('cost_or_notes', old('cost_or_notes'), ['class' => 'form-control', 'placeholder' => '']) }}
                                    <p class="help-block"></p>
                                    @if($errors->has('cost_or_notes'))
                                        <p class="help-block"> {{ $errors->first('cost_or_notes') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('description', trans('quickadmin.showcase_or_prospect.fields.description').': ', ['class' => 'col-sm-3 control-label']) !!}              <div class="col-sm-8">
                                    {{ Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' => '']) }}
                                    <p class="help-block"></p>
                                    @if($errors->has('description'))
                                        <p class="help-block"> {{ $errors->first('description') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('other_information', trans('quickadmin.showcase_or_prospect.fields.other_information').': ', ['class' => 'col-sm-3 control-label']) !!}              <div class="col-sm-8">
                                    {{ Form::textarea('other_information', old('other_information'), ['class' => 'form-control', 'placeholder' => '']) }}
                                    <p class="help-block"></p>
                                    @if($errors->has('other_information'))
                                        <p class="help-block"> {{ $errors->first('other_information') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('attachment_name_1', trans('quickadmin.showcase_or_prospect.fields.attachment_name_1') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::file('attachment_name_1', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            @if (!empty($showcaseOrProspect->attachment_path_1)  && $showcaseOrProspect->attachment_path_1 != ' ')
                                <div class="form-group">
                                    {!! Form::label('current_assigned_image', trans('quickadmin.showcase_or_prospect.fields.current_attachment_name_1') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                    <div class="col-sm-8">
                                        {{ $showcaseOrProspect->attachment_name_1 }}
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                {!! Form::label('attachment_name_2', trans('quickadmin.showcase_or_prospect.fields.attachment_name_2') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::file('attachment_name_2', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            @if (!empty($showcaseOrProspect->attachment_path_2) && $showcaseOrProspect->attachment_path_2 != ' ')
                                <div class="form-group">
                                    {!! Form::label('current_assigned_image', trans('quickadmin.showcase_or_prospect.fields.current_attachment_name_2') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                    <div class="col-sm-8">
                                        {{ $showcaseOrProspect->attachment_name_2 }}
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                {!! Form::label('is_active', trans('quickadmin.showcase_or_prospect.fields.is_active').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::checkbox('is_active', null,  old('is_active'), array('id' => 'is_active')) !!}
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('is_send_email_to_user', trans('quickadmin.showcase_or_prospect.fields.is_send_email_to_user').':', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::checkbox('is_send_email_to_user', null,  old('is_send_email_to_user'), array('id' => 'is_send_email_to_user')) !!}
                                </div>
                            </div>
                        </div>
                        <!-- /.box -->
                        <div class="form-navigation">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <a href="{{ route('member.showcase_or_prospect.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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
@endsection @section('javascript')
    <script>
        var _token = "{{ csrf_token() }}";
        var get_google_longitude_latitude_url ="{{ URL::to('member/get_google_longitude_latitude') }}";
    </script>
@endsection 