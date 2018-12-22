@extends('member.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.showcase_or_prospect.title') </h1>
  <p class="text-right"><a href="{{ route('member.showcase_or_prospect.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
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
       	 {!! Form::open(['method' => 'get','name'=>'camp-or-clinic-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['member.showcase_or_prospect.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search Showcase  Or Prospect','previous'=>'previous'	]) !!}           
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
                <th>@lang('quickadmin.showcase_or_prospect.fields.showcase_or_prospect_camp')</th>
                <th>@lang('quickadmin.showcase_or_prospect.fields.age_groups')</th>
                <th>@lang('quickadmin.showcase_or_prospect.fields.type_of_event')</th>
                <th>@lang('quickadmin.showcase_or_prospect.fields.address')</th>
                <th>@lang('quickadmin.showcase_or_prospect.fields.showcase_organization_id')</th>
                <th>@lang('quickadmin.showcase_or_prospect.fields.submission_date')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($showcaseOrProspects) > 0)
            @foreach ($showcaseOrProspects as $showcaseOrProspect)
            <tr data-entry-id="{{ $showcaseOrProspect->showcase_or_prospect_id }}"> 
              <td field-key='name'>{{ $showcaseOrProspect->name }}</td>
              <td field-key='age_group'>
                @foreach($showcaseOrProspect->age_group as $name)
                  @if (!$loop->first) - @endif {{ $name }}
               @endforeach</td>
              <td field-key='type'>{{ ($showcaseOrProspect->type ==1) ? 'Showcase' : 'Prospect Camp' }}</td>
              <td field-key='address'>{{ $showcaseOrProspect->address_1 }}</td>
              <td field-key='showcaseOrganization'>{{ $showcaseOrProspect->showcaseorganization->name }}</td>
              <td field-key='created_at'>{{ DateFacades::dateFormat($showcaseOrProspect->created_at,'formate-4') }}</td>
              <td>
               <a href="{{ route('member.showcase_or_prospect.edit',[$showcaseOrProspect->showcase_or_prospect_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['member.showcase_or_prospect.destroy', $showcaseOrProspect->showcase_or_prospect_id])) !!}
                {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                {!! Form::close() !!}
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