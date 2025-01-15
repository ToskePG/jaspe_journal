<?php

	include "db.php";

	if (isset($_GET['id'])) {
		$id = htmlspecialchars($_GET['id']);
	}
	else{
		$id = "NULL";
	}

	$ip = $_SERVER['REMOTE_ADDR'];
	
	$dap_query = mysql_query("SELECT * FROM `clanci` WHERE `id` = '$id' LIMIT 1") or die(mysql_error());

	if(mysql_num_rows($dap_query) == 0) { 	// app doesn't exist in apps table
		echo "Document not found!";
		exit();
	}
	
	$dap_result = mysql_fetch_array($dap_query);
	
	$currentip = $_SERVER['REMOTE_ADDR'];

	if($dap_result['lastdownloadip'] != $currentip){
		mysql_query("UPDATE clanci SET downloads = downloads + 1 WHERE id = '".$dap_result['id']."'") or die(mysql_error());
		mysql_query("UPDATE clanci SET lastdownloadip = '". $currentip ."' WHERE id = '".$dap_result['id']."'") or die(mysql_error());
	}

//	header('Location: /clanci/' . $dap_result['file']);
?>