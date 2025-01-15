<?php


	if (isset($_GET['a'])) {
		$sdf = $_GET['a'];
		echo md5($sdf);
	}
	else{
		echo "Nothing here to see. Move on...";
	}




?>