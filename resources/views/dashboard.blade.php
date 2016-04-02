<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->

<!-- Mirrored from 192.185.228.226/projects/ma/v1-4-1/jQuery/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 27 Jul 2015 02:15:42 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Material Admin</title>

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
                <li id="toggle-width">
                    <div class="toggle-switch">
                        <input id="tw-switch" type="checkbox" hidden="hidden">
                        <label for="tw-switch" class="ts-helper"></label>
                    </div>
                </li>
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

            <div class="mini-charts">
                <div class="row">
                    <div class="col-sm-6 col-md-3">
                        <div class="mini-charts-item bgm-cyan">
                            <div class="clearfix">
                                <div class="chart stats-bar"></div>
                                <div class="count">
                                    <small>Website Traffics</small>
                                    <h2>987,459</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="mini-charts-item bgm-lightgreen">
                            <div class="clearfix">
                                <div class="chart stats-bar-2"></div>
                                <div class="count">
                                    <small>Website Impressions</small>
                                    <h2>356,785K</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="mini-charts-item bgm-orange">
                            <div class="clearfix">
                                <div class="chart stats-line"></div>
                                <div class="count">
                                    <small>Total Sales</small>
                                    <h2>$ 458,778</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="mini-charts-item bgm-bluegray">
                            <div class="clearfix">
                                <div class="chart stats-line-2"></div>
                                <div class="count">
                                    <small>Support Tickets</small>
                                    <h2>23,856</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="dash-widgets">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div id="site-visits" class="dash-widget-item bgm-teal">
                            <div class="dash-widget-header">
                                <div class="p-20">
                                    <div class="dash-widget-visits"></div>
                                </div>

                                <div class="dash-widget-title">For the past 30 days</div>

                                <ul class="actions actions-alt">
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

                            <div class="p-20">

                                <small>Page Views</small>
                                <h3 class="m-0 f-400">47,896,536</h3>

                                <br/>

                                <small>Site Visitors</small>
                                <h3 class="m-0 f-400">24,456,799</h3>

                                <br/>

                                <small>Total Clicks</small>
                                <h3 class="m-0 f-400">13,965</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div id="pie-charts" class="dash-widget-item">
                            <div class="bgm-pink">
                                <div class="dash-widget-header">
                                    <div class="dash-widget-title">Email Statistics</div>
                                </div>

                                <div class="clearfix"></div>

                                <div class="text-center p-20 m-t-25">
                                    <div class="easy-pie main-pie" data-percent="75">
                                        <div class="percent">45</div>
                                        <div class="pie-title">Total Emails Sent</div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-t-20 p-b-20 text-center">
                                <div class="easy-pie sub-pie-1" data-percent="56">
                                    <div class="percent">56</div>
                                    <div class="pie-title">Bounce Rate</div>
                                </div>
                                <div class="easy-pie sub-pie-2" data-percent="84">
                                    <div class="percent">84</div>
                                    <div class="pie-title">Total Opened</div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="dash-widget-item bgm-lime">
                            <div id="weather-widget"></div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div id="best-selling" class="dash-widget-item">
                            <div class="dash-widget-header">
                                <div class="dash-widget-title">Best Sellings</div>
                                <img src="img/widgets/alpha.jpg" alt="">
                                <div class="main-item">
                                    <small>Samsung Galaxy Alpha</small>
                                    <h2>$799.99</h2>
                                </div>
                            </div>

                            <div class="listview p-t-5">
                                <a class="lv-item" href="#">
                                    <div class="media">
                                        <div class="pull-left">
                                            <img class="lv-img-sm" src="img/widgets/note4.jpg" alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="lv-title">Samsung Galaxy Note 4</div>
                                            <small class="lv-small">$850.00 - $1199.99</small>
                                        </div>
                                    </div>
                                </a>
                                <a class="lv-item" href="#">
                                    <div class="media">
                                        <div class="pull-left">
                                            <img class="lv-img-sm" src="img/widgets/mate7.jpg" alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="lv-title">Huawei Ascend Mate</div>
                                            <small class="lv-small">$649.59 - $749.99</small>
                                        </div>
                                    </div>
                                </a>
                                <a class="lv-item" href="#">
                                    <div class="media">
                                        <div class="pull-left">
                                            <img class="lv-img-sm" src="img/widgets/535.jpg" alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="lv-title">Nokia Lumia 535</div>
                                            <small class="lv-small">$189.99 - $250.00</small>
                                        </div>
                                    </div>
                                </a>

                                <a class="lv-footer" href="#">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">

                    <!-- Calendar -->
                    <div id="calendar-widget"></div>
                </div>

                <div class="col-sm-6">

                    <!-- Recent Posts -->
                    <div class="card">
                        <div class="card-header ch-alt m-b-20">
                            <h2>Recent Posts <small>Phasellus condimentum ipsum id auctor imperdie</small></h2>
                            <ul class="actions">
                                <li>
                                    <a href="#">
                                        <i class="md md-cached"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="md md-file-download"></i>
                                    </a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" data-toggle="dropdown">
                                        <i class="md md-more-vert"></i>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="#">Change Date Range</a>
                                        </li>
                                        <li>
                                            <a href="#">Change Graph Type</a>
                                        </li>
                                        <li>
                                            <a href="#">Other Settings</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>

                            <button class="btn bgm-cyan btn-float"><i class="md md-add"></i></button>
                        </div>

                        <div class="card-body">
                            <div class="listview">
                                <a class="lv-item" href="#">
                                    <div class="media">
                                        <div class="pull-left">
                                            <img class="lv-img-sm" src="img/profile-pics/1.jpg" alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="lv-title">David Belle</div>
                                            <small class="lv-small">Cum sociis natoque penatibus et magnis dis parturient montes</small>
                                        </div>
                                    </div>
                                </a>
                                <a class="lv-item" href="#">
                                    <div class="media">
                                        <div class="pull-left">
                                            <img class="lv-img-sm" src="img/profile-pics/2.jpg" alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="lv-title">Jonathan Morris</div>
                                            <small class="lv-small">Nunc quis diam diamurabitur at dolor elementum, dictum turpis vel</small>
                                        </div>
                                    </div>
                                </a>
                                <a class="lv-item" href="#">
                                    <div class="media">
                                        <div class="pull-left">
                                            <img class="lv-img-sm" src="img/profile-pics/3.jpg" alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="lv-title">Fredric Mitchell Jr.</div>
                                            <small class="lv-small">Phasellus a ante et est ornare accumsan at vel magnauis blandit turpis at augue ultricies</small>
                                        </div>
                                    </div>
                                </a>
                                <a class="lv-item" href="#">
                                    <div class="media">
                                        <div class="pull-left">
                                            <img class="lv-img-sm" src="img/profile-pics/4.jpg" alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="lv-title">Glenn Jecobs</div>
                                            <small class="lv-small">Ut vitae lacus sem ellentesque maximus, nunc sit amet varius dignissim, dui est consectetur neque</small>
                                        </div>
                                    </div>
                                </a>
                                <a class="lv-item" href="#">
                                    <div class="media">
                                        <div class="pull-left">
                                            <img class="lv-img-sm" src="img/profile-pics/4.jpg" alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="lv-title">Bill Phillips</div>
                                            <small class="lv-small">Proin laoreet commodo eros id faucibus. Donec ligula quam, imperdiet vel ante placerat</small>
                                        </div>
                                    </div>
                                </a>
                                <a class="lv-footer" href="#">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

</body>

<!-- Mirrored from 192.185.228.226/projects/ma/v1-4-1/jQuery/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 27 Jul 2015 02:16:44 GMT -->
</html>