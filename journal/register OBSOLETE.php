<script type="text/javascript">
function validate(form){
 var myusername = form.myusername.value;
 var mypassword = form.mypassword.value;
 var myname = form.myname.value;
 var mylastname = form.mylastname.value;
 var myemail = form.myemail.value;
 var myinstitution = form.myinstitution.value;
 var errors = [];

 if (!checkLength(myusername, 4, 64)) {
  errors[errors.length] = "You must enter a username (4-64 characters).";
 }

 if (!checkLength(mypassword, 4, 64)) {
  errors[errors.length] = "You must enter a password (4-64 characters).";
 }

 if (!checkLength(myname, 2, 64)) {
  errors[errors.length] = "You must enter your first name (2-64 characters).";
 }

 if (!checkLength(mylastname, 2, 64)) {
  errors[errors.length] = "You must enter your last name (2-64 characters).";
 }

 if (!checkLength(myemail, 4, 64) || !checkMailSyntax(myemail)) {
  errors[errors.length] = "You must enter valid email (4-64 characters).";
 }

 if (!checkLength(myinstitution, 2, 64)) {
  errors[errors.length] = "You must enter your institution (2-256 characters).";
 }

 if (errors.length > 0) {
  reportErrors(errors);
  return false;
 }

 return true;
}

function checkMailSyntax(text)
{
var atpos=text.indexOf("@");
var dotpos=text.lastIndexOf(".");
if (atpos<1 || dotpos<atpos+2 || dotpos+2>=text.length)
  {
  return false;
  }
  return true;
}

function checkLength(text, min, max){
 min = min || 1;
 max = max || 10000;

 if (text.length < min || text.length > max) {
  return false;
 }
 return true;
}

function reportErrors(errors){
 var msg = "There were some problems...\n";
 var numError;
 for (var i = 0; i<errors.length; i++) {
  numError = i + 1;
  msg += "\n" + numError + ". " + errors[i];
 }
 alert(msg);
}
</script>

<h3>New User</h3>

<table width="500" border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
<form name="form3" method="post" action="doregister.php" onsubmit="return validate(this);">
<td align="center">
<table width="100%" border="0" cellpadding="1" cellspacing="1">
<tr>
<td colspan="2"></td>
</tr>
<tr>
<td width="78">Choose your username:</td>
<td width="100"><input name="myusername" type="text" id="myusername"></td>
</tr>
<tr>
<td>Password:</td>
<td><input name="mypassword" type="text" id="mypassword"></td>
</tr>
<tr>
<td>First Name:</td>
<td><input name="myname" type="text" id="myname"></td>
</tr>
<tr>
<td>Last Name:</td>
<td><input name="mylastname" type="text" id="mylastname"></td>
</tr>
<tr>
<td>E-mail:</td>
<td><input name="myemail" type="text" id="myemail"></td>
</tr>
<tr>
<td>Institution:</td>
<td><input name="myinstitution" type="text" id="myinstitution"></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" name="Submit" value="Register"></td>
</tr>
</table>
</td>
</form>
</tr>
</table>
