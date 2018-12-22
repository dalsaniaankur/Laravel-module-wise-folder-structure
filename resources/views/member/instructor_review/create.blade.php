@extends('member.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.instructor_review.title_single') </h1>
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
      
        {!! Form::model($instructor_review, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 
        'route' => ['member.instructor_review.save']]) !!}

         <input type="hidden" name="id" value="{{ $instructor_review->review_id}}" />
      
        <div class="tab-content ">
         <div class="tab-pane active" id="instructor_reviewInfo">

          <div class="form-group"> 
            {!! Form::label('member_id', trans('quickadmin.instructor_review.fields.member_id').': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               <p class="help-block"></p>
               <a href="mailto:{{ $instructor_review->email }}">{{ $instructor_review->email }}</a>
            </div>
          </div>

          <div class="form-group"> 
            {!! Form::label('member_display_name', trans('quickadmin.instructor_review.fields.member_display_name').': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               <p class="help-block"></p>
               {{ $instructor_review->first_name }} {{ $instructor_review->last_name }}
            </div>
          </div>

          <div class="form-group"> 
            {!! Form::label('review_summary', trans('quickadmin.instructor_review.fields.review_summary').': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               <p class="help-block"></p>
               {{ $instructor_review->review_summary}}
            </div>
          </div>

          <div class="form-group"> 
            {!! Form::label('review_detail', trans('quickadmin.instructor_review.fields.review_detail').': ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8">
               <p class="help-block"></p>
              {{ $instructor_review->review_detail}}
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
             <a href="{{ route('member.instructor_review.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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