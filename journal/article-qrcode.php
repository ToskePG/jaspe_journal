<?php 
	if (isset($_GET['qrcodetext'])) {
		$qrcode = $_GET['qrcodetext'];
	}
	else{
		$qrcode = "Text nije pravilno unijet u QR Code!";
	}

include('qrcodegen/qrlib.php');
QRcode::png($qrcode);
?>
