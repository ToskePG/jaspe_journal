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


	if($alc=="autor"){
		echo "<h3>Articles by author <b>".fetch_autor($alv)."</b></h3>";
		$al_query = mysql_query("SELECT * FROM clanci WHERE `autor1`='$alv' OR `autor2`='$alv' OR `autor3`='$alv' OR `autor4`='$alv' OR `autor5`='$alv'") or die(mysql_error());
	}elseif($alc=="press"){ // Articles in Press
		echo "<h3>Articles in Press</h3>";
		$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='$alv'") or die(mysql_error());
		if(mysql_num_rows($al_query)==0) echo "NO ACCEPTED MANUSCRIPTS";
	}elseif($alc=="current"){ // Current Issue
		echo "<h3>Current Issue</h3>";
		$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='$alv'") or die(mysql_error());
	}elseif($alc=="past"){ // Past Issues
		echo "<h3>Past Issues</h3>";
		?>
		<div class="faq">
			<div class="question"><u><b>2012</b></u></div>
			<div class="answer">
				

		<?php
		
		$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='$alv'") or die(mysql_error());
		echo "<p><img src=\"images/knjigica-3d-1.jpg\" border=\"0\"><br /><b>Septembar 2012, Vol.1, No.1</b></p>";
	}elseif($alc=="search"){ // search
	
		$alv=$_POST['searchstring'];
	
		echo "<h3>Search results for search string <em>\"".$alv."\"</em></h3>";
		$al_query = mysql_query("SELECT * FROM clanci WHERE `naslov_eng` like '%$alv%' OR `naslov_mne` like '%$alv%' OR `sazetak_eng` like '%$alv%' OR `sazetak_mne` like '%$alv%' OR `keywords_eng` like '%$alv%' OR `keywords_mne` like '%$alv%'") or die(mysql_error());
	}else{
		$al_query = mysql_query("SELECT * FROM clanci WHERE `$alc`='$alv'") or die(mysql_error());
	}
	
	
	while($al_result = mysql_fetch_array($al_query)){
	
?>
<div class="sizeclanka">
<p class="tip"><?php echo "&nbsp;&nbsp;" . $al_result["tip"]; ?></p>
<p>
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
	
	$aut_string = "<em><a href=\"/?sekcija=articles&alc=autor&alv=".$al_result["autor1"]."\">".fetch_autor($autor_id[1])."</a></em><sup>1</sup>";
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
			$ins_counter++;
			$ins_string = $ins_string ."<sup>". $ins_counter . "</sup><em>" . fetch_institucija($institucija_id[$i]) . "</em><br />";
			$aut_string = $aut_string . ", <em><a href=\"/?sekcija=articles&alc=autor&alv=".$autor_id[$i]."\">" . fetch_autor($autor_id[$i]) . "</a></em><sup>" . $ins_counter . "</sup>";
		}else{
			$aut_string = $aut_string . ", <em><a href=\"/?sekcija=articles&alc=autor&alv=".$autor_id[$i]."\">" . fetch_autor($autor_id[$i]). "</a></em><sup>" .$institucija_postoji . "</sup>";
		}
	}
	
	echo "<p>".$aut_string."</p>";
	echo "<p>".$ins_string."</p>";
//echo $ins_counter . "<br />";



/*

	for($i=1;$i<=$broj_autora;$i++){
		echo $autor_id[$i]." iz ";
		echo $institucija_id[$i]."<br />";
	}
*/

?>

<?php if(!isset($_SESSION["myusername"])){
$tekstic="Login required to view full text";
$clink="<a href=\"#\" onclick=\"$('#login-login').click()\";>";
}else{ 
$tekstic="";
$clink="<a href=\"clanci/" . $al_result["file"] . "\" target=\"_blank\">";
} ?>


<?php
/* Privremeno */
if($alc!="press"){
?>

<p><span class="artlink"><a href="/?sekcija=abstract&artid=<?php echo $al_result["id"]; ?>">Abstract & Keywords</a> &mdash; <?php echo $clink; ?>FULL TEXT (PDF <?php echo round(filesize("clanci/".$al_result["file"])/1024) ."KB)"; ?></a> <?php echo $tekstic; ?></span></p>
<?php
}
?>
</div> <!--  sizeclanka -->
<?php
	} // while

	
if($alc=="past"){
?>
			</div>
		</div>
		

		<div class="faq">
			<div class="question"><u><b>2013</b></u></div>
			<div class="answer">
				Yet to come...<br />
				Yet to come...<br />
				Yet to come...<br />
				Yet to come...<br />
				Yet to come...
			</div>
		</div>
		<div class="faq">
			<div class="question"><u><b>2014</b></u></div>
			<div class="answer">
				Yet to come...<br />
				Yet to come...<br />
				Yet to come...<br />
				Yet to come...<br />
				Yet to come...
			</div>
		</div>
		<div class="faq">
			<div class="question"><u><b>2015</b></u></div>
			<div class="answer">
				Yet to come...<br />
				Yet to come...<br />
				Yet to come...<br />
				Yet to come...<br />
				Yet to come...
			</div>
		</div>

<?php
} // zadnji if
?>