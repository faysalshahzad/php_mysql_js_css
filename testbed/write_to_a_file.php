<?php
    
    $file_pointer = fopen("test.txt", "wb");
	
	for ($i=0; $i < 100; $i++) { 
		$written = fwrite($file_pointer, "This is line ".$i."\n");
		echo "i am at line ".$i."<br>";
		if($written == FALSE)break;
	}
?>