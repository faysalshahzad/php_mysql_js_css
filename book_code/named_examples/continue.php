<?php // continue.php

// This is the first version of continue,php

session_start();

if (isset($_SESSION['username']))
{
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$forename = $_SESSION['forename'];
	$surname  = $_SESSION['surname'];

	echo "Welcome back $forename.<br />
		  Your full name is $forename $surname.<br />
		  Your username is '$username'
		  and your password is '$password'.";
}
else echo "Please <a href=authenticate2.php>click here</a> to log in.";

// The updated version of continue.php is below. To use it comment out everything above and uncomment everything below this line

/*

session_start();

if (isset($_SESSION['username']))
{
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$forename = $_SESSION['forename'];
	$surname  = $_SESSION['surname'];

	echo "Welcome back $forename.<br />
		 Your full name is $forename $surname.<br />
		 Your username is '$username'
		 and your password is '$password'.";

	destroy_session_and_data();
}
else echo "Please <a href=authenticate2.php>click here</a> to log in.";

function destroy_session_and_data()
{
	$_SESSION = array();
	if (session_id() != "" || isset($_COOKIE[session_name()]))
	    setcookie(session_name(), '', time() - 2592000, '/');
	session_destroy();
}

*/

?>