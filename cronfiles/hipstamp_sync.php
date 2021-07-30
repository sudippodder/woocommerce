<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );


update_option("check_cron",time());
define('LIMIT',100);

//save page number to option
$option_name = 'page_no' ;
$page_start_value = 1 ;
$page_start_value = get_option( $option_name ) ;

if ($page_start_value == false ) {
    // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
    $deprecated = null;
    $autoload = 'no';
    add_option( $option_name, $page_start_value, $deprecated, $autoload );
    $page_start_value = 1 ;
}else{

}

$page_start_value = 0 ;
$page_end_value = 1 ;



   // The option already exists, so we just update it.
  
echo $page_start_value.'<br>';


        //for ($i = 1; $i < 450; $i++) 
       // for ($page = $page_start_value; $page < $page_end_value; $page++){

           // if($page!=''){
               //".$page."
                $cmd = shell_exec("php ./hipstamp_sync_loop.php  >/dev/null 2>&1 & ");
                // >/dev/null 2>&1 &
                var_dump($cmd);
               // if($page%10==0){
                //sleep(10); 
                echo 'sleep';
               // }
           // }
            
            
       // }
            
      
    
    

        // update wp_posts set wp_posts.post_status = 'draft' where wp_posts.post_type = 'product' and wp_posts.ID not in(SELECT ID from ( SELECT  wp_posts.ID FROM wp_posts  INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) WHERE 1=1  AND ( 
        //     ( wp_postmeta.meta_key = 'custom_field_check_for_api' AND wp_postmeta.meta_value = '1' )
        //   ) AND wp_posts.post_type = 'product' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'acf-disabled' OR wp_posts.post_status = 'private')) as inner_table )