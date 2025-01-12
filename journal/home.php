<div id="lijevi">
	<img src="images/knjigica_3d_49.jpg" />
	<h3>Dear Readers,</h3>

	<p>Sport Mont was founded in 2003 and has a fairly long tradition. To this day, more than 1,400 scientific papers of
		researches from all continents have been published in it. These are mostly papers presented at the scientific
		conferences of the Montenegrin Sports Academy, which are traditionally held every year at the end of March or
		early April.</p>
	<p>In 2016, the site of the journal was redesigned and a milestone was made, from which the journal will continue to
		grow and develop faster. Namely, the instructions for authors, design and structure were reformed and the
		decision to publish the journal three times a year (on February, June and October) with 10 papers each was made.
		Since that year, the editorial board has been strengthened, and this will be done continuously so the journal
		would grow constantly. Today, Sport Mont is indexed into twenty three international databases, of which the most
		significant is Scopus, and it must be pointed out that, at the moment, it is also passing through the evaluation
		process in the Web of Science database, and this process will be completed very soon.</p>
	<p>Since 2017, a journal cover page got a new logo. For the first time, each scientific paper has got a recognizable
		DOI number. Now, the magazine has two editor-in-chiefs, together with professor Dusko Bjelica who is performing
		this function from the very beginning, Professor Zoran Milosevic has been promoted. A new and modern design of
		PDF papers has been done for the February issue of 2018, and since June issue of 2018 the Editorial Board has
		reached the decision to publish 20 papers per issue. Significant changes were made on the Sport Mont website.
		The last 20 published papers, which so far could only be found in the site archive, from now on will be
		contained at the home page of the site. Also, the statistic of the journal was introduced, where the latest
		statistical indicators can be viewed. The system of downloading papers in PDF format has also been redesigned;
		bar codes for each paper, citation data and number of downloaded papers have been introduced. Also, under each
		paper a discussion forum was introduced, where readers can post t heir comments and suggestions that can improve
		the quality of the journal.</p>
	<p>We thank all readers of Sport Mont and we are confident that this latest edition will be informative enough.</p>

	<p>Editors-in-Chief<br />
		Prof. Dusko Bjelica, PhD<br />
		Prof. Zoran Milosevic, PhD</p>






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
	$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='49' ORDER BY id ASC") or die(mysql_error());


	$b_query = mysql_query("SELECT * FROM brojevi WHERE `id`='49' LIMIT 1") or die(mysql_error());
	$b_result = mysql_fetch_array($b_query);



	while ($al_result = mysql_fetch_array($al_query)) {
		?>

		<div class="sizeclanka">
			<p class="tip">
				<?php echo "&nbsp;&nbsp;" . $al_result["tip"]; ?>
			</p>
			<p><!-- sekcija=article umjesto sekcija=under-construction -->
				<span class="artlink"><a href="?sekcija=article&artid=<?php echo $al_result["id"]; ?>">
						<?php echo $al_result["naslov_eng"]; ?>
					</a></span>
			</p>

			<?php
			$institucija_id = array();
			$broj_autora = 0;
			for ($i = 1; $i <= 11; $i++) {
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

			echo "<p><span class=\"podcrveno\">" . $aut_string . "</span></p>";

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
					Sport Mont
					<?php echo $b_result["godina"]; ?>,
					<?php echo $b_result["vol"]; ?>(
					<?php echo $b_result["no"]; ?>),
					<?php echo $al_result["str"]; ?>
				</span></p>
			<?php if ($al_result["doi"] != "") { ?>
				<p><span class="artlink-manji">
						DOI: <a href="https://doi.org/<?php echo $al_result["doi"]; ?>" target="_blank">
							<?php echo $al_result["doi"]; ?>
						</a>
					</span></p>
			<?php } ?>

			<!-- ?sekcija=abstract-->
			<p><span class="artlink-manji"><a href="/?sekcija=abstract&artid=<?php echo $al_result["id"]; ?>">Abstract </a>
					|
					<?php echo $clink; ?>Article (PDF –
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