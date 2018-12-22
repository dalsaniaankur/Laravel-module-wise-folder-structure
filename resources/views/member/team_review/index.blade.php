@extends('member.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.team_review.title') </h1>
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
       	 {!! Form::open(['method' => 'get','name'=>'team_review-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['member.team_review.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search Team Review','previous'=>'previous']) !!}
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
          <table id="team_reviewsgrid" class="table table-bordered table-striped">
            <thead>
              <tr> 
                <th>@lang('quickadmin.team_review.fields.member_first_name')</th>
                <th>@lang('quickadmin.team_review.fields.member_last_name')</th>
                <th>@lang('quickadmin.team_review.fields.review_for_first_name')</th>
                <th>@lang('quickadmin.team_review.fields.is_active')</th>
                <th>@lang('quickadmin.team_review.fields.submission_date')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($team_review) > 0)
            @foreach ($team_review as $data)
            <tr data-entry-id="{{ $data->review_id }}"> 
              <td field-key='member_first_name'>{{ $data->member_first_name }}</td>
              <td field-key='member_last_name'>{{ $data->member_last_name }}</td>
              <td field-key='name'>{{ $data->name }}</td>
              <td field-key='is_active'>{{ ($data->is_active == 1 ) ? 'checked' : 'unChecked' }}</td>
              <td field-key='created_at'>{{ DateFacades::dateFormat($data->created_at,'formate-4') }}</td>
              <td> 
               <a href="{{ route('member.team_review.edit',[$data->review_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
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