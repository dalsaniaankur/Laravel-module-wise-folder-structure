<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('partials.head')
<body class="sbc">
	<div class="loader" id="loader"></div>
  <div class="page-wrapper">
    @include('partials.header')
    @yield('content')  
    @include('partials.footer')
  </div>
  @include('partials.javascripts')
</body>
</html>