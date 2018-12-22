@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.age_group_position.title') </h1>
  <p class="text-right">
    @can('tryout_agegroup_position_add')
    <a href="{{ url('administrator/agegroup_position/create/'.$age_group_id.'/'.$tryout_id) }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
    @endcan
  </p>
  
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

       	 {!! Form::open(['url' => 'administrator/agegroup_position/'.$age_group_id.'/'.$tryout_id, 'method' => 'get','name'=>'tournament_organization-form', 'class'=>'form-horizontal validation-form','data-parsley-validate']) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search Position','previous'=>'previous']) !!}           
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
                <th>@lang('quickadmin.age_group_position.fields.position_id')</th>
                <th>@lang('quickadmin.age_group_position.fields.age_group')</th>
                <th>@lang('quickadmin.age_group_position.fields.tryout')</th>
                <th>@lang('quickadmin.age_group_position.fields.team_name')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($ageGroupPosition) > 0)
            @foreach ($ageGroupPosition as $data)
            <tr data-entry-id="{{ $data->agegroup_position_id }}"> 
              <td field-key='position_id'>{{ $data->position->name }}</td>
              <td field-key='position_id'>{{ $data->agegroup->name }}</td>
              <td field-key='position_id'>{{ $data->tryout->tryout_name }}</td>
              <td field-key='position_id'>{{ $team_name }}</td>
              <td> 
               @can('tryout_agegroup_position_edit')
               <a href="{{ url('administrator/agegroup_position/edit/'.$data->agegroup_position_id.'/'.$age_group_id.'/'.$tryout_id) }}" class="btn btn-xs btn-info">
               @lang('quickadmin.qa_edit')</a>
              @endcan
              @can('tryout_agegroup_position_delete')
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'url' => 'administrator/agegroup_position/destroy/'.$data->agegroup_position_id.'/'.$age_group_id.'/'.$tryout_id
                
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
@endsection 