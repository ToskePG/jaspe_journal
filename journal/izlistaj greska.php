<?php
	include "db.php";
	header('Content-Type: text/html; charset=UTF-8');
	
	if (isset($_GET['godina'])) {
		$godina = $_GET['godina'];
	}
	else{
		$godina = "N/A";
	}

?>

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

function brisi_duplikate($array){
	$noviArray = array();
	foreach($array as $key=>$val){
		$noviArray[$val] = 1;
	}
	return array_keys($noviArray);
}

function stampaj_listu($al_query) {
	while($al_result = mysql_fetch_array($al_query)){
	
	$broj = $al_result["broj"];
	$b_query = mysql_query("SELECT * FROM brojevi WHERE `id`=$broj LIMIT 1") or die(mysql_error());
	$b_result = mysql_fetch_array($b_query);
	
?>
<div class="sizeclanka">
<p class="tip"><?php echo "&nbsp;&nbsp;" . $al_result["tip"]; ?></p>
<p>
<span class="artlink"><a href="?sekcija=article&artid=<?php echo $al_result["id"]; ?>"><?php echo $al_result["naslov_eng"]; ?></a></span>
</p>

<?php
	$institucija_id = array();
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


	$ins_counter = 0;
	
//	$aut_string = "<em><a href=\"/?sekcija=articles&alc=autor&alv=".$al_result["autor1"]."\">".fetch_autor($autor_id[1])."</a></em><sup>1</sup>";
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

<?php /*if(!isset($_SESSION["myusername"])){
$tekstic="Login required to view full text";
$clink="<a href=\"#\" onclick=\"$('#login-login').click()\";>";
}else{ */
$tekstic="";
$clink="<a href=\"dl.php?id=" . $al_result["id"] . "\" target=\"_blank\">";
/*}*/ ?>


<!-- SMJ broj i DOI -->
<p><span class="artlink-manji">
Sport Mont <?php echo $b_result["godina"]; ?>, <?php echo $b_result["vol"]; ?>(<?php echo $b_result["no"]; ?>), <?php echo $al_result["str"]; ?>
</span></p>
<?php if($al_result["doi"]!=""){?>
<p><span class="artlink-manji">
 DOI: <a href="https://doi.org/<?php echo $al_result["doi"]; ?>" target="_blank"><?php echo $al_result["doi"]; ?></a>
</span></p>
<?php } ?>

<!-- ?sekcija=abstract-->
<p><span class="artlink"><a href="/?sekcija=abstract&artid=<?php echo $al_result["id"]; ?>">Abstract </a> | <?php echo $clink; ?>Article (PDF – <?php echo round(filesize("clanci/".$al_result["file"])/1024) ."KB)"; ?></a>
<?php if($al_result["references"]!=""){?>
 | <a href="/?sekcija=abstract&artid=<?php echo $al_result["id"]; ?>#references">References</a>
 <?php } ?>
</span></p>

</div> <!--  sizeclanka -->
<?php
	} // while end

} // stampaj_listu(...) end

?>

<?php
	$broj_god_result = mysql_query("SELECT * FROM brojevi WHERE `godina`='$godina' ORDER BY id") or die(mysql_error());
		while ($broj_god_row = mysql_fetch_array($broj_god_result)){
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='".$broj_god_row['id']."' ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-".$broj_god_row['id'].".jpg\" border=\"0\"><br /><b>".$broj_god_row['period_eng']." <a href='download_pdf_arhiva.php/".$broj_god_row['file']."'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		}
?>