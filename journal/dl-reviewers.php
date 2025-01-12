<?php
 if ($handle = opendir('./download/reviewers/')) {
   while (false !== ($file = readdir($handle)))
      {
          if ($file != "." && $file != "..")
	  {
          	$thelist .= '<span class="artlink">- <a href="/download/reviewers/'.$file.'">'.$file.' ('.round(filesize("./download/reviewers/".$file)/1024).' KB)</a></span><br />';
      }
  }
  closedir($handle);
  }
?>
<h3>Download</h3>
<p>When preparing the reviewing form, reviewers should strictly follow Guidelines for Reviewers.</p>

<p>Here is a list of all downloadable material connected with reviewing process:</p>

<p><?=$thelist?></p>

<p>All files are Microsoft Office Word files. If you are having trouble opening them please contact Journal Office.</p>