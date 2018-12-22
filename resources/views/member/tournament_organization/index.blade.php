@extends('member.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.tournament_organization.title') </h1>
  <p class="text-right"><a href="{{ route('member.tournament_organizations.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
</section>
<!-- Main content -->
<section class="content"> 
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
       	 {!! Form::open(['method' => 'get','name'=>'tournament_organization-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['member.tournament_organizations.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search Tournament Organization','previous'=>'previous']) !!}           <span class="input-group-btn">
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
                <th>@lang('quickadmin.tournament_organization.fields.name')</th>
                <th>@lang('quickadmin.tournament_organization.fields.address')</th>
                <th>@lang('quickadmin.tournament_organization.fields.city')</th>
                <th>@lang('quickadmin.tournament_organization.fields.state_id')</th>
                <th>@lang('quickadmin.tournament_organization.fields.zip')</th>
                <th>@lang('quickadmin.tournament_organization.fields.submission_date')</th>
                <th>@lang('quickadmin.tournament_organization.fields.approval_status')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($tournamentOrganizations) > 0)
            @foreach ($tournamentOrganizations as $data)
            <tr data-entry-id="{{ $data->team_id }}">
              <td field-key='first_name'>{{ $data->name }}</td>
              <td field-key='address'>{{ $data->address_1 }} {{ $data->address_2}}</td>
              <td field-key='last_name'>{{ $data->city->city }}</td>
              <td field-key='state'>{{ $data->state->name or '' }}</td>
              <td field-key='zip'>{{ $data->zip }}</td>
              <td field-key='created_at'>{{ DateFacades::dateFormat($data->created_at,'formate-4') }}</td>
              <td field-key='approval_status'>{{ ucfirst($data->approval_status) }} </td>
              <td> 
                
               <a href="{{url('member/tournament/'.$data->tournament_organization_id)}}" class="btn btn-xs btn-info">@lang('quickadmin.tournament_organization.tournaments')</a>
               
               
               <a href="{{ route('member.tournament_organizations.edit',[$data->tournament_organization_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
              
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['member.tournament_organizations.destroy', $data->tournament_organization_id])) !!}
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