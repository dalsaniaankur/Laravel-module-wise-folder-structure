@extends('administrator.layouts.auth')
@section('content')

<div class="login-box">
  <div class="login-logo"> <a href="#"><b>Reset Password</b></a> </div>
  <div class="login-box-body"> @if (session('status'))
    <div class="alert alert-success"> {{ session('status') }} </div>
    @endif
    @if (count($errors) > 0)
    <div class="alert alert-danger"> <strong>@lang('quickadmin.qa_whoops')</strong> @lang('quickadmin.qa_there_were_problems_with_input'): <br>
      <br>
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    <form data-parsley-validate  class="form" method="POST" action="{{ route('administrator.password.reset') }}">
      {{ csrf_field() }}
      <input type="hidden" name="token" value="{{ $token }}">
      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <input id="email" type="email" required class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required>
        @if ($errors->has('email')) <span class="help-block"> <strong>{{ $errors->first('email') }}</strong> </span> @endif </div>
      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <input id="password" type="password" required placeholder="Password" class="form-control" name="password" required>
        @if ($errors->has('password')) <span class="help-block"> <strong>{{ $errors->first('password') }}</strong> </span> @endif </div>
      <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <input id="password-confirm" data-parsley-equalto="#password" required placeholder="Confirm Password"  type="password" class="form-control" name="password_confirmation" data-parsley-equalto-message ="Password and confirm password should be same." required>
        @if ($errors->has('password_confirmation')) <span class="help-block"> <strong>{{ $errors->first('password_confirmation') }}</strong> </span> @endif </div>
      <div class="row"> 
        <!-- /.col -->
        <div class="col-xs-4">
          <div class="form-group">
            <button type="submit" class="btn btn btn-primary btn-flat"> Reset Password </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
