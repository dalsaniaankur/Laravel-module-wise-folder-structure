<script src="{{ url('front/assets/js/jquery-3.3.1.min.js')}}" type="text/javascript"></script>
<script src="{{ url('front/assets/js/slick.min.js')}}" type="text/javascript"></script>
<script src="{{ url('front/assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{ url('front/assets/js/script.js')}}" type="text/javascript"></script>

<!-- bootstrap datepicker -->
<script src="{{url('adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>

<!--Date js-->
<script src="{{url('js/moment.min.js')}}"></script>

<!--valiation-->
<script type="text/javascript" src="{{url('js/parsley.min.js')}}"></script>
<script type="text/javascript" src="{{url('js/wizard.js')}}"></script>

<script type="text/javascript" src="{{url('front/assets/js/jquery-ui.js')}}"></script>

<!-- Bootstrap Datepicker -->
<script type="text/javascript" src="{{url('front/assets/js/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

<script>

    $('.showcases_search_date').datepicker({
        autoclose: true
    });

    $("#start_date, #end_date").datepicker({
        useCurrent: false
    });

    $("#end_date").change(function () {
        var startDate = document.getElementById("start_date").value;
        var endDate = document.getElementById("end_date").value;

        if ((Date.parse(endDate) < Date.parse(startDate))) {
            alert("End date should be greater than Start date");
            document.getElementById("end_date").value = "";
        }
    });

    $("#start_date").change(function () {
        var startDate = document.getElementById("start_date").value;
        var endDate = document.getElementById("end_date").value;

        if ((Date.parse(endDate) < Date.parse(startDate))) {
            alert("Start date should be less then than End date");
            document.getElementById("start_date").value = "";
        }
    });

    $(function () {
        $(".custom-select > select").each(function () {
            var placeholder = $(this).attr('data-placeholder');
            $(this).select2({
                placeholder: placeholder,
            });
        });
    });

    $(function(){
        $('#shop_slider').slick({
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1469,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });

    window.baseURI = "{{ url('/') }}";

    /* City State */
    window._token = "{{ csrf_token() }}";
    window.getcitydropdown ="{{ URL::to('getcitydropdown') }}";
    window.get_citydropdown_for_registration_page ="{{ URL::to('get_citydropdown_for_registration_page') }}";
    window.banner_tracking_url ="{{ URL::to('banner_tracking') }}";
    window.google_recaptcha_validation_url ="{{ URL::to('google_recaptcha_validation') }}";
    window.send_enquiry_mail_url ="{{ URL::to('send_enquiry_mail') }}";
</script>

<!-- select2 -->
<script type="text/javascript" src="{{url('js/select2.min.js')}}"></script>

<!--Google ReCaptcha-->
<!--<script src="{{ url('front/assets/js/recaptcha/api.js')}}" type="text/javascript"></script>-->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script src="{{ url('front/assets/js/common.js')}}" type="text/javascript"></script>

<!--<script src="{{url('adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>-->

@yield('javascript')