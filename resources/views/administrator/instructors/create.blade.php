@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.instructor.title_single') </h1>
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
            <a href="#instructorInfo" data-toggle="tab">{{trans('quickadmin.instructor-tab.basic-info')}}</a>
          </li>
          <li>
            <a href="#extra_details" data-toggle="tab">{{trans('quickadmin.instructor-tab.extra-details')}}</a>
          </li>
        </ul>
        <!-- form start --> 
      
      @if(isset($instructor))
     
        {!! Form::model($instructor, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 
        'route' => ['administrator.instructor.save']]) !!}
         <input type="hidden" name="id" value="{{$instructor->instructor_id}}" />
      @else
          {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'instructor-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.instructor.save']]) !!}
         <input type="hidden" name="id" value="" />
      @endif
        <div class="tab-content ">
         <div class="tab-pane active" id="instructorInfo">
              <div class="form-group"> {!! Form::label('member_id', trans('quickadmin.instructor.fields.member_id') .': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::select('member_id', $member, old('member_id'), ['class' => 'form-control', 'required' => '']) !!}
                  <p class="help-block"></p>
                  @if($errors->has('member_id'))
                  <p class="help-block"> {{ $errors->first('member_id') }} </p>
                  @endif 
                </div>
              </div>

             <div class="form-group"> 
                {!! Form::label('title', trans('quickadmin.instructor.fields.title').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '']) !!}
                   <p class="help-block"></p>
                </div>
              </div>

             


               <div class="form-group"> 
                {!! Form::label('first_name', trans('quickadmin.instructor.fields.first_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('first_name'))
                   <p class="help-block"> {{ $errors->first('first_name') }} </p>
                   @endif 
                </div>
              </div>

              <div class="form-group"> 
                {!! Form::label('last_name', trans('quickadmin.instructor.fields.last_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('last_name'))
                   <p class="help-block"> {{ $errors->first('last_name') }} </p>
                   @endif 
                </div>
              </div>

              <div class="form-group"> 
                {!! Form::label('address_1', trans('quickadmin.instructor.fields.address_1').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('address_1', old('address_1'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('address_1'))
                   <p class="help-block"> {{ $errors->first('address_1') }} </p>
                   @endif 
                </div>
              </div>


              <div class="form-group"> 
                {!! Form::label('address_2', trans('quickadmin.instructor.fields.address_2').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('address_2', old('address_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                   <p class="help-block"></p>
                </div>
              </div>

               <div class="form-group"> {!! Form::label('state_id', trans('quickadmin.instructor.fields.state_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> {!! Form::select('state_id', $state, old('state_id'), ['class' => 'form-control','onchange' => "getCityDropDown()", 'id' => 'state_id', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('state_id'))
                <p class="help-block"> {{ $errors->first('state_id') }} </p>
                @endif </div>
            </div>

            <div class="form-group"> {!! Form::label('city_id', trans('quickadmin.instructor.fields.city') .': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8 city"> {!! Form::select('city_id', $city, old('city_id'), ['id' => 'city_id', 'class' => 'form-control', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('city_id'))
                <p class="help-block"> {{ $errors->first('city_id') }} </p>
                @endif 
                <div class="custom-city-error-message"><span>@lang('quickadmin.qa_city_custom_error_msg')</span></div>
              </div>
            </div>


              <div class="form-group"> 
                {!! Form::label('url_key', trans('quickadmin.instructor.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
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
                <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick="generateUrlKey(['title','city_id','state_id']);">@lang('quickadmin.btn_generate_url_key') </button>
                <p class="help-block"></p>
              </div>
            </div>


               <div class="form-group"> {!! Form::label('zip', trans('quickadmin.instructor.fields.zip').': * ', ['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-8"> {!! Form::text('zip', old('zip'), ['class' => 'form-control','data-parsley-type'=>'digits', 'data-parsley-trigger' => 'keyup', 'placeholder' => '', 'data-parsley-minlength' => '5', 'data-parsley-maxlength' => '5' , 'required' => '']) !!}

                    <p class="help-block"></p>
                    @if($errors->has('zip'))
                    <p class="help-block"> {{ $errors->first('zip') }} </p>
                    @endif 
                  </div>
              </div>

               <div class="form-group"> {!! Form::label('phone_number', trans('quickadmin.instructor.fields.phone_number').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('phone_number', old('phone_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                  <p class="help-block"></p>
                  @if($errors->has('phone_number'))
                  <p class="help-block"> {{ $errors->first('phone_number') }} </p>
                  @endif 
                </div>
              </div>
               <div class="form-group"> {!! Form::label('email', trans('quickadmin.instructor.fields.email').': * ', ['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-8"> 
                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                  @if($errors->has('email'))
                  <p class="help-block"> {{ $errors->first('email') }} </p>
                  @endif 
                  </div>
              </div>

              <div class="form-group"> {!! Form::label('is_subscribe_newsletter', trans('quickadmin.instructor.fields.is_subscribe_newsletter').': ', ['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-8"> 
                    {!! Form::checkbox('is_subscribe_newsletter', null,  old('is_subscribe_newsletter'), array('id' => 'is_subscribe_newsletter')) !!}

                  </div>
              </div>


             <div class="form-group"> 
                {!! Form::label('website_url', trans('quickadmin.instructor.fields.website_url').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('website_url', old('website_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                   <p class="help-block"></p>
                </div>
              </div>


              <div class="form-group"> 
                {!! Form::label('blog_url', trans('quickadmin.instructor.fields.blog_url').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('blog_url', old('blog_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                   <p class="help-block"></p>
                </div>
              </div>


              <div class="form-group"> 
                {!! Form::label('affilated_academy', trans('quickadmin.instructor.fields.affilated_academy').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('affilated_academy', old('affilated_academy'), ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
              </div>


            <div class="form-group"> 
              {!! Form::label('age_group_id', trans('quickadmin.instructor.fields.age_group_id').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                  @foreach ($age_group as $key => $value)
                     @php ($is_checked = false)
                     @if (!empty($instructor->age_group_id) && in_array($key, $instructor->age_group_id))
                        @php ($is_checked = true)
                     @endif
                    {!! Form::checkbox('age_group_id[]', $key, $is_checked, array('id' => 'age_group'.$key)) !!}
                    {!! Form::label('age_group'.$key, $value) !!}<br>
                  @endforeach

                  <p class="help-block"></p>
                  @if($errors->has('email'))
                  <p class="help-block"> {{ $errors->first('email') }} </p>
                  @endif 
                  
              </div>
            </div>

              <div class="form-group"> 
              {!! Form::label('focus_id', trans('quickadmin.instructor.fields.focus_id').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                  @foreach ($focus as $key => $value)
                     @php ($is_checked = false)
                     @if (!empty($instructor->focus_id) && in_array($key, $instructor->focus_id))
                        @php ($is_checked = true)
                     @endif
                    {!! Form::checkbox('focus_id[]', $key, $is_checked, array('id' => 'focus'.$key)) !!}
                    {!! Form::label('focus'.$key, $value) !!}<br>
                  @endforeach

                  <p class="help-block"></p>
                  @if($errors->has('focus_id'))
                  <p class="help-block"> {{ $errors->first('focus_id') }} </p>
                  @endif 

              </div>
            </div>

            <div class="form-group"> {!! Form::label('is_team_coach', trans('quickadmin.instructor.fields.is_team_coach') .': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::select('is_team_coach', $team_coach, old('is_team_coach'), ['class' => 'form-control']) !!}
                </div>
              </div>


             <div class="form-group"> 
              {!! Form::label('profile_description', trans('quickadmin.instructor.fields.profile_description').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                 
                {{ Form::textarea('profile_description', old('profile_description'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                
              </div>
            </div> 


             <div class="form-group"> 
              {!! Form::label('notable_coaching_achievements', trans('quickadmin.instructor.fields.notable_coaching_achievements').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                 
                {{ Form::textarea('notable_coaching_achievements', old('notable_coaching_achievements'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                
              </div>
            </div> 
           
         </div>
         <div class="tab-pane" id="extra_details">


            <div class="form-group"> 
                {!! Form::label('facebook_url', trans('quickadmin.instructor.fields.facebook_url').' : ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('facebook_url', old('facebook_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('facebook_url'))
                   <p class="help-block"> {{ $errors->first('facebook_url') }} </p>
                   @endif 
                </div>
              </div>


               <div class="form-group"> 
                {!! Form::label('twitter_url', trans('quickadmin.instructor.fields.twitter_url').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('twitter_url', old('twitter_url'), ['class' => 'form-control', 'placeholder' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('twitter_url'))
                   <p class="help-block"> {{ $errors->first('twitter_url') }} </p>
                   @endif 
                </div>
              </div>


               <div class="form-group"> 
                {!! Form::label('youtube_video_id_1', trans('quickadmin.instructor.fields.youtube_video_id_1').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('youtube_video_id_1', old('youtube_video_id_1'), ['class' => 'form-control', 'placeholder' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('youtube_video_id_1'))
                   <p class="help-block"> {{ $errors->first('youtube_video_id_1') }} </p>
                   @endif 
                </div>
              </div>


               <div class="form-group"> 
                {!! Form::label('youtube_video_id_2', trans('quickadmin.instructor.fields.youtube_video_id_2').': ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('youtube_video_id_2', old('youtube_video_id_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('youtube_video_id_2'))
                   <p class="help-block"> {{ $errors->first('youtube_video_id_2') }} </p>
                   @endif 
                </div>
              </div>


              <div class="form-group"> 
              {!! Form::label('article_url_1', trans('quickadmin.instructor.fields.article_url_1').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                 {!! Form::text('article_url_1', old('article_url_1'), ['class' => 'form-control', 'placeholder' => '']) !!}
                 <p class="help-block"></p>
                 @if($errors->has('article_url_1'))
                 <p class="help-block"> {{ $errors->first('article_url_1') }} </p>
                 @endif 
              </div>
            </div>


             <div class="form-group"> 
              {!! Form::label('article_url_2', trans('quickadmin.instructor.fields.article_url_2').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                 {!! Form::text('article_url_2', old('article_url_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                 <p class="help-block"></p>
                 @if($errors->has('article_url_2'))
                 <p class="help-block"> {{ $errors->first('article_url_2') }} </p>
                 @endif 
              </div>
            </div>

            <div class="form-group"> {!! Form::label('Is_show_advertise', trans('quickadmin.instructor.fields.Is_show_advertise') .': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> {!! Form::select('Is_show_advertise', $show_advertise, old('Is_show_advertise'), ['class' => 'form-control', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('Is_show_advertise'))
                <p class="help-block"> {{ $errors->first('Is_show_advertise') }} </p>
                @endif 
              </div>
            </div>


             <div class="form-group"> 
              {!! Form::label('longitude', trans('quickadmin.instructor.fields.longitude').': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                 {!! Form::text('longitude', old('longitude'), ['class' => 'form-control', 'id' => 'longitude', 'placeholder' => '','required' => '']) !!}
                 <p class="help-block"></p>
                 @if($errors->has('longitude'))
                 <p class="help-block"> {{ $errors->first('longitude') }} </p>
                 @endif 
              </div>
            </div>
            <div class="form-group"> 
              {!! Form::label('latitude', trans('quickadmin.instructor.fields.latitude').': * ', ['class' => 'col-sm-3 control-label']) !!}
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


            <div class="form-group"> {!! Form::label('image_id', trans('quickadmin.instructor.fields.select_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::select('image_id', $images, old('image_id'), ['class' => 'form-control']) !!}
                <p class="help-block"></p>
                @if($errors->has('image_id'))
                <p class="help-block"> {{ $errors->first('image_id') }} </p>
                @endif 
             </div>
            </div>


           <div class="form-group"> 
            {!! Form::label('image_id', trans('quickadmin.instructor.fields.upload_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              {!! Form::file('instructor_image', null, ['class' => 'form-control']) !!}
            </div>
          </div>
           
          <div class="form-group"> 
            {!! Form::label('current_assigned_image', trans('quickadmin.instructor.fields.current_assigned_image') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              @if (!empty($instructor->Images->image_path))
                
                 {!! Form::image(URL::to('/') .'/'.$instructor->Images->image_path, 'Current Assigned Image', array( 'width' => 150, 'height' => 'auto' ),['class' => 'form-control']) !!}

              @else
                  No Image Assigned.
              @endif
              <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.image') }} </div> 
            </div>
          </div>

         <div class="form-group"> {!! Form::label('is_active', trans('quickadmin.instructor.fields.is_active').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::checkbox('is_active', null,  old('is_active'), array('id' => 'is_active')) !!}

              </div>
          </div>

           <div class="form-group"> {!! Form::label('is_send_email_to_user', trans('quickadmin.instructor.fields.is_send_email_to_user').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::checkbox('is_send_email_to_user', null,  old('is_send_email_to_user'), array('id' => 'is_send_email_to_user')) !!}

              </div>
          </div>
            
          </div>
         <!-- /.box --> 
         <div class="form-navigation">
          	 <div class="form-group">
               <div class="col-sm-offset-3 col-sm-8"> 
               <a href="{{ route('administrator.instructors.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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