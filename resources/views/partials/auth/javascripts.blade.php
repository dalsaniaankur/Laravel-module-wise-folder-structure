<!-- /.login-box -->
<!-- jQuery 3 -->
<script src="{{url('adminlte/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{url('adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{url('adminlte/plugins/iCheck/icheck.min.js')}}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
<script>
    window._token = '{{ csrf_token() }}';
</script>
<!-- AdminLTE App -->
<script src="{{url('adminlte/dist/js/adminlte.min.js')}}"></script><!-- DASHBOARD PAGE-->
<!-- DataTables  Users Page-->
<script src="{{url('adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- SlimScroll -->
<script src="{{url('adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{url('adminlte/bower_components/fastclick/lib/fastclick.js')}}"></script>

<!-- Profile Picture change js and css Cropmaster -->
<link rel="stylesheet" href="{{url('crop-avatar/dist/cropper.min.css')}}">
<link rel="stylesheet" href="{{url('crop-avatar/css/main.css')}}">
<script src="{{url('crop-avatar/dist/cropper.min.js')}}"></script>
<script src="{{url('crop-avatar/js/main.js')}}"></script>

<!-- bootstrap datepicker -->
<script src="{{url('adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- Select2 -->
<script src="{{url('adminlte/bower_components/select2/dist/js/select2.full.min.js')}}"></script>

<!--Date js-->
<script src="{{url('js/moment.min.js')}}"></script>
<script>
  $(function () {
	 $('#date').datepicker({
       autoclose: true,
	   endDate: new Date(), 
	 })
 })
</script>

<!-- Theme style -->
<link rel="stylesheet" href="{{ url('css/custom-tab.css')}}">
<!--valiation-->
<script type="text/javascript" src="{{url('js/parsley.min.js')}}"></script>
<script type="text/javascript" src="{{url('js/wizard.js')}}"></script>	
	
<link type="text/css" rel="stylesheet" href="{{ url('css/parsley.css')}}">

<link rel="stylesheet" href="{{ url('css/custom.css')}}">  

@yield('javascript')