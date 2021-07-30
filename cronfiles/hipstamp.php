<?php 
ini_set('display_errors',1);

   


$cmd = shell_exec("php ./insert_script.php  >/dev/null 2>&1 & ");
var_dump($cmd);