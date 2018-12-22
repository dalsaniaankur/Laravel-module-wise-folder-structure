@extends('member.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.coaches_needed.title') </h1>
  <p class="text-right"><a href="{{ route('member.coaches_needed.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
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
       	 {!! Form::open(['method' => 'get','name'=>'coaches_needed-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['member.coaches_needed.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search Coaches Needed','previous'=>'previous']) !!}         
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
          <table id="coaches_neededgrid" class="table table-bordered table-striped">
            <thead>
              <tr> 
                <th>@lang('quickadmin.coaches_needed.fields.record_number')</th>
                <th>@lang('quickadmin.coaches_needed.fields.team')</th>
                <th>@lang('quickadmin.coaches_needed.fields.contact_first_name')</th>
                <th>@lang('quickadmin.coaches_needed.fields.contact_last_name')</th>
                <th>@lang('quickadmin.coaches_needed.fields.phone_number')</th>
                <th>@lang('quickadmin.coaches_needed.fields.email')</th>
                <th>@lang('quickadmin.coaches_needed.fields.submission_date')</th>
                <th>@lang('quickadmin.coaches_needed.fields.is_active')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($coaches_needed) > 0)
            @foreach ($coaches_needed as $coaches_needed)
            <tr data-entry-id="{{ $coaches_needed->coaches_needed_id }}"> 
              <td field-key='coaches_needed_id'>{{ $coaches_needed->coaches_needed_id }}</td>
              <td field-key='team_id'>{{ $coaches_needed->team->name }}</td>
              <td field-key='contact_first_name'>{{ $coaches_needed->contact_first_name }}</td>
              <td field-key='contact_last_name'>{{ $coaches_needed->contact_last_name }}</td>
              <td field-key='phone_number'>{{ $coaches_needed->phone_number }}</td>
              <td field-key='email'>{{ $coaches_needed->email }}</td>
              <td field-key='created_at'>{{ DateFacades::dateFormat($coaches_needed->created_at,'formate-4') }}</td>
              <td field-key='is_active'>{{ $coaches_needed->is_active == 1 ? 'checked' : 'UnChecked' }}</td>
              <td> 
               <a href="{{ route('member.coaches_needed.edit',[$coaches_needed->coaches_needed_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['member.coaches_needed.destroy', $coaches_needed->coaches_needed_id])) !!}
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