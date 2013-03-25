<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="<?=C('HOST_URL')?>/static/css/bootstrap.min.css?v=20130305" />
  <style type="text/css">
  body {
    padding-top: 60px;
    padding-bottom: 40px;
  }
  .top-bar {
    margin-left: 5px;
    margin-top: 5px;
  }
  </style>
  <link rel="stylesheet" type="text/css" href="<?=C('HOST_URL')?>/static/css/bootstrap-responsive.min.css?v=20130305" />
  <link rel="stylesheet" type="text/css" href="<?=C('HOST_URL')?>/static/css/style.css?v=201130305" />
  <script type='text/javascript' src="<?=C('HOST_URL')?>/static/js/jquery-1.9.1.min.js?v=20130305"></script>
  <title><?php echo ($title); ?></title>
</head>
<body>
<div class="navbar navbar-fixed-top" id="top-bar">
  <div class="navbar-inner">
    <div class="top-bar">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="nav-collapse collapse">
        <form class="navbar-form search-form" method="GET" action="<?=C('HOST_URL')?>/Search">
          <div class="input-append">
            <input class="span3 search-q" type="text" name="q" placeholder="">
            <button type="submit" class="btn">搜索</button>
          </div>
        </form>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<style type="text/css">
body {
  padding-top: 280px;
  padding-bottom: 40px;
}

#top-bar {
  display: none;
}
</style>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span6 offset3">
      <form class="search-form">
        <div class="row-fluid">
          <input class="span12 search-q" type="text" name="q" placeholder="">
        </div>
        <div class="row-fluid">
          <input class="span4 offset4 btn" type="submit" value="搜索">
        </div>
      </form>
    </div>
  </div>
</div>
<script type='text/javascript' src="<?=C('HOST_URL')?>/static/js/bootstrap.min.js?v=20130305"></script>
<script>
$(".search-form").submit(function() {
  if($(this).find(".search-q").val() == "") return false;
});
</script>
</body>
</html>