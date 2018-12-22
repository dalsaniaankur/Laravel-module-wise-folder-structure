@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.lookup_for_player_experience.title') </h1>
 @can('players_looking_for_a_team_add')
  <p class="text-right"><a href="{{ route('administrator.lookup_for_player_experience.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
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
       	 {!! Form::open(['method' => 'get','name'=>'lookup_for_player_experience-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.lookup_for_player_experience.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search Lookup For Player Experience','previous'=>'previous']) !!}         
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
          <table id="lookup_for_player_experiencegrid" class="table table-bordered table-striped">
            <thead>
              <tr> 
                <th>@lang('quickadmin.lookup_for_player_experience.fields.record_number')</th>
                <th>@lang('quickadmin.lookup_for_player_experience.fields.player_first_name')</th>
                <th>@lang('quickadmin.lookup_for_player_experience.fields.player_last_name')</th>
                <th>@lang('quickadmin.lookup_for_player_experience.fields.player_phone_number')</th>
                <th>@lang('quickadmin.lookup_for_player_experience.fields.player_email')</th>
                <th>@lang('quickadmin.lookup_for_player_experience.fields.is_active')</th>
                <th>@lang('quickadmin.lookup_for_player_experience.fields.submission_date')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($lookup_for_player_experience) > 0)
            @foreach ($lookup_for_player_experience as $lookup_for_player_experience)
            <tr data-entry-id="{{ $lookup_for_player_experience->lookup_for_player_experience_id }}"> 
              <td field-key='lookup_for_player_experience_id'>{{ $lookup_for_player_experience->lookup_for_player_experience_id }}</td>
              <td field-key='player_first_name'>{{ $lookup_for_player_experience->player_first_name }}</td>
              <td field-key='player_last_name'>{{ $lookup_for_player_experience->player_last_name }}</td>
              <td field-key='player_phone_number'>{{ $lookup_for_player_experience->player_phone_number }}</td>
              <td field-key='player_email'>{{ $lookup_for_player_experience->player_email }}</td>
              <td field-key='is_active'>{{ $lookup_for_player_experience->is_active == 1 ? 'checked' : 'UnChecked' }}</td>
              <td field-key='created_at'>{{ DateFacades::dateFormat($lookup_for_player_experience->created_at,'formate-4') }}</td>
              <td>
                <a href="{{ url('administrator/lookup_for_player_experience_duplicate/'.$lookup_for_player_experience->lookup_for_player_experience_id) }}" class="btn btn-xs btn-info"> @lang('quickadmin.qa_duplicate')</a>
              @can('players_looking_for_a_team_edit')
               <a href="{{ route('administrator.lookup_for_player_experience.edit',[$lookup_for_player_experience->lookup_for_player_experience_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                @endcan
                @can('players_looking_for_a_team_delete')
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['administrator.lookup_for_player_experience.destroy', $lookup_for_player_experience->lookup_for_player_experience_id])) !!}
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
<script>
</script> 
@endsection
@section('javascript') 
<script>
	
</script> 
@endsection