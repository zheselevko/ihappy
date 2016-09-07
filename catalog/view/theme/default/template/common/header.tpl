<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <script src="catalog/view/theme/default/js/jquery-ui.js"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/css/jquery.fancybox.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/css/jquery.sb.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/css/pikachoose.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/css/style.css" />

    <script src="catalog/view/theme/default/js/jquery.fancybox.pack.js"></script>
    <script src="catalog/view/theme/default/js/jquery.sb.js"></script>
    <script src="catalog/view/theme/default/js/jquery.carouFredSel-6.1.0-packed.js"></script>
    <script src="catalog/view/theme/default/js/ti_custom_checkbox.js"></script>
   <script src="catalog/view/theme/default/js/jquery.pikachoose.min.js"></script>
   
    <script src="catalog/view/theme/default/js/jquery.jcarousel.min.js"></script>
    <script src="catalog/view/theme/default/js/scripts.js"></script>
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>

<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
</head>
<body class="<?php echo $class; ?>">
<div id="page">
<div id="back_call">
<div>
<div id="close_back_call">x</div>
<div style="pading-top: 0px;" class="popup_body" id="divForm">
                <div class="pagename">
                    Обратный звонок</div>
                <div class="f_line h clear">
                    <label class="fl">
                        Телефон*</label>
                    <input type="text" maxlength="100" class="loginInpt fl req" id="tbPhone" name="tbPhone">
                    <span style="color:Red;visibility:hidden;" class="label_required fr" id="RequiredFieldValidator2"></span>
                </div>
                <div class="f_line h clear">
                    <label class="fl">
                        Ваше имя*</label>
                    <input type="text" maxlength="100" class="loginInpt fl req" id="tbName" name="tbName">
                    <span style="color:Red;visibility:hidden;" class="label_required fr" id="RequiredFieldValidator1"></span>
                    <!--
                    <div class="label_required fr">
                    </div>-->
                </div>
                <div class="f_line h clear">
                    <label class="fl">
                        Город</label>
                    <input type="text" maxlength="100" class="loginInpt fl" id="tbCity" name="tbCity">
                </div>
                <div class="f_line h clear inline">
                    <label class="fl">
                        Удобное время</label>
                    <div style="padding: 0 55px" class="fl">
                        c
                    </div>
                    <input type="text" style="left: 180px; width: 40px;" maxlength="20" class="loginInpt fl" id="tbFrom" name="tbFrom">
                    <div style="padding: 0 5px" class="fl">
                        по
                    </div>
                    <input type="text" style="width: 40px; left: 257px;" maxlength="20" class="loginInpt fl" id="tbTo" name="tbTo">
                    <span style="color:Red;visibility:hidden;" class="label_required fr" id="valTo"></span>
                    <span style="color:Red;display:none;" class="label_required fr" id="valFrom"></span>
                </div>
                <div class="f_line h clear">
                    <label style="line-height: 1.33" class="fl">
                        Что вас интересует*</label>
                    <textarea rows="4" cols="60" type="text" class="req fl" id="tbMessage" name="tbMessage"></textarea>
                </div>
                <div class="button" id="fedb_call_b">
                    Заказать
                </div>
            </div>
</div>
</div>
<header id="header">

    <div class="top-nav o-shell">
                <div class="phones aleft"><?=$telephone?></div>
                <a id="fedb_call_head" href="#" title="обратный звонок" class="order-call aleft">обратный звонок</a>
                <ul class="top-menu aright">
                    <li><a href="index.php?route=information/information&information_id=6" title="доставка и оплата">доставка и оплата</a></li>
                    <li><a href="/index.php?route=information/information&information_id=4" title="о компании">о компании</a></li>
                    <li><a href="/index.php?route=information/contact" title="контакты">контакты</a></li>
                    <?php if (!$logged) { ?>
    <?php echo '<li><a href="/index.php?route=account/login" title="">войти на сайт</a></li>'; ?>
    <?php } else { ?>
    <?php /*echo $text_logged; */
  echo '<li><a href="/index.php?route=account/account" title="">личный кабинет</a></li>'; 
  ?>
    <?php } ?>
  
                </ul>
            </div>
       <div class="header-mid">
      <?php echo $cart;?>
                <a href="/" title="" class="logo">интернет-магазин <span>аксессуаров для мобильных устройств</span></a>
            </div>
</header>
<?php if ($categories) { ?>
<div class="container">
  <nav id="menu" class="navbar">
    <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav">
        <?php foreach ($categories as $category) { ?>
        <?php if ($category['children']) { ?>
        <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>
          <div class="dropdown-menu">
            <div class="dropdown-inner">
              <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
              <ul class="list-unstyled">
                <?php foreach ($children as $child) { ?>
                <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                <?php } ?>
              </ul>
              <?php } ?>
            </div>
            <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a> </div>
        </li>
        <?php } else { ?>
        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
        <?php } ?>
        <?php } ?>
      </ul>
    </div>
  </nav>
</div>
<?php } ?>
