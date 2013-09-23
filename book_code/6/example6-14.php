<?php
$fname   = "Elizabeth";
$sname   = "Windsor";
$address = "Buckingham Palace";
$city    = "London";
$country = "United Kingdom";

$i=0;
$contact = compact('fname', 'sname', 'address', 'city', 'country');
print_r($contact);

foreach ($contact as $key => $value) {
echo "<br>";	
echo "$key"." "."$value";
}

foreach ($contact as $value) {
echo "<br>";	
echo ++$i. "  " ."$value";
}
?>
