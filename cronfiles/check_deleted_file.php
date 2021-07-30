<?php

ini_set('display_errors',1);
$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );
$previous_day = date('Y-m-d',strtotime("-1 days"));
$previous_day = '2019-09-05';
$url = 'https://www.hipstamp.com/api/stores/Antonios/listings/active/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8';
//&start_time_from='.$previous_day.'&show=pending'
echo $url;
$data       =  file_get_contents($url);
$characters =  json_decode($data);
$itemsPerPage = 100;
//var_dump($characters->count);
$items = $characters->count;
$pages = ceil($items/$itemsPerPage);

var_dump($pages);
 //$pages = 5;
for ($i = 1; $i < $pages; $i++) 
{

     $cmd = shell_exec("php /var/www/html/site01/cronfiles/deleted_file.php 'page=".$i."'  >/dev/null 2>&1 &");
    var_dump($cmd);
    
}

