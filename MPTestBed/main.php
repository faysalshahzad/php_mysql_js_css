
<?php

    include_once('MPGoogleGeocoder.php');
    echo "start of the file.<br>";
    $geo = new MPGoogleGeocoder();
    
    
    echo $geo->geocodeAddress("Erdingerstrasse 10, München, Deutschland");
?>