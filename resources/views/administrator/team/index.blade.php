@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.teams.title') </h1>
  @can('team_add')
  <p class="text-right"><a href="{{ route('administrator.teams.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
  @endcan
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
       	 {!! Form::open(['method' => 'get','name'=>'team-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.teams.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'id' => 'search', 'placeholder' => 'Search Team','previous'=>'previous']) !!}
           <span class="input-group-btn">
              <button class="btn btn-default" type="submit">
                <i class="ion-ios-search-strong"></i>
              </button>
              <button class="btn btn-default" type="button" id='export_csv' onclick="ExportCsv(['search'], '{{ $entity }}')" title="Export CSV">
                <i class="ion-ios-cloud-download-outline"></i>
             </button>
             <button class="btn btn-default" type="button" id='import_csv' onclick="OpenImportCsvModel()" title="Import CSV">
                <i class="ion-ios-cloud-upload-outline"></i>
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
          <table id="teamgrid" class="table table-bordered table-striped">
            <thead>
              <tr> 
                <th>@lang('quickadmin.teams.fields.name')</th>
                <th>@lang('quickadmin.teams.fields.address')</th>
                <th>@lang('quickadmin.teams.fields.city')</th>
                <th>@lang('quickadmin.teams.fields.state')</th>
                <th>@lang('quickadmin.teams.fields.zip')</th>
                <th>@lang('quickadmin.teams.fields.approval_status')</th>
                <th>@lang('quickadmin.teams.fields.submission_date')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($teams) > 0)
            @foreach ($teams as $data)
            <tr data-entry-id="{{ $data->team_id }}">
              <td field-key='first_name'>{{ $data->name }}</td>
              <td field-key='last_name'>{{ $data->address_1 }}</td>
              <td field-key='title'>{{ $data->city->city or 'None'}}</td>
              <td field-key='title'>{{ $data->state->name or '' }}</td>
              <td field-key='title'>{{ $data->zip or '' }}</td>
              <td field-key='approval_status'>{{ ucfirst($data->approval_status) }}</td>
              <td field-key='created_at'>{{ DateFacades::dateFormat($data->created_at,'formate-4') }}</td>
              <td>
                <a href="{{ url('administrator/team_duplicate/'.$data->team_id) }}" class="btn btn-xs btn-info"> @lang('quickadmin.qa_duplicate')</a>
               @can('tryouts')
               <a href="{{url('administrator/tryout/'.$data->team_id)}}" class="btn btn-xs btn-info">@lang('quickadmin.tryout.title')</a>
               @endcan
               
               @can('team_edit')
               <a href="{{ route('administrator.teams.edit',[$data->team_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')
               </a>
               @endcan
               @can('team_delete')
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['administrator.teams.destroy', $data->team_id])) !!}
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
@include('administrator.partials.popup_import_csv')
<!-- /.content --> 
@endsection