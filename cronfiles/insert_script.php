
<?php
ini_set('display_errors',1);
$projectF = 'site01';
//$projectF = 'woocommerce';
$wp_load = '/var/www/html/site01';

require( $wp_load . '/wp-load.php' );
global $woocommerce, $wpdb, $product;
$p = $wpdb->prefix;
define('LIMIT',100);

$total_pageno = 415;
//$total_pageno = 2;

$checkbox = get_option('check_cron');

$pageno = (int)$checkbox['pageno'];
if($pageno > 1 ){
    $iset = $pageno;
}else{
    $iset = 1;
}

for ($i = $iset; $i <= $total_pageno; $i++){ 

    $cmd = shell_exec("php ./insertWoocommerce.php 'page=".$i."' ");
    
}