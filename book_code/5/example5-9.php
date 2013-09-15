<?php
if (function_exists("array_combine"))
{
	echo "Function exists ". phpversion();
}
else
{
	echo "Function does not exist - better write our own";
}
?>
