<?php 
ini_set('display_errors',1);
$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );


update_option("check_cron",time());
define('LIMIT',100);




        for ($i = 1; $i < 3; $i++) 
        {

             $cmd = shell_exec("php ./hipstamp_close_check.php 'page=".$i."'  >/dev/null 2>&1 &");
            var_dump($cmd);
            
        }
            
