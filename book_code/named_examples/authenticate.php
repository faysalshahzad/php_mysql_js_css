<?php // authenticate.php

// This is the first version of authenticate,php

require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
mysql_select_db($db_database)
	or die("Unable to select database: " . mysql_error());

if (isset($_SERVER['PHP_AUTH_USER']) &&
	isset($_SERVER['PHP_AUTH_PW']))
{
	$un_temp = mysql_entities_fix_string($_SERVER['PHP_AUTH_USER']);
	$pw_temp = mysql_entities_fix_string($_SERVER['PHP_AUTH_PW']);

	$query = "SELECT * FROM users WHERE username='$un_temp'";
	$result = mysql_query($query);
	if (!$result) die("Database access failed: " . mysql_error());
	elseif (mysql_num_rows($result))
	{
		$row = mysql_fetch_row($result);
		$salt1 = "qm&h*";
		$salt2 = "pg!@";
		$token = md5("$salt1$pw_temp$salt2");
		
		if ($token == $row[3]) echo "$row[0] $row[1] : 
			Hi $row[0], you are now logged in as '$row[2]'";
		else die("Invalid username/password combination");
	}
	else die("Invalid username/password combination");
}
else
{
	header('WWW-Authenticate: Basic realm="Restricted Section"');
	header('HTTP/1.0 401 Unauthorized');
	die ("Please enter your username and password");
}

function mysql_entities_fix_string($string)
{
	return htmlentities(mysql_fix_string($string));
}	

function mysql_fix_string($string)
{
	if (get_magic_quotes_gpc()) $string = stripslashes($string);
	return mysql_real_escape_string($string);
}

// The updated version of authenticate.php is below. To use it comment out everything above and uncomment everything below this line

/*

require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
mysql_select_db($db_database)
	or die("Unable to select database: " . mysql_error());

if (isset($_SERVER['PHP_AUTH_USER']) &&
	isset($_SERVER['PHP_AUTH_PW']))
{
	$un_temp = mysql_entities_fix_string($_SERVER['PHP_AUTH_USER']);
	$pw_temp = mysql_entities_fix_string($_SERVER['PHP_AUTH_PW']);

	$query = "SELECT * FROM users WHERE username='$un_temp'";
	$result = mysql_query($query);
	if (!$result) die("Database access failed: " . mysql_error());
	elseif (mysql_num_rows($result))
	{
		$row = mysql_fetch_row($result);
		$salt1 = "qm&h*";
		$salt2 = "pg!@";
		$token = md5("$salt1$pw_temp$salt2");
		
		if ($token == $row[3])
		{
			session_start();
			$_SESSION['username'] = $un_temp;
			$_SESSION['password'] = $pw_temp;
			$_SESSION['forename'] = $row[0];
			$_SESSION['surname']  = $row[1];
			echo "$row[0] $row[1] : Hi $row[0],
				you are now logged in as '$row[2]'";
			die ("<p><a href=continue.php>Click here to continue</a></p>");
		}
		else die("Invalid username/password combination");
	}
	else die("Invalid username/password combination");
}
else
{
	header('WWW-Authenticate: Basic realm="Restricted Section"');
	header('HTTP/1.0 401 Unauthorized');
	die ("Please enter your username and password");
}

function mysql_entities_fix_string($string)
{
	return htmlentities(mysql_fix_string($string));
}	

function mysql_fix_string($string)
{
	if (get_magic_quotes_gpc()) $string = stripslashes($string);
	return mysql_real_escape_string($string);
}

*/

?>