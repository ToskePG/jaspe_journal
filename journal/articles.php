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
			<p class="tip"><?php echo "&nbsp;&nbsp;" . $al_result["tip"]; ?></p>
			<p>
				<span class="artlink"><a
						href="?sekcija=article&artid=<?php echo $al_result["id"]; ?>"><?php echo $al_result["naslov_eng"]; ?></a></span>
			</p>

			<?php
			$institucija_id = array();
			$broj_autora = 0;
			for ($i = 1; $i <= 10; $i++) {
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

			echo "<p>" . $aut_string . "</p>";


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




			/*}*/ ?>

			<!-- SMJ broj i DOI -->
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
// Kod odavde


switch ($alc) {
	case "autor":
		echo "<h3>Articles by author <b>" . fetch_autor($alv) . "</b></h3>";
		$al_query = mysql_query("SELECT * FROM clanci WHERE `autor1`='$alv' OR `autor2`='$alv' OR `autor3`='$alv' OR `autor4`='$alv' OR `autor5`='$alv' OR `autor6`='$alv' OR `autor7`='$alv' OR `autor8`='$alv' OR `autor9`='$alv' OR `autor10`='$alv'") or die(mysql_error());
		stampaj_listu($al_query);
		break;

	case "press":
		echo "<h3>Ahead of Print</h3>";
		$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`>'$alv' ORDER BY id ASC") or die(mysql_error());
		stampaj_listu($al_query);
		if (mysql_num_rows($al_query) == 0)
			echo "NO ACCEPTED MANUSCRIPTS";
		break;

	case "current":
		echo "<h3>Current Issue</h3>";
		$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`='$alv' ORDER BY id ASC") or die(mysql_error());
		stampaj_listu($al_query);
		break;

	case "past":
		echo "<h3>Past Issues</h3>";
		?>

		<div class="faq">
			<div class="question"><u><b>2017</b></u></div>
			<div class="answer">
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=1 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-1.jpg\" border=\"0\"><br /><b>October 2017, 1(1) <a href='/download_pdf_arhiva.php/JASPE_October_2017.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>

			</div>
		</div>


		<div class="faq">
			<div class="question"><u><b>2018</b></u></div>
			<div class="answer">
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=2 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-2.jpg\" border=\"0\"><br /><b>January 2018, 2(1) <a href='/download_pdf_arhiva.php/JASPE_January_2018.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=3 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-3.jpg\" border=\"0\"><br /><b>April 2018, 2(2) <a href='/download_pdf_arhiva.php/JASPE_April_2018.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=4 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-4.jpg\" border=\"0\"><br /><b>July 2018, 2(3) <a href='/download_pdf_arhiva.php/JASPE_July_2018.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=5 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-5.jpg\" border=\"0\"><br /><b>October 2018, 2(4) <a href='/download_pdf_arhiva.php/JASPE_October_2018.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>

			</div>
		</div>


		<div class="faq">
			<div class="question"><u><b>2019</b></u></div>
			<div class="answer">
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=6 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-6.jpg\" border=\"0\"><br /><b>January 2019, 3(1) <a href='/download_pdf_arhiva.php/JASPE_January_2019.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=7 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-7.jpg\" border=\"0\"><br /><b>April 2019, 3(2) <a href='/download_pdf_arhiva.php/JASPE_April_2019.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=8 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-8.jpg\" border=\"0\"><br /><b>July 2019, 3(3) <a href='/download_pdf_arhiva.php/JASPE_July_2019.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=9 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-9.jpg\" border=\"0\"><br /><b>October 2019, 3(4) <a href='/download_pdf_arhiva.php/JASPE_October_2019.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>

			</div>
		</div>

		<div class="faq">
			<div class="question"><u><b>2020</b></u></div>
			<div class="answer">
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=10 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-10.jpg\" border=\"0\"><br /><b>January 2020, 4(1) <a href='/download_pdf_arhiva.php/JASPE_January_2020.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=11 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-11.jpg\" border=\"0\"><br /><b>April 2020, 4(2) <a href='/download_pdf_arhiva.php/JASPE_April_2020.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=12 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-12.jpg\" border=\"0\"><br /><b>July 2020, 4(3) <a href='/download_pdf_arhiva.php/JASPE_July_2020.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=13 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-13.jpg\" border=\"0\"><br /><b>October 2020, 4(4) <a href='/download_pdf_arhiva.php/JASPE_October_2020.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>

			</div>
		</div>


		<div class="faq">
			<div class="question"><u><b>2021</b></u></div>
			<div class="answer">
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=14 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica-3d-14.jpg\" border=\"0\"><br /><b>January 2021, 5(1) <a href='/download_pdf_arhiva.php/JASPE_January_2021.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=15 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_15.jpg\" border=\"0\"><br /><b>April 2021, 5(2) <a href='/download_pdf_arhiva.php/JASPE_April_2021.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=16 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_16.jpg\" border=\"0\"><br /><b>July 2021, 5(3) <a href='/download_pdf_arhiva.php/JASPE_July_2021.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=17 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_17.jpg\" border=\"0\"><br /><b>October 2021, 5(4) <a href='/download_pdf_arhiva.php/JASPE_October_2021.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>

			</div>
		</div>

		<div class="faq">
			<div class="question"><u><b>2022</b></u></div>
			<div class="answer">
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=18 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_18.jpg\" border=\"0\"><br /><b>January 2022, 6(1) <a href='/download_pdf_arhiva.php/JASPE_January_2022.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=19 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_19.jpg\" border=\"0\"><br /><b>April 2022, 6(2) <a href='/download_pdf_arhiva.php/JASPE_April_2022.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=20 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_20.jpg\" border=\"0\"><br /><b>July 2022, 6(3) <a href='/download_pdf_arhiva.php/JASPE_July_2022.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=21 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_20.jpg\" border=\"0\"><br /><b>October 2022, 6(4) <a href='/download_pdf_arhiva.php/JASPE_October_2022.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
			</div>
		</div>

		<div class="faq">
			<div class="question"><u><b>2023</b></u></div>
			<div class="answer">
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`= 22 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_22.jpg\" border=\"0\"><br /><b>January 2023, 7(1) <a href='/download_pdf_arhiva.php/JASPE_January_2023.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`= 23 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_23.jpg\" border=\"0\"><br /><b>April 2023, 7(2) <a href='/download_pdf_arhiva.php/JASPE_April_2023.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`= 24 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_24.jpg\" border=\"0\"><br /><b>July 2023, 7(3) <a href='/download_pdf_arhiva.php/JASPE_July_2023.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`= 25 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_25.jpg\" border=\"0\"><br /><b>October 2023, 7(4) <a href='/download_pdf_arhiva.php/JASPE_October_2023.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
			</div>
		</div>


		<div class="faq">
			<div class="question"><u><b>2024</b></u></div>
			<div class="answer">
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=26 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_26.jpg\" border=\"0\"><br /><b>January 2024, 8(1) <a href='/download_pdf_arhiva.php/JASPE_January_2024.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=27 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_27.jpg\" border=\"0\"><br /><b>April 2024, 8(2) <a href='/download_pdf_arhiva.php/JASPE_April_2024.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=28 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_28.jpg\" border=\"0\"><br /><b>July 2024, 8(3) <a href='/download_pdf_arhiva.php/JASPE_July_2024.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=29 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_29.jpg\" border=\"0\"><br /><b>October 2024, 8(4) <a href='/download_pdf_arhiva.php/JASPE_October_2024.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
			</div>
		</div>

		<div class="faq">
			<div class="question"><u><b>2025</b></u></div>
			<div class="answer">
				<?php
				$al_query = mysql_query("SELECT * FROM clanci WHERE `broj`=30 ORDER BY id ASC") or die(mysql_error());
				echo "<p><img src=\"images/knjigica_3d_30.jpg\" border=\"0\"><br /><b>January 2025, 9(1) <a href='/download_pdf_arhiva.php/JASPE_January_2025.pdf' target='_blank'>[print version]</a></b></p>";
				stampaj_listu($al_query);
				?>
			</div>
		</div>



		<?php
		break;

	case "search":
		$alv = $_POST['searchstring'];
		echo "<h3>Search results for search string <em>\"" . $alv . "\"</em></h3>";
		$al_query = mysql_query("SELECT * FROM clanci WHERE `naslov_eng` like '%$alv%' OR `naslov_mne` like '%$alv%' OR `sazetak_eng` like '%$alv%' OR `sazetak_mne` like '%$alv%' OR `keywords_eng` like '%$alv%' OR `keywords_mne` like '%$alv%'") or die(mysql_error());
		stampaj_listu($al_query);
		break;

	default:
		$al_query = mysql_query("SELECT * FROM clanci WHERE `$alc`='$alv' ORDER BY id ASC") or die(mysql_error());
		stampaj_listu($al_query);

}
//	global $alc;
?>