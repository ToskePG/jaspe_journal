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

function addDOI($str) {

	$str = str_replace("DOI: ", "https://doi.org/", $str);
	$str = str_replace("DOI:", "https://doi.org/", $str);
	$str = str_replace("doi: ", "https://doi.org/", $str);
	$str = str_replace("doi:", "https://doi.org/", $str);
	
	return $str;
	
}

function brisi_duplikate($array){
	$noviArray = array();
	foreach($array as $key=>$val){
		$noviArray[$val] = 1;
	}
	return array_keys($noviArray);
}

function addLinks($str, $attributes=array()) {
	$attrs = '';
	foreach ($attributes as $attribute => $value) {
		$attrs .= " {$attribute}=\"{$value}\"";
	}

	$str = ' ' . $str;
	$str = preg_replace(
		'`([^"=\'>])((http|https|ftp)://[^\s<]+[^\s<\.)])`i',
		'$1<a href="$2"'.$attrs.'>$2</a>',
		$str
	);
	$str = substr($str, 1);
	
	return $str;
}

//function addLinks($text) {
/*      $text = ereg_replace("www\.", "http://www.", $text);
      $text = ereg_replace("http://http://www\.", "http://www.", $text);
      $text = ereg_replace("https://http://www\.", "https://www.", $text);
      $exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
      preg_match_all($exUrl, $text, $url);
      foreach($url[0] as $k=>$v) $text = str_replace($url[0][$k], '<a href="'.$url[0][$k].'" target="_blank" rel="nofollow">'.$url[0][$k].'</a>', $text);
      return $text;*/
	  

//    $re = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';    
  //  $text = preg_replace($re, '<a href="$0" rel="nofollow">$0</a>', $text);

//return preg_replace('@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,.\s])@', '<a href="http$2://$4">$1$2$3$4</a>', $text);  
  //  return $text;

//}

?>

<?php
 	$a_query = mysql_query("SELECT * FROM clanci WHERE `id`='$article_id' LIMIT 1") or die(mysql_error());
	$a_result = mysql_fetch_array($a_query);

	$broj = $a_result["broj"];
	
 	$b_query = mysql_query("SELECT * FROM brojevi WHERE `id`='$broj' LIMIT 1") or die(mysql_error());
	$b_result = mysql_fetch_array($b_query);
	
	

?>

<?php
	$institucija_id = array();
	$broj_autora = 0;
	for($i=1;$i<=10;$i++){
		if($a_result["autor".$i]!=""){
			$autor_id[$i]=$a_result["autor".$i];
			$institucija_id[$i]=explode(",",$a_result["institucija".$i]);
			$broj_autora++;
		}
	}

	$ins_counter = 0;
	$institucije = array();

	foreach ($institucija_id as $h){
		for($k=1; $k<=sizeof($h); $k++){
			$institucije[] = $h[$k-1];
		}
	}

	$institucije = brisi_duplikate($institucije);

	$aut_string = "";
	$ins_string = "";

	for($i=1; $i<=$broj_autora; $i++){
		if($i>1) $aut_string = $aut_string . ", ";
		$aut_string .= "<em><a href=\"/?sekcija=articles&alc=autor&alv=".$autor_id[$i]."\">" . fetch_autor($autor_id[$i]) . "</a></em><sup>";
		$nadjen = false;
		foreach ($institucija_id[$i] as $h){
			$pretraga = array_search($h,$institucije);
			if($nadjen) $aut_string = $aut_string . ",";
			$aut_string .= ($pretraga+1);
			$nadjen = true;
		}
		$aut_string .= "</sup>";
	}
	
	echo "<p><span class=\"podcrveno\">".$aut_string."</span></p>";
	
	
	foreach ($institucije as $key => $h){
		$ins_string .= "<sup>".($key+1)."</sup><em>" . fetch_institucija($institucije[$key]) . "</em><br />";
	}

	
	echo "<p>".$ins_string."</p>";

?>


<h1><?php echo $a_result["naslov_eng"]; ?></h1>
<?php if($a_result["naslov_mne"]!="" && strtoupper($a_result["naslov_mne"])!="N/A"){ ?>
<h4><?php echo $a_result["naslov_mne"]; ?></h4>
<?php } ?>

<!-- MJSSM broj i DOI -->
<p><span class="artlink-manji">
Sport Mont <?php echo $b_result["godina"]; ?>, <?php echo $b_result["vol"]; ?>(<?php echo $b_result["no"]; ?>), <?php echo $a_result["str"]; ?>
<?php if($a_result["doi"]!=""){?>
 | DOI: <a href="https://doi.org/<?php echo $a_result["doi"]; ?>" target="_blank"><?php echo $a_result["doi"]; ?></a>
</span></p>
<?php } ?>

<h3>Abstract</h3>
<p><?php echo $a_result["sazetak_eng"]; ?></p>
<h3>Keywords</h3>
<p><?php echo $a_result["keywords_eng"]; ?></p>
<?php if($a_result["sazetak_mne"]!="" && strtoupper($a_result["sazetak_mne"])!="N/A"){ ?>
<h3>Abstract (MNE)</h3>
<p><?php echo $a_result["sazetak_mne"]; ?></p>
<?php } ?>
<?php if($a_result["keywords_mne"]!="" && strtoupper($a_result["keywords_mne"])!="N/A"){ ?>
<h3>Keywords (MNE)</h3>
<p><?php echo $a_result["keywords_mne"]; ?></p>
<?php } ?>
<br />

<p style="text-align:center;"><span class="artlink"><a href="dl.php?id=<?php echo $a_result["id"]; ?>" target="_blank">
<img src="images/pdf64.gif" /><br />
View full article<br />
(PDF – <?php echo round(filesize("clanci/".$a_result["file"])/1024) ."KB)"; ?></a></span></p>

<?php if($a_result["references"]!=""){?>
<h3 id="references">References</h3>
<p><?php echo addLinks(addDOI($a_result["references"]), array("target"=>"_blank","rel"=>"nofollow")); ?></p>
<?php } ?>

<?php /*if(!isset($_SESSION["myusername"])){ ?>
<h3>Please <a href="#" onclick="$('#login-login').click()";>login or register</a> to view complete articles in PDF format.</h3>
<?php }else{*/ ?>




<?php /*}*/ ?>

<br /><br />


<div id="disqus_thread"></div>
<script>

/**
*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/

var disqus_config = function () {
this.page.url = 'http://www.sportmont.ucg.ac.me/?sekcija=article&artid=<?php echo $a_result["id"]; ?>';  // Replace PAGE_URL with your page's canonical URL variable
this.page.identifier = '<?php echo $a_result["id"]; ?>'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};

(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = 'https://sportmont.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
