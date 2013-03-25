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

<style>
.red {
  color: #dd4b39;
}
</style>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span4">
      <?php if($result): if(is_array($result)): foreach($result as $key=>$vo): ?><div class="row-fluid">
            <div class="btn span12">
              <div class="media" item-id="<?php echo ($vo["id"]); ?>">
                <a class="pull-left">
                  <img class="media-object img-polaroid" data-src="holder.js/56x56" alt="56x56" style="width: 56px; height: 56px;" src="<?php echo ($vo["image"]); ?>">
                </a>
                <div class="media-body">
                  <h3 class="media-heading"><?php echo ($vo["item_name"]); ?></h3>
                  <em>Id: <?php echo ($vo["id"]); ?></em>
                </div>
              </div>
            </div>
          </div>
          <br><?php endforeach; endif; ?>
      <?php else: ?>
        <div class="row-fluid">
          <div class="span12">
            找不到 "<span class="red"><?=htmlspecialchars($_GET['q'])?></span>"
          </div>
        </div><?php endif; ?>
    </div>
    <div class="span8">
      <div class="ui-tooltip" id="ui-tooltip" style="display: none;">
        <div class="tooltip-content"></div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12">
      <?php echo W('Pagination', array('totalNum' => isset($totalNum) ? $totalNum : null, 'pageSize' => isset($pageSize) ? $pageSize : null, 'showPage' => isset($showPage) ? $showPage : null));?>
    </div>
  </div>
</div>
<script>
var tooltipCache = new Array();
var domMouseEnter = false;
$(".media").mouseenter(function() {
  domMouseEnter = $(this);
  var domThis = $(this);
  if($(window).width() > $(this).width() + 340) {
    var itemId = $(this).attr('item-id');
    var top = $(this).position().top;
    $('#ui-tooltip').css({
      "left": $(this).parent().parent().width() + 100,
      "top": top
    });
    if(tooltipCache[itemId]) {
      $('#ui-tooltip .tooltip-content').html(tooltipCache[itemId]);
      $('#ui-tooltip').show();
    }else{
      $.post('/wowAH/Api/wowItemTooltip', {'itemId': itemId}, function(result) {
        if(result['status']) {
          tooltipCache[itemId] = result['data'];
          if(domThis[0] == domMouseEnter[0]) {
            $('#ui-tooltip .tooltip-content').html(result['data']);
            $('#ui-tooltip').show();
          }
        }
      }, 'json');
    }
  }
});
$(".media").mouseleave(function() {
  $('#ui-tooltip').hide();
  domMouseEnter = false;
});
$(".media").click(function() {
  document.location.href = "<?=C('HOST_URL')?>/Item/" + $(this).attr('item-id');
});
</script>
<script type='text/javascript' src="<?=C('HOST_URL')?>/static/js/bootstrap.min.js?v=20130305"></script>
<script>
$(".search-form").submit(function() {
  if($(this).find(".search-q").val() == "") return false;
});
</script>
</body>
</html>
<script>
  $(".search-form").attr("action", "<?=C('HOST_URL')?>/Admin/AddItem")
</script>