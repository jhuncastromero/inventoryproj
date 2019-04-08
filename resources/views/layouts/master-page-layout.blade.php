<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1.0"/>
	<meta name="csrf_token" content="{{ csrf_token () }}" charset="UTF-8"/>
	
	<title>@yield('title-section')</title>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/definedcss.css')}} ">
	
</head>
<body id="mainpage" name="mainpage">
		@include('module1.personnel.personnel-nav-links')
		@include('module1.personnel.personnel-sidenav-links')
		<div id="offset_content">
		    @yield('content-section')
		</div>


</body>

<script type="text/javascript"src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js">></script>
<script type="text/javascript" src="{{ asset('js/init.js') }}" ></script>

@yield('jquery-section')

</html>