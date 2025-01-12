<div id="lijevi">
      <img src="images/knjigica-3d-17.jpg" />
      <h3>Dear Readers,</h3>

<p>It is not news that the world is currently in a state of tremendous turmoil. For many of us it is a toxic world. Since our mission is to serve children, youth, families, communities, and school personnel from around the world, we do believe that you will find the Sport Mont Journal to be fertile ground for offering assistance to others via the generation, dissemination, and preservation of findings from front-line research. On the other hand, the science is the fuel of progress, in the sports sciences, medicine, and much wider and we have to use this opportunity to spread out the best ideas and make this World better, mostly due to the reason the industrialization and technological advancement has adversely affected our environment and lifestyles that has manifested in new types of diseases and ailments, which poses a challenge not only to medical but also to sociological, psychological and more related fraternities. Hence one needs to do specialization and also acquire certain skills in advanced technologies, necessary to operate and use sophisticated equipment’s, which not only help in exact diagnosis of the subjects in need but also provide options to use non-invasive methods of treatment and also increase healthy lifestyles dramatically. Maximum improvement of health and relief from suffering within available resources should be our main goal.</p>

<p>Finally, we wish to encourage more contributions from the scientific community and industry practitioners to ensure a continued success of our journal. Authors, reviewers and guest editors are always welcome. We also welcome comments and suggestions that could improve the quality of our journal.</p>

<p>Thank you for reading us and we hope you will find this issue of SMJ informative enough.</p>

<p>Editor-in-Chief<br />
Prof. Duško Bjelica, PhD</p>





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
	$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='17' ORDER BY id ASC") or die(mysql_error());
	while($al_result = mysql_fetch_array($al_query)){
?>

<div class="sizeclanka">
<p class="tip"><?php echo "&nbsp;&nbsp;" . $al_result["tip"]; ?></p>
<p><!-- sekcija=article umjesto sekcija=under-construction -->
<span class="artlink"><a href="?sekcija=article&artid=<?php echo $al_result["id"]; ?>"><?php echo $al_result["naslov_eng"]; ?> (Pg. <?php echo $al_result["str"]; ?>)</a></span>
</p>

<?php
	$broj_autora = 0;
	for($i=1;$i<=10;$i++){
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

<?php /*if(!isset($_SESSION["myusername"])){
$clink="<a href=\"#\" onclick=\"$('#login-login').click()\";>";
}else{ */
$clink="<a href=\"clanci/" . $al_result["file"] . "\" target=\"_blank\">";
/*}*/ ?>


<!-- ?sekcija=abstract-->
<p><span class="artlink-manji"><a href="/?sekcija=abstract&artid=<?php echo $al_result["id"]; ?>">Abstract & Keywords</a> &mdash; <?php echo $clink; ?>FULL TEXT (PDF <?php echo round(filesize("clanci/".$al_result["file"])/1024) ."KB)"; ?></a></span></p>

</div> <!--  sizeclanka -->
<?php
	}
?>

</div>
