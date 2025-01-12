<?php
if($a_result["doi"]!=""){

	$qrcodetext = rawurlencode("https://doi.org/" . $a_result["doi"]);
}else{
	$qrcodetext = rawurlencode("http://www.sportmont.ucg.ac.me/?sekcija=article&artid=" . $a_result["id"]);
}
?>

<img src="article-qrcode.php?qrcodetext=<?php echo $qrcodetext; ?>" style="border:1px solid black" title="<?php echo rawurldecode($qrcodetext); ?>" /> <br /><br />

<!------------------------------ CITATI ------------------------------------->

<?php

	include "name-parser.php";

	$autor_components = Array();
	$autor_name = Array();
	foreach($autor_id as $a){
		$autor_name = fetch_autor($a);
		$autor_brojprezimena = fetch_brojprezimena($a);
		if($autor_brojprezimena == "") $autor_brojprezimena = 1;
		$parser = new FullNameParser();
		$autor_components[] = $parser->parse_name($autor_name, $autor_brojprezimena);
	}
	
	
	
	
	//////////////////// APA /////////////////
	$apa = "";
	
	for($i=0;$i<$broj_autora;$i++){
		if($i == $broj_autora-1){ // poslednji autor
			if($broj_autora > 1) $apa .= ", & ";
		}else{
			if ($i != 0) $apa .= ", ";
		}
		$inicijali_imena = Array();
		$inicijali_imena = explode(" ", $autor_components[$i][fname]);
		$skracenice = "";
		foreach ($inicijali_imena as $w) {
			$skracenice .= $w[0].". ";
		}
		// autori su najveca frka
		$apa .= $autor_components[$i][lname] . ", ";
		$apa .= trim($skracenice);
		if($autor_components[$i][initials] != "") {
			if($skracenice != ""){
				$apa .= " ";
			}else{
				$apa .= ", ";
			}
			$apa .= $autor_components[$i][initials];
		}
		if($autor_components[$i][suffix] != "") $apa .= ", " . $autor_components[$i][suffix];
	}

	// ostalo je ok...
	$apa .= " (" . $b_result["godina"] . "). ";
	$apa .= ($a_result["naslov_eng"]) . ". ";
	$apa .= "<i>Sport Mont, " . $b_result["vol"] . "</i>(" . $b_result["no"] . "), " . $a_result["str"] . "."; 
	if($a_result["doi"] != "") $apa .= " doi: " . $a_result["doi"];


	if($a_result["citation"] != ""){
		$apa = $a_result["citation"];
	}else{
		// radimo update citata u bazi
		mysql_query("UPDATE clanci SET citation = '".$apa."' WHERE id = '".$a_result['id']."'") or die(mysql_error());
	}
	
	
	
	///////////////////////// MLA //////////////////////////////
	
	$mla = "";
	
	for($i=0;$i<$broj_autora;$i++){
		if($i == $broj_autora-1){ // poslednji autor
			if($broj_autora > 1) $mla .= ", and ";
		}else{
			if ($i != 0) $mla .= ", ";
		}
		$inicijali_imena = Array();
		$inicijali_imena = explode(" ", $autor_components[$i][fname]);
		// autori su najveca frka
		$mla .= $autor_components[$i][lname] . ", ";
		$mla .= $autor_components[$i][fname];
		if($autor_components[$i][initials] != "") $mla .= " " . $autor_components[$i][initials];
		if($autor_components[$i][suffix] != "") $mla .= ", " . $autor_components[$i][suffix];
	}

	// ostalo je ok...
	$mla .= ". \"" . $a_result["naslov_eng"] . ".\" ";
	$mla .= "<i>Sport Mont</i>, vol. " . $b_result["vol"] . ", no. " . $b_result["no"] . ", " . $b_result["godina"] . ", ";
	if($a_result["str"] != "Ahead of Print"){
		$mla .= " pp. " . $a_result["str"] . ".";
	}else{
		$mla .= " Ahead of Print.";
	}
	if($a_result["doi"] != "") $mla .= " doi: " . $a_result["doi"];


	

	///////////////////// CHICAGO ///////////////////
	
	$chi = "";
	
	for($i=0;$i<$broj_autora;$i++){
		if($i == $broj_autora-1){ // poslednji autor
			if($broj_autora > 1) $chi .= ", and ";
		}else{
			if ($i != 0) $chi .= ", ";
		}
		$inicijali_imena = Array();
		$inicijali_imena = explode(" ", $autor_components[$i][fname]);
		// autori su najveca frka
		$chi .= $autor_components[$i][lname] . ", ";
		$chi .= $autor_components[$i][fname];
		if($autor_components[$i][initials] != "") $chi .= " " . $autor_components[$i][initials];
		if($autor_components[$i][suffix] != "") $chi .= ", " . $autor_components[$i][suffix];
	}

	// ostalo je ok...
	$chi .= ". \"" . $a_result["naslov_eng"] . ".\" ";
	$chi .= "<i>Sport Mont</i> " . $b_result["vol"] . ", no. " . $b_result["no"] . " (" . $b_result["godina"] . "). ";
	$chi .= $a_result["str"] . ".";
	if($a_result["doi"] != "") $chi .= " doi: " . $a_result["doi"];
		

	
?>

<div id="accordion">
    <a href="#one" class="first">APA citation</a>
    <div id="one">
        <?php echo $apa; ?>
    </div>
    
    <a href="#two">MLA8 citation</a>
    <div id="two">
        <?php echo $mla; ?>
    </div>
    
    <a href="#three">Chicago citation</a>
    <div id="three">
        <?php echo $chi; ?>
    </div>
</div>

<!------------------------------ CITATI ------------------------------------->

<div style="height: 20px; font-style: normal;"></div>

<?php


// scopus citati odavde
if($a_result["scopus_id"]!=""){
?>
<div style="height: 20px;"></div>
<img width="180px" border="1" height="26px" src="http://api.elsevier.com/content/abstract/citation-count?scopus_id=<?php echo $a_result["scopus_id"]; ?>&httpAccept=image/jpeg&apiKey=abfff77d6e4a3d04c9f2f9dfe365b603" /><br /><br />
<?php
}
?>

<?php
// brojaci
$articlehits = $a_result['hits'];
$currentip = $_SERVER['REMOTE_ADDR'];

if($a_result['lastip'] != $currentip){
	mysql_query("UPDATE clanci SET hits = hits + 1 WHERE id = '".$a_result['id']."'") or die(mysql_error());
	mysql_query("UPDATE clanci SET lastip = '". $currentip ."' WHERE id = '".$a_result['id']."'") or die(mysql_error());
	$articlehits++;
}
?>

<div style="color: #333; background-color: #e0f1fb; margin: auto; width: 180px; border: 1px solid; border-radius: 2px 15px; padding: 5px 0px;">
This page has been<br />visited <b><span style="color: #0070C0; font-size: 1.75em;"><?php echo $articlehits; ?></span></b> times<br />
</div>

<div style="height: 20px;"></div>

<div style="color: #333; background-color: #e0f1fb; margin: auto; width: 180px; border: 1px solid; border-radius: 2px 15px; padding: 5px 0px;">
This article has been<br />
downloaded <b><span style="color: #0070C0; font-size: 1.75em;"><?php echo $a_result['downloads']; ?></span></b> times<br />
</div>

