<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>




<?php
	include "db.php";

	if (isset($_GET['sekcija'])) {
		$section = $_GET['sekcija'];
	}
	else{
		$section = "home";
	}
	
	if (isset($_GET['lang'])) {
		$jezik = $_GET['lang'];
	}
	else{
		$jezik = "mn";
	}

	if (isset($_GET['p'])) {
		$pid = $_GET['p'];
	}
	else{
		$pid = "NULL";
	}
	
?>

<?php
$title="Montenegrin Journal of Sports Science And Medicine";

		switch($section){
			case 'home':
				$ifajl = "home.php";
				break;
			case 'page':
				$ifajl = "page.php";
				$title .= " - Strana";
				break;
			case 'rad':
				$ifajl = "rad1.php";
				$title .= " - Rad";
				break;
			
			default:
				$ifajl = "home.php";
				break;
		} // end switch
?>





<head>
<title><?php echo $title;?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="styles.css" />
<!--[if gte IE 5.5]>
<script language="JavaScript" src="ie.js" type="text/JavaScript"></script>
<![endif]-->

<script type="text/javascript" src="jquery162min.js"></script>

<script type="text/javascript"><!--
SSS_faq = {
	init : function() {
//		$('div.faq .answer').slideToggle('fast');
//								$('div.faq .answer').not(':first').hide(); // skrivamo sve osim prvog
		$('div.faq .answer').not(':first').slideToggle('slow','linear'); // komentovano jer ne sklapamo vec skriveno
//										$('div.faq .answer:first').slideToggle('slow'); // otvaramo samo prvi ako su svi ostali zatvoreni
		$('div.faq .question').click(function() { SSS_faq.toggle(this) });
	},
	
	toggle : function(elt) {
		$(elt).toggleClass('active');
		$(elt).siblings('.answer').slideToggle('slow','linear');
	}
}

$(function() { 
	SSS_faq.init();
	
//$('div.faq .answer:first').slideToggle('fast');


});


//--></script>





</head>
<body>
<div id="container">
<div id="header"><h1></h1></div>
<div id="menuline">

<ul id="navmenu">
  <li><a href="?">Home</a></li>
  <li><a href="?sekcija=page&p=1">Journal Info</a>
	<ul>
	  <li><a href="?sekcija=page&p=1#about">About the Journal</a></li>
	  <li><a href="?sekcija=page&p=1#mission">Mission</a></li>
	  <li><a href="?sekcija=page&p=1#scope">Scope</a></li>
	  <li><a href="?sekcija=page&p=1#index">Index Caverage</a></li>
	  <li><a href="?sekcija=page&p=1#advertising">Advertising</a></li>
	  <li><a href="?sekcija=page&p=1#rights">Rights and Permissions</a></li>
	</ul>
  </li>
  <li><a href="?sekcija=page&p=5">Journal Management</a></li>
  <li><a href="#">Articles & Issues</a>
    <ul>
      <li><a href="?sekcija=rad">Articles in Press</a></li>
      <li><a href="?sekcija=rad">Current Issue</a></li>
      <li><a href="?sekcija=rad">Past Issues</a></li>
    </ul>
  </li>
  <li><a href="?sekcija=page&p=8">For Authors</a>
    <ul>
      <li><a href="?sekcija=page&p=8">Why publish with us</a></li>
      <li><a href="?sekcija=page&p=9">Guidelines for Authors</a></li>
      <li><a href="?sekcija=page&p=10">Authorship Statement</a></li>
      <li><a href="?sekcija=page&p=11">Submission process</a></li>
      <li><a href="?sekcija=page&p=12">Download</a></li>
    </ul>
  </li>
  <li><a href="?sekcija=page&p=13">For Reviewers</a>
    <ul>
      <li><a href="?sekcija=page&p=13">General policies and procedures</a></li>
      <li><a href="?sekcija=page&p=14">Guidelines for reviewers</a></li>
      <li><a href="?sekcija=page&p=15">Become a reviewer</a></li>
      <li><a href="?sekcija=page&p=16">Download</a></li>
    </ul>
  </li>
  <li><a href="?sekcija=page&p=17">Subscription</a></li>
  <li><a href="?sekcija=page&p=18">Links</a></li>
  <li><a href="?sekcija=page&p=19">Contact</a></li>
</ul>

</div>
  <div id="wrapper">
    <div id="content">

<?php include $ifajl; ?>

	<div class="faq">
		<div class="question active">Author Guide</div>
		<div class="answer">
			<p>Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. </p>
			<p>Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. </p>
			<p>Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. </p>
			<p>Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. </p>
		</div>
	</div>
	<div class="faq">
		<div class="question">How and when</div>
		<div class="answer">
			<p>lfsjdif lsd flsjd flsdjfljklsajt naše kompanije. Redizajnirali smo sajt naše kompanije. </p>
			<p>Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. </p>
			<p>Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. </p>
			<p>Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. </p>
		</div>
	</div>
	<div class="faq">
		<div class="question">Usage</div>
		<div class="answer">
			<p>Use use use use sdjfljklsajt naše kompanije. Redizajnirali smo sajt naše kompanije. </p>
			<p>Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. </p>
			<p>Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. </p>
			<p>Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. Redizajnirali smo sajt naše kompanije. </p>
		</div>
	</div>

    </div>
  </div>
  <div id="navigation">
	<a href="http://www.sportmont.me/" target="_blank"><img src="reklame/sportmont.png" border="0" /></a>  
	<a href="http://fsnk.ac.me/" target="_blank"><img src="reklame/fsnk.png" border="0" /></a>  
	<a href="http://www.csa.me/" target="_blank"><img src="reklame/csa.png" border="0" /></a>  
  </div>
  <div id="extra">
    <p><strong>Boks 2 - Još nekih stvari ovdje + Adobe Reader XX.</strong></p>
    <p>Lorem ipsum dolor sit amet sit malesuada lacus pellus parturpiscing. Pellenterdumat maecenatoque cras a magna nibh et quis diam ames et. Laoremvolutpat ac dolor eget eget temper lacus vestibus velit lacus venean. Magnaipsum tellus morbi leo aliquat nulla convallis pellentesque.</p>
	  
  <div style="text-align:center">
  <a href="http://get.adobe.com/reader/" target="_blank"><img src="images/get_adobe_reader.png" border="0" /></a>  
  </div>
  </div>

  <div id="footer">
    <p>
	<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />	
	Copyright (c)2012 Montenegrin Journal of Sports Science And Medicine. All rights reserved. Design and development by Borissssssss</p>
  </div>
</div>
</body>
</html>
