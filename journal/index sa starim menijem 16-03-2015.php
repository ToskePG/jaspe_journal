<?php /////// session_start(); 

function fetch_autor2($aut) { // ovo je obicni duplikat funkcije
	$aa_query = mysql_query("SELECT * FROM autori WHERE `id`='$aut' LIMIT 1") or die(mysql_error());
	$aa_result = mysql_fetch_array($aa_query);

	return $aa_result["autor_eng"];
}

?>

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
		$page_id = $_GET['p'];
	}
	else{
		$page_id = "NULL";
	}

	if (isset($_GET['artid'])) {
		$article_id = $_GET['artid'];
	}
	else{
		$article_id = "NULL";
	}

	if (isset($_GET['alc'])) { // Article list condition
		$alc = $_GET['alc'];
	}
	else{
		$alc = "NULL";
	}

	if (isset($_GET['alv'])) { // Article list value
		$alv = $_GET['alv'];
	}
	else{
		$alv = "NULL";
	}
	
?>

<?php
$title="Montenegrin Journal of Sports Science and Medicine";

		switch($section){
			case 'home':
				$ifajl = "home.php";
				break;
			case 'page':
				$ifajl = "page.php";
				$qstr = mysql_query('select * from `strane` where `id` = "'.$page_id.'" limit 1') or die(mysql_error());
				$rowqstr = mysql_fetch_assoc($qstr);
				$title .= " - ".$rowqstr["headline"];
				break;
			case 'articles':
				$ifajl = "articles.php";
				$title .= " - Articles";
				break;
			case 'article':
				$ifajl = "article.php";
				$title .= " - Article";
				break;
			case 'abstract':
				$ifajl = "abstract.php";
				$title .= " - Abstract";
				break;
			case 'success':
				$ifajl = "success.php";
				$title .= " - Login Success";
				break;
			case 'register':
				$ifajl = "register.php";
				$title .= " - New User";
				break;
			case 'dl-authors':
				$ifajl = "dl-authors.php";
				$title .= " - For Authors";
				break;
			case 'dl-reviewers':
				$ifajl = "dl-reviewers.php";
				$title .= " - For Reviewers";
				break;
			case 'under-construction':
				$ifajl = "under-construction.php";
				$title .= " - Under construction";
				break;
			
			default:
				$ifajl = "home.php";
				break;
		} // end switch
?>





<head>
<title><?php echo $title;?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">


<?php
if ($section=="article" || $section=="abstract"){
	$qart = mysql_query("SELECT * FROM clanci WHERE `id`='$article_id' LIMIT 1") or die(mysql_error());
	$rowart = mysql_fetch_assoc($qart);
	$trbroj = $rowart["broj"];
	$qb = mysql_query("SELECT * FROM brojevi WHERE `broj`='$trbroj' LIMIT 1") or die(mysql_error());
	$rowb = mysql_fetch_assoc($qb);

?>	
<meta name="citation_title" content="<?php echo $rowart["naslov_eng"]; ?>">
<?php for($jk=1;$jk<=10;$jk++){
if($rowart["autor".$jk]){ ?>
<meta name="citation_author" content="<?php echo fetch_autor2($rowart["autor".$jk]); ?>">
<?php }} ?>
<meta name="citation_journal_title" content="Montenegrin Journal of Sports Science and Medicine">
<meta name="citation_volume" content="<?php echo $rowb["vol"]; ?>">
<meta name="citation_issue" content="<?php echo $rowb["no"]; ?>">
<?php
	$straneclanka  = $rowart["str"];
	$stranepojedinacno = explode("-", $straneclanka);
?>
<meta name="citation_firstpage" content="<?php echo $stranepojedinacno[0]; ?>">
<meta name="citation_lastpage" content="<?php echo $stranepojedinacno[1]; ?>">
<meta name="citation_pdf_url" content="http://www.mjssm.me/clanci/<?php echo $rowart["file"]; ?>">
<?php	
	
}
?>









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
										$('div.faq .answer:first').slideToggle('fast'); // otvaramo samo prvi ako su svi ostali zatvoreni
		$('div.faq .question').click(function() { SSS_faq.toggle(this) });
	},
	
	toggle : function(elt) {
		$(elt).toggleClass('active');
		$(elt).siblings('.answer').slideToggle('slow','linear');
	}
	
	
}


SSS_login = {
	init : function() {
		$('#login-login').click(function() { SSS_login.toggle(this) });
	},
	
	toggle : function(elt) {
		//$('#login').slideToggle('fast');

		var a = parseInt($("#login").css("top"));
		if (a ==-10) {

			$("#login").animate({"top": "-=180px"}, "fast");
		} else {
			$("#login").animate({"top": "+=180px"}, "fast");
		}
	}
}


$(function() { 
	SSS_faq.init();
	SSS_login.init();
	
//$('div.faq .answer:first').slideToggle('fast');


});


//--></script>





</head>
<body>
<div id="container">
<div id="login">

<?php

 /////// if(isset($_SESSION["myusername"])){

?>
<!--
<table width="180" border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
<form name="form2" method="post" action="logout.php">
<td align="center">
<br /><br /><span class="artlink"><a href="/?sekcija=success"> -->
<?php /////// echo $_SESSION["myusername"];?>
<!-- </a></span><br />
Are you sure you want to logout?
<input type="submit" name="Submit" value="Logout">
</td>
</form>
</tr>
</table> -->

<?php /////// }else{

?>
<table width="180" border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
<form name="form1" method="post" action="subscr.php">
<td align="center">
<table width="100%" border="0" cellpadding="1" cellspacing="1">
<tr>
<td colspan="2"><strong>Enter your details:</strong></td>
</tr>
<tr>
<td width="78">Name:</td>
<td width="100"><input name="myusername" type="text" id="myusername"></td>
</tr>
<tr>
<td>E-mail:</td>
<td><input name="myemail" type="text" id="myemail"></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" name="Submit" value="Subscribe"></td>
</tr>
</table>
Thank you for subscribing.
</td>
</form>
</tr>
</table>
<?php

 /////// } 

 ?>

	<div id="login-login">

<?php
/*if(isset($_SESSION["myusername"])){
	$qkoris = mysql_query('select * from `korisnici` where `korisnik` = "'.$_SESSION["myusername"].'" limit 1') or die(mysql_error());
	$rowk = mysql_fetch_assoc($qkoris);
	echo $rowk["ime"]." ".$rowk["prezime"]." (Logout)";

} else {*/
	echo "Subscribe to our newsletter!";
/*}*/
?>

		
	</div>

	
	
	

</div>
<div id="header"><h1></h1></div>
<div id="menuline">

<ul id="navmenu">
  <li><a href="?">Home</a></li>
  <li><a href="?sekcija=page&p=21">Journal Info</a>
	<ul>
	  <li><a href="?sekcija=page&p=21">About the Journal</a></li>
	  <li><a href="?sekcija=page&p=22">Journal Statistics</a></li>
	  <li><a href="?sekcija=page&p=23">Editorial Policies</a></li>
	  <li><a href="?sekcija=page&p=24">Index Coverage</a></li>
	  <li><a href="?sekcija=page&p=25">Copyright and Privacy</a></li>
	  <li><a href="?sekcija=page&p=26">Advertising</a></li>
	</ul>
  </li>
  <li><a href="?sekcija=page&p=5">Editorial Board</a></li>
  <li><a href="?sekcija=articles&alc=current&alv=6">Articles & Issues</a> <!-- kad je press, onda je link ?sekcija=articles&alc=press&alv=2 -->
    <ul>
<!--      <li><a href="?sekcija=articles&alc=press&alv=1">Articles in Press</a></li>
      <li><a href="?sekcija=under-construction">Current Issue</a></li>
      <li><a href="?sekcija=under-construction">Past Issues</a></li> -->
<!--      <li><a href="?sekcija=articles&alc=press&alv=2">Articles in Press</a></li>       komentujemo jer nema nista u stampi, vec je izaslo  -->
      <li><a href="?sekcija=articles&alc=current&alv=6">Current Issue</a></li>
      <li><a href="?sekcija=articles&alc=past&alv=1">Past Issues</a></li> 
    </ul>
  </li>
  <li><a href="?sekcija=page&p=51">For Authors</a>
    <ul>
      <li><a href="?sekcija=page&p=51">New Guidelines for Authors</a></li>
      <li><a href="/download/Authorship Statement.pdf" target="_blank">Authorship Statement</a></li>
      <li><a href="/download/Declaration of Potential Conflict of Interest.pdf" target="_blank">Declaration of Potential Conflict</a></li>
      <li><a href="?sekcija=dl-authors">Download</a></li>
    </ul>
  </li>
  <li><a href="?sekcija=page&p=61">For Reviewers</a>
    <ul>
      <li><a href="?sekcija=page&p=61">Become a Reviewer</a></li>
      <li><a href="?sekcija=page&p=62">Guidelines for Reviewers</a></li>
      <li><a href="?sekcija=page&p=63">Thank You to Reviewers</a></li>
      <li><a href="?sekcija=dl-reviewers">Download</a></li>
    </ul>
  </li>
  <li><a href="?sekcija=page&p=7">Subscription</a></li>
<!--  <li><a href="?sekcija=page&p=18">Links</a></li> -->
  <li><a href="?sekcija=page&p=8">Contact</a></li>
</ul>

</div>
  <div id="wrapper">
    <div id="content">

<?php include $ifajl; ?>

    </div>
  </div>
  <div id="navigation">
  
  
  
<table width="275" border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
<form name="form3" method="post" action="?sekcija=articles&alc=search">
<td align="center">
<table width="100%" border="0" cellpadding="1" cellspacing="1">
<tr>
<td colspan="2"><strong>Search articles:</strong></td>
</tr>
<tr>
<td colspan="2" align="center"><input name="searchstring" type="text" id="searchstring">&nbsp;<input type="submit" name="Submit" value="Search"></td>
</tr>
</table>
<br />
</td>
</form>
</tr>
</table>
  
  
  
	<!-- <a href="http://www.euraxess.me/" target="_blank"><img src="reklame/euraxess.gif" border="0" /></a> -->
	<a href="http://www.sportmont.ucg.ac.me/conference2015/" target="_blank"><img src="reklame/mba2015-banner.gif" border="0" /></a>  
	<a href="http://www.sportmont.ucg.ac.me/" target="_blank"><img src="reklame/sportmont.png" border="0" /></a>  
	<a href="http://www.fsnk.ucg.ac.me/" target="_blank"><img src="reklame/fsnk.png" border="0" /></a>  
	<a href="http://www.pansportmedical.ro/" target="_blank"><img src="reklame/pansportmedical-okvir.png" title="PanSportMedical.Ro - Romanian WebSite of Sport Medicine" alt="PanSportMedical.Ro - Romanian WebSite of Sport Medicine" border="0"></a><br/>
	<a href="http://www.instituteofmartialartsandsciences.com/" target="_blank"><img src="reklame/IMASlogo-okvir.jpg" border="0" /></a><br/>

	<a href="http://www.ecss.mobi/" target="_blank"><img src="reklame/ecss-okvir.jpg" border="0" /></a><br/>
	<a href="http://ecss-congress.eu/2015/15/" target="_blank"><img src="reklame/ecss-malmo-okvir.jpg" border="0" /></a><br />
	<a href="http://www.fsfvns.rs/" target="_blank"><img src="reklame/dif-plava-280.png" border="0" /></a><br />
	<a href="http://www.israjif.org/" target="_blank"><img src="reklame/israjif.png" border="0" /></a>
	
	<a href="?sekcija=page&p=1#advertising" target="_blank"><img src="reklame/advertise-here.jpg" border="0" /></a>  
	
	<br /><br />
	<a href="?sekcija=page&p=20"><b>[ Link To Us ]</b></a>
	<br /><br />

<h3>Connect with Us</h3>
<div id="connect">
<p>
<a href="http://www.facebook.com/MontenJSportsSciMed" target="_blank">
			<img class="color-image" title="" alt="" src="/images/connect/fb-color.jpg">
			<img class="grayscale-image" title="" alt="" src="/images/connect/fb-gray.jpg">
			<span class="link">Facebook</span></a><br>
		<span class="support-text">Friend us on Facebook </span>
</p>
<p>
<a href="http://www.twitter.com/MJSSMontenegro" target="_blank">
			<img class="color-image" title="" alt="" src="/images/connect/tw-color.jpg">
			<img class="grayscale-image" title="" alt="" src="/images/connect/tw-gray.jpg">
			<span class="link">Twitter</span></a><br>
		<span class="support-text">Follow our tweets on Twitter </span>
</p>
<p>
<a href="http://www.youtube.com/TheMJSSM" target="_blank">
			<img class="color-image" title="" alt="" src="/images/connect/youtube-color.jpg">
			<img class="grayscale-image" title="" alt="" src="/images/connect/youtube-gray.jpg">
			<span class="link">YouTube</span></a><br>
		<span class="support-text">Watch our YouTube Channel</span>
</p>
<p>
<a href="mjssm.rss" target="_blank">
			<img class="color-image" title="" alt="" src="/images/connect/bg-color.jpg">
			<img class="grayscale-image" title="" alt="" src="/images/connect/bg-gray.jpg">
			<span class="link">Our Blog</span></a><br>
		<span class="support-text">Read our news and ideas </span>
</p>
</div>
	
  </div>
  <!-- <div id="extra">
    <p><strong>Boks 2</strong></p>
    <p>Lorem ipsum dolor sit amet sit malesuada lacus pellus parturpiscing. Pellenterdumat maecenatoque cras a magna nibh et quis diam ames et. Laoremvolutpat ac dolor eget eget temper lacus vestibus velit lacus venean. Magnaipsum tellus morbi leo aliquat nulla convallis pellentesque.</p>
	  
  <div style="text-align:center">
  <a href="http://get.adobe.com/reader/" target="_blank"><img src="images/get_adobe_reader.png" border="0" /></a>
  </div>
  </div> -->

  <div id="footer">
    <p>
	<br /><br /><br /><br />	
	Copyright (c)2012-2015 Montenegrin Journal of Sports Science and Medicine. All rights reserved. Design and development by <a href="http://ideamn.com" target="_blank">Boris Sundic</a>.</p>
  </div>
</div>
</body>
</html>
