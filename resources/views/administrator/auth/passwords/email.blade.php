@extends('administrator.layouts.auth')

@section('content')
<div class="login-box">
  <div class="login-logo"> <a href="#"><b>Reset Password</b></a> </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
   @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
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
    <form data-parsley-validate class="form" method="POST" action="{{ route('administrator.password.email') }}">
        {{ csrf_field() }}
       <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <input id="email" type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
       </div>                        
       <div class="row">
        <!-- /.col -->
        <div class="col-xs-4">
          <div class="form-group">
               <button type="submit" class="btn btn btn-primary btn-flat">
                Send Password Reset Link
               </button>
           </div>
          </div>
        </div>
        <!-- /.col --> 
      </div>
    </form>
    <!--<a href="register.html" class="text-center">Register a new membership</a> 
  </div>
  <!-- /.login-box-body --> 
</div>
@endsection
