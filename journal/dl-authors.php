<?php
$thelist = ""; // stariji php

 if ($handle = opendir('./download/authors/')) {
   while (false !== ($file = readdir($handle)))
      {
          if ($file != "." && $file != "..")
	  {
          	$thelist .= '<span class="artlink">- <a href="/download/authors/'.$file.'">'.$file.' ('.round(filesize("./download/authors/".$file)/1024).' KB)</a></span><br />';
      }
  }
  closedir($handle);
  }
?>
<h3>Download</h3>

<p>When preparing the final version of the manuscript, authors should strictly follow Guidelines for Authors.</p>

<p>Here is a list of all downloadable material connected with manuscript submission:</p>

<p><?=$thelist?></p>

<p>All files are Microsoft Office Word files. If you are having trouble opening them please contact Journal Office.</p>