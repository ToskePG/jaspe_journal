<?php
$pdf = substr($_SERVER['PATH_INFO'], 1);
$put = $_SERVER['DOCUMENT_ROOT']."/clanci/arhiva/";
if(preg_match('/^[a-zA-Z0-9_\-]+.pdf$/', $pdf) == 0) {
	print "Illegal name: $pdf";
	return;
}
if (file_exists($put . $pdf)) {
	header('Content-type: application/pdf');
	header('Content-disposition: attachment; filename=' . $pdf);
	header('Content-Length: ' . filesize($put . $pdf));
	ob_clean();
	flush();
	readfile($put . $pdf);
	exit;
} else {echo "File missing: ".$put . $pdf;}
?>