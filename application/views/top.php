<!DOCTYPE html>
<html>
<head>
<?php $row=$text->row(); ?>
	<meta charset="utf-8">
	<meta name="description" content="<?=$row->desc?>">
  
<link rel="stylesheet" href="/css/jquery-ui-1.8.15.custom.css">
	<link rel="stylesheet" href="/css/style.css">
	<link href="/favicon.ico" rel="icon" type="image/x-icon" />
	<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        
	<!--[if IE 7]><link rel="stylesheet" type="text/css" href="/css/ie7.css" /><![endif]-->
	<title><?=$row->title?></title>
	<script>
	function setCheckedValue(radioObj) {
	if(!radioObj)
		return;
	radioObj.checked = true;
		
}
	</script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
        
        <script type="text/javascript" src="/js/func.js"></script>
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-26013418-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>
<body>
<div class="beforeFooter">
<div class="siteHeader">
	<div class="header">
		<a href="/"><h1 class="logo">
			<img src="/img/logo.png" alt="Простір КР - реклама на радио Кривой Рог">
			<span>Лучше сто раз услышать!</span>
		</h1></a>
		<!--<div class="banner"></div>-->
		<div class="contHead">
			<p class="phone">‎(096) 340-03-03<br> (050) 362-76-99</p>
			<p class="cont">г. Кривой Рог, ул. Соборности 32 <br> e-mail: trkprostir@gmail.com</p>
		
		</div>
	</div>
</div>
<div class="menu">
	<div class="topname"><div class="slogan"><p class="hh">Эксклюзивное размещение рекламы на радио </p><p>в Кривом Роге и Никополе</p></div>
	<div class="menumain">
		<ul>
			<li><a href="/">Главная</a></li>
			<li><a href="/main/about/">О нас</a></li>
			<li><a href="/main/client">Клиенты</a></li>
			<li><a href="/main/contact">Контакты</a></li>
			
		</ul>
	</div>
	</div>
	<div class="radiomenu" >
		<a href="/main/radio/xitfm"><img src="/img/hitfm.png" alt="реклама на Хит ФМ"><span class="radioname">Радио Хит FM<br><span>106,9</span></span></a>
		<a href="/main/radio/rusradio"><img src="/img/rusradio.png" alt="реклама на Русское радио"><span class="radioname">Русское радио<br><span>105,9</span></span></a>
		<a href="/main/radio/radioes"><img src="/img/radio-relax.jpg" alt="Радио Relax" height="67"><span class="radioname">Радио Relax<br><span>104,7</span></span></a>
		<a href="/main/radio/erafm"><img src="/img/erafm.png" alt="радио Эра фм"><span class="radioname">Радио Эра FM<br><span>107,4</span></span></a>
	</div>
</div>
<!--site-->
<div class="tabsMain">
	<ul class="tabs">
		<li<?=($menu == "works")?' class="active">Наши работы':'><a href="/main/works">Наши работы</a>'?></li
		><li<?=($menu == "stock")?' class="active">Акции':'><a href="/main/stock">Акции</a>'?></li
		><li <?=($menu == "calc")?'class="active">Составить рекламную кампанию':'><a href="/main/calculator">Составить рекламную кампанию</a>'?></li
		><!--<li><a href="">Фото</a></li
		>-->
		<!--<li <?=($menu == "silpo")?' class="active">Реклама в «Сильпо» <span style="color:red; font-size:12px;">new!<span>':'><a href="/main/silpo">Реклама в «Сильпо» <span style="color:red; font-size:12px;">new!<span></a></li>'?>-->
	</ul>
</div>