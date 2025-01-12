<div id="lijevi">
      <img src="images/knjigica-3d.jpg" />
      <h3>Dear all,</h3>
<p>we would like to invite you to participate in the 10th International Scientific Conference in Sports Science and Physical Education that will be held in Podgorica, Montenegro on April 4– 7, 2013. The Conference will deal with all different aspects of Transformation Process in Sports Science and Physical Education. Experts will update you to the newest cognition. The mix of key note talks and workshops with hands-on training turns the program very attractive for young scientists as well as for experts. </p>

<p>We still encourage scientists and especially young scientist to submit their work. Because of high demand  the deadline for paper submission has been extended until February 25, 2013. Please download our leaflet  for more details. </p>

<p align="center">
<div align="center"><a href="download/Leaflet.pdf" target="_blank"><img src="img/montenegro_adv1.jpg" border="0" /></a></div>
</p>

<p>All accepted papers will be published in the journal Sport Mont!</p>

<p>Looking forward welcoming you in Podgorica in April!</p>

<p>Best regards,</p>

<p>Prof. Dusko Bjelica, PhD<br />
Chairman of the Conference</p>


</div>
<div id="desni">
	  <h3>Current Issue</h3>

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
	$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='1' ORDER BY id ASC") or die(mysql_error());
	while($al_result = mysql_fetch_array($al_query)){
?>

<div class="sizeclanka">
<p class="tip"><?php echo "&nbsp;&nbsp;" . $al_result["tip"]; ?></p>
<p><!-- sekcija=article umjesto sekcija=under-construction -->
<span class="artlink"><a href="?sekcija=article&artid=<?php echo $al_result["id"]; ?>"><?php echo $al_result["naslov_eng"]; ?></a></span>
</p>

<?php
	$broj_autora = 0;
	for($i=1;$i<=5;$i++){
		if($al_result["autor".$i]!=""){
			$autor_id[$i]=$al_result["autor".$i];
			$institucija_id[$i]=$al_result["institucija".$i];
			
/*			$aa_query = mysql_query("SELECT * FROM autori WHERE `id`='$autor_id[$i]' LIMIT 1") or die(mysql_error());
			$aa_result = mysql_fetch_array($aa_query);
			$ai_query = mysql_query("SELECT * FROM institucije WHERE `id`='$institucija_id[$i]' LIMIT 1") or die(mysql_error());
			$ai_result = mysql_fetch_array($ai_query);
			
			echo $aa_result["autor_eng"]."".$ai_result["institucija_eng"]."<br />";
*/			
			$broj_autora++;
		}
	}


	$ins_counter = 1;
	// sekcija=articles umjesto sekcija=under-construction
	$aut_string = "<em><a href=\"/?sekcija=articles&alc=autor&alv=".$al_result["autor1"]."\">".fetch_autor($autor_id[1])."</a></em>";
	$ins_string = "<sup>1</sup><em>".fetch_institucija($institucija_id[1])."</em><br />";
	for($i=2;$i<=$broj_autora;$i++){
		$institucija_postoji = 0;
		for($j=1;$j<$i;$j++){
			if($institucija_id[$i] == $institucija_id[$j]){
				$institucija_postoji = $j;
				break;
//				echo $j . " vec postoji<br />";
			}
		}
		if($institucija_postoji == 0){
			$ins_counter++; // sekcija=articles
			$ins_string = $ins_string ."<sup>". $ins_counter . "</sup><em>" . fetch_institucija($institucija_id[$i]) . "</em><br />";
			$aut_string = $aut_string . ", <em><a href=\"/?sekcija=articles&alc=autor&alv=".$autor_id[$i]."\">" . fetch_autor($autor_id[$i]) . "</a></em>";
		}else{ /* sekcija=articles */
			$aut_string = $aut_string . ", <em><a href=\"/?sekcija=articles&alc=autor&alv=".$autor_id[$i]."\">" . fetch_autor($autor_id[$i]). "</a></em>";
		}
	}
	
	echo "<p>".$aut_string."</p>";
//echo $ins_counter . "<br />";



/*

	for($i=1;$i<=$broj_autora;$i++){
		echo $autor_id[$i]." iz ";
		echo $institucija_id[$i]."<br />";
	}
*/

?>

<?php if(!isset($_SESSION["myusername"])){
$clink="<a href=\"#\" onclick=\"$('#login-login').click()\";>";
}else{ 
$clink="<a href=\"clanci/" . $al_result["file"] . "\" target=\"_blank\">";
} ?>


<!-- ?sekcija=abstract-->
<p><span class="artlink-manji"><a href="/?sekcija=abstract&artid=<?php echo $al_result["id"]; ?>">Abstract & Keywords</a> &mdash; <?php echo $clink; ?>FULL TEXT (PDF <?php echo round(filesize("clanci/".$al_result["file"])/1024) ."KB)"; ?></a></span></p>

</div> <!--  sizeclanka -->
<?php
	}
?>

</div>
