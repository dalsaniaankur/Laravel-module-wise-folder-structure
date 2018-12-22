@extends('member.layouts.app')
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> @lang('quickadmin.academy.title_single') </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <!--------------------------
      | Your Page Content Here |
      -------------------------->
    <div class="row">
      <div class="col-md-12"> @if (Session::has('success'))
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
        @endif </div>
    </div>
    <div class="row">
      <!-- left column -->
      <div class="col-md-9">
        <div class="nav-tabs-custom validation-tab" >
          <ul class="nav nav-tabs">
            <li class="active"> <a href="#academy_info" data-toggle="tab">{{trans('quickadmin.academy-tab.basic-info')}}</a> </li>
            <li> <a href="#extra_details" data-toggle="tab">{{trans('quickadmin.academy-tab.extra-details')}}</a> </li>
          </ul>
          <!-- form start -->
          @if(isset($academies))

            {!! Form::model($academies, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate',
            'route' => ['member.academy.save']]) !!}
            <input type="hidden" name="id" value="{{ $academies->academy_id }}" />
            <input type="hidden" name="approval_status" value="{{ $academies->approval_status }}" />

          @else

            {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'academies-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['member.academy.save']]) !!}

            <input type="hidden" name="id" value="" />
            <input type="hidden" name="approval_status" value="{{ $defaultApprovalStatus }}" />

          @endif

          <div class="tab-content ">
            <div class="tab-pane active" id="academy_info">

              <input type="hidden" name="member_id" value="{{ $member_id }}" />

              <div class="form-group"> {!! Form::label('academy_name', trans('quickadmin.academy.fields.academy_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('academy_name', old('academy_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                  <p class="help-block"></p>
                  @if($errors->has('academy_name'))
                    <p class="help-block"> {{ $errors->first('academy_name') }} </p>
                  @endif
                </div>
              </div>

              <div class="form-group"> {!! Form::label('address_1', trans('quickadmin.academy.fields.address_1').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('address_1', old('address_1'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                  <p class="help-block"></p>
                  @if($errors->has('address_1'))
                    <p class="help-block"> {{ $errors->first('address_1') }} </p>
                  @endif </div>
              </div>
              <div class="form-group"> {!! Form::label('address_2', trans('quickadmin.academy.fields.address_2').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('address_2', old('address_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                  <p class="help-block"></p>
                </div>
              </div>

              <div class="form-group">
                {!! Form::label('state_id', trans('quickadmin.academy.fields.state_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                  {!! Form::select('state_id', $state, old('state_id'), ['class' => 'form-control','onchange' => "getCityDropDown()", 'id' => 'state_id', 'required' => '']) !!}
                  <p class="help-block"></p>
                  @if($errors->has('state_id'))
                    <p class="help-block"> {{ $errors->first('state_id') }} </p>
                  @endif
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('city_id', trans('quickadmin.academy.fields.city') .': * ', ['class' => 'col-sm-3 control-label']) !!}
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
                {!! Form::label('url_key', trans('quickadmin.academy.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
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
                  <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick="generateUrlKey(['academy_name','city_id','state_id']);">@lang('quickadmin.btn_generate_url_key') </button>
                  <p class="help-block"></p>
                </div>
              </div>

              <div class="form-group"> {!! Form::label('zip', trans('quickadmin.academy.fields.zip').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('zip', old('zip'), ['class' => 'form-control','data-parsley-type'=>'digits', 'data-parsley-trigger' => 'keyup', 'placeholder' => '', 'data-parsley-minlength' => '5', 'data-parsley-maxlength' => '5', 'required' => '']) !!}
                  <p class="help-block"></p>
                  @if($errors->has('zip'))
                    <p class="help-block"> {{ $errors->first('zip') }} </p>
                  @endif </div>
              </div>

              <div class="form-group"> {!! Form::label('phone_number', trans('quickadmin.academy.fields.phone_number').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('phone_number', old('phone_number'), ['class' => 'form-control', 'placeholder' => '']) !!}
                  <p class="help-block"></p>
                </div>
              </div>
              <div class="form-group"> {!! Form::label('email', trans('quickadmin.academy.fields.email').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '']) !!} </div>
              </div>

              <div class="form-group"> {!! Form::label('agree_to_recevie_email_updates', trans('quickadmin.academy.fields.agree_to_recevie_email_updates').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                  {!! Form::checkbox('agree_to_recevie_email_updates', null,  old('agree_to_recevie_email_updates'), array('id' => 'agree_to_recevie_email_updates')) !!}
                </div>
              </div>

              <div class="form-group"> {!! Form::label('is_subscribe_newsletter', trans('quickadmin.academy.fields.is_subscribe_newsletter').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::checkbox('is_subscribe_newsletter', null,  old('is_subscribe_newsletter'), array('id' => 'is_subscribe_newsletter')) !!}
                  <p class="help-block"></p>
                </div>
              </div>

              <div class="form-group"> {!! Form::label('website_url', trans('quickadmin.academy.fields.website_url').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('website_url', old('website_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                  <p class="help-block"></p>
                </div>
              </div>

              <div class="form-group"> {!! Form::label('service_id', trans('quickadmin.academy.fields.service_id').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> @foreach ($services as $key => $value)
                    @php ($is_checked = false)
                    @if (!empty($academies->service_id) && in_array($key, $academies->service_id))
                      @php ($is_checked = true)
                    @endif
                    {!! Form::checkbox('service_id[]', $key, $is_checked, array('id' => 'service_'.$key)) !!}
                    {!! Form::label('service_'.$key, $value) !!}<br>
                  @endforeach </div>
              </div>
              <div class="form-group"> {!! Form::label('about', trans('quickadmin.academy.fields.about').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {{ Form::textarea('about', old('about'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                  <p class="help-block"></p>
                </div>
              </div>
              <div class="form-group"> {!! Form::label('objectives', trans('quickadmin.academy.fields.objectives').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {{ Form::textarea('objectives', old('objectives'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                  <p class="help-block"></p>
                </div>
              </div>

              <div class="form-group"> {!! Form::label('programs', trans('quickadmin.academy.fields.programs').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {{ Form::textarea('programs', old('programs'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                  <p class="help-block"></p>
                </div>
              </div>

              <div class="form-group"> {!! Form::label('alumni', trans('quickadmin.academy.fields.alumni').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {{ Form::textarea('alumni', old('alumni'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                  <p class="help-block"></p>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="extra_details">
              <div class="form-group"> {!! Form::label('facebook_url', trans('quickadmin.academy.fields.facebook_url').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('facebook_url', old('facebook_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                  <p class="help-block"></p>
                </div>
              </div>
              <div class="form-group"> {!! Form::label('twitter_url', trans('quickadmin.academy.fields.twitter_url').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('twitter_url', old('twitter_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                  <p class="help-block"></p>
                </div>
              </div>
              <div class="form-group"> {!! Form::label('instagram_url', trans('quickadmin.academy.fields.instagram_url').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('instagram_url', old('instagram_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                  <p class="help-block"></p>
                </div>
              </div>
              <div class="form-group"> {!! Form::label('youtube_video_id_1', trans('quickadmin.academy.fields.youtube_video_id_1').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('youtube_video_id_1', old('youtube_video_id_1'), ['class' => 'form-control', 'placeholder' => '']) !!}
                  <p class="help-block"></p>
                </div>
              </div>
              <div class="form-group"> {!! Form::label('youtube_video_id_2', trans('quickadmin.academy.fields.youtube_video_id_2').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('youtube_video_id_2', old('youtube_video_id_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                  <p class="help-block"></p>
                </div>
              </div>

              <div class="form-group"> {!! Form::label('longitude', trans('quickadmin.academy.fields.longitude').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('longitude', old('longitude'), ['class' => 'form-control', 'id' => 'longitude', 'placeholder' => '','required' => '']) !!}
                  <p class="help-block"></p>
                  @if($errors->has('longitude'))
                    <p class="help-block"> {{ $errors->first('longitude') }} </p>
                  @endif
                </div>
              </div>

              <div class="form-group"> {!! Form::label('latitude', trans('quickadmin.academy.fields.latitude').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('latitude', old('latitude'), ['class' => 'form-control', 'id' => 'latitude', 'placeholder' => '','required' => '']) !!}
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

              <div class="form-group"> {!! Form::label('image_id', trans('quickadmin.academy.fields.select_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::select('image_id', $images, old('image_id'), ['class' => 'form-control', 'required' => '']) !!}
                  <p class="help-block"></p>
                </div>
              </div>
              <div class="form-group"> {!! Form::label('image_id', trans('quickadmin.academy.fields.upload_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::file('academy_image', null, ['class' => 'form-control']) !!} </div>
              </div>

              <div class="form-group"> {!! Form::label('current_assigned_image', trans('quickadmin.academy.fields.current_assigned_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> @if (!empty($academies->Images->image_path))

                    {!! Form::image(URL::to('/') .'/'.$academies->Images->image_path, 'Current Assigned Image', array( 'width' => 128, 'height' => '128' ),['class' => 'form-control']) !!}

                  @else
                    No Image Assigned.
                  @endif
                  <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.academy_image') }} </div>
                </div>

              </div>

              <div class="form-group"> {!! Form::label('is_active', trans('quickadmin.academy.fields.is_active').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::checkbox('is_active', null,  old('is_active'), array('id' => 'is_active')) !!} </div>
              </div>
              <div class="form-group"> {!! Form::label('is_send_email_to_user', trans('quickadmin.academy.fields.is_send_email_to_user').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::checkbox('is_send_email_to_user', null,  old('is_send_email_to_user'), array('id' => 'is_send_email_to_user')) !!}
                  <p class="help-block"></p>
                </div>
              </div>
            </div>
            <!-- /.box -->
            <div class="form-navigation">
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-8"> <a href="{{ route('member.academies.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a> {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
                  <button type="button" class="previous btn btn-primary">@lang('quickadmin.previous')</button>
                  <button type="button" class="next btn btn-primary">@lang('quickadmin.next') </button>
                  <span class="clearfix"></span> </div>
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