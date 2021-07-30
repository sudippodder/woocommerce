<?php

$file = "/var/www/html/site01/closefile.txt" ;

date_default_timezone_set('Australia/Melbourne');
//$date = date('m/d/Y h:i:s a', time());
$date = 'dasfsgfsdfsd';
$linecount = 0;
$handle = fopen($file, "r") ;
$allContent = array();
    while(!feof($handle)){
    $line = fgets($handle);
    
    if($line){
        $line = str_replace("\n", "", $line);
        $allContent[] .= $line;
    }
        
        $linecount++;
        
    
    }

$allContent[] = $date;

$handle = fopen($file, "w") ;
$allContent = implode("\n",$allContent);
fwrite($handle, $allContent );
fclose($handle); 

