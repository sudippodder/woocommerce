<?php 
$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );
$file = $wp_load."/cronfiles/newfile.txt" ;

$tt = parse_str($argv[1],$output);
// $post_meta = get_post_meta(99593,true);
// print '<pre>';
// print_r($post_meta);
// print '</pre>';
// exit;
echo $page_no = $_GET['page'];
$w_content = 'Page no : '.$page_no.'';

$sku = $_GET['sku'];

echo 'SKU : '.$sku;


define('LIMIT',50);

/*$url        = 'https://www.hipstamp.com/api/stores/Antonios/listings/active/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8&limit='.LIMIT.'&page=' . $page_no . '&sort=started_desc';*/
$url = 'https://www.hipstamp.com/api/listings/'.$sku.'?api_key=pr_ba0e3eddf53e363fba637df4eb4f8';
$data       =  file_get_contents($url);
$characters =  json_decode($data);

print '<pre>';
print_r($characters);
print '</pre>';

// echo $total_rec;
foreach ($characters->results as $k=>$character) 
{
    

        /***********************HIPSTAMP CHANGE OR UPDATE OR DELETE OR NEW********************************************************** */
        // search with sku & Hipstamp ID . 
        // 1. sku not match :---New entry
        // 2. sku match but deleted:- delete entry
        // 3. sku match not deleted:- update the entry
        
        $day   = date('Y-m-d', strtotime($character->updated_at));
        $today = date('Y-m-d');
        //global $product;
        // echo $character->name . '<br>';
        $w_content = 'Product Deails Updated on : '.$character->name.' ('.$character->id.')';
        
        $product = get_product_by_sku_update($character->id);

        echo 'Character Id: '.$character->id.' and Product Id : '.$product;

        echo 'image : '.$character->images[0];

         $pid    = $product;

        if (has_post_thumbnail($pid)){
        	echo "Exit";
        }else{
        	echo "Not Exit";
        } 

        if (has_post_thumbnail($pid)) {
            $attachment_id = get_post_thumbnail_id($pid);
            wp_delete_attachment($attachment_id, true);
            echo 'Delete';

            // if(set_post_thumbnail( $pid, $attachment_id ))
            // {
            //     echo "Set";   
            // }
            // else{
            //     echo "Not set";
            // }
            
        }
        
        if(attach_image_url3($character->images[0], $pid)){
        	echo 'Modification is Done!' . '<br>';
    	}


/** File Upload start **/

        // $image = 'http://antoniosphilatelics.com/wp-content/uploads/2020/06/afad7c11d8cd6036014f64994ef831ca.jpg';

        // $get = wp_remote_get( $image );

        // $type = wp_remote_retrieve_header( $get, 'content-type' );

        // if (!$type)
        //     return false;

        // $mirror = wp_upload_bits( basename( $image ), '', wp_remote_retrieve_body( $get ) );

        // $attachment = array(
        //     'post_title'=> basename( $image ),
        //     'post_mime_type' => $type
        // );

        // $attach_id = wp_insert_attachment( $attachment, $mirror['file'], $parent_id );

        // require_once(ABSPATH . 'wp-admin/includes/image.php');

        // $attach_data = wp_generate_attachment_metadata( $attach_id, $mirror['file'] );

        // wp_update_attachment_metadata( $attach_id, $attach_data );

        // echo 'Attach Id '.$attach_id; 


        // include_once( ABSPATH . 'wp-admin/includes/image.php' );
        // $imageurl = 'http://s3-us-east-2.amazonaws.com/hipstamp/20200524002433/0d978f57373e91840cc48eeebdd7a46e.jpg';
        // $imagetype = end(explode('/', getimagesize($imageurl)['mime']));
        // $uniq_name = date('dmY').''.(int) microtime(true); 
        // $filename = $uniq_name.'.'.$imagetype;

        // $uploaddir = wp_upload_dir();
        // $uploadfile = $uploaddir['path'] . '/' . $filename;
        // $contents= file_get_contents($imageurl);
        // $savefile = fopen($uploadfile, 'w');
        // fwrite($savefile, $contents);
        // fclose($savefile);

        // $wp_filetype = wp_check_filetype(basename($filename), null );
        // $attachment = array(
        //     'post_mime_type' => $wp_filetype['type'],
        //     'post_title' => $filename,
        //     'post_content' => '',
        //     'post_status' => 'inherit'
        // );

        // $attach_id = wp_insert_attachment( $attachment, $uploadfile );
        // $imagenew = get_post( $attach_id );
        // $fullsizepath = get_attached_file( $imagenew->ID );
        // $attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
        // wp_update_attachment_metadata( $attach_id, $attach_data ); 

        // echo 'Attach Id '.$attach_id;







         //File upload end
    
}


function get_product_by_sku_update($sku)
{
  
    global $wpdb;
    
    $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 50", $sku));
    
    if ($product_id)
    return $product_id;
        //return new WC_Product($product_id);
    

    return null;
}
?>