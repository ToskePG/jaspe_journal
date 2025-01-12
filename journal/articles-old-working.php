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


function stampaj_listu($al_query) {
	while($al_result = mysql_fetch_array($al_query)){
	
?>
<div class="sizeclanka">
<p class="tip"><?php echo "&nbsp;&nbsp;" . $al_result["tip"]; ?></p>
<p>
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
$clink="<a href=\"clanci/" . $al_result["file"] . "\" target=\"_blank\">";
/*}*/ ?>


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

}




?>



<?php


	if($alc=="autor"){
		echo "<h3>Articles by author <b>".fetch_autor($alv)."</b></h3>";
		$al_query = mysql_query("SELECT * FROM clanci WHERE `autor1`='$alv' OR `autor2`='$alv' OR `autor3`='$alv' OR `autor4`='$alv' OR `autor5`='$alv' OR `autor6`='$alv' OR `autor7`='$alv' OR `autor8`='$alv' OR `autor9`='$alv' OR `autor10`='$alv'") or die(mysql_error());
	}elseif($alc=="press"){ // Articles in Press
		echo "<h3>Articles in Press</h3>";
		$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='$alv' ORDER BY id ASC") or die(mysql_error());
		if(mysql_num_rows($al_query)==0) echo "NO ACCEPTED MANUSCRIPTS";
	}elseif($alc=="current"){ // Current Issue
		echo "<h3>Current Issue</h3>";
		$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='$alv' ORDER BY id ASC") or die(mysql_error());
	}elseif($alc=="past"){ // Past Issues
		echo "<h3>Past Issues</h3>";
		?>
		<div class="faq">
			<div class="question"><u><b>2003</b></u></div>
			<div class="answer">
				

		<?php
		
		$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='$alv' ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
		echo "<p><img src=\"images/knjigica-3d-1.jpg\" border=\"0\"><br /><b>December 2003 - No. 1/I <a href='download_pdf_arhiva.php/SM_01.pdf' target='_blank'>[print version]</a></b></p>";
	}elseif($alc=="search"){ // search
	
		$alv=$_POST['searchstring'];
	
		echo "<h3>Search results for search string <em>\"".$alv."\"</em></h3>";
		$al_query = mysql_query("SELECT * FROM clanci WHERE `naslov_eng` like '%$alv%' OR `naslov_mne` like '%$alv%' OR `sazetak_eng` like '%$alv%' OR `sazetak_mne` like '%$alv%' OR `keywords_eng` like '%$alv%' OR `keywords_mne` like '%$alv%'") or die(mysql_error());
	}else{
		$al_query = mysql_query("SELECT * FROM clanci WHERE `$alc`='$alv' ORDER BY id ASC") or die(mysql_error());
	}
	
	
stampaj_listu($al_query);

//	global $alc;
if($alc=="past"){
?>
			</div>
		</div>


		<!-- 
		<div class="faq">
			<div class="question"><u><b>2003</b></u></div>
			<div class="answer"> -->
		<?php
		//	$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=1 ORDER BY id ASC") or die(mysql_error());
		//	echo "<p><img src=\"images/knjigica-3d-1.jpg\" border=\"0\"><br /><b>December 2003 - No. 1/I <a href='download_pdf_arhiva.php/SM_01.pdf' target='_blank'>[print version]</a></b></p>";
		//	stampaj_listu($al_query);
		?>
		<!--	</div>
		</div>
		 -->
		<div class="faq">
			<div class="question"><u><b>2004</b></u></div>
			<div class="answer">
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=2 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-2.jpg\" border=\"0\"><br /><b>May 2004 - No. 2-3/II <a href='download_pdf_arhiva.php/SM_02-03.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=3 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-3.jpg\" border=\"0\"><br /><b>September 2004 - No. 4/II <a href='download_pdf_arhiva.php/SM_04.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
			</div>
		</div>
		
		<div class="faq">
			<div class="question"><u><b>2005</b></u></div>
			<div class="answer">
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=4 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error()); //cast(replace(`broj`,'-','.') as DECIMAL)
			echo "<p><img src=\"images/knjigica-3d-4.jpg\" border=\"0\"><br /><b>January 2005 - No. 5/III <a href='download_pdf_arhiva.php/SM_05.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=5 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-5.jpg\" border=\"0\"><br /><b>April 2005 - No. 6-7/III <a href='download_pdf_arhiva.php/SM_06-07.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=6 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-6.jpg\" border=\"0\"><br /><b>May 2005 - No. 8-9/III <a href='download_pdf_arhiva.php/SM_08-09.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
			</div>
		</div>
		
		<div class="faq">
			<div class="question"><u><b>2006</b></u></div>
			<div class="answer">
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=7 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-7.jpg\" border=\"0\"><br /><b>Jun 2006 - No. 10-11/IV <a href='download_pdf_arhiva.php/SM_10-11.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
			</div>
		</div>
		
		<div class="faq">
			<div class="question"><u><b>2007</b></u></div>
			<div class="answer">
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=8 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-8.jpg\" border=\"0\"><br /><b>May 2007 - No. 12-13-14/V <a href='download_pdf_arhiva.php/SM_12-13-14.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
			</div>
		</div>
		
		<div class="faq">
			<div class="question"><u><b>2008</b></u></div>
			<div class="answer">
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=9 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-9.jpg\" border=\"0\"><br /><b>August 2008 - No. 15-16-17/VI <a href='download_pdf_arhiva.php/SM_15-16-17.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
			</div>
		</div>
		
		<div class="faq">
			<div class="question"><u><b>2009</b></u></div>
			<div class="answer">
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=10 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-10.jpg\" border=\"0\"><br /><b>November 2009 - No. 18-19-20/VI <a href='download_pdf_arhiva.php/SM_18-19-20.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
			</div>
		</div>
		
		<div class="faq">
			<div class="question"><u><b>2010</b></u></div>
			<div class="answer">
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=11 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-11.jpg\" border=\"0\"><br /><b>March 2010 - No. 21-22/VII <a href='download_pdf_arhiva.php/SM_21-22.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=12 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-12.jpg\" border=\"0\"><br /><b>September 2010 - No. 23-24/VIII <a href='download_pdf_arhiva.php/SM_23-24.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
			</div>
		</div>
		
		<div class="faq">
			<div class="question"><u><b>2011</b></u></div>
			<div class="answer">
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=13 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-13.jpg\" border=\"0\"><br /><b>March 2011 - No. 25-26-27/VIII <a href='download_pdf_arhiva.php/SM_25-26-27.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=14 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-14.jpg\" border=\"0\"><br /><b>August 2011 - No. 28-29-30/IX <a href='download_pdf_arhiva.php/SM_28-29-30.pdf' target='_blank'>[print version]</a></b></p>";
			//stampaj_listu($al_query);
		?>
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=15 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-15.jpg\" border=\"0\"><br /><b>September 2011 - No. 31-32-33/IX <a href='download_pdf_arhiva.php/SM_31-32-33.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
			</div>
		</div>
		
		<div class="faq">
			<div class="question"><u><b>2012</b></u></div>
			<div class="answer">
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=16 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-16.jpg\" border=\"0\"><br /><b>September 2012 - No. 34-35-36/X <a href='download_pdf_arhiva.php/SM_34-35-36.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
			</div>
		</div>
		
		<div class="faq">
			<div class="question"><u><b>2013</b></u></div>
			<div class="answer">
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=17 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-17.jpg\" border=\"0\"><br /><b>July 2013 - No. 37-38-39/XI <a href='download_pdf_arhiva.php/SM_37-38-39.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
			</div>
		</div>		
		
		<div class="faq">
			<div class="question"><u><b>2014</b></u></div>
			<div class="answer">
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=18 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-18.jpg\" border=\"0\"><br /><b>Jun 2014 - No. 40-41-42/XII <a href='download_pdf_arhiva.php/SM_40-41-42.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
			</div>
		</div>		

		<div class="faq">
			<div class="question"><u><b>2015</b></u></div>
			<div class="answer">
		<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=19 ORDER BY cast(replace(`str`,'-','.') as DECIMAL)") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-19.jpg\" border=\"0\"><br /><b>May 2015 - No. 43-44-45/XIII <a href='download_pdf_arhiva.php/SM_43-44-45.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
		?>
			</div>
		</div>

		


<?php
} // zadnji if
?>