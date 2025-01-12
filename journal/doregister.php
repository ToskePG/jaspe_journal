<?php
	include "db.php";

// Define $myusername and $mypassword
$myusername=$_POST['myusername'];
$mypassword=$_POST['mypassword'];
$myname=$_POST['myname'];
$mylastname=$_POST['mylastname'];
$myemail=$_POST['myemail'];
$myinstitution=$_POST['myinstitution'];

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myname=stripslashes($myname);
$mylastname=stripslashes($mylastname);
$myemail=stripslashes($myemail);
$myinstitution=stripslashes($myinstitution);
$myusername = mysql_real_escape_string($myusername);
$mypassword = mysql_real_escape_string($mypassword);
$myname=mysql_real_escape_string($myname);
$mylastname=mysql_real_escape_string($mylastname);
$myemail=mysql_real_escape_string($myemail);
$myinstitution=mysql_real_escape_string($myinstitution);

$sql="SELECT * FROM `korisnici` WHERE korisnik='$myusername'";
$result=mysql_query($sql);
$count=mysql_num_rows($result);

if($count>0){ // Greska
	header( "refresh:4;url=./?sekcija=register" );
	echo "Sorry, that username is not available.<br />";
	echo "Please wait 5 seconds for redirect...";
}
else {

         $sql = mysql_query("insert into `korisnici` (korisnik, sifra, ime, prezime, email, institucija, datum) values
         ('$myusername', '".md5($mypassword)."', '$myname', '$mylastname', '$myemail', '$myinstitution', NOW())") or die(mysql_error());



		 
	$user_ip = $_SERVER['REMOTE_ADDR'];

	$header = "From: \"MJSSM registration mailer - Do not reply\" <noreply@mjssm.me>\r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Mailer: MJSSM script v1.00\r\n";
	$header .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
	$header .= "Content-Transfer-Encoding: 7bit\r\n";
	$subject = "MJSSM registration information";
	$body = "Thank you for registering with MJSSM.\r\n\r\n";
	$body .= "Your login information:\r\n";
	$body .= "_________________________________________\r\n";
	$body .= "Username: ".$myusername."\r\n";
	$body .= "Password: ".$mypassword."\r\n";
	$body .= "First name: ".$myname."\r\n";
	$body .= "Last name: ".$mylastname."\r\n";
	$body .= "E-mail: ".$myemail."\r\n\r\n";
	$body .= "Institution: ".$myinstitution."\r\n";
	$body .= "_________________________________________\r\n";
	$body .= "\r\n\r\nRegistered from computer with IP: ".$user_ip."\r\n\r\nPlease keep this message for future reference.\r\nThank you.\r\n\r\n\r\n\r\n";
	mail($myemail, $subject, $body, $header);

		 
		 
		 
		 
header( "refresh:4;url=./" );
echo "SUCCESS!<br /><br />";
echo "Confirmation message sent to your email address (".$myemail.").<br /><br />";
echo "Please wait 5 seconds for redirect...";
}

?>