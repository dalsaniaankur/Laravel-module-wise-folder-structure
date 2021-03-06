@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.tryout.title') </h1>
  @can('tryout_add')
  <p class="text-right"><a href="{{ url('administrator/tryout/create/'.$team_id) }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
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

       	 {!! Form::open(['url' => 'administrator/tryout/'.$team_id, 'method' => 'get','name'=>'tournament_organization-form', 'class'=>'form-horizontal validation-form','data-parsley-validate']) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control','id' => 'search', 'placeholder' => 'Search Tryout','previous'=>'previous']) !!}
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
          <table id="instructorsgrid" class="table table-bordered table-striped">
            <thead>
              <tr> 
                <th>@lang('quickadmin.tryout.fields.tryout_name')</th>
                <th>@lang('quickadmin.tryout.fields.team_name')</th>
                <th>@lang('quickadmin.tryout.fields.age_group_id')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($tryout) > 0)
            @foreach ($tryout as $data)
            <tr data-entry-id="{{ $data->tryout_id }}"> 
              <td field-key='tryout_id'>{{ $data->tryout_name }}</td>
              <td field-key='team_name'>{{ $data->team->name }}</td>
              <td field-key='age_group_id'>
                @foreach($data->age_group as $name)
                  @if (!$loop->first) - @endif {{ $name }}
                @endforeach
              </td>
              <td>
                <a href="{{ url('administrator/tryout_duplicate/'.$data->tryout_id.'/'.$team_id) }}" class="btn btn-xs btn-info"> @lang('quickadmin.qa_duplicate')</a>
               @can('tryout_agegroup')
               <a href="{{url('administrator/agegroup/'.$data->tryout_id)}}" class="btn btn-xs btn-info">@lang('quickadmin.tryout-age_group.title')</a>
               @endcan
               
               @can('tryout_edit')
               <a href="{{ url('administrator/tryout/edit/'.$data->tryout_id.'/'.$team_id) }}" class="btn btn-xs btn-info">
               @lang('quickadmin.qa_edit')</a>
               @endcan
               
               @can('tryout_delete')
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'url' => 'administrator/tryout/destroy/'.$data->tryout_id.'/'.$team_id
                
                )) !!}
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
@include('administrator.partials.popup_import_csv')
@endsection 