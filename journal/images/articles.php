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

function stampaj_listu($al_query)
{
	while ($al_result = mysql_fetch_array($al_query)) {

		$broj = $al_result["broj"];
		$b_query = mysql_query("SELECT * FROM brojevi WHERE `id`=$broj LIMIT 1") or die(mysql_error());
		$b_result = mysql_fetch_array($b_query);


		?>
		<div class="sizeclanka">
			<p class="tip">
				<?php echo "&nbsp;&nbsp;" . $al_result["tip"]; ?>
			</p>
			<p>
				<span class="artlink"><a href="?sekcija=article&artid=<?php echo $al_result["id"]; ?>">
						<?php echo $al_result["naslov_eng"]; ?>
					</a></span>
			</p>

			<?php
			$institucija_id = array();
			$broj_autora = 0;
			for ($i = 1; $i <= 16; $i++) {
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
				$aut_string .= "<em><a href=\"/?sekcija=articles&alc=autor&alv=" . $autor_id[$i] . "\">" . fetch_autor($autor_id[$i]) . "</a></em><sup>";
				$nadjen = false;
				foreach ($institucija_id[$i] as $h) {
					$pretraga = array_search($h, $institucije);
					if ($nadjen)
						$aut_string = $aut_string . ",";
					$aut_string .= ($pretraga + 1);
					$nadjen = true;
				}
				$aut_string .= "</sup>";
			}

			echo "<p><span class=\"podcrveno\">" . $aut_string . "</span></p>";


			foreach ($institucije as $key => $h) {
				$ins_string .= "<sup>" . ($key + 1) . "</sup><em>" . fetch_institucija($institucije[$key]) . "</em><br />";
			}


			echo "<p>" . $ins_string . "</p>";

			?>

			<?php /*if(!isset($_SESSION["myusername"])){
									$tekstic="Login required to view full text";
									$clink="<a href=\"#\" onclick=\"$('#login-login').click()\";>";
									}else{ */
			$tekstic = "";




			$clink = "<a href=\"/clanci/" . $al_result["file"] . "\" onclick=\"brojac(" . $al_result["id"] . ")\" target=\"_blank\">";






			/*}*/?>

			<!-- SMJ broj i DOI -->
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
			<p><span class="artlink"><a href="/?sekcija=abstract&artid=<?php echo $al_result["id"]; ?>">Abstract </a> |
					<?php echo $clink; ?>Article (PDF –
					<?php echo round(filesize("clanci/" . $al_result["file"]) / 1024) . "KB)"; ?></a>
					<?php if ($al_result["references"] != "") { ?>
						| <a href="/?sekcija=abstract&artid=<?php echo $al_result["id"]; ?>#references">References</a>
					<?php } ?>
				</span></p>

		</div> <!--  sizeclanka -->
		<?php
	} // while end

} // stampaj_listu(...) end

?>
<?php


if ($alc == "autor") {
	echo "<h3>Articles by author <b>" . fetch_autor($alv) . "</b></h3>";
	$al_query = mysql_query("SELECT * FROM clanci WHERE `autor1`='$alv' OR `autor2`='$alv' OR `autor3`='$alv' OR `autor4`='$alv' OR `autor5`='$alv' OR `autor6`='$alv' OR `autor7`='$alv' OR `autor8`='$alv' OR `autor9`='$alv' OR `autor10`='$alv' OR `autor11`='$alv' OR `autor13`='$alv' OR `autor12`='$alv' OR `autor14`='$alv'  OR `autor15`='$alv'  OR `autor16`='$alv'") or die(mysql_error());
} elseif ($alc == "press") { // Articles in Press
	echo "<h3>Ahead of Print</h3>"; // staviti ovo na kraju ako ima clanaka: (last updated 05/02/2019)
	$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`>='$alv' ORDER BY id ASC") or die(mysql_error());
	if (mysql_num_rows($al_query) == 0)
		echo "NO ACCEPTED MANUSCRIPTS";
} elseif ($alc == "current") { // Current Issue
	echo "<h3>Current Issue</h3>";
	$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='$alv' ORDER BY id ASC") or die(mysql_error());
} elseif ($alc == "past") { // Past Issues
	echo "<h3>Past Issues</h3>";
	?>
	<div class="faq">
		<div class="question"><u><b>2003</b></u></div>
		<div class="answer">


			<?php

			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='$alv' ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-1.jpg\" border=\"0\"><br /><b>December 2003, I(1) <a href='/download_pdf_arhiva.php/SM_01.pdf' target='_blank'>[print version]</a></b></p>";
} elseif ($alc == "search") { // search

	$alv = $_POST['searchstring'];

	echo "<h3>Search results for search string <em>\"" . $alv . "\"</em></h3>";
	$al_query = mysql_query("SELECT * FROM clanci WHERE `naslov_eng` like '%$alv%' OR `naslov_mne` like '%$alv%' OR `sazetak_eng` like '%$alv%' OR `sazetak_mne` like '%$alv%' OR `keywords_eng` like '%$alv%' OR `keywords_mne` like '%$alv%'") or die(mysql_error());
	//		$al_query = mysql_query("SELECT * FROM clanci c LEFT JOIN (autori as a1, autori as a2, autori as a3, autori as a4, autori as a5) ON (c.autor1 = a1.id OR c.autor2 = a2.id OR c.autor3 = a3.id OR c.autor4 = a4.id OR c.autor5 = a5.id) WHERE `naslov_eng` like '%$alv%' OR `naslov_mne` like '%$alv%' OR `sazetak_eng` like '%$alv%' OR `sazetak_mne` like '%$alv%' OR `keywords_eng` like '%$alv%' OR `keywords_mne` like '%$alv%' OR a1.autor_eng like '%$alv%'") or die(mysql_error());
} else {
	$al_query = mysql_query("SELECT * FROM clanci WHERE `$alc`='$alv' ORDER BY id ASC") or die(mysql_error());
}


stampaj_listu($al_query);


if ($alc == "past") {
	?>
		</div>
	</div>


	<div class="faq">
		<div class="question"><u><b>2004</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=2 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-2.jpg\" border=\"0\"><br /><b>May 2004, II(2-3) <a href='/download_pdf_arhiva.php/SM_02-03.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=3 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-3.jpg\" border=\"0\"><br /><b>September 2004, II(4) <a href='/download_pdf_arhiva.php/SM_04.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>


	<div class="faq">
		<div class="question"><u><b>2005</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=4 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-4.jpg\" border=\"0\"><br /><b>January 2005, III(5) <a href='/download_pdf_arhiva.php/SM_05.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=5 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-5.jpg\" border=\"0\"><br /><b>April 2005, III(6-7) <a href='/download_pdf_arhiva.php/SM_06-07.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=6 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-6.jpg\" border=\"0\"><br /><b>May 2005, III(8-9) <a href='/download_pdf_arhiva.php/SM_08-09.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>

	<div class="faq">
		<div class="question"><u><b>2006</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=7 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-7.jpg\" border=\"0\"><br /><b>Jun 2006, IV(10-11) <a href='/download_pdf_arhiva.php/SM_10-11.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>

		</div>
	</div>

	<div class="faq">
		<div class="question"><u><b>2007</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=8 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-8.jpg\" border=\"0\"><br /><b>May 2007, V(12-13-14) <a href='/download_pdf_arhiva.php/SM_12-13-14.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>

		</div>
	</div>

	<div class="faq">
		<div class="question"><u><b>2008</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=9 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-9.jpg\" border=\"0\"><br /><b>August 2008, VI(15-16-17) <a href='/download_pdf_arhiva.php/SM_15-16-17.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>

		</div>
	</div>

	<div class="faq">
		<div class="question"><u><b>2009</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=10 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-10.jpg\" border=\"0\"><br /><b>November 2009, VI(18-19-20) <a href='/download_pdf_arhiva.php/SM_18-19-20.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>

		</div>
	</div>


	<div class="faq">
		<div class="question"><u><b>2010</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=11 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-11.jpg\" border=\"0\"><br /><b>March 2010, VII(21-22) <a href='/download_pdf_arhiva.php/SM_21-22.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=12 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-12.jpg\" border=\"0\"><br /><b>September 2010, VIII(23-24) <a href='/download_pdf_arhiva.php/SM_23-24.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>

		</div>
	</div>
	<div class="faq">
		<div class="question"><u><b>2011</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=13 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-13.jpg\" border=\"0\"><br /><b>March 2011, VIII(25-26-27) <a href='/download_pdf_arhiva.php/SM_25-26-27.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=14 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-14.jpg\" border=\"0\"><br /><b>August 2011, IX(28-29-30) <a href='/download_pdf_arhiva.php/SM_28-29-30.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=15 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-15.jpg\" border=\"0\"><br /><b>September 2011, IX(31-32-33) <a href='/download_pdf_arhiva.php/SM_31-32-33.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>
	<div class="faq">
		<div class="question"><u><b>2012</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=16 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-16.jpg\" border=\"0\"><br /><b>September 2012, X(34-35-36) <a href='/download_pdf_arhiva.php/SM_34-35-36.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>
	<div class="faq">
		<div class="question"><u><b>2013</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=17 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-17.jpg\" border=\"0\"><br /><b>July 2013, XI(37-38-39) <a href='/download_pdf_arhiva.php/SM_37-38-39.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>
	<div class="faq">
		<div class="question"><u><b>2014</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=18 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-18.jpg\" border=\"0\"><br /><b>June 2014, XII(40-41-42) <a href='/download_pdf_arhiva.php/SM_40-41-42.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>
	<div class="faq">
		<div class="question"><u><b>2015</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=19 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-19.jpg\" border=\"0\"><br /><b>May 2015, XIII(43-44-45) <a href='/download_pdf_arhiva.php/SM_43-44-45.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>
	<div class="faq">
		<div class="question"><u><b>2016</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=20 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-20.jpg\" border=\"0\"><br /><b>February 2016, 14(1) <a href='/download_pdf_arhiva.php/SM_feb_2016.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>
	<div class="faq">
		<div class="question"><u><b>2017</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=23 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-23.jpg\" border=\"0\"><br /><b>February 2017, 15(1) <a href='/download_pdf_arhiva.php/SM_februar_2017.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=24 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-24.jpg\" border=\"0\"><br /><b>June 2017, 15(2) <a href='/download_pdf_arhiva.php/SM_jun_2017.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=25 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-25.jpg\" border=\"0\"><br /><b>October 2017, 15(3) <a href='/download_pdf_arhiva.php/SMJ_October_2017.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>
	<div class="faq">
		<div class="question"><u><b>2018</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=26 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-26.jpg\" border=\"0\"><br /><b>February 2018, 16(1) <a href='/download_pdf_arhiva.php/SMJ_February_2018.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=27 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-27.jpg\" border=\"0\"><br /><b>June 2018, 16(2) <a href='/download_pdf_arhiva.php/SMJ_June_2018.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=28 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-28.jpg\" border=\"0\"><br /><b>October 2018, 16(3) <a href='/download_pdf_arhiva.php/SMJ_October_2018.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>
	<div class="faq">
		<div class="question"><u><b>2019</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=29 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-29.jpg\" border=\"0\"><br /><b>February 2019, 17(1) <a href='/download_pdf_arhiva.php/SM_February_2019.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=30 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-30.jpg\" border=\"0\"><br /><b>June 2019, 17(2) <a href='/download_pdf_arhiva.php/SM_June_2019.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=31 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-31.jpg\" border=\"0\"><br /><b>October 2019, 17(3) <a href='/download_pdf_arhiva.php/SM_October_2019.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>

	<div class="faq">
		<div class="question"><u><b>2020</b></u></div>
		<div class="answer">

			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=32 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-32.jpg\" border=\"0\"><br /><b>February 2020, 18(1) <a href='/download_pdf_arhiva.php/SM_February_2020.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>

			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=33 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-33.jpg\" border=\"0\"><br /><b>June 2020, 18(2) <a href='/download_pdf_arhiva.php/sportmont_june_2020.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>

			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=34 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-34.jpg\" border=\"0\"><br /><b>October 2020, 18(3) <a href='/download_pdf_arhiva.php/sportmont_october_2020.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>



		</div>
	</div>

	<div class="faq">
		<div class="question"><u><b>2021</b></u></div>
		<div class="answer">

			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=35 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-35.jpg\" border=\"0\"><br /><b>February 2021, 19(1) <a href='/download_pdf_arhiva.php/SM_February_2021.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>

			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=36 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-36.jpg\" border=\"0\"><br /><b>June 2021, 19(2) <a href='/download_pdf_arhiva.php/SM_June_2021.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>

			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=38 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-38.jpg\" border=\"0\"><br /><b>September 2021, 19(S2) <a href='/download_pdf_arhiva.php/SM_September_2021.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>

			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=39 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica-3d-39.jpg\" border=\"0\"><br /><b>October 2021, 19(3) <a href='/download_pdf_arhiva.php/SM_October_2021.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>

	<div class="faq">
		<div class="question"><u><b>2022</b></u></div>
		<div class="answer">

			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=40 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica_3d_40.jpg\" border=\"0\"><br /><b>February 2022, 20(1) <a href='/download_pdf_arhiva.php/SM_February_2022.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=41 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica_3d_41.jpg\" border=\"0\"><br /><b>June 2022, 20(2) <a href='/download_pdf_arhiva.php/SM_June_2022.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=43 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica_3d_43.jpg\" border=\"0\"><br /><b>October 2022, 20(3) <a href='/download_pdf_arhiva.php/SM_October_2022.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>

	<div class="faq">
		<div class="question"><u><b>2023</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`= 44 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica_3d_44.jpg\" border=\"0\"><br /><b>February 2023, 21(1) <a href='/download_pdf_arhiva.php/SM_February_2023.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`= 45 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica_3d_45.jpg\" border=\"0\"><br /><b>June 2023, 21(2) <a href='/download_pdf_arhiva.php/SM_June_2023.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query)
				?>
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`= 46 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica_3d_46.jpg\" border=\"0\"><br /><b>October 2023, 21(3) <a href='/download_pdf_arhiva.php/SM_October_2023.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query)
				?>
		</div>
	</div>

	<div class="faq">
		<div class="question"><u><b>2024</b></u></div>
		<div class="answer">
			<?php
			$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`= 47 ORDER BY id ASC") or die(mysql_error());
			echo "<p><img src=\"images/knjigica_3d_47.jpg\" border=\"0\"><br /><b>February 2024, 22(1) <a href='/download_pdf_arhiva.php/SM_February_2024.pdf' target='_blank'>[print version]</a></b></p>";
			stampaj_listu($al_query);
			?>
		</div>
	</div>




	<?php
} // zadnji if
?>