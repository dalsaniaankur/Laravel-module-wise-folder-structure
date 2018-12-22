@extends('administrator.layouts.app')
@section('content') 
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> @lang('quickadmin.email_template.title') </h1>
  @can('email_template_add')
  <p class="text-right"><a href="{{ route('administrator.email_template.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a></p>
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
      @endif 
      <div class="alert alert-success send_mail_message">
        <p></p>
     </div>
     <div class="alert alert-danger send_mail_message">
        <p></p>
     </div>
      </div>
  </div>

   <div class="content-header-with-cell">
      <div class="custom-search-block">
       	 {!! Form::open(['method' => 'get','name'=>'email_template-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['administrator.email_template.index']]) !!}
          <div class="input-group input-group-lg">
           {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Search Email Template','previous'=>'previous']) !!}         
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
          <table id="email_templatesgrid" class="table table-bordered table-striped">
            <thead>
              <tr> 
                <th>@lang('quickadmin.email_template.fields.entity')</th>
                <th>@lang('quickadmin.email_template.fields.subject')</th>
                <th>@lang('quickadmin.email_template.fields.last_email_sent_date')</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            @if (count($email_template) > 0)
            @foreach ($email_template as $email_template)
            <tr data-entry-id="{{ $email_template->email_template_id }}"> 
              <td field-key='entity'>{{ $email_template->entity }}</td>
              <td field-key='subject'>{{ $email_template->subject }}</td>
              <td field-key='last_email_sent_date'>{{ ($email_template->last_email_sent_date != '0000-00-00 00:00:00' && $email_template->last_email_sent_date != ' ' && $email_template->last_email_sent_date != NULL )
                ? DateFacades::dateFormat($email_template->last_email_sent_date,'formate-3') : 'N/A' }}</td>
              <td> 
              @can('email_template_edit')
               <a href="{{ route('administrator.email_template.edit',[$email_template->email_template_id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
              @endcan
               @can('email_template_delete')
               {!! Form::open(array(
                'style' => 'display: inline-block;',
                'method' => 'DELETE',
                'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                'route' => ['administrator.email_template.destroy', $email_template->email_template_id])) !!}
                {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                {!! Form::close() !!}
                @endcan 
                @can('email_template_send')
                <a href="#" class="btn btn-xs btn btn-success" onclick="ajaxCall('{{ $email_template->entity }}');">@lang('quickadmin.qa_send_email')</a>
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
@section('javascript') 
<script>
  
  function hideAllMessage(){
    $(".alert.alert-success.send_mail_message").hide();
    $(".alert.alert-danger.send_mail_message").hide();
  }

  function hideLoader(){
    $("div#loader").hide();
  }

  function showLoader(){
    $("div#loader").show();
  }

  function showMessage(selector, message){
    $(selector +" p").html(message);
    $(selector).show();
  }

	function ajaxCall(customrUrl){

    showLoader();
    hideAllMessage();

    var ajaxCallUrl ='./send_mail/' + customrUrl;
    
    jQuery.ajax({
        url: ajaxCallUrl,
        method: 'get',
        data: {
        },
        success: function(response){

          if(response.success == true){
            showMessage(".alert.alert-success.send_mail_message", response.message);
          }else{
            showMessage(".alert.alert-danger.send_mail_message", response.message);
          }

          hideLoader();
        },
        
        error: function (xhr, status) {  
          hideLoader();
        } 

      });
    }
</script>  
@endsection 