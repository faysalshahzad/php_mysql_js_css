<?php
    
    echo "This is a create file demo.<br>";
    
    $fpointer = fopen("table_two.txt", "w+");
    
    //writing to the file
    for ($i=1; $i <= 10; $i++) { 
        fwrite($fpointer, "2 x $i = ". 2*$i. "\n");
    }

    //move the file pointer at the beginning 
    rewind($fpointer);
    
    //read from the file line by line
    for ($j=1; $j <= 10; $j++) { 
        echo fgets($fpointer)."<br>";
    }
        
    fclose($fpointer);
?>