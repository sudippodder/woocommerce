<?php

$cmd = shell_exec("php ./insert_script.php  >/dev/null 2>&1 & ");
var_dump($cmd);

// die();

// $previous_day = date('Y-m-d',strtotime("-1 days"));
// $url = 'https://www.hipstamp.com/api/stores/Antonios/listings/active/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8';
// $data       =  file_get_contents($url);
// $characters =  json_decode($data);
// $itemsPerPage = 100;
// var_dump($characters->count);
// $items = $characters->count;
// $pages = ceil($items/$itemsPerPage);

// echo $pages;



// for ($i = 1; $i < $pages; $i++) 
// {

//      $cmd = shell_exec("php /var/www/html/site01/cronfiles/update_all.php 'page=".$i."'  >/dev/null 2>&1 &");
//     var_dump($cmd);
    
// }
