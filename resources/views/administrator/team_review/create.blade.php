@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.team_review.title_single') </h1>
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
        </ul>

        <!-- form start --> 
      
        {!! Form::model($team_review, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate',
        'route' => ['administrator.team_review.save']]) !!}

         <input type="hidden" name="id" value="{{ $team_review->review_id}}" />
      
        <div class="tab-content ">
         <div class="tab-pane active" id="team_reviewInfo">
          

          <div class="form-group"> 
            {!! Form::label('member_display_name', trans('quickadmin.team_review.fields.member_display_name').': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               <p class="help-block"></p>
               {{ $team_review->member_first_name }}
            </div>
          </div>

          <div class="form-group"> 
            {!! Form::label('review_summary', trans('quickadmin.team_review.fields.review_summary').': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               <p class="help-block"></p>
               {{ $team_review->review_summary}}
            </div>
          </div>

          <div class="form-group"> 
            {!! Form::label('review_detail', trans('quickadmin.team_review.fields.review_detail').': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               <p class="help-block"></p>
              {{ $team_review->review_detail}}
            </div>
          </div>

          <div class="form-group"> {!! Form::label('is_active', trans('quickadmin.lookup_for_player_experience.fields.is_active').': ', ['class' => 'col-sm-3 control-label']) !!}
              <div class="col-sm-8"> 
                <p class="help-block"></p>
                {!! Form::checkbox('is_active', null,  old('is_active'), array('id' => 'is_active')) !!}
                <p class="help-block"></p>
              </div>
          </div>
           
        </div>

       <!-- /.box --> 
        <div class="form-navigation">
          <div class="form-group">
           <div class="col-sm-offset-3 col-sm-8"> 
             <a href="{{ route('administrator.team_review.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
             {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!} 
             <button type="button" class="previous btn btn-primary">@lang('quickadmin.previous')</button>
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
@endsection 