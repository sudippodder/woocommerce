
<?php

$cmd = shell_exec("php ./test_script.php  >/dev/null 2>&1 & ");
var_dump($cmd);
?>
