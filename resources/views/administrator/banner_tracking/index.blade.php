@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.banner_tracking.title') </h1>
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
       	 {!! Form::open(['method' => 'get','name'=>'banner-tracking-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.banner_tracking.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control tracking-search', 'id' => 'search', 'placeholder' => 'Search By Banner Title Or Page Url Or Redirect Link']) !!}
           {!! Form::text('start_date', old('start_date'), ['class' => 'form-control tracking-start-date', 'placeholder' => 'From Date',  'id' => 'start_date', 'autocomplete' => 'off' ]) !!}         
           {!! Form::text('end_date', old('end_date'), ['class' => 'form-control tracking-end-date', 'placeholder' => 'End Date', 'id' => 'end_date', 'autocomplete' => 'off']) !!}         

           <span class="input-group-btn">
              <button class="btn btn-default" type="submit" title="Search">
                <i class="ion-ios-search-strong"></i>
              </button>
              <button class="btn btn-default" type="button" id='export_banner_tracking' onclick="ExportBannerTracking()" title="Export CSV">
                <i class="ion-ios-download-outline"></i>
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
                <th>@lang('quickadmin.banner_tracking.fields.banner_ads_id')</th>
                <th>@lang('quickadmin.banner_tracking.fields.page_url')</th>
                <th>@lang('quickadmin.banner_tracking.fields.ip_address')</th>
                <th>@lang('quickadmin.banner_tracking.fields.banner_redirect_link')</th>
                <th>@lang('quickadmin.banner_tracking.fields.created_at')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($bannerTracking) > 0)
            @foreach ($bannerTracking as $data)
            <tr data-entry-id="{{ $data->banner_tracking_id }}"> 
              <td field-key='title'>{{ $data->banner_ads_title }}</td>
              <td field-key='title'>{{ $data->page_url }}</td>
              <td field-key='title'>{{ $data->ip_address }}</td>
              <td field-key='title'>{{ $data->banner_redirect_link }}</td>
              <td field-key='created_at'>{{ DateFacades::dateFormat($data->created_at,'formate-4') }}</td>
              <td> 
              @can('banner_tracking_edit')
               <a href="{{ route('administrator.banner_tracking.edit',[$data->banner_tracking_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
              @endcan
              @can('banner_tracking_delete')
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['administrator.banner_tracking.destroy', $data->banner_tracking_id])) !!}
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
@section('javascript')
<script type="text/javascript">
window.export_csv_for_banner_tracking_url ="{{ URL::to('administrator/export_csv_for_banner_tracking') }}";
function ExportBannerTracking(){

   showLoader();
   var search = $('#search').val();
   var start_date = $('#start_date').val();
   var end_date = $('#end_date').val();
   
   jQuery.ajax({
      url: export_csv_for_banner_tracking_url,
      method: 'post',
      dataType: 'JSON',
      data : { '_token': _token , 'search' : search, 'start_date': start_date, 'end_date': end_date },
      success: function(response){

        if(response.success ==true){
          window.location.href = response.file_url;
        }
        hideLoader();
      },
      error: function (xhr, status) {  
        hideLoader();
        alert("Something went wrong");
      } 
  });
}  
</script>
@endsection