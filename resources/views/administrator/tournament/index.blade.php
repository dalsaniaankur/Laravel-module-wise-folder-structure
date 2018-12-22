@extends('administrator.layouts.app')
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> @lang('quickadmin.tournament.title') </h1>
    @can('tournament_add')
      <p class="text-right"><a href="{{ url('administrator/tournament/create/'.$tournament_organization_id) }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
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

        {!! Form::open(['url' => 'administrator/tournament/'.$tournament_organization_id, 'method' => 'get','name'=>'tournament_organization-form', 'class'=>'form-horizontal validation-form','data-parsley-validate']) !!}
        <div class="input-group input-group-lg">
          {!! Form::text('search', old('search'), ['id' => 'search', 'class' => 'form-control', 'placeholder' => 'Search Tournament','previous'=>'previous']) !!}
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
                <th>@lang('quickadmin.tournament.fields.tournament_name')</th>
                <th>@lang('quickadmin.tournament.fields.age_group_id')</th>
                <th >@lang('quickadmin.tournament.fields.start_date')</th>
                <th >@lang('quickadmin.tournament.fields.end_date')</th>
                <th>&nbsp;</th>
              </tr>
              </thead>
              <tbody>
              @if (count($tournament) > 0)
                @foreach ($tournament as $data)
                  <tr data-entry-id="{{ $data->tournament_id }}">
                    <td field-key='tournament_id'>{{ $data->tournament_name }}</td>
                    <td field-key='age_group_id'>
                      @foreach($data->age_group as $name)
                        @if (!$loop->first) - @endif {{ $name }}
                      @endforeach
                    </td>

                    <td field-key='start_date'>{{ (!empty($data->start_date) && $data->start_date != '0000-00-00') ? DateFacades::dateFormat($data->start_date,'formate-3') : 'N/A'}}</td>
                    <td field-key='end_date'>{{ (!empty($data->end_date) && $data->end_date != '0000-00-00') ? DateFacades::dateFormat($data->end_date,'formate-3') : 'N/A'}}</td>
                    <td>
                      <a href="{{ url('administrator/tournament_duplicate/'.$data->tournament_id.'/'.$tournament_organization_id) }}" class="btn btn-xs btn-info"> @lang('quickadmin.qa_duplicate')</a>
                      @can('tournament_edit')
                        <a href="{{ url('administrator/tournament/edit/'.$data->tournament_id.'/'.$tournament_organization_id) }}" class="btn btn-xs btn-info">
                          @lang('quickadmin.qa_edit')</a>
                      @endcan

                      @can('tournament_delete')
                        {!! Form::open(array(
                         'style' => 'display: inline-block;',
                         'method' => 'DELETE',
                         'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                         'url' => 'administrator/tournament/destroy/'.$data->tournament_id.'/'.$tournament_organization_id

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