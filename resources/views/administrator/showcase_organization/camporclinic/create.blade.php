@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.camp_or_clinic.title_single') </h1>
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
            <a href="#teamsInfo" data-toggle="tab">{{trans('quickadmin.camp_or_clinic-tab.basic-info')}}</a>
          </li>
          <li>
            <a href="#extra_details" data-toggle="tab">{{trans('quickadmin.camp_or_clinic-tab.extra-details')}}</a>
          </li>
       </ul>
       <!-- form start --> 
       @if(isset($campOrClinic->camp_clinic_id))
        {!! Form::model($campOrClinic, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 
        'route' => ['administrator.camp_or_clinic.save']]) !!}
         <input type="hidden" name="id" value="{{$campOrClinic->camp_clinic_id}}" />
      @else
          {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'team-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.camp_or_clinic.save']]) !!}
         <input type="hidden" name="id" value="" />
      @endif
        <div class="tab-content ">
         <div class="tab-pane active" id="teamsInfo">
              <div class="form-group"> 
                {!! Form::label('submitted_by_id', trans('quickadmin.camp_or_clinic.fields.submitted_by_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::select('submitted_by_id', $member, old('submitted_by_id'), ['class' => 'form-control', 'required' => '']) !!}
                  <p class="help-block"></p>
                  @if($errors->has('member_id'))
                  <p class="help-block"> {{ $errors->first('member_id') }} </p>
                  @endif 
                </div>
              </div>
              <div class="form-group"> 
                {!! Form::label('type', trans('quickadmin.camp_or_clinic.fields.type').': * ', ['class' => 'col-sm-3 control-label']) !!}
                 <div class="col-sm-8">
                    @php ($is_checked = false)
                    @foreach ($types as $key => $value)
                      @if($campOrClinic->type == $key)
                        @php ($is_checked = true)
                      @endif
                      {!! Form::radio('type', $key, $is_checked, array('id' => 'type'.$key)) !!}
                      {!! Form::label('type'.$key,$value) !!} &nbsp; &nbsp;
                      @php ($is_checked = false)
                    @endforeach
                </div>
              </div>
              
        		  <div class="form-group"> 
                    {!! Form::label('showcase_organization_id', trans('quickadmin.camp_or_clinic.fields.showcase_organization_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-8"> {!! Form::select('showcase_organization_id', $showcase_organizations, old('showcase_organization_id'), ['class' => 'form-control', 'required' => '']) !!}
                      <p class="help-block"></p>
                      @if($errors->has('showcase_organization_id'))
                      <p class="help-block"> {{ $errors->first('showcase_organization_id') }} </p>
                      @endif 
                    </div>
        	  	  </div>
              <div class="form-group"> 
                {!! Form::label('name', trans('quickadmin.camp_or_clinic.fields.name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('name'))
                   <p class="help-block"> {{ $errors->first('name') }} </p>
                   @endif 
                </div>
              </div>

              
              <div class="form-group"> 
                {!! Form::label('date', trans('quickadmin.camp_or_clinic.fields.date').':', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('date', old('date'), ['class' => 'form-control', 'id'=>'date' ,'placeholder' => '', 'required' => '', 'autocomplete' => 'off']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('date'))
                   <p class="help-block"> {{ $errors->first('date') }} </p>
                   @endif 
                </div>
              </div>
              
              <div class="form-group"> 
                {!! Form::label('address_1', trans('quickadmin.camp_or_clinic.fields.address_1').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('address_1', old('address_1'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                   <p class="help-block"></p>
                   @if($errors->has('address_1'))
                   <p class="help-block"> {{ $errors->first('address_1') }} </p>
                   @endif 
                </div>
              </div>

              <div class="form-group"> 
                {!! Form::label('address_2', trans('quickadmin.camp_or_clinic.fields.address_2').':', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                   {!! Form::text('address_2', old('address_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                   <p class="help-block"></p>
                </div>
              </div>

              <div class="form-group"> 
              {!! Form::label('state_id', trans('quickadmin.camp_or_clinic.fields.state_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                {!! Form::select('state_id', $state, old('state_id'), ['class' => 'form-control','onchange' => "getCityDropDown()", 'id' => 'state_id', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('state_id'))
                <p class="help-block"> {{ $errors->first('state_id') }} </p>
                @endif 
              </div>
            </div>
            <div class="form-group"> 
              {!! Form::label('city_id', trans('quickadmin.camp_or_clinic.fields.city') .': * ', ['class' => 'col-sm-3 control-label']) !!}
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
              {!! Form::label('url_key', trans('quickadmin.camp_or_clinic.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
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
                  {!! Form::label('zip', trans('quickadmin.camp_or_clinic.fields.zip').': * ', ['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-8"> {!! Form::text('zip', old('zip'), ['class' => 'form-control','required' => '','data-parsley-type'=>'digits', 'data-parsley-trigger' => 'keyup', 'placeholder' => '', 'data-parsley-minlength' => '5', 'data-parsley-maxlength' => '5']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('zip'))
                    <p class="help-block"> {{ $errors->first('zip') }} </p>
                    @endif 
                  </div>
              </div>

             <div class="form-group">
                {!! Form::label('phone_number', trans('quickadmin.camp_or_clinic.fields.phone_number').': * ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-8"> {!! Form::text('phone_number', old('phone_number'), ['class' => 'form-control', 'placeholder' => '','required' => ''])!!}
                  <p class="help-block"></p>
                  @if($errors->has('phone_number'))
                  <p class="help-block"> {{ $errors->first('phone_number') }} </p>
                  @endif 
                </div>
              </div>

              <div class="form-group"> 
              	{!! Form::label('email', trans('quickadmin.camp_or_clinic.fields.email').': * ', ['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-8"> 
                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '','required' => '']) !!}
                  </div>
             </div>

             <div class="form-group"> 
                {!! Form::label('website_url', trans('quickadmin.camp_or_clinic.fields.website_url').': ', ['class' => 'col-sm-3 control-label']) !!}
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

              {!! Form::label('age_group_id', trans('quickadmin.camp_or_clinic.fields.age_group_id').': *', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                  @foreach ($agegroup as $key => $value)
                     @php ($is_checked = false)
                     @if (!empty($campOrClinic->age_group_id) && in_array($key, $campOrClinic->age_group_id))
                        @php ($is_checked = true)
                     @endif
                    {!! Form::checkbox('age_group_id[]', $key, $is_checked, array('id' => 'agegroup'.$key)) !!}
                    {!! Form::label('agegroup'.$key, $value) !!}<br>
                  @endforeach
              </div>
           </div>

           <div class="form-group"> 
              {!! Form::label('service_id', trans('quickadmin.camp_or_clinic.fields.service_id').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                  @foreach ($services as $key => $value)
                     @php ($is_checked = false)
                     @if (!empty($campOrClinic->service_id) && in_array($key, $campOrClinic->service_id))
                        @php ($is_checked = true)
                     @endif
                    {!! Form::checkbox('service_id[]', $key, $is_checked, array('id' => 'service_id'.$key)) !!}
                    {!! Form::label('service_id'.$key, $value) !!}<br>
                  @endforeach
              </div>
           </div>

           <div class="form-group"> 
              {!! Form::label('type_of_camp_or_clinic_id', trans('quickadmin.camp_or_clinic.fields.type_of_camp_or_clinic_id').':  ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                  @foreach ($typeofcamporclinic as $key => $value)
                     @php ($is_checked = false)
                     @if (!empty($campOrClinic->type_of_camp_or_clinic_id) && in_array($key, $campOrClinic->type_of_camp_or_clinic_id))
                        @php ($is_checked = true)
                     @endif
                    {!! Form::checkbox('type_of_camp_or_clinic_id[]', $key, $is_checked, array('id' => 'type_of_camp_or_clinic_id'.$key)) !!}
                    {!! Form::label('type_of_camp_or_clinic_id'.$key, $value) !!}<br>
                  @endforeach
              </div>
          </div>

          <div class="form-group"> 
              {!! Form::label('boys_or_girls', trans('quickadmin.camp_or_clinic.fields.boys_or_girls').':  ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8">
                  @foreach ($boys_or_girls as $key => $value)
                     @php ($is_checked = false)
                     @if (!empty($campOrClinic->boys_or_girls) && in_array($key, $campOrClinic->boys_or_girls))
                        @php ($is_checked = true)
                     @endif
                    {!! Form::checkbox('boys_or_girls[]', $key, $is_checked, array('id' => 'boys_or_girls'.$key)) !!}
                    {!! Form::label('boys_or_girls'.$key, $value) !!} &nbsp; &nbsp;
                  @endforeach
              </div>
          </div>

           <div class="form-group"> 
            {!! Form::label('attachment_name_1', trans('quickadmin.camp_or_clinic.fields.attachment_name_1') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              {!! Form::file('attachment_name_1', null, ['class' => 'form-control']) !!}
            </div>
          </div>

          @if (!empty($campOrClinic->attachment_path_1)  && $campOrClinic->attachment_path_1 != ' ')
          <div class="form-group"> 
            {!! Form::label('current_assigned_image', trans('quickadmin.camp_or_clinic.fields.current_attachment_name_1') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
                 {{ $campOrClinic->attachment_name_1 }}
            </div>
          </div>
          @endif

          <div class="form-group"> 
            {!! Form::label('attachment_name_2', trans('quickadmin.camp_or_clinic.fields.attachment_name_2') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              {!! Form::file('attachment_name_2', null, ['class' => 'form-control']) !!}
            </div>
          </div>

          @if (!empty($campOrClinic->attachment_path_2) && $campOrClinic->attachment_path_2 != ' ')
          <div class="form-group"> 
            {!! Form::label('current_assigned_image', trans('quickadmin.camp_or_clinic.fields.current_attachment_name_2') .': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
                 {{ $campOrClinic->attachment_name_2 }}
            </div>
          </div>
          @endif

          <div class="form-group"> 
            {!! Form::label('cost_or_notes', trans('quickadmin.camp_or_clinic.fields.cost_or_notes').': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
              {{ Form::textarea('cost_or_notes', old('cost_or_notes'), ['class' => 'form-control', 'placeholder' => '']) }}
               <p class="help-block"></p>
            </div>
           </div>  

          <div class="form-group"> 
            {!! Form::label('description', trans('quickadmin.camp_or_clinic.fields.description').': ', ['class' => 'col-sm-3 control-label']) !!}              <div class="col-sm-8">
              {{ Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' => '']) }}
               <p class="help-block"></p>
            </div>
           </div> 

 	         <div class="form-group"> 
            {!! Form::label('other_information', trans('quickadmin.camp_or_clinic.fields.other_information').': ', ['class' => 'col-sm-3 control-label']) !!}              <div class="col-sm-8">
              {{ Form::textarea('other_information', old('other_information'), ['class' => 'form-control', 'placeholder' => '']) }}
               <p class="help-block"></p>
            </div>
           </div> 
           <div class="form-group"> 
             {!! Form::label('is_active', trans('quickadmin.camp_or_clinic.fields.is_active').': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              {!! Form::checkbox('is_active', null,  old('is_active'), array('id' => 'is_active')) !!}
            </div>
           </div>
           <div class="form-group"> {!! Form::label('is_send_email_to_user', trans('quickadmin.camp_or_clinic.fields.is_send_email_to_user').':', ['class' => 'col-sm-3 control-label']) !!}
             <div class="col-sm-8"> 
               {!! Form::checkbox('is_send_email_to_user', null,  old('is_send_email_to_user'), array('id' => 'is_send_email_to_user')) !!}
              </div>
           </div>
       </div>
         <!-- /.box --> 
         <div class="form-navigation">
          	 <div class="form-group">
               <div class="col-sm-offset-3 col-sm-8"> 
               <a href="{{ route('administrator.camp_or_clinic.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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