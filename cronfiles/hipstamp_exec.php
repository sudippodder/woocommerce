<?php 
ini_set('display_errors',1);
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

/*function my_function_on_each_cron_call() {
    if ( defined( 'DOING_CRON' ) )
    {*/

        //for ($i = 1; $i < 450; $i++) 
        for ($i = $page_start_value; $i < $page_end_value; $i++) 
        {


            $cmd = shell_exec("php ./hipstamp_insert.php 'page=".$i."' ");
            //>/dev/null 2>&1 &
            var_dump($cmd);
           
        }
            
      