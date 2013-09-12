<?php // adduser.php

// Start with the PHP code

$forename = $surname = $username = $password = $age = $email = "";

if (isset($_POST['forename']))
	$forename = fix_string($_POST['forename']);
if (isset($_POST['surname']))
	$surname = fix_string($_POST['surname']);
if (isset($_POST['username']))
	$username = fix_string($_POST['username']);
if (isset($_POST['password']))
	$password = fix_string($_POST['password']);
if (isset($_POST['age']))
	$age = fix_string($_POST['age']);
if (isset($_POST['email']))
	$email = fix_string($_POST['email']);

$fail  = validate_forename($forename);
$fail .= validate_surname($surname);
$fail .= validate_username($username);
$fail .= validate_password($password);
$fail .= validate_age($age);
$fail .= validate_email($email);

echo "<html><head><title>An Example Form</title>";

if ($fail == "") {
	echo "</head><body>Form data successfully validated: $forename,
		$surname, $username, $password, $age, $email.</body></html>";

	// This is where you would enter the posted fields into a database

	exit;
}

// Now output the HTML and JavaScript code

echo <<<_END

<!-- The HTML section -->

<style>.signup { border: 1px solid #999999;
	font: normal 14px helvetica; color:#444444; }</style>
<script type="text/javascript">
function validate(form)
{
	fail  = validateForename(form.forename.value)
	fail += validateSurname(form.surname.value)
	fail += validateUsername(form.username.value)
	fail += validatePassword(form.password.value)
	fail += validateAge(form.age.value)
	fail += validateEmail(form.email.value)
	if (fail == "") return true
	else { alert(fail); return false }
}
</script></head><body>
<table class="signup" border="0" cellpadding="2"
	cellspacing="5" bgcolor="#eeeeee">
<th colspan="2" align="center">Signup Form</th>

<tr><td colspan="2">Sorry, the following errors were found<br />
in your form: <p><font color=red size=1><i>$fail</i></font></p>
</td></tr>

<form method="post" action="adduser.php"
	onSubmit="return validate(this)">
     <tr><td>Forename</td><td><input type="text" maxlength="32"
	name="forename" value="$forename" /></td>
</tr><tr><td>Surname</td><td><input type="text" maxlength="32"
	name="surname" value="$surname" /></td>
</tr><tr><td>Username</td><td><input type="text" maxlength="16"
	name="username" value="$username" /></td>
</tr><tr><td>Password</td><td><input type="text" maxlength="12"
	name="password" value="$password" /></td>
</tr><tr><td>Age</td><td><input type="text" maxlength="3"
	name="age" value="$age" /></td>
</tr><tr><td>Email</td><td><input type="text" maxlength="64"
	name="email" value="$email" /></td>
</tr><tr><td colspan="2" align="center">
	<input type="submit" value="Signup" /></td>
</tr></form></table>

<!-- The JavaScript section -->

<script type="text/javascript">
function validateForename(field) {
	if (field == "") return "No Forename was entered.\\n"
	return ""
}

function validateSurname(field) {
	if (field == "") return "No Surname was entered.\\n"
	return ""
}

function validateUsername(field) {
	if (field == "") return "No Username was entered.\\n"
	else if (field.length < 5)
		return "Usernames must be at least 5 characters.\\n"
	else if (/[^a-zA-Z0-9_-]/.test(field))
		return "Only a-z, A-Z, 0-9, - and _ allowed in Usernames.\\n"
	return ""
}

function validatePassword(field) {
	if (field == "") return "No Password was entered.\\n"
	else if (field.length < 6)
		return "Passwords must be at least 6 characters.\\n"
	else if (!/[a-z]/.test(field) || ! /[A-Z]/.test(field) ||
		    ! /[0-9]/.test(field))
		return "Passwords require one each of a-z, A-Z and 0-9.\\n"
	return ""
}

function validateAge(field) {
	if (field == "") return "No Age was entered.\\n"
	else if (field < 18 || field > 110)
		return "Age must be between 18 and 110.\\n"
	return ""
}

function validateEmail(field) {
	if (field == "") return "No Email was entered.\\n"
		else if (!((field.indexOf(".") > 0) &&
			     (field.indexOf("@") > 0)) ||
			    /[^a-zA-Z0-9.@_-]/.test(field))
		return "The Email address is invalid.\\n"
	return ""
}
</script></body></html>
_END;

// Finally, here are the PHP functions

function validate_forename($field) {
	if ($field == "") return "No Forename was entered<br />";
	return "";
}

function validate_surname($field) {
	if ($field == "") return "No Surname was entered<br />";
	return "";
}

function validate_username($field) {
	if ($field == "") return "No Username was entered<br />";
	else if (strlen($field) < 5)
		return "Usernames must be at least 5 characters<br />";
	else if (preg_match("/[^a-zA-Z0-9_-]/", $field))
		return "Letters, numbers, - and _ only in Usernames<br />";
	return "";
}

function validate_password($field) {
	if ($field == "") return "No Password was entered<br />";
	else if (strlen($field) < 6)
		return "Passwords must be at least 6 characters<br />";
	else if ( !preg_match("/[a-z]/", $field) ||
			!preg_match("/[A-Z]/", $field) ||
			!preg_match("/[0-9]/", $field))
		return "Passwords require 1 each of a-z, A-Z and 0-9<br />";
	return "";
}

function validate_age($field) {
	if ($field == "") return "No Age was entered<br />";
	else if ($field < 18 || $field > 110)
		return "Age must be between 18 and 110<br />";
	return "";
}

function validate_email($field) {
	if ($field == "") return "No Email was entered<br />";
		else if (!((strpos($field, ".") > 0) &&
			      (strpos($field, "@") > 0)) ||
			    preg_match("/[^a-zA-Z0-9.@_-]/", $field))
		return "The Email address is invalid<br />";
	return "";
}

function fix_string($string) {
	if (get_magic_quotes_gpc()) $string = stripslashes($string);
	return htmlentities ($string);
}
?>
