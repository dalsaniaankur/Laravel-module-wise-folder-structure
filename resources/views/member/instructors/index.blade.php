@extends('member.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.instructor.title') </h1>
  <p class="text-right"><a href="{{ route('member.instructors.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
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
      @endif </div>
  </div>

   <div class="content-header-with-cell">
      <div class="custom-search-block">
       	 {!! Form::open(['method' => 'get','name'=>'instructor-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['member.instructors.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search Instructor','previous'=>'previous']) !!}
           <span class="input-group-btn">
              <button class="btn btn-default" type="submit">
                <i class="ion-ios-search-strong"></i>
              </button>
            </span>
          </div>
       {!! Form::close() !!} 
      </div>
   </div> 
  <div class="row"> 
    <!-- Page content goes here-->
    <div class="col-md-12"> 
      <!-- general form elements -->
      <div class="box box-primary">
        <!-- /.box-header -->
        <div class="box-body">
          <table id="instructorsgrid" class="table table-bordered table-striped">
            <thead>
              <tr> 
                <th>@lang('quickadmin.instructor.fields.first_name')</th>
                <th>@lang('quickadmin.instructor.fields.last_name')</th>
                <th>@lang('quickadmin.instructor.fields.title')</th>
                <th>@lang('quickadmin.instructor.fields.address')</th>
                <th>@lang('quickadmin.instructor.fields.city')</th>
                <th>@lang('quickadmin.instructor.fields.state_id')</th>
                <th>@lang('quickadmin.instructor.fields.zip')</th>
                <th>@lang('quickadmin.instructor.fields.submission_date')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($instructors) > 0)
            @foreach ($instructors as $instructor)
            <tr data-entry-id="{{ $instructor->instructor_id }}"> 
              <td field-key='first_name'>{{ $instructor->first_name }}</td>
              <td field-key='last_name'>{{ $instructor->last_name }}</td>
              <td field-key='title'>{{ $instructor->title }}</td>
              <td field-key='address_1'>{{ $instructor->address_1 }}</td>
              <td field-key='city'>{{ $instructor->city->city }}</td>
              <td field-key='state_id'>{{ $instructor->state->name }}</td>
              <td field-key='zip'>{{ $instructor->zip }}</td>
              <td field-key='created_at'>{{ DateFacades::dateFormat($instructor->created_at,'formate-4') }}</td>
              <td> 

               <a href="{{ route('member.instructors.edit',[$instructor->instructor_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>

               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['member.instructors.destroy', $instructor->instructor_id])) !!}
                {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                {!! Form::close() !!}

              </td>
            </tr>
            @endforeach
            @else
            <tr>
              <td colspan="10">@lang('quickadmin.qa_no_entries_in_table')</td>
            </tr>
            @endif
              </tbody>
            
          </table>
        </div>
        <div class="pagination" style="text-align:center">
         {!! $paging !!}
        </div>
        <!-- /.box-body --> 
      </div>
    </div>
    <!-- End page content--> 
  </div>
</section>
<!-- /.content -->
@endsection 