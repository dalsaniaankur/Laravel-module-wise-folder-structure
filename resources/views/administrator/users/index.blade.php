@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.user.title') </h1>
  @can('user_management_add')
  <p class="text-right"><a href="{{ route('administrator.users.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
  @endcan
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
       	 {!! Form::open(['method' => 'get','name'=>'user-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.users.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search User','previous'=>'previous']) !!}
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
          <table id="usersgrid" class="table table-bordered table-striped">
            <thead>
              <tr> 
                <!--@can('user_delete')
                 <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                @endcan-->
                <th>@lang('quickadmin.user.fields.first_name')</th>
                <th>@lang('quickadmin.user.fields.last_name')</th>
                <th>@lang('quickadmin.user.fields.email')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($users) > 0)
            @foreach ($users as $user)
            <tr data-entry-id="{{ $user->user_id }}"> 
              <td field-key='first_name'>{{ $user->first_name }}</td>
              <td field-key='last_name'>{{ $user->last_name }}</td>
              <td field-key='username'>{{ $user->email }}</td>
              <td>  
              @can('user_management_edit')
              <a href="{{ route('administrator.users.edit',[$user->user_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
              @endcan
               @can('user_management_delete')
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['administrator.users.destroy', $user->user_id])) !!}
                {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                {!! Form::close() !!}
                @endcan
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