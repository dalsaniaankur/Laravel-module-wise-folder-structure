@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.banner_ads_category.title') </h1>
   @can('banner_ads_category_add')
  <p class="text-right"><a href="{{ route('administrator.banner_ads_category.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
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
       	 {!! Form::open(['method' => 'get','name'=>'event-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.banner_ads_category.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search Banner Ads Category','previous'=>'previous']) !!}         
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
          <table id="eventsgrid" class="table table-bordered table-striped">
            <thead>
              <tr> 
                <th>@lang('quickadmin.banner_ads_category.fields.name')</th>
                <th>@lang('quickadmin.banner_ads_category.fields.reservation_category_for')</th>
                <th>@lang('quickadmin.banner_ads_category.fields.sort')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($bannerAdsCategory) > 0)
            @foreach ($bannerAdsCategory as $data)
            <tr data-entry-id="{{ $data->banner_ads_category_id }}"> 
              <td field-key='title'>{{ $data->name }}</td>
              <td field-key='title'>{{ $data->reservation_category_for }}</td>
              <td field-key='title'>{{ $data->sort }}</td>
              
              <td> 
              @can('banner_ads')  
              <a href="{{url('administrator/banner_ads/'.$data->banner_ads_category_id)}}" class="btn btn-xs btn-info">@lang('quickadmin.banner_ads_category.banner_ads')</a>
              @endcan
              @can('banner_ads_category_edit')
               <a href="{{ route('administrator.banner_ads_category.edit',[$data->banner_ads_category_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
              @endcan
              @can('banner_ads_category_delete')
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['administrator.banner_ads_category.destroy', $data->banner_ads_category_id])) !!}
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