@extends('member.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.lookup_for_player_experience.title_single') </h1>
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
            <a href="#lookup_for_player_experience_info" data-toggle="tab">{{trans('quickadmin.lookup_for_player_experience-tab.basic-info')}}</a>
          </li>
          <li>
            <a href="#extra_details" data-toggle="tab">{{trans('quickadmin.lookup_for_player_experience-tab.extra-details')}}</a>
          </li>
        </ul>
        <!-- form start --> 
      
      @if(isset($lookup_for_player_experience))
     
        {!! Form::model($lookup_for_player_experience, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 
        'route' => ['member.lookup_for_player_experience.save']]) !!}

         <input type="hidden" name="id" value="{{$lookup_for_player_experience->lookup_for_player_experience_id}}" />


      @else
        
          {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'lookup_for_player_experience-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['member.lookup_for_player_experience.save']]) !!}

         <input type="hidden" name="id" value="" />

      @endif

        <div class="tab-content ">
         <div class="tab-pane active" id="lookup_for_player_experience_info">

             <input type="hidden" name="member_id" value="{{ $member_id }}" />

             <div class="form-group"> 
                {!! Form::label('player_first_name', trans('quickadmin.lookup_for_player_experience.fields.player_first_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('player_first_name', old('player_first_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup', 'data-parsley-maxlength' => '100']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('player_first_name'))
                   <p class="help-block"> {{ $errors->first('player_first_name') }} </p>
                   @endif 
                </div>
              </div>


               <div class="form-group"> 
                {!! Form::label('player_last_name', trans('quickadmin.lookup_for_player_experience.fields.player_last_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('player_last_name', old('player_last_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup', 'data-parsley-maxlength' => '100']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('player_last_name'))
                   <p class="help-block"> {{ $errors->first('player_last_name') }} </p>
                   @endif 
                </div>
              </div>

              <div class="form-group"> 
              {!! Form::label('url_key', trans('quickadmin.lookup_for_player_experience.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
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
                <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick="generateUrlKey(['player_first_name','player_last_name']);">@lang('quickadmin.btn_generate_url_key') </button>
                <p class="help-block"></p>
              </div>
            </div>

              <div class="form-group"> {!! Form::label('player_phone_number', trans('quickadmin.lookup_for_player_experience.fields.player_phone_number').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('player_phone_number', old('player_phone_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                  <p class="help-block"></p>
                  @if($errors->has('player_phone_number'))
                  <p class="help-block"> {{ $errors->first('player_phone_number') }} </p>
                  @endif 
                </div>
              </div>

              <div class="form-group"> {!! Form::label('player_email', trans('quickadmin.lookup_for_player_experience.fields.player_email').': * ', ['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-8"> 
                    {!! Form::email('player_email', old('player_email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('player_email'))
                    <p class="help-block"> {{ $errors->first('player_email') }} </p>
                    @endif 
                  </div>
              </div>

               <div class="form-group"> {!! Form::label('player_zip', trans('quickadmin.lookup_for_player_experience.fields.player_zip').': * ', ['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-8"> {!! Form::text('player_zip', old('player_zip'), ['class' => 'form-control','data-parsley-type'=>'digits', 'data-parsley-trigger' => 'keyup', 'placeholder' => '', 'data-parsley-minlength' => '5', 'data-parsley-maxlength' => '5' , 'required' => '']) !!}

                    <p class="help-block"></p>
                    @if($errors->has('player_zip'))
                    <p class="help-block"> {{ $errors->first('player_zip') }} </p>
                    @endif 
                  </div>
              </div>


              <div class="form-group"> {!! Form::label('is_subscribe_newsletter', trans('quickadmin.lookup_for_player_experience.fields.is_subscribe_newsletter').':', ['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-8"> 
                    {!! Form::checkbox('is_subscribe_newsletter', null,  old('is_subscribe_newsletter'), array('id' => 'is_subscribe_newsletter')) !!}
                    <p class="help-block"></p>
                  </div>
              </div>

         </div>
         <div class="tab-pane" id="extra_details">

           <div class="form-group"> 
              {!! Form::label('age_group_id', trans('quickadmin.lookup_for_player_experience.fields.age_group_id').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                  @foreach ($agegroup as $key => $value)
                     @php ($is_checked = false)
                     @if (!empty($lookup_for_player_experience->age_group_id) && in_array($key, $lookup_for_player_experience->age_group_id))
                        @php ($is_checked = true)
                     @endif
                    {!! Form::checkbox('age_group_id[]', $key, $is_checked, array('id' => 'age_group'.$key)) !!}
                    {!! Form::label('age_group'.$key, $value) !!} <br/>
                  @endforeach
              </div>
            </div>

            <div class="form-group"> 
              {!! Form::label('position_id', trans('quickadmin.lookup_for_player_experience.fields.position_id').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                  @foreach ($position as $key => $value)
                     @php ($is_checked = false)
                     @if (!empty($lookup_for_player_experience->position_id) && in_array($key, $lookup_for_player_experience->position_id))
                        @php ($is_checked = true)
                     @endif
                    {!! Form::checkbox('position_id[]', $key, $is_checked, array('id' => 'position_id_'.$key)) !!}
                    {!! Form::label('position_id_'.$key, $value) !!} <br/>
                  @endforeach
              </div>
            </div>

            <div class="form-group"> {!! Form::label('bats_or_throw_id', trans('quickadmin.lookup_for_player_experience.fields.bats_or_throw_id') .': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::select('bats_or_throw_id', $bats_or_throws, old('bats_or_throw_id'), ['class' => 'form-control', 'required' => '']) !!}
                  <p class="help-block"></p>
                   @if($errors->has('bats_or_throw_id'))
                    <p class="help-block"> {{ $errors->first('bats_or_throw_id') }} </p>
                    @endif 
                </div>
              </div>

              
           <div class="form-group"> {!! Form::label('experience_id', trans('quickadmin.lookup_for_player_experience.fields.experience_id') .': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::select('experience_id', $experience, old('experience_id'), ['class' => 'form-control', 'required' => '']) !!}
                  <p class="help-block"></p>
                </div>
              </div>

             <div class="form-group"> 
              {!! Form::label('comments', trans('quickadmin.lookup_for_player_experience.fields.comments').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                 
                {{ Form::textarea('comments', old('comments'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                 <p class="help-block"></p>
              </div>
            </div> 
                    
            <div class="form-group"> {!! Form::label('image_id', trans('quickadmin.lookup_for_player_experience.fields.select_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::select('image_id', $images, old('image_id'), ['class' => 'form-control', 'required' => '']) !!}
                <p class="help-block"></p>
             </div>
            </div>


           <div class="form-group"> 
            {!! Form::label('image_id', trans('quickadmin.lookup_for_player_experience.fields.upload_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              {!! Form::file('lookup_for_player_experience_image', null, ['class' => 'form-control']) !!}
              <p class="help-block"></p>
            </div>
          </div>
           
           <div class="form-group"> 
            {!! Form::label('current_assigned_image', trans('quickadmin.lookup_for_player_experience.fields.current_assigned_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              @if (!empty($lookup_for_player_experience->Images->image_path))
                
                 {!! Form::image(URL::to('/') .'/'.$lookup_for_player_experience->Images->image_path, 'Current Assigned Image', array( 'width' => 150, 'height' => 'auto' ),['class' => 'form-control']) !!}

              @else
                  No Image Assigned.
              @endif
              <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.image') }} </div>
            </div>
          </div>

         <div class="form-group"> {!! Form::label('is_active', trans('quickadmin.lookup_for_player_experience.fields.is_active').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::checkbox('is_active', null,  old('is_active'), array('id' => 'is_active')) !!}
                <p class="help-block"></p>
              </div>
          </div>


           <div class="form-group"> {!! Form::label('is_send_email_to_user', trans('quickadmin.lookup_for_player_experience.fields.is_send_email_to_user').': ', ['class' => 'col-sm-3 control-label']) !!}
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
               <a href="{{ route('member.lookup_for_player_experience.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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