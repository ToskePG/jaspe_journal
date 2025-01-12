<?php
ob_start();
	include "db.php";

// Define $myusername and $myemail
$myusername=$_POST['myusername'];
$myemail=$_POST['myemail'];

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$myemail = stripslashes($myemail);
$myusername = mysql_real_escape_string($myusername);
$myemail = mysql_real_escape_string($myemail);

if($myusername == "" || $myemail == ""){
header( "refresh:4;url=./" );
echo "Please enter your complete and correct details.<br />";
echo "Please wait 5 seconds for redirect...";
exit;

} 

$sql="SELECT * FROM `imejlovi` WHERE imejl='$myemail'";
$result=mysql_query($sql);

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);
// If result matched $myusername and $myemail, table row must be 1 row

if($count==0){

         $sql = mysql_query("insert into `imejlovi` (korisnik, imejl) values
         ('$myusername', '$myemail')") or die(mysql_error());



		 
	$user_ip = $_SERVER['REMOTE_ADDR'];

	$header = "From: \"SMJ newsletter\" <noreply@sportmont.ucg.ac.me>\r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Mailer: SMJ script v1.00\r\n";
	$header .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
	$header .= "Content-Transfer-Encoding: 7bit\r\n";
	$subject = "Thank you for subscribing to SMJ newsletter";
	$body = "Thank you for subscribing to SMJ newsletter.\r\n\r\n";
	$body .= "Your details:\r\n";
	$body .= "_________________________________________\r\n\r\n";
	$body .= "Username: ".$myusername."\r\n";
	$body .= "E-mail: ".$myemail."\r\n\r\n";
	$body .= "_________________________________________\r\n";
	$body .= "\r\n\r\nRegistered from computer with IP: ".$user_ip."\r\n\r\nPlease keep this message for future reference.\r\nThank you.\r\n\r\n\r\n\r\n";
	mail($myemail, $subject, $body, $header);
	mail("sportmont@ac.me", $subject, $body, $header);



header("location:./?sekcija=success");
}
else {
header( "refresh:4;url=./" );
echo "E-mail is already in our database.<br />";
echo "Please wait 5 seconds for redirect...";
}

ob_end_flush();
?>