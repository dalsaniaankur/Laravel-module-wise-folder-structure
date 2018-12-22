@extends('administrator.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.banner_ads.title') </h1>
   @can('banner_ads_add')
  <p class="text-right"><a href="{{ url('administrator/banner_ads/create/'.$banner_ads_category_id) }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
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

       	 {!! Form::open(['url' => 'administrator/banner_ads/'.$banner_ads_category_id, 'method' => 'get','name'=>'tournament_organization-form', 'class'=>'form-horizontal validation-form','data-parsley-validate']) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search Banner Ad','previous'=>'previous']) !!}
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
                <th>@lang('quickadmin.banner_ads.fields.title')</th>
                <th>@lang('quickadmin.banner_ads.fields.banner_ads_category')</th>
                <th>@lang('quickadmin.banner_ads.fields.type')</th>
                <th>@lang('quickadmin.banner_ads.fields.forward_url')</th>
                <th>@lang('quickadmin.banner_ads.fields.sort')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($bannerAds) > 0)
            @foreach ($bannerAds as $data)
            <tr data-entry-id="{{ $data->banner_ads_id }}"> 
              <td field-key='banner_ads_id'>{{ $data->title }}</td>
              <td field-key='banner_ads_id'>{{ $data->bannerAdsCategory->name }}</td>
              <td field-key='banner_ads_id'>{{ $data->type }}</td>
              <td field-key='banner_ads_id'>{{ $data->forward_url }}</td>
              <td field-key='banner_ads_id'>{{ $data->sort }}</td>
              <td> 
               </a>
               @can('banner_ads_edit')
               <a href="{{ url('administrator/banner_ads/edit/'.$data->banner_ads_id.'/'.$banner_ads_category_id) }}" class="btn btn-xs btn-info">
               @lang('quickadmin.qa_edit')</a>
              @endcan
              @can('banner_ads_delete')
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'url' => 'administrator/banner_ads/destroy/'.$data->banner_ads_id.'/'.$banner_ads_category_id
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