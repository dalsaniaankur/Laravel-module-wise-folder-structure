@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.email_template.title_single') </h1>
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
    <div class="col-md-12">
      <div class="nav-tabs-custom validation-tab" >
        <ul class="nav nav-tabs">
        </ul>

        <!-- form start --> 
       @if(isset($email_template))
        {!! Form::model($email_template, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 
        'route' => ['administrator.email_template.save']]) !!}

         <input type="hidden" name="id" value="{{$email_template->email_template_id}}" />

      @else
        
	 	      {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'email_template-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.email_template.save']]) !!}

         <input type="hidden" name="id" value="" /> 

      @endif
        <div class="tab-content ">
         <div class="tab-pane active" id="email_templateInfo">

          <div class="form-group"> {!! Form::label('entity', trans('quickadmin.email_template.fields.entity').': * ', ['class' => 'col-sm-3 control-label']) !!}
          <div class="col-sm-8"> {!! Form::select('entity', $entity, old('entity'), ['class' => 'form-control' , 'required' => '']) !!}
             <p class="help-block"></p>
               @if($errors->has('entity'))
               <p class="help-block"> {{ $errors->first('entity') }} </p>
               @endif 
          </div>

        </div>
        
        <div class="form-group"> 
            {!! Form::label('subject', trans('quickadmin.email_template.fields.subject').': * ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8"> 
              {!! Form::text('subject', old('subject'), ['class' => 'form-control','placeholder' => '', 'required' => '']) !!}
               <p class="help-block"></p>
                 @if($errors->has('subject'))
                 <p class="help-block"> {{ $errors->first('subject') }} </p>
                 @endif 
            </div>
          </div>

         <div class="form-group"> 
          {!! Form::label('template_content', trans('quickadmin.email_template.fields.template_content').': * ', ['class' => 'col-sm-3 control-label']) !!}
          <div class="col-sm-8">
             
            {{ Form::textarea('template_content', old('template_content'), ['id' => 'html_content_editor', 'class' => 'form-control html_content_editor', 'placeholder' => '', 'required' => '']) }}
             <p class="help-block"></p>
             @if($errors->has('template_content'))
             <p class="help-block"> {{ $errors->first('template_content') }} </p>
             @endif 
          </div>
        </div>

       <!-- /.box --> 
        <div class="form-navigation">
          <div class="form-group">
           <div class="col-sm-offset-3 col-sm-8"> 
             <a href="{{ route('administrator.email_template.index') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
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
<!-- /.content --> 
<script type="text/javascript">
tinymceInit();
</script>
@endsection