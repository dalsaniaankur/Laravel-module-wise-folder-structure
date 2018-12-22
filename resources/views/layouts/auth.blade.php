<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('partials.head')
<body class="sbc">
  <div class="page-wrapper">
    @include('partials.header')
    @yield('content')  
    @include('partials.footer')
  </div>
  @include('partials.javascripts')
</body>
</html>