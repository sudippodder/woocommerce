<?php
   
//------------------------------------------------------------------------------------

   
$projectF = 'site01';
//$projectF = 'woocommerce';
ini_set('display_errors',1);
$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );
//update_option("check_cron",time());
define('LIMIT',100);
$tt = parse_str($argv[1],$output);
$page_no = $output['page'];

//$page_no = 1;


if($page_no!=''){
    $i = $page_no;
    $option_name = 'page_no' ;
    
    $url        = 'https://www.hipstamp.com/api/stores/Antonios/listings/active/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8&limit='.LIMIT.'&page=' . $i . '&sort=started_desc';
    $data       =  file_get_contents($url);
    
    $characters =  json_decode($data);
    $total_rec = count($characters->results);
    $rec_inc = 0; 
    
    foreach ($characters->results as $character) 
    {
        $sku = $character->id;
        $product_id = get_product_by_sku($sku);
        $write = $page_no;
        
        //var_dump([$sku,$product_id]);
        $product_id = (int)$product_id;
        
        if(isset($product_id) && $product_id != '' && $product_id > 0){
            
            //echo 'update';
            $write = $product_id;
            insertUpdateproductDB($product_id,$character);
        }else{
            $write = '--inser -- '.$product_id;
            insertUpdateproductDB('',$character);
            //echo 'insert';
            
        }
        
       // write_file($write);
    }
    $row['time'] = time();
    $row['pageno'] = $page_no;
    update_option("check_cron",$row);
}


function write_file($add_content){
    $file = "/var/www/html/site01/test.txt" ;
    date_default_timezone_set('Australia/Melbourne');
    //$date = date('m/d/Y h:i:s a', time());
    $date = $add_content;
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
}



function insertUpdateproductDB($product_id,$character){
    //var_dump([$product_id,$character]);die();
    $character = (array)$character;
    $arg = array(
        'ID' => isset($product_id)?$product_id:false,
        'post_title' => $character['name'],
        'post_content' => $character['description'],
        'post_excerpt' => $character['description'],
        'post_status' => 'publish',
        'post_type' => 'product',
        'post_author' => 1
    );

    $post_id = wp_insert_post( $arg );


    if(isset($character['item_specifics_01_country']) && !empty($character['item_specifics_01_country'])){
        insetCategories($post_id,$character['item_specifics_01_country']);
    }
    //variable
    wp_set_object_terms( $post_id, 'simple', 'product_type', true );
    update_post_meta($post_id, '_visibility', 'visible');

    if( empty( get_post_meta( $post_id, '_sku', true ) ) ) {
        update_post_meta( $post_id, '_sku', $character['id'] );
    }
    if( isset( $character['current_price'] ) ) {
        update_post_meta( $post_id, '_regular_price', $character['current_price'] );
        update_post_meta( $post_id, '_price', $character['current_price'] );

    }
    update_post_meta($post_id, '_manage_stock', "yes");
    if( isset( $character['quantity'] ) ) {
        update_post_meta($post_id, '_stock', $character['quantity']);
    }
    if( isset( $character['currency'] ) ) {
        update_post_meta( $post_id, 'currency', $character['currency'] );//custom field
    }
    //tag name with "source_"
    if( isset( $character['country'] ) ) {
        update_post_meta( $post_id, 'source_country', $character['country'] );//custom field
    }
    if( isset( $character['state'] ) ) {
        update_post_meta( $post_id, 'source_state', $character['state'] );//custom field
    }
    if( isset( $character['postal_code'] ) ) {
        update_post_meta( $post_id, 'source_postal_code', $character['postal_code'] );//custom field
    }
    if( isset( $character['returns_policy'] ) ) {
        update_post_meta( $post_id, 'source_returns_policy', $character['returns_policy'] );//custom field
    }
    if( isset( $character['item_specifics_01_country'] ) ) {
        update_post_meta( $post_id, 'source_item_specifics_01_country', $character['item_specifics_01_country'] );//custom field
    }
    if( isset( $character['item_specifics_02_catalog_number'] ) ) {
        update_post_meta( $post_id, 'source_item_specifics_02_catalog_number', $character['item_specifics_02_catalog_number'] );//custom field
    }
    if( isset( $character['item_specifics_03_stamp_type'] ) ) {
        update_post_meta( $post_id, 'source_item_specifics_03_stamp_type', $character['item_specifics_03_stamp_type'] );//custom field
    }
    if( isset( $character['item_specifics_04_condition'] ) ) {
        update_post_meta( $post_id, 'source_item_specifics_04_condition', $character['item_specifics_04_condition'] );//custom field
    }
    if( isset( $character['item_specifics_08_centering'] ) ) {
        update_post_meta( $post_id, 'source_item_specifics_08_centering', $character['item_specifics_08_centering'] );//custom field
    }
    if( isset( $character['item_specifics_05_stamp_format'] ) ) {
        update_post_meta( $post_id, 'source_item_specifics_05_stamp_format', $character['item_specifics_05_stamp_format'] );//custom field
    }
    if( isset( $character['item_specifics_09_has_a_certificate'] ) ) {
        update_post_meta( $post_id, 'source_item_specifics_09_has_a_certificate', $character['item_specifics_09_has_a_certificate'] );//custom field
    }
    if( isset( $character['item_specifics_10_certificate_grade'] ) ) {
        update_post_meta( $post_id, 'source_item_specifics_10_certificate_grade', $character['item_specifics_10_certificate_grade'] );//custom field
    }   
    if( isset( $character['item_specifics_06_topic'] ) ) {
        update_post_meta( $post_id, 'source_item_specifics_06_topic', $character['item_specifics_06_topic'] );//custom field
    }     
    if( isset( $character['item_specifics_07_year_of_issue'] ) ) {
        update_post_meta( $post_id, 'source_item_specifics_07_year_of_issue', $character['item_specifics_07_year_of_issue'] );//custom field
    }  
    if( isset( $character['category_id'] ) ) {
        update_post_meta( $post_id, 'source_category_id', $character['category_id'] );//custom field
    }  
    
    
    //images > array
    if(is_array($character['images']) && count($character['images']) > 0)
    {
        foreach($character['images'] as $img){
            update_image_with_id($post_id,$img,$character['name']);
            
        }
        
        
    }

}


// function get_product_by_sku( $sku ) {

//     global $wpdb;

//     $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

//     if ( $product_id ) return $product_id ;

//     return null;
// }

function insetCategories($post_id,$category){

    

        $category_slug = sanitize_title($category);
        $getCategory = get_term_by('slug', $category_slug, 'product_cat');
        if(is_object($getCategory)){
            $term_id = $getCategory->term_id;
            wp_set_post_terms( $post_id, array($getCategory->term_id), 'product_cat', true );
        }else{
            //insert category for products
            $term_data = wp_insert_term(
            $category, // the term
            'product_cat'
            );
            $term_obj = (object) $term_data;
            $getCategory->term_id = $term_obj->term_id;
            $term_id = $getCategory->term_id;
            wp_set_post_terms( $post_id, array($getCategory->term_id), 'product_cat', true );
        }
    return $term_id;
    
}
function update_image_with_id( $post_id,$p_image_url,$p_post_title )
{
    $image = $p_image_url;
    $title = $p_post_title;
    // only need these if performing outside of admin environment
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    //define path
    $uploaddir = wp_upload_dir();
    $image = $p_image_url;
    $filenameArr = parse_url($p_image_url,PHP_URL_PATH);

    $filename = basename($filenameArr);
    $uploadfile = $uploaddir['path'] . '/' . $filename;
    //save file
    // $contents= file_get_contents($image);
    // $savefile = fopen($uploadfile, 'a+');
    // fwrite($savefile, $contents);
    // fclose($savefile);

    $wp_filetype = wp_check_filetype($filename, null );
    $uploadfile = $image;
    if(($wp_filetype['type'] == 'image/png')|| ($wp_filetype['type'] == 'image/jpg') || ($wp_filetype['type'] == 'image/jpeg') || ($wp_filetype['type'] == 'image/gif')){
        $media = media_sideload_image($uploadfile, $post_id);

        //var_dump([$media]);($post_id==276?die():0);
        // therefore we must find it so we can set it as featured ID
        if(!empty($media) && !is_wp_error($media)){
            $args = array(
            'post_type' => 'attachment',
            'posts_per_page' => -1,
            'post_title' => $title,
            'post_status' => 'any',
            'post_parent' => $post_id
            );

            // reference new image to set as featured
            $attachments = get_posts($args);

            if(isset($attachments) && is_array($attachments)){
                foreach($attachments as $attachment){
                    // grab source of full size images (so no 300x150 nonsense in path)
                    $image = wp_get_attachment_image_src($attachment->ID, 'full');
                    // determine if in the $media image we created, the string of the URL exists
                        if(strpos($media, $image[0]) !== false){
                        // if so, we found our image. set it as thumbnail
                            set_post_thumbnail($post_id, $attachment->ID);
                            // only want one image
                            break;
                        }
                }
            }
        }
    }
    // magic sideload image returns an HTML image, not an ID

}