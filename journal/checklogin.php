<?php
ob_start();
	include "db.php";

// Define $myusername and $mypassword
$myusername=$_POST['myusername'];
$mypassword=$_POST['mypassword'];

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysql_real_escape_string($myusername);
$mypassword = mysql_real_escape_string($mypassword);

$sql="SELECT * FROM `korisnici` WHERE korisnik='$myusername' and sifra='".md5($mypassword)."'";
$result=mysql_query($sql);

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);
// If result matched $myusername and $mypassword, table row must be 1 row

if($count==1){
// Register $myusername, $mypassword and redirect to file "login_success.php"
session_register("myusername");
session_register("mypassword");

//$_SESSION['login_user']=$myusername;
//$_SESSION['login_user']=$mypassword;
header("location:./?sekcija=success");
}
else {
header( "refresh:4;url=./" );
echo "Wrong Username or Password<br />";
echo "Please wait 5 seconds for redirect...";
}

ob_end_flush();
?>