<?
session_start();
session_destroy();
header( "refresh:2;url=./" );
echo "You have been logged out.<br />";
echo "Please wait 3 seconds for redirect...";

?>