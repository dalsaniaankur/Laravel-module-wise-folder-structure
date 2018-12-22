@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.camp_or_clinic.title') </h1>
  @can('camp_or_clinic_add')
  <p class="text-right"><a href="{{ route('administrator.camp_or_clinic.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
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
       	 {!! Form::open(['method' => 'get','name'=>'camp-or-clinic-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.camp_or_clinic.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search Camp Or Clinic','previous'=>'previous'	]) !!}           <span class="input-group-btn">
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
                <th>@lang('quickadmin.camp_or_clinic.fields.name')</th>
                <th>@lang('quickadmin.camp_or_clinic.fields.date')</th>
                <th>@lang('quickadmin.camp_or_clinic.fields.age_groups')</th>
                <th>@lang('quickadmin.camp_or_clinic.fields.type_of_event')</th>
                <th>@lang('quickadmin.camp_or_clinic.fields.address')</th>
                <th>@lang('quickadmin.camp_or_clinic.fields.showcase_organization_id')</th>
                <th>@lang('quickadmin.camp_or_clinic.fields.submission_date')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>

            @if (count($campOrClinics) > 0)
            @foreach ($campOrClinics as $campOrClinic)
            <tr data-entry-id="{{ $campOrClinic->camp_clinic_id }}"> 
              <td field-key='name'>{{ $campOrClinic->name }}</td>
              <td field-key='date'>{{ $campOrClinic->date }}</td>
              <td field-key='age_group_id'>
               @foreach($campOrClinic->age_group as $name)
                  @if (!$loop->first) - @endif {{ $name }}
               @endforeach
              </td>
              <td field-key='type'>{{ ($campOrClinic->type == 1) ? 'Camp' : 'Clinic' }}</td>
              <td field-key='address'>{{ $campOrClinic->address_1 }}</td>
              <td field-key='showcase_organization_id'> {{ $campOrClinic->showcaseorganization->name}} </td>
              <td field-key='created_at'>{{ DateFacades::dateFormat($campOrClinic->created_at,'formate-4') }}</td>
              <td>
                <a href="{{ url('administrator/camp_or_clinic_duplicate/'.$campOrClinic->camp_clinic_id) }}" class="btn btn-xs btn-info"> @lang('quickadmin.qa_duplicate')</a>
               @can('camp_or_clinic_edit')
               <a href="{{ route('administrator.camp_or_clinic.edit',[$campOrClinic->camp_clinic_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                @endcan 
               @can('camp_or_clinic_delete') 
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['administrator.camp_or_clinic.destroy', $campOrClinic->camp_clinic_id])) !!}
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
<!-- page script --> 
@endsection