<?php 
$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );
$file = $wp_load."/cronfiles/newfile.txt" ;

$tt = parse_str($argv[1],$output);

$page_no = $output['page'];
$w_content = 'Page no : '.$page_no.'';


define('LIMIT',100);
//$page_no =1;
