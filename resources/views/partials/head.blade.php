<head>
	<title>@yield('title', trans('quickadmin.front_title'))</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	@yield('meta')
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700,800|Roboto+Slab:400,700" rel="stylesheet">
	<link rel="stylesheet" href="{{ url('front/assets/fonts/fonts.css')}}">
	<link rel="stylesheet" href="{{ url('front/assets/fonts/font-awesome/fontawesome-all.min.css')}}">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
	<link rel="stylesheet" href="{{ url('front/assets/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{ url('front/assets/css/slick.css')}}">
	<link rel="stylesheet" href="{{ url('front/assets/css/custom.css')}}">

	<!--valiation-->
	<link type="text/css" rel="stylesheet" href="{{ url('css/parsley.css')}}">

	<!--jquery-ui-->
	<link type="text/css" rel="stylesheet" href="{{ url('front/assets/css/jquery-ui.css')}}">

	<!-- select2 -->
	<link type="text/css" rel="stylesheet" href="{{ url('css/select2.min.css')}}">

	<!-- Bootstrap Datepicker -->
	<link rel="stylesheet" href="{{ url('front/assets/css/bootstrap-datepicker/bootstrap-datepicker3.css')}}">

	@yield('stylesheet')
</head>