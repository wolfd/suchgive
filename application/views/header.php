<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $title;?></title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet" class="cssSandpaper-noIndex">
    <!-- custom -->
    <link href="/css/site.css" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-47994807-1', 'suchgive.org');
        ga('send', 'pageview');
    </script>
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-47994807-1']);
        _gaq.push(['_trackPageview']);
        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;

            ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';

            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">suchgive.</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li<?php echo ($active_page == "home" ? ' class="active"' : ''); ?>><a href="/">home</a></li>
                <li<?php echo ($active_page == "about" ? ' class="active"' : ''); ?>><a href="/about">about</a></li>
            </ul>
            <?php
            if (!$logged_in) {
                ?>
                <form class="navbar-form navbar-right" role="form" name="login-form" action="/account/login" method="post">
                    <div class="form-group">
                        <input type="text" name="nickname" placeholder="nickname" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success">sign in</button>
                    <a class="btn btn-primary" href="/account/signup">sign up</a>
                </form>
            <?php
            }
            else {
                ?>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <li<?php echo ($active_page == "account" ? ' class="active"' : ''); ?>><a href="/account">my account</a></li>
                        <li><a href="/account/logout">logout</a></li>
                    </ul>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<div class="container">
    <div class="suchgive-content clearfix">