<?php
    
    echo "This programs corrects the name format <br>";
	
	$Name1 = "faySAL";	
	$Name2 = "ADIl";	
	$Name3 = "NomAAn";	
	
	echo "$Name1 ". "$Name2 " . "$Name3 " ;
	
	echo "<br>After correction <br>";
	
	name_format($Name1, $Name2, $Name3);
	
	echo "$Name1 ". "$Name2 " . "$Name3 " ;
	
	function name_format(&$first, &$second, &$third) {
		$first = ucfirst(strtolower($first));	
		$second = ucfirst(strtolower($second));	
		$third = ucfirst(strtolower($third));	
	}
?>