<?php
    
    echo "This programs corrects the name format <br>";
	
	$names = name_format("fAySal", "ADIL","noman");
	
	echo "$names[0] ". "$names[1] " . "$names[2] " ;
	
	function name_format($first, $second, $third) {
		$correctName1 = ucfirst(strtolower($first));	
		$correctName2 = ucfirst(strtolower($second));	
		$correctName3 = ucfirst(strtolower($third));	
		
		return array($correctName1, $correctName2, $correctName3);
	}
?>