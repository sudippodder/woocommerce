<?php 
ini_set('display_errors',1);
//require( dirname(__FILE__) . '/wp-load.php' );

$wp_load = '/var/www/html/site01';

$file = $wp_load."/cronfiles/id_list.txt" ;


//$record = write_txt_file($file);
//var_dump($record);

$lines = file($file, FILE_IGNORE_NEW_LINES);

//var_dump($lines);
echo count($lines);
echo '<br>';
$arr = array_unique($lines);
echo count($arr);
//var_dump($arr);