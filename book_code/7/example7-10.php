<?php // deletefile.php
if (!unlink('../../testbed/test1.txt')) echo "Could not delete file";
else echo "File 'testfile2.new' successfully deleted";
?>
