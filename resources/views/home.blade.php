<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->

<!-- Mirrored from 192.185.228.226/projects/ma/v1-4-1/jQuery/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 27 Jul 2015 02:21:15 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Material Admin</title>

    <!-- Vendor CSS -->
    <link href="vendors/bower_components/material-design-iconic-font/css/material-design-iconic-font.min.css" rel="stylesheet">

    <!-- CSS -->
    <link href="css/app.min.1.css" rel="stylesheet">
    <link href="css/app.min.2.css" rel="stylesheet">
    <link href="css/styles.min.css" rel="stylesheet">

    <!-- Aplication CSS -->
    <link href="css/toastr.min.css" rel="stylesheet">

</head>

<body class="login-content" ng-app="loginApp">

<div ui-view></div>

<!-- Older IE warning message -->
<!--[if lt IE 9]>
<div class="ie-warning">
    <h1 class="c-white">Warning!!</h1>
    <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
    <div class="iew-container">
        <ul class="iew-download">
            <li>
                <a href="http://www.google.com/chrome/">
                    <img src="img/browsers/chrome.png" alt="">
                    <div>Chrome</div>
                </a>
            </li>
            <li>
                <a href="https://www.mozilla.org/en-US/firefox/new/">
                    <img src="img/browsers/firefox.png" alt="">
                    <div>Firefox</div>
                </a>
            </li>
            <li>
                <a href="http://www.opera.com">
                    <img src="img/browsers/opera.png" alt="">
                    <div>Opera</div>
                </a>
            </li>
            <li>
                <a href="https://www.apple.com/safari/">
                    <img src="img/browsers/safari.png" alt="">
                    <div>Safari</div>
                </a>
            </li>
            <li>
                <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                    <img src="img/browsers/ie.png" alt="">
                    <div>IE (New)</div>
                </a>
            </li>
        </ul>
    </div>
    <p>Sorry for the inconvenience!</p>
</div>
<![endif]-->

<!-- Javascript Libraries -->
<script src="vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="vendors/bower_components/Waves/dist/waves.min.js"></script>

<script src="js/functions.js"></script>

<!-- Third-party Libraries -->
<script src="/app/lib/angular/angular.js"></script>
<script src="/app/lib/angular/angular-animate.js"></script>
<script src="/app/lib/angular/angular-messages.js"></script>
<script src="/app/lib/angular/angular-resource.js"></script>
<script src="/app/lib/angular/angular-sanitize.js"></script>
<script src="/app/lib/angular/angular-ui-router.js"></script>
<script src="/app/lib/angular/angular-toastr.tpls.js"></script>
<script src="/app/lib/satellizer.min.js"></script>

<!-- Application Code -->
<script src="app/appLogin.js"></script>
<script src="app/controllers/loginCtrl.js"></script>
</body>

<!-- Mirrored from 192.185.228.226/projects/ma/v1-4-1/jQuery/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 27 Jul 2015 02:21:15 GMT -->
</html>