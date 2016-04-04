<!DOCTYPE>
<html ng-app="App" ng-controller="appCtrl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title ng-bind-html="pageTitle"></title>

    @include('layouts.dashHead')

</head>
<body>
<header id="header">
    <ul class="header-inner">
        <li id="menu-trigger" data-trigger="#sidebar">
            <div class="line-wrap">
                <div class="line top"></div>
                <div class="line center"></div>
                <div class="line bottom"></div>
            </div>
        </li>

        <li class="logo hidden-xs">
            <a href="index.html">Material Admin</a>
        </li>

        <li class="pull-right">
            <ul class="top-menu">
                <li id="top-search">
                    <a class="tm-search" href="#"></a>
                </li>
                @include('layouts.dashMessages')
                @include('layouts.dashNotification')
                @include('layouts.dashTask')
                @include('layouts.dashOthers')
            </ul>
        </li>
    </ul>

    <!-- Top Search Content -->
    <div id="top-search-wrap">
        <input type="text">
        <i id="top-search-close">&times;</i>
    </div>
</header>

<section id="main">
    @include('layouts.dashMenu')
    @include('layouts.dashOthersChat')

    <section id="content">
        <div class="container">
            <div class="block-header">
                <h2>Dashboard</h2>

                <ul class="actions">
                    <li>
                        <a href="#">
                            <i class="md md-trending-up"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="md md-done-all"></i>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown">
                            <i class="md md-more-vert"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a href="#">Refresh</a>
                            </li>
                            <li>
                                <a href="#">Manage Widgets</a>
                            </li>
                            <li>
                                <a href="#">Widgets Settings</a>
                            </li>
                        </ul>
                    </li>
                </ul>

            </div>
            <div ui-view></div>
        </div>
    </section>
</section>

<footer id="footer">
    Copyright &copy; 2015 Material Admin

    <ul class="f-menu">
        <li><a href="#">Home</a></li>
        <li><a href="#">Dashboard</a></li>
        <li><a href="#">Reports</a></li>
        <li><a href="#">Support</a></li>
        <li><a href="#">Contact</a></li>
    </ul>
</footer>

<!-- Javascript Libraries -->
<script src="vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="vendors/bower_components/flot/jquery.flot.js"></script>
<script src="vendors/bower_components/flot/jquery.flot.resize.js"></script>
<script src="vendors/bower_components/flot.curvedlines/curvedLines.js"></script>
<script src="vendors/sparklines/jquery.sparkline.min.js"></script>
<script src="vendors/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
<script src="vendors/bower_components/moment/min/moment.min.js"></script>
<script src="vendors/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="vendors/bower_components/simpleWeather/jquery.simpleWeather.min.js"></script>
<script src="vendors/bower_components/jquery.nicescroll/jquery.nicescroll.min.js"></script>
<script src="vendors/bower_components/Waves/dist/waves.min.js"></script>
<script src="vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
<script src="vendors/bower_components/sweetalert/dist/sweetalert.min.js"></script>

<script src="js/flot-charts/curved-line-chart.js"></script>
<script src="js/flot-charts/line-chart.js"></script>
<script src="js/charts.js"></script>
<script src="js/charts.js"></script>
<script src="js/functions.js"></script>
<script src="js/demo.js"></script>

<!-- Third-party Libraries -->
<script src="/app/lib/angular/angular.js"></script>
<script src="/app/lib/angular/angular-animate.js"></script>
<script src="/app/lib/angular/angular-messages.js"></script>
<script src="/app/lib/angular/angular-resource.js"></script>
<script src="/app/lib/angular/angular-sanitize.js"></script>
<script src="/app/lib/angular/angular-ui-router.js"></script>
<script src="/app/lib/angular/angular-toastr.tpls.js"></script>
<script src="/app/lib/satellizer.js"></script>

<!-- Application Code -->
<script src="app/app.js"></script>

</body>
</html>