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
            <a href="#/" ng-bind-html="'Dashboard | ' + userInfo.role"></a>
        </li>

        <li class="pull-right">
            <ul class="top-menu">
                {{--<li id="toggle-width">--}}
                    {{--<div class="toggle-switch">--}}
                        {{--<input id="tw-switch" type="checkbox" hidden="hidden">--}}
                        {{--<label for="tw-switch" class="ts-helper"></label>--}}
                    {{--</div>--}}
                {{--</li>--}}
                {{--<li id="top-search">--}}
                    {{--<a class="tm-search" href="#"></a>--}}
                {{--</li>--}}
                {{--@include('layouts.dashMessages')--}}
                {{--@include('layouts.dashNotification')--}}
                {{--@include('layouts.dashTask')--}}
                {{--@include('layouts.dashOthers')--}}
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

    <aside id="sidebar">
        <div class="sidebar-inner c-overflow">
            <div class="profile-menu">
                <a href="#">
                    <div class="profile-pic" style="padding-bottom: 65px;">
                        <img src="" alt="" style="display: none">
                    </div>

                    <div class="profile-info">
                        <span ng-bind-html="userInfo.name"></span>
                        <i class="md md-arrow-drop-down"></i>
                    </div>
                </a>

                <ul class="main-menu">
                    <li>
                        <a href="#"><i class="md md-person"></i> Ver Perfil</a>
                    </li>
                    <li>
                        <a href="#" ng-click="logout()"><i class="md md-history"></i> Cerrar Sesion</a>
                    </li>
                </ul>
            </div>

            @yield('dashMenu')
        </div>
    </aside>

    <section id="content">

        <div class="container">


            <div ui-view style=""></div>

        </div>
    </section>
</section>

<footer id="footer">
    Copyright &copy; 2016 NosVenden.com | DashBoard

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
<script src="vendors/bower_components/flot/jquery.flot.pie.js"></script>
<script src="vendors/bower_components/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
<script src="vendors/bower_components/flot-orderBars/js/jquery.flot.orderBars.js"></script>
<script src="vendors/bower_components/flot.curvedlines/curvedLines.js"></script>
<script src="vendors/bower_components/flot-orderBars/js/jquery.flot.orderBars.js"></script>

<script src="vendors/sparklines/jquery.sparkline.min.js"></script>
<script src="vendors/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
<script src="vendors/bower_components/moment/min/moment.min.js"></script>
<script src="vendors/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="vendors/bower_components/simpleWeather/jquery.simpleWeather.min.js"></script>
<script src="vendors/bower_components/jquery.nicescroll/jquery.nicescroll.min.js"></script>
<script src="vendors/bower_components/Waves/dist/waves.min.js"></script>
<script src="vendors/bower_components/sweetalert/dist/sweetalert.min.js"></script>
<script src="vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
<script src="vendors/bower_components/sweetalert/dist/sweetalert.min.js"></script>
<script src="vendors/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="vendors/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>

<script src="js/functions.js"></script>

<!-- Others Libraries -->
<script type="text/javascript" src="http://s3.amazonaws.com/codecademy-content/courses/hour-of-code/js/alphabet.js"></script>

<!-- Third-party Libraries -->
<script src="app/lib/angular/angular.js"></script>
<script src="app/lib/angular/angular-animate.js"></script>
<script src="app/lib/angular/angular-messages.js"></script>
<script src="app/lib/angular/angular-resource.js"></script>
<script src="app/lib/angular/angular-sanitize.js"></script>
<script src="app/lib/angular/angular-ui-router.js"></script>
<script src="app/lib/angular/angular-toastr.tpls.js"></script>
<script src="app/lib/satellizer.min.js"></script>
<script src="app/lib/AJQtable.js"></script>

<!-- Application Code -->

<script src="app/app.js"></script>
@yield('dashCtrls')


</body>
</html>