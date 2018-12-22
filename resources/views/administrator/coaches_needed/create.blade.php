@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.coaches_needed.title_single') </h1>
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
          <li class="active">
            <a href="#coaches_needed_info" data-toggle="tab">{{trans('quickadmin.coaches_needed-tab.basic-info')}}</a>
          </li>
          <li>
            <a href="#extra_details" data-toggle="tab">{{trans('quickadmin.coaches_needed-tab.extra-details')}}</a>
          </li>
        </ul>
        <!-- form start --> 
      @if(isset($coaches_needed))
     
        {!! Form::model($coaches_needed, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 
        'route' => ['administrator.coaches_needed.save']]) !!}
         <input type="hidden" name="id" value="{{$coaches_needed->coaches_needed_id}}" />
      @else
          {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'coaches_needed-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.coaches_needed.save']]) !!}
         <input type="hidden" name="id" value="" />
      @endif

        <div class="tab-content ">
         <div class="tab-pane active" id="coaches_needed_info">
              <div class="form-group"> {!! Form::label('member_id', trans('quickadmin.coaches_needed.fields.member_id') .': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::select('member_id', $member, old('member_id'), ['class' => 'form-control', 'required' => '']) !!}
                  <p class="help-block"></p>
                  @if($errors->has('member_id'))
                  <p class="help-block"> {{ $errors->first('member_id') }} </p>
                  @endif 
                </div>
              </div>

             <div class="form-group"> 
                {!! Form::label('contact_first_name', trans('quickadmin.coaches_needed.fields.contact_first_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('contact_first_name', old('contact_first_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('contact_first_name'))
                   <p class="help-block"> {{ $errors->first('contact_first_name') }} </p>
                   @endif 
                </div>
              </div>


               <div class="form-group"> 
                {!! Form::label('contact_last_name', trans('quickadmin.coaches_needed.fields.contact_last_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('contact_last_name', old('contact_last_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('contact_last_name'))
                   <p class="help-block"> {{ $errors->first('contact_last_name') }} </p>
                   @endif 
                </div>
              </div>

              <div class="form-group"> 
              {!! Form::label('url_key', trans('quickadmin.coaches_needed.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
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
                <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick="generateUrlKey(['contact_first_name','contact_last_name']);">@lang('quickadmin.btn_generate_url_key') </button>
                <p class="help-block"></p>
              </div>
            </div>

              <div class="form-group"> {!! Form::label('phone_number', trans('quickadmin.coaches_needed.fields.phone_number').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('phone_number', old('phone_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                  <p class="help-block"></p>
                  @if($errors->has('phone_number'))
                  <p class="help-block"> {{ $errors->first('phone_number') }} </p>
                  @endif 
                </div>
              </div>

              <div class="form-group"> {!! Form::label('email', trans('quickadmin.coaches_needed.fields.email').': * ', ['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-8"> 
                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('email'))
                    <p class="help-block"> {{ $errors->first('email') }} </p>
                    @endif 
                  </div>
              </div>
              <div class="form-group"> {!! Form::label('is_subscribe_newsletter', trans('quickadmin.coaches_needed.fields.is_subscribe_newsletter').': ', ['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-8"> 
                    {!! Form::checkbox('is_subscribe_newsletter', null,  old('is_subscribe_newsletter'), array('id' => 'is_subscribe_newsletter')) !!}

                  </div>
              </div>
               <div class="form-group"> 
                     {!! Form::label('team_id', trans('quickadmin.coaches_needed.fields.team_id') .': * ', ['class' => 'col-sm-3 control-label']) !!} <div class="col-sm-8"> {!! Form::select('team_id',$teams, old('team_id'), ['class' => 'form-control','required'=>'']) !!}
                     <p class="help-block"></p>
                        @if($errors->has('team_id'))
                          <p class="help-block"> {{ $errors->first('member_id') }} </p>
                        @endif 
                      </div>
               </div>

              <div class="form-group"> 
              {!! Form::label('position_id', trans('quickadmin.coaches_needed.fields.position_id').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                  @foreach ($position as $key => $value)
                     @php ($is_checked = false)
                     @if (!empty($coaches_needed->position_id) && in_array($key, $coaches_needed->position_id))
                        @php ($is_checked = true)
                     @endif
                    {!! Form::checkbox('position_id[]', $key, $is_checked, array('id' => 'position_id_'.$key)) !!}
                    {!! Form::label('position_id_'.$key, $value) !!}<br>
                  @endforeach
              </div>
            </div>

             <div class="form-group"> 
              {!! Form::label('age_group_id', trans('quickadmin.coaches_needed.fields.age_group_id').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                  @foreach ($agegroup as $key => $value)
                     @php ($is_checked = false)
                     @if (!empty($coaches_needed->age_group_id) && in_array($key, $coaches_needed->age_group_id))
                        @php ($is_checked = true)
                     @endif
                    {!! Form::checkbox('age_group_id[]', $key, $is_checked, array('id' => 'age_group_'.$key)) !!}
                    {!! Form::label('age_group_'.$key, $value) !!}<br>
                  @endforeach
              </div>
            </div>
           
         </div>
         <div class="tab-pane" id="extra_details">
             <div class="form-group"> 
           		{!! Form::label('experience_id', trans('quickadmin.coaches_needed.fields.experience_id') .': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> 
                {!! Form::select('experience_id', $experience, old('experience_id'), ['class' => 'form-control', 'required' => '']) !!}
                  <p class="help-block"></p>
                </div>
             </div>
             <div class="form-group"> 
              {!! Form::label('description', trans('quickadmin.coaches_needed.fields.description').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                {{ Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}  
                <p class="help-block"></p>
              </div>
            </div> 
            <div class="form-group"> {!! Form::label('image_id', trans('quickadmin.coaches_needed.fields.select_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::select('image_id', $images, old('image_id'), ['class' => 'form-control']) !!}
                <p class="help-block"></p>
             </div>
            </div>
           <div class="form-group"> 
            {!! Form::label('image_id', trans('quickadmin.coaches_needed.fields.upload_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              {!! Form::file('coaches_needed_image', null, ['class' => 'form-control']) !!}
              <p class="help-block"></p>
            </div>
          </div>
           <div class="form-group"> 
            {!! Form::label('current_assigned_image', trans('quickadmin.coaches_needed.fields.current_assigned_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              @if (!empty($coaches_needed->Images->image_path))
                
                 {!! Form::image(URL::to('/') .'/'.$coaches_needed->Images->image_path, 'Current Assigned Image', array( 'width' => 150, 'height' => 'auto' ),['class' => 'form-control']) !!}

              @else
                  No Image Assigned.
              @endif
            <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.image') }} </div>
            </div>
          </div>

         <div class="form-group"> {!! Form::label('is_active', trans('quickadmin.coaches_needed.fields.is_active').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::checkbox('is_active', null,  old('is_active'), array('id' => 'is_active')) !!}

              </div>
          </div>

           <div class="form-group"> {!! Form::label('is_send_email_to_user', trans('quickadmin.coaches_needed.fields.is_send_email_to_user').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::checkbox('is_send_email_to_user', null,  old('is_send_email_to_user'), array('id' => 'is_send_email_to_user')) !!}
                <p class="help-block"></p>
              </div>
          </div>
            
          </div>
         <!-- /.box --> 
         <div class="form-navigation">
          	 <div class="form-group">
               <div class="col-sm-offset-3 col-sm-8"> 
               <a href="{{ route('administrator.coaches_needed.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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