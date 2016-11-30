<?php
/** @var \FPopov\Core\ViewInterface $this */ ;
$uriJunk = isset($uriJunk) ? $uriJunk : '';
?>

<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>FPopov</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description"/>
    <meta content="" name="author"/>

<!--    <link rel="stylesheet" href="https://bootswatch.com/yeti/bootstrap.css"/>-->

    <link rel="stylesheet" type="text/css" href="<?php echo $uriJunk?>/js/bootstrap/bootstrap-3.3.7-dist/css/bootstrap.min.css">

    <script type="text/javascript" src="<?php echo $uriJunk?>/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $uriJunk?>/js/bootstrap/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $uriJunk?>/js/bootstrap/bootstrap-confirmation/bootstrap-confirmation.min.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Blog</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="<?=$this->uri("categories", "add");?>">Add Categories</a></li>
                <li><a href="<?=$this->uri("categories", "view");?>">View Categories </a></li>
            </ul>
        </div>
    </div>
</nav>