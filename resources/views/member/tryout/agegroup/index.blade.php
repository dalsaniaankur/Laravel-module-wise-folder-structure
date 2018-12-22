@extends('member.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.tryout-age_group.title') </h1>
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

       	 {!! Form::open(['url' => 'member/agegroup/'.$tryout_id, 'method' => 'get','name'=>'tournament_organization-form', 'class'=>'form-horizontal validation-form','data-parsley-validate']) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search Age Group','previous'=>'previous']) !!}           
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
                <th>@lang('quickadmin.tryout-age_group.fields.name')</th>
                <th>@lang('quickadmin.tryout-age_group.fields.tryout')</th>
                <th>@lang('quickadmin.tryout-age_group.fields.team_name')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($agegroup) > 0)
            @foreach ($agegroup as $data)
            <tr data-entry-id="{{ $data->age_group_id }}"> 
              <td field-key='name'>{{ $data->name }}</td>
              <td field-key='name'>{{ $tryout_name }}</td>
              <td field-key='name'>{{ $team_name }}</td>
              <td> 
               <a href="{{url('member/agegroup_position/'.$data->age_group_id.'/'.$tryout_id)}}" class="btn btn-xs btn-info">@lang('quickadmin.tryout-age_group.position')</a>
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