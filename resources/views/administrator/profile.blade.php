@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.users.user_profile') </h1>
</section>
<!-- Main content -->
<section class="content"> 
  <!--------------------------
    | Your Page Content Here |
    -------------------------->
  <div class="row">
    <div class="col-md-12"> @if (Session::has('message'))
      <div class="alert alert-info">
        <p>{{ Session::get('message') }}</p>
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
      @if(session('success')) 
      <!-- If password successfully show message -->
      <div class="alert alert-success"> {{ session('success') }} </div>
      @endif </div>
  </div>
  <div class="row">
    <div class="col-md-3"> 
      <!-- Profile Image -->
      <div class="box box-primary"> 
        <!-- FORM START-->
        <div class="" id="crop-avatar"> {!! Form::model($user, ['method' => 'post', 'class'=>'form-horizontal','route' => ['administrator_change_profile_picture']]) !!}
          <div class="box-body box-profile"> 
            <!-- Current avatar -->
            <div class="avatar-view" title="Change Profile Picture"> @if ($user->profile_picture) <img class="profile-user-img img-responsive img-circle" src="{{url($user->profile_picture)}}" alt="Profile Picture"> @else <img  class="profile-user-img img-responsive img-circle" src="{{url('images/person-placeholder.jpg')}}" alt="Profile Picture"> @endif </div>
            <h3 class="profile-username text-center"> {{$user->first_name}} {{$user->last_name}} </h3>
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item"> <b> @lang('quickadmin.qa_join_date') </b> <a class="pull-right"> {{ DateFacades::dateFormat($user->created_at,'formate-1') }} </a> </li>
            </ul>
            <!--<a href="#" class="btn btn-primary btn-block " id="showAvatar"><b>Change Profile </b></a>--> 
          </div>
          <!-- /.box-body --> 
          {!! Form::close() !!} 
          <!-- Cropping modal -->
          <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-lg">
              <div class="modal-content"> 
                <!-- <form class="avatar-form" action="crop.php" enctype="multipart/form-data" method="post">--> 
                {!! Form::model($user, ['method' => 'post', 'class'=>'avatar-form form-horizontal','route' => ['administrator_change_profile_picture']]) !!}
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title" id="avatar-modal-label">Change Profile Picture</h4>
                </div>
                <div class="modal-body">
                  <div class="avatar-body"> 
                    <!-- Upload image and data -->
                    <div class="avatar-upload">
                      <input type="hidden" class="avatar-src" name="avatar_src">
                      <input type="hidden" class="avatar-data" name="avatar_data">
                      <label for="avatarInput">Upload</label>
                      <input type="file" class="avatar-input" id="avatarInput" name="avatar_file">
                    </div>
                    <!-- Crop and preview -->
                    <div class="row">
                      <div class="col-md-9">
                        <div class="avatar-wrapper"></div>
                      </div>
                      <div class="col-md-3">
                        <div class="avatar-preview preview-lg"></div>
                        <div class="avatar-preview preview-md"></div>
                        <div class="avatar-preview preview-sm"></div>
                      </div>
                    </div>
                    <div class="row avatar-btns">
                      <div class="col-md-9">
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary" data-method="rotate" data-option="-90" title="Rotate -90 degrees">Rotate Left</button>
                          <button type="button" class="btn btn-primary" data-method="rotate" data-option="-15">-15deg</button>
                          <button type="button" class="btn btn-primary" data-method="rotate" data-option="-30">-30deg</button>
                          <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45">-45deg</button>
                        </div>
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary" data-method="rotate" data-option="90" title="Rotate 90 degrees">Rotate Right</button>
                          <button type="button" class="btn btn-primary" data-method="rotate" data-option="15">15deg</button>
                          <button type="button" class="btn btn-primary" data-method="rotate" data-option="30">30deg</button>
                          <button type="button" class="btn btn-primary" data-method="rotate" data-option="45">45deg</button>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-block avatar-save">Done</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!--
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> --> 
                <!--</form>--> 
                {!! Form::close() !!} </div>
            </div>
          </div>
          <!-- /.modal --> 
          <!-- Loading state -->
          <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
        </div>
      </div>
      <!-- /.box --> 
    </div>
    <div class="col-md-9">
      <div class="nav-tabs-custom validation-tab">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#userInfo" data-toggle="tab">{{trans('quickadmin.client-tab.basic-info')}}</a></li>
        </ul>
        {!! Form::model($user, ['method' => 'post', 'data-parsley-validate','class'=>'form-horizontal validation-form','route' => ['administrator_change_profile']]) !!}
        <input type="hidden" name="id" value="{{$user->user_id}}"/>
        <div class="tab-content ">
          <div class="tab-pane active" id="userInfo">
            <div class="form-group"> {!! Form::label('first_name', trans('quickadmin.users.fields.first_name').'*', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-8"> {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('first_name'))
                <p class="help-block"> {{ $errors->first('first_name') }} </p>
                @endif </div>
            </div>
            <div class="form-group"> {!! Form::label('last_name', trans('quickadmin.users.fields.last_name').'*', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-8"> {!! Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('last_name'))
                <p class="help-block"> {{ $errors->first('last_name') }} </p>
                @endif </div>
            </div>
            <div class="form-group"> {!! Form::label('email', trans('quickadmin.users.fields.email').'*', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-8"> {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('email'))
                <p class="help-block"> {{ $errors->first('email') }} </p>
                @endif </div>
            </div>
          </div>
          <!-- /.tab-pane --> 
          <div class="form-navigation">
          	 <div class="form-group">
               <div class="col-sm-offset-2 col-sm-8"> 
                {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!} 
                <button type="button" class="previous btn btn-primary">@lang('quickadmin.previous')</button>
                <button type="button" class="next btn btn-primary">@lang('quickadmin.next') </button>
                <span class="clearfix"></span>
               </div>
             </div>
		  </div>
        </div>
        <!-- /.tab-content --> 
        {!! Form::close() !!} </div>
    </div>
  </div>
</section>
<!-- /.content --> 
@endsection 