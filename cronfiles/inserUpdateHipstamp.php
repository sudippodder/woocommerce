
<?php
ini_set('display_errors',1);
$projectF = 'site01';

$wp_load = '/var/www/html/site01';

require( $wp_load . '/wp-load.php' );
global $woocommerce, $wpdb, $product;
$p = $wpdb->prefix;
define('LIMIT',100);

$total_pageno = 415;
//$total_pageno = 2;


for ($i = 1; $i <= $total_pageno; $i++){ 

    $cmd = shell_exec("php ./insert.php 'page=".$i."' ");
    
}