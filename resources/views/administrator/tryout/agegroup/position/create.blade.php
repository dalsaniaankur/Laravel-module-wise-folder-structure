@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.age_group_position.title_single') </h1>
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
       <!-- form start --> 
      @if(isset($ageGroupPosition))
        {!! Form::model($ageGroupPosition, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 'url' => 'administrator/agegroup_position/save/'.$age_group_id.'/'.$tryout_id ]) !!}
        
         <input type="hidden" name="id" value="{{ $ageGroupPosition->agegroup_position_id}}" />
      @else
          {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'team-form', 'class'=>'form-horizontal validation-form','data-parsley-validate', 'url' => 'administrator/agegroup_position/save/'.$age_group_id.'/'.$tryout_id]) !!}
         <input type="hidden" name="id" value="" />

      @endif

      <div class="tab-content ">
       <div class="tab-pane active">
            <div class="form-group"> {!! Form::label('position_id', trans('quickadmin.age_group_position.fields.position_id') .': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> {!! Form::select('position_id', $position, old('position_id'), ['class' => 'form-control']) !!}
                <p class="help-block"></p>
              </div>
            </div>
       </div>
       
       <div class="form-navigation">
        	 <div class="form-group">
             <div class="col-sm-offset-3 col-sm-8"> 
             <a href="{{ url('administrator/agegroup_position') }}/{{ $age_group_id}}/{{ $tryout_id}} " class="btn btn-primary">@lang('quickadmin.cancel')</a>
              {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!} 
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