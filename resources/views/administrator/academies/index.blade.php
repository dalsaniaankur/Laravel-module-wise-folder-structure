@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.academy.title') </h1>
 @can('academie_add')
  <p class="text-right"><a href="{{ route('administrator.academies.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
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
       	 {!! Form::open(['method' => 'get','name'=>'academies-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.academies.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control search', 'id' => 'search', 'placeholder' => 'Search Academy','previous'=>'previous']) !!}
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
          <table id="academiesgrid" class="table table-bordered table-striped">
            <thead>
              <tr> 
                <th>@lang('quickadmin.academy.fields.name')</th>
                <th>@lang('quickadmin.academy.fields.address')</th>
                <th>@lang('quickadmin.academy.fields.city')</th>
                <th>@lang('quickadmin.academy.fields.state_id')</th>
                <th>@lang('quickadmin.academy.fields.zip')</th>
                <th>@lang('quickadmin.academy.fields.approval_status')</th>
                <th>@lang('quickadmin.academy.fields.submission_date')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($academies) > 0)
            @foreach ($academies as $academy)
            <tr data-entry-id="{{ $academy->academy_id }}"> 
              <td field-key='academy_name'>{{ $academy->academy_name }}</td>
              <td field-key='address_1'>{{ $academy->address_1 }}</td>
              <td field-key='city'>{{ $academy->city->city or "None"}}</td>
              <td field-key='state_id'>{{ $academy->state->name or "None" }}</td>
              <td field-key='zip'>{{ $academy->zip }}</td>
              <td field-key='approval_status'>{{ ucfirst($academy->approval_status) }}</td>
              <td field-key='created_at'>{{ DateFacades::dateFormat($academy->created_at,'formate-4') }}</td>
              <td>
              <a href="{{ url('administrator/academies_duplicate/'.$academy->academy_id) }}" class="btn btn-xs btn-info"> @lang('quickadmin.qa_duplicate')</a>
              @can('academie_edit')
               <a href="{{ route('administrator.academies.edit',[$academy->academy_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
              @endcan
              @can('academie_delete')
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['administrator.academies.destroy', $academy->academy_id])) !!}
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