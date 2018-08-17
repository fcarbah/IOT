<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="">


    {{-- <!-- @if NODE_ENV='development' --> --}}
<!--		<link rel="stylesheet" href="build/assets/css/application.css">-->
    {{-- <!-- @endif --> --}}
    {{-- <!-- @if NODE_ENV='production' --> --}}
    <link rel="stylesheet" href="build/assets/css/application.min.css">
    {{-- <!-- @endif --> --}}
    <link rel="stylesheet" href="assets/css/main.css">

    <link rel="apple-touch-icon" sizes="57x57" href="assets/img/fav/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="assets/img/fav/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="assets/img/fav/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/fav/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="assets/img/fav/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="assets/img/fav/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="assets/img/fav/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/fav/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="assets/img/fav/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/img/fav/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/fav/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/img/fav/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

</head>
<body>
	
    @yield('body')

	<!-- remove this section to prevent loading splash -->
	<div class="page loading">
            <div class="slice1"></div>
            <div class="slice2"></div>
	</div>


		<!-- build:js assets/js/application.js -->
<!--		<script src="../bower_components/angular/angular.js"></script>
		<script src="../bower_components/angular-animate/angular-animate.js"></script>
		<script src="../bower_components/angular-sanitize/angular-sanitize.js"></script>
		<script src="../bower_components/angular-touch/angular-touch.js"></script>
		<script src="../bower_components/angular-ui-router/release/angular-ui-router.js"></script>
		<script src="../bower_components/oclazyload/dist/ocLazyLoad.js"></script>
		<script src="assets/js/router/routes.js"></script>
		<script src="assets/js/application.js"></script>-->
		<!-- endbuild -->



		<!-- build:js assets/js/application.min.js -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
                <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script> -->
                <script src="assets/js/vendor/thirdparty/moment.js"></script>
                <script src="assets/js/vendor/thirdparty/d3.js"></script>
                <script src="assets/js/vendor/thirdparty/c3.js"></script>
                <script src="https://cdn.jsdelivr.net/lodash/4.17.3/lodash.min.js"></script>
                <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
                <script src="https://cdn.socket.io/socket.io-1.4.5.js"></script> -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.min.js"></script>
		<script src="../bower_components/angular/angular.js"></script>
                <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.0/angular-messages.js"></script>
		<script src="../bower_components/angular-animate/angular-animate.js"></script>
		<script src="../bower_components/angular-sanitize/angular-sanitize.js"></script>
		<script src="../bower_components/angular-touch/angular-touch.js"></script>
		<script src="../bower_components/angular-ui-router/release/angular-ui-router.js"></script>
		<script src="../bower_components/oclazyload/dist/ocLazyLoad.js"></script>
<!--                <script src="assets/js/vendor/jquery.input-ip-address-control-1.0.min.js"></script>-->
                <script src="assets/js/vendor/jquery.input-ip-address-control.js"></script>
<!--                <script src="assets/js/vendor/thirdparty/lodash.js"></script>-->
                <script src="assets/js/app.js"></script>
		<script src="assets/js/router/routes.js"></script>
		<script src="assets/js/application.js"></script>
		<!-- endbuild -->


</body>
</html>
