@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.page_builder.title') </h1>
 @can('page_builder_add')
  <p class="text-right"><a href="{{ route('administrator.page_builder.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
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
       	 {!! Form::open(['method' => 'get','name'=>'page_builder-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.page_builder.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search Page Builder','previous'=>'previous']) !!}         
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
          <table id="page_buildergrid" class="table table-bordered table-striped">
            <thead>
              <tr> 
                <th>@lang('quickadmin.page_builder.fields.page_title')</th>
                <th>@lang('quickadmin.page_builder.fields.url_key')</th>
                <th>@lang('quickadmin.page_builder.fields.display_banner_ads')</th>
                <th>@lang('quickadmin.page_builder.fields.filter_table')</th>
                <th>@lang('quickadmin.page_builder.fields.city')</th>
                <th>@lang('quickadmin.page_builder.fields.state_id')</th>
                <th>@lang('quickadmin.page_builder.fields.status')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($page_builder) > 0)
            @foreach ($page_builder as $data)
            <tr data-entry-id="{{ $data->page_builder_id }}"> 
              <td field-key='page_title'>{{ $data->page_title }}</td>
              <td field-key='url_key'>{{ $data->url_key }}</td>
              <td field-key='display_banner_ads '>{{ $data->display_banner_ads == 1 ? 'Yes' : 'No' }}</td>
              <td field-key='filter_table'>{{ $data->filter_table }}</td>
              <td field-key='city'>{{ $data->city->city or 'None' }}</td>
              <td field-key='state_id'>{{ $data->state->name }}</td>
              <td field-key='status'>{{ $data->status ==1 ? 'Active' : 'InActive' }}</td>
              <td>
                <a href="{{ url('administrator/page_builder_duplicate/'.$data->page_builder_id) }}" class="btn btn-xs btn-info"> @lang('quickadmin.qa_duplicate')</a>
              @can('page_builder_edit')
               <a href="{{ route('administrator.page_builder.edit',[$data->page_builder_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
              @endcan
              @can('page_builder_delete')
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['administrator.page_builder.destroy', $data->page_builder_id])) !!}
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