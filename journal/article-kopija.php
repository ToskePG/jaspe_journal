<?php
function fetch_autor($aut) {
	$aa_query = mysql_query("SELECT * FROM autori WHERE `id`='$aut' LIMIT 1") or die(mysql_error());
	$aa_result = mysql_fetch_array($aa_query);
	
	return $aa_result["autor_eng"];
}
function fetch_institucija($ins) {
	$ai_query = mysql_query("SELECT * FROM institucije WHERE `id`='$ins' LIMIT 1") or die(mysql_error());
	$ai_result = mysql_fetch_array($ai_query);
	
	return $ai_result["institucija_eng"];
}

?>

<?php
 	$a_query = mysql_query("SELECT * FROM clanci WHERE `id`='$article_id' LIMIT 1") or die(mysql_error());
	$a_result = mysql_fetch_array($a_query);
?>

<?php
	$broj_autora = 0;
	for($i=1;$i<=10;$i++){
		if($a_result["autor".$i]!=""){
			$autor_id[$i]=$a_result["autor".$i];
			$institucija_id[$i]=$a_result["institucija".$i];
			
/*			$aa_query = mysql_query("SELECT * FROM autori WHERE `id`='$autor_id[$i]' LIMIT 1") or die(mysql_error());
			$aa_result = mysql_fetch_array($aa_query);
			$ai_query = mysql_query("SELECT * FROM institucije WHERE `id`='$institucija_id[$i]' LIMIT 1") or die(mysql_error());
			$ai_result = mysql_fetch_array($ai_query);
			
			echo $aa_result["autor_eng"]."".$ai_result["institucija_eng"]."<br />";
*/			
			$broj_autora++;
		}
	}


	$ins_counter = 0;
	
//	$aut_string = "<em><a href=\"/?sekcija=articles&alc=autor&alv=".$autor_id[1]."\">".fetch_autor($autor_id[1])."</a></em><sup>1</sup>";
//	$ins_string = "<sup>1</sup><em>".fetch_institucija($institucija_id[1])."</em><br />";
	$aut_string = "";
	$ins_string = "";

	for($i=1;$i<=$broj_autora;$i++){
		$institucija_postoji = 0;
		for($j=1;$j<$i;$j++){
			if($institucija_id[$i] == $institucija_id[$j]){
				$institucija_postoji = $j;
				//echo "<span style=\"color:#ddd;\">".$i.":".$j."-</span>";
				break;
				//echo $j . " vec postoji<br />";
			}
		}
		if($i>1) $aut_string = $aut_string . ", ";
		if($institucija_postoji == 0){
			$ins_counter++;
			$indeksi_institucija[$i] = $ins_counter;
			$ins_string = $ins_string ."<sup>". $ins_counter . "</sup><em>" . fetch_institucija($institucija_id[$i]) . "</em><br />";
			$aut_string = $aut_string . "<em><a href=\"/?sekcija=articles&alc=autor&alv=".$autor_id[$i]."\">" . fetch_autor($autor_id[$i]) . "</a></em><sup>" . $ins_counter . "</sup>";
		}else{
			$indeksi_institucija[$i] = $indeksi_institucija[$institucija_postoji];
			$aut_string = $aut_string . "<em><a href=\"/?sekcija=articles&alc=autor&alv=".$autor_id[$i]."\">" . fetch_autor($autor_id[$i]). "</a></em><sup>" . $indeksi_institucija[$i] . "</sup>";
		}
	}
	
	echo "<p><span class=\"podcrveno\">".$aut_string."</span></p>";
	echo "<p>".$ins_string."</p>";
//echo $ins_counter . "<br />";



/*

	for($i=1;$i<=$broj_autora;$i++){
		echo $autor_id[$i]." iz ";
		echo $institucija_id[$i]."<br />";
	}
*/

?>


<h1><?php echo $a_result["naslov_eng"]; ?></h1>
<?php if($a_result["naslov_mne"]!=""){ ?>
<h4><?php echo $a_result["naslov_mne"]; ?></h4>
<?php } ?>
<h3>Abstract (ENG)</h3>
<p><?php echo $a_result["sazetak_eng"]; ?></p>
<h3>Keywords (ENG)</h3>
<p><?php echo $a_result["keywords_eng"]; ?></p>
<?php if($a_result["sazetak_mne"]!=""){ ?>
<h3>Abstract (MNE)</h3>
<p><?php echo $a_result["sazetak_mne"]; ?></p>
<?php } ?>
<?php if($a_result["keywords_mne"]!=""){ ?>
<h3>Keywords (MNE)</h3>
<p><?php echo $a_result["keywords_mne"]; ?></p>
<?php } ?>
<?php /*if(!isset($_SESSION["myusername"])){ ?>
<h3>Please <a href="#" onclick="$('#login-login').click()";>login or register</a> to view complete articles in PDF format.</h3>
<?php }else{*/ ?>
<p><span class="artlink"><a href="clanci/<?php echo $a_result["file"]; ?>" target="_blank">VIEW FULL ARTICLE</a></span></p>
<?php /*}*/ ?>