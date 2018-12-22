<?php $theme='skin_blue'; $theme=\Config::get('administrator-configuration.default_theme');?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	@include('member.partials.head')
    @include('member.partials.javascripts')
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="loader" id="loader"></div>
    <div class="wrapper">
    @include('member.partials.topbar')
    @include('member.partials.sidebar')
   <div class="content-wrapper">
     @yield('content')
  </div>
  <!-- /.content-wrapper -->
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <!-- Default to the left -->
    <strong>Copyright &copy; 2018 <a href="https://softballconnected.com" target="_blank">SOFTBALLCONNECTED.COM</a></strong> All rights reserved.
  </footer>
  {!! Form::open(['route' => 'member_logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">Logout</button>
{!! Form::close() !!}
 </body>
</html>
