<?php
if (!defined('APPPATH'))
    exit('No direct script access allowed');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>{pagetitle}</title>
        <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen"/>
        <link rel="stylesheet" type="text/css" href="/assets/css/style.css"/>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <img src="/assets/images/logo.png" alt="lightning" style="width:32px;height:32px;">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">{menubar}</div>
            </div>
        </nav>
        <div class="container">
            <div id="title">
                <h1 class="my-4">{pagetitle}
                    <br/>
                    <small>We'll get you there in a flash!</small>
                </h1>
            </div>
            {content}
        </div>
        <footer class="py-5">
            <div class="container">
                <p class="m-0 text-center">{footer}</p>
            </div>
        </footer>
        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
    </body>
</html>
