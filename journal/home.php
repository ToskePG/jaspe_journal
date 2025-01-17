<div id="lijevi">
	<img src="images/knjigica_3d_30.jpg">

	<h3>Dear Readers,</h3>
	<p> Journal of Anthropology of Sport and Physical Education (JASPE) was founded in 2017 and to this day 242
		scientific papers of researches from all continents have been published in it. </p>
	<p> In 2018, the editorial board has been strengthened, and this will be done continuously so the journal would grow
		constantly. Today, Journal of Anthropology of Sport and Physical Education (JASPE) is indexed into seven
		international databases, of which the most significant are DOAJ and Index Copernicus, and it must be pointed out
		that, at the moment, it is also passing through the evaluation process in the Scopus database, and this process
		will be completed very soon. </p>
	<p> Since 2017, each scientific paper has got a recognizable DOI number. Editor-in-chief is Fidanka Vasileva who is
		performing this function from the beggining of 2021. A new and modern design of PDF papers has been done for the
		July issue of 2018, and since January issue of 2019 the Editorial Board has reached the decision to publish 10
		papers per issue. Since January issue of 2021 the Editorial Board reached the decision to reduce the number of
		published papers on five. The last five published papers, will be contained at the home page of the site. Also,
		the statistic of the journal was introduced, where the latest statistical indicators can be viewed. The system
		of downloading papers in PDF format is modern; bar codes for each paper have been introduced, while the number
		of visits and downloads are visible. Also, under each paper a discussion forum was introduced, where readers can
		post their comments and suggestions that can improve the quality of the journal. </p>
	<p> We thank all readers of Journal of Anthropology of Sport and Physical Education (JASPE) and we are confident
		that this latest edition will be informative enough. </p>

	<p>Editor-in-Chief<br />
		Fidanka Vasileva<br />

		<?php
		//	$qstrana = mysql_query('select * from `strane` where `id` = "21" limit 1') or die(mysql_error());
//	$row = mysql_fetch_assoc($qstrana);
//    echo "<h3>".$row["headline"]."</h3>";
//    echo $row["content"];
		?>

</div>
<div id="desni">
	<h3>Current Issue</h3>
	<?php
	function fetch_autor($aut)
	{
		$aa_query = mysql_query("SELECT * FROM autori WHERE `id`='$aut' LIMIT 1") or die(mysql_error());
		$aa_result = mysql_fetch_array($aa_query);

		return $aa_result["autor_eng"];
	}
	function fetch_institucija($ins)
	{
		$ai_query = mysql_query("SELECT * FROM institucije WHERE `id`='$ins' LIMIT 1") or die(mysql_error());
		$ai_result = mysql_fetch_array($ai_query);

		return $ai_result["institucija_eng"];
	}

	function brisi_duplikate($array)
	{
		$noviArray = array();
		foreach ($array as $key => $val) {
			$noviArray[$val] = 1;
		}
		return array_keys($noviArray);
	}

	?>

	<?php
	$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='30' ORDER BY id ASC") or die(mysql_error());
	$b_query = mysql_query("SELECT * FROM brojevi WHERE `id`='30' LIMIT 1") or die(mysql_error());
	$b_result = mysql_fetch_array($b_query);

	while ($al_result = mysql_fetch_array($al_query)) {
		?>

		<div class="sizeclanka">
			<p class="tip"><?php echo "&nbsp;&nbsp;" . $al_result["tip"]; ?></p>
			<p><!-- sekcija=article umjesto sekcija=under-construction -->
				<span class="artlink"><a
						href="?sekcija=article&artid=<?php echo $al_result["id"]; ?>"><?php echo $al_result["naslov_eng"]; ?>
					</a></span>
			</p>

			<?php
			$institucija_id = array();
			$broj_autora = 0;
			for ($i = 1; $i <=50; $i++) {
				if ($al_result["autor" . $i] != "") {
					$autor_id[$i] = $al_result["autor" . $i];
					$institucija_id[$i] = explode(",", $al_result["institucija" . $i]);
					$broj_autora++;
				}
			}

			$ins_counter = 0;
			$institucije = array();

			foreach ($institucija_id as $h) {
				for ($k = 1; $k <= sizeof($h); $k++) {
					$institucije[] = $h[$k - 1];
				}
			}

			$institucije = brisi_duplikate($institucije);

			$aut_string = "";
			$ins_string = "";

			for ($i = 1; $i <= $broj_autora; $i++) {
				if ($i > 1)
					$aut_string = $aut_string . ", ";
				$aut_string .= "<em><a href=\"/?sekcija=articles&alc=autor&alv=" . $autor_id[$i] . "\">" . fetch_autor($autor_id[$i]) . "</a></em>";
			}

			echo "<p>" . $aut_string . "</p>";

			/*	
															 foreach ($institucije as $key => $h){
																 $ins_string .= "<sup>".($key+1)."</sup><em>" . fetch_institucija($institucije[$key]) . "</em><br />";
															 }

															 
															 echo "<p>".$ins_string."</p>";
														 */
			?>

			<?php /*if(!isset($_SESSION["myusername"])){
							  $clink="<a href=\"#\" onclick=\"$('#login-login').click()\";>";
							  }else{ */
			$clink = "<a href=\"/clanci/" . $al_result["file"] . "\" onclick=\"brojac(" . $al_result["id"] . ")\" target=\"_blank\">";
			/*}*/ ?>


			<!-- MJSSM broj i DOI -->
			<p><span class="artlink-manji">
					J. Anthr. Sport Phys. Educ. <?php echo $b_result["godina"]; ?>,
					<?php echo $b_result["vol"]; ?>(<?php echo $b_result["no"]; ?>), <?php echo $al_result["str"]; ?>
				</span></p>
			<?php if ($al_result["doi"] != "") { ?>
				<p><span class="artlink-manji">
						DOI: <a href="https://doi.org/<?php echo $al_result["doi"]; ?>"
							target="_blank"><?php echo $al_result["doi"]; ?></a>
					</span></p>
			<?php } ?>

			<!-- ?sekcija=abstract-->
			<p><span class="artlink-manji"><a href="/?sekcija=abstract&artid=<?php echo $al_result["id"]; ?>">Abstract </a>
					| <?php echo $clink; ?>Article (PDF –
					<?php echo round(filesize("clanci/" . $al_result["file"]) / 1024) . "KB)"; ?></a>
					<?php if ($al_result["references"] != "") { ?>
						| <a href="/?sekcija=abstract&artid=<?php echo $al_result["id"]; ?>#references">References</a>
					<?php } ?>
				</span></p>

		</div> <!--  sizeclanka -->
		<?php
	}
	?>

</div>

<div id="hovertoseemore">
	(Hover to see all
	articles)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>


<?php /*
$qstrana = mysql_query('select * from `strane` where `id` = "21" limit 1') or die(mysql_error());

$row = mysql_fetch_assoc($qstrana);
echo "<h3>".$row["headline"]."</h3>";
echo $row["content"];

$qstrana = mysql_query('select * from `strane` where `id` = "24" limit 1') or die(mysql_error());

$row = mysql_fetch_assoc($qstrana);
echo "<h3>".$row["headline"]."</h3>";
echo $row["content"];

*/
?>