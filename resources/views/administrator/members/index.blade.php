@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.member.title') </h1>
  @can('member_add')
  <p class="text-right"><a href="{{ route('administrator.members.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
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
       	 {!! Form::open(['method' => 'get','name'=>'member-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.members.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'id'=>'search', 'placeholder' => 'Search Member','previous'=>'previous']) !!}         <span class="input-group-btn">
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
                <th>@lang('quickadmin.member.fields.first_name')</th>
                <th>@lang('quickadmin.member.fields.last_name')</th>
                <th>@lang('quickadmin.member.fields.user_name')</th>
                <th>@lang('quickadmin.member.fields.registered_date')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($members) > 0)
            @foreach ($members as $member)
            <tr data-entry-id="{{ $member->member_id }}"> 
              <td field-key='name'>{{ $member->first_name }}</td>
              <td field-key='name'>{{ $member->last_name }}</td>
              <td field-key='email'>
              	<a href="mailto:{{ $member->email }}" title="Mail to {{ $member->email }}">{{ $member->email }}</a>
              </td>
              <td field-key='created_date'>{{ DateFacades::dateFormat($member->created_at,'formate-2') }}</td>
          
              <td> 
              @can('member_edit')
               <a href="{{ route('administrator.members.edit',[$member->member_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
              @endcan
              @can('member_delete')
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['administrator.members.destroy', $member->member_id])) !!}
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
@endsection