<?php /////// session_start(); 
//include "db.php";
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
$title="Sport Mont";

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
			case 'articles2':
				$ifajl = "articles2.php";
				$title .= " - Articles";
				break;
			case 'article':
				$ifajl = "article.php";
//				$title .= " - Article";
				break;
			case 'abstract':
				$ifajl = "abstract.php";
//				$title .= " - Abstract";
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

<link rel="icon" href="images/sportmont-vrh.png">

<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="alternate" href="/rss/" title="Sport Mont RSS Feed" type="application/rss+xml" />

<?php
if ($section=="article" || $section=="abstract"){
	$qart = mysql_query("SELECT * FROM clanci WHERE `id`='$article_id' LIMIT 1") or die(mysql_error());
	$rowart = mysql_fetch_assoc($qart);
	$trbroj = $rowart["broj"];
	$qb = mysql_query("SELECT * FROM brojevi WHERE `id`='$trbroj' LIMIT 1") or die(mysql_error());
	$rowb = mysql_fetch_assoc($qb);

?>	
<meta name="citation_title" content="<?php echo $rowart["naslov_eng"]; ?>">
<?php for($jk=1;$jk<=13;$jk++){
if($rowart["autor".$jk]){ ?>
<meta name="citation_author" content="<?php echo fetch_autor2($rowart["autor".$jk]); ?>">
<?php }} ?>
<meta name="citation_journal_title" content="Sport Mont">
<meta name="citation_volume" content="<?php echo $rowb["vol"]; ?>">
<meta name="citation_issue" content="<?php echo $rowb["no"]; ?>">
<?php
	$straneclanka  = $rowart["str"];
	$stranepojedinacno = explode("-", $straneclanka);
?>
<meta name="citation_firstpage" content="<?php echo $stranepojedinacno[0]; ?>">
<meta name="citation_lastpage" content="<?php echo $stranepojedinacno[1]; ?>">
<meta name="citation_pdf_url" content="http://www.sportmont.ucg.ac.me/clanci/<?php echo $rowart["file"]; ?>">
<?php $title = $rowart["naslov_eng"]. " - " .$title;?>

<style>
#accordion {
	 margin-left:10px;
     margin-top:10px;
     border:thin solid #cecece;   
     border-top:none;
     border-bottom:none;
	 width: 265px;
}

#accordion div {
	padding-top: 10px;
    background:white;
    height:auto;
/*    line-height:10px;*/
    display:none;
    border-bottom:thin solid #cecece;
    padding-left:10px;
    padding-right:10px;
    padding-bottom:10px;
}

#accordion a {
    display:block;
    width:250px;
    background:#f4f4f4;
    background-image: -webkit-linear-gradient(white,#ededed);
    background-image: -moz-linear-gradient(white,#ededed);
    background-image: -o-linear-gradient(white,#ededed);
    background-image: -ms-linear-gradient(white,#ededed);
    background-image:linear-gradient(white,#ededed);
    color:#AA0000;
    padding-left:15px;
    height:40px;
    line-height:40px;
    text-decoration:none;
    border-bottom:thin solid #cecece;
    font-family:Arial;
    font-size:13px;
    font-weight:bold;
    text-shadow:0px 1px 1px white;
}

.first {
    border-top:thin solid #cecece;    
}
</style>



<?php	
	
}
?>







<title><?php echo $title;?></title>

<!-- Start Alexa Certify Javascript -->
<script type="text/javascript">
_atrk_opts = { atrk_acct:"8v4Sr1KAfD20Cs", domain:"ucg.ac.me",dynamic: true};
(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://certify-js.alexametrics.com/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
</script>
<noscript><img src="https://certify.alexametrics.com/atrk.gif?account=8v4Sr1KAfD20Cs" style="display:none" height="1" width="1" alt="" /></noscript>
<!-- End Alexa Certify Javascript -->

<link rel="stylesheet" type="text/css" href="styles.css" />
<!--[if gte IE 5.5]>
<script language="JavaScript" src="ie.js" type="text/JavaScript"></script>
<![endif]-->

<script type="text/javascript" src="jquery321min.js"></script>


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

SSS_faq2 = {
	init : function() {
//		$('div.faq2 .answer2').slideToggle('fast');
//								$('div.faq2 .answer2').not(':first').hide(); // skrivamo sve osim prvog
		$('div.faq2 .answer2').not(':first').slideToggle('slow','linear'); // komentovano jer ne sklapamo vec skriveno
										$('div.faq2 .answer2:first').slideToggle('fast'); // otvaramo samo prvi ako su svi ostali zatvoreni
		$('div.faq2 .question2').click(function() { SSS_faq2.toggle(this) });
	},
	
	toggle : function(elt) {
		
		var godina = $(elt).attr('class').replace('question2 godina', '');
		
		if(!$(elt).hasClass("active")){ // samo ako stavka nije aktivna
		$(elt).siblings('.answer2').html("Loading...");
			$.ajax({ // ajax request prema izlistaj.php
				type: "GET",
				url: "izlistaj.php",
				dataType: "html",
				data: "godina="+godina,
				contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(response){                    
					$(elt).siblings('.answer2').html(response); 
//					alert(response);
				}
			});
		}
		
		$(elt).toggleClass('active');
		$(elt).siblings('.answer2').slideToggle('slow','linear');
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
	SSS_faq2.init();
	SSS_login.init();
	
//$('div.faq .answer:first').slideToggle('fast');



//////////////////// CITATI /////////////////////////
	// display the first div by default.
	$("#accordion div").first().css('display', 'block');


	// Get all the links.
	var link = $("#accordion a");

	// On clicking of the links do something.
	link.on('click', function(e) {

		e.preventDefault();

		var a = $(this).attr("href");

		$(a).slideDown('fast');

		//$(a).slideToggle('fast');
		$("#accordion div").not(a).slideUp('fast');
		
	});	
//////////////////// CITATI /////////////////////////





});


//--></script>



<script type="text/javascript"><!--
function brojac(artid)
{
    $.get("dl.php?id=" + artid);
}
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
	  <li><a href="?sekcija=page&p=24">Index Coverage</a></li>
	  <li><a href="?sekcija=page&p=26">Advertising</a></li>
	</ul>
  </li>
  <li><a href="?sekcija=page&p=5">Editorial Board</a></li>

  <li><a href="?sekcija=page&p=23">Editorial Policies</a>
	<ul>
	  <li><a href="?sekcija=page&p=23#sectionpolicies">Section Policies</a></li>
	  <li><a href="?sekcija=page&p=23#peerreviewprocess">Peer Review Process</a></li>
	  <li><a href="?sekcija=page&p=23#publishercopyrightpolicies">Publisher Copyright Policies</a></li>
	  <li><a href="?sekcija=page&p=23#smjapa">SM adopts the APA guidelines</a></li>
	  <li><a href="?sekcija=page&p=23#cope">Code of Conduct Ethics Committee</a></li>
	  <li><a href="/download/Authorship Statement.pdf" target="_blank">Authorship Statement</a></li>
	  <li><a href="/download/Declaration of Potential Conflict of Interest.pdf" target="_blank">Declaration of Potential Conflict</a></li>
<!--	  <li><a href="/download/authors/Journal Publishing Agreement.doc" target="_blank">Journal Publishing Agreement</a></li> -->
	</ul>
  </li>

  <li><a href="?sekcija=articles&alc=press&alv=50">Ahead of Print</a> <!-- kad je press, onda je link ?sekcija=articles&alc=press&alv=XX --></li>


  <li><a href="?sekcija=articles&alc=past&alv=1">Archive</a> <!-- kad je press, onda je link ?sekcija=articles&alc=press&alv=2 -->

<!--      <li><a href="?sekcija=articles&alc=press&alv=1">Articles in Press</a></li>
      <li><a href="?sekcija=under-construction">Current Issue</a></li>
      <li><a href="?sekcija=under-construction">Past Issues</a></li> -->
<!--      <li><a href="?sekcija=articles&alc=press&alv=2">Articles in Press</a></li>       komentujemo jer nema nista u stampi, vec je izaslo  -->
<!--      <li><a href="?sekcija=articles&alc=current&alv=6">Current Issue</a></li>         ovo izbacismo --> 

  </li>
  <li><a href="?sekcija=page&p=51">Submissions</a>
    <ul>
      <li><a href="?sekcija=page&p=51">Guidelines for Authors</a></li>
<!--	  <li><a href="?sekcija=page&p=25">Copyright and Privacy</a></li>-->
      <li><a href="?sekcija=page&p=62">Guidelines for Reviewers</a></li>
<!--      <li><a href="?sekcija=page&p=63">Thank You to Reviewers</a></li>-->
      <li><a href="?sekcija=dl-authors">Download</a></li>
    </ul>
  </li>
<!--  <li><a href="?sekcija=page&p=61">For Reviewers</a>
    <ul>
      <li><a href="?sekcija=page&p=61">Become a Reviewer</a></li>
      <li><a href="?sekcija=dl-reviewers">Download</a></li>
    </ul>
  </li> -->
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
  
<?php if($section != "article" && $section != "abstract" ){ ?>
  
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
  
  <a href="https://www.scimagojr.com/journalsearch.php?q=21100855988&tip=sid&clean=0;tip=sid&amp;exact=no" title="SCImago Journal &amp; Country Rank"><img border="0" src="http://www.scimagojr.com/journal_img.php?id=21100855988" alt="SCImago Journal &amp; Country Rank"  /></a>

  
	<!-- <a href="http://www.euraxess.me/" target="_blank"><img src="reklame/euraxess.gif" border="0" /></a> -->
	<a href="http://www.csakademija.me/" target="_blank"><img src="reklame/csa-280.jpg" border="0" /></a>  
	<a href="http://www.sportmont.ucg.ac.me/conference2015/" target="_blank"><img src="reklame/banner_2023.jpg" border="0" /></a>  
	<a href="http://www.mjssm.me/" target="_blank" title="mjssm" alt="mjssm"><img src="http://www.mjssm.me/images/banners/mjssm-280.png" width="278px" style="border:1px solid black" border="0"></a> 
	<a href="http://www.jaspe.ac.me/" target="_blank" title="jaspe" alt="jaspe"><img src="reklame/jaspe-280.png" border="0"></a>  
	<a href="http://www.cok.me/" target="_blank"><img src="reklame/COK280.png" border="0" /></a>  
<!--	<a href="http://www.fsfvns.rs/" target="_blank"><img src="reklame/dif-plava-280.png" border="0" /></a><br /> -->
	<!-- <a href="http://www.fasto.unsa.ba/" target="_blank"><img src="reklame/fasto-280.jpg" border="0" /></a><br />
	<a href="http://www.iscs-a.org/" target="_blank"><img src="reklame/iscsa.jpg" border="0" /></a><br /> -->
	<a href="http://aesasport.com/" target="_blank"><img src="reklame/aesa-280.png" border="0" /></a><br />
<!--	<a href="http://www.pansportmedical.ro/" target="_blank"><img src="reklame/pansportmedical-okvir.png" title="PanSportMedical.Ro - Romanian WebSite of Sport Medicine" alt="PanSportMedical.Ro - Romanian WebSite of Sport Medicine" border="0"></a><br/>
	<a href="http://www.instituteofmartialartsandsciences.com/" target="_blank"><img src="reklame/IMASlogo-okvir.jpg" border="0" /></a><br/>
-->
	<a href="http://www.ecss.mobi/" target="_blank"><img src="reklame/ecss-okvir.jpg" border="0" /></a><br/>
	<!-- <a href="http://ecss-congress.eu/2019/" target="_blank"><img src="reklame/ecss-prague.jpg" border="0" /></a><br /> -->
<!--	<a href="http://www.israjif.org/" target="_blank"><img src="reklame/israjif.png" border="0" /></a> -->
	
<!-- <a href="https://www.mdpi.com/journal/ijerph/special_issues/UCR" target="_blank"><img src="reklame/rsz_bannermdpi.png" border="0" /></a><br/> -->

	<a href="?sekcija=page&p=26"><img src="reklame/advertise-here.jpg" border="0" /></a>  
	
	<br /><br />
	<a href="?sekcija=page&p=20"><b>[ Link To Us ]</b></a>
	<br /><br />


<h3>Connect with Us</h3>
<div id="connect">
<p>
<a href="https://www.facebook.com/sportmont.journal.9" target="_blank">
			<img class="color-image" title="" alt="" src="/images/connect/fb-color.jpg">
			<img class="grayscale-image" title="" alt="" src="/images/connect/fb-gray.jpg">
			<span class="link">Facebook</span></a><br>
		<span class="support-text">Friend us on Facebook </span>
</p>
<p>
<a href="https://twitter.com/sport_mont?lang=sr" target="_blank">
			<img class="color-image" title="" alt="" src="/images/connect/tw-color.jpg">
			<img class="grayscale-image" title="" alt="" src="/images/connect/tw-gray.jpg">
			<span class="link">Twitter</span></a><br>
		<span class="support-text">Follow our tweets on Twitter </span>
</p>
<p>
<a href="https://www.youtube.com/channel/UCk0pmpuVmZwk0puk_2cC3Yg" target="_blank">
			<img class="color-image" title="" alt="" src="/images/connect/youtube-color.jpg">
			<img class="grayscale-image" title="" alt="" src="/images/connect/youtube-gray.jpg">
			<span class="link">YouTube</span></a><br>
		<span class="support-text">Watch our YouTube Channel</span>
</p>
<p>
<a href="/rss/" target="_blank">
			<img class="color-image" title="" alt="" src="/images/connect/bg-color.jpg">
			<img class="grayscale-image" title="" alt="" src="/images/connect/bg-gray.jpg">
			<span class="link">Our RSS Feed</span></a><br>
		<span class="support-text">Read our news and ideas </span>
</p>
</div>




<?php }else{include "article-sidebar.php";} ?>
  </div> <!-- NAVIGATION sekcija -->


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
	Authorized by <a href="http://www.csakademija.me" target="_blank">Montenegrin Sports Academy</a><a href="http://www.ucg.ac.me" target="_blank"></a> (2003-2023). Design and development by <a href="http://www.ideamn.com" target="_blank">Boris &#352;undi&#263;</a>, <a href="https://www.ucg.ac.me/tumber.php?src=skladiste/blog_18002/objava_1//zeljkopekic.png&w=264&h=350" target="_blank">&#381;eljko Peki&#263;, Danilo Tosic</a>.</p>
  </div>
</div>
</body>
</html>
