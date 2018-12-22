@extends('administrator.layouts.auth')
@section('content')
<div class="login-box">
  <div class="login-logo"> <a href="#"><b>Administrator Login</b></a> </div>
  <!-- /.login-logo -->
  <div class="login-box-body"> @if (count($errors) > 0)
    <div class="alert alert-danger"> <strong>@lang('quickadmin.qa_whoops')</strong> @lang('quickadmin.qa_there_were_problems_with_input'): <br>
      <br>
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    
    <form data-parsley-validate class="form"
                          role="form"
                          method="POST"
                          action="{{ url('administrator_login') }}">
      <input type="hidden"
                               name="_token"
                               value="{{ csrf_token() }}">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" required
                                       name="email"
                                       value="{{ old('email') }}"  placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span> </div>
      <div class="form-group has-feedback">
        <input type="password" required class="form-control" name="password" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span> </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember">
              @lang('quickadmin.qa_remember_me')</label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat" style="margin-right: 15px;"> @lang('quickadmin.qa_login') </button>
        </div>
        <!-- /.col --> 
      </div>
    </form>
    <a href="{{ route('administrator.password.reset')}}">@lang('quickadmin.qa_forgot_password')</a>
    <!-- /.login-box-body --> 
</div>
<!-- END Mahendra--> 
@endsection