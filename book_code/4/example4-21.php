<?php
$bank_balance=50;
$money = 10;
$savings= 100;

if ($bank_balance < 100)
{
	$money += 1000;
	$bank_balance += $money;
}
else if ($bank_balance > 200)
{
	$savings += 100;
	$bank_balance -= 100;
}
else
{
	$savings += 50;
	$bank_balance -= 50;
}

echo $bank_balance. "<br>";
echo $money. "<br>";
echo $savings. "<br>";


?>
