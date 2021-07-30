<?php 
ini_set('display_errors',1);
$wp_load = '/var/www/html/site01';
//require( dirname(__FILE__) . '/wp-load.php' );
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

$page_end_value = 450 ;




   // The option already exists, so we just update it.
  
echo $page_start_value.'<br>';

/*function my_function_on_each_cron_call() {
    if ( defined( 'DOING_CRON' ) )
    {*/

        //for ($i = 1; $i < 450; $i++) 
        for ($i = $page_start_value; $i < $page_end_value; $i++) 
        {
            // 1. sku not match :---New entry
            $url        = 'https://www.hipstamp.com/api/stores/Antonios/listings/active/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8&limit='.LIMIT.'&page=' . $i . '&sort=started_desc';
            $data       =  file_get_contents($url);
            $characters =  json_decode($data);
            $total_rec = count($characters->results);
            $rec_inc = 0; 
            foreach ($characters->results as $character) 
            {

                    /***********************HIPSTAMP CHANGE OR UPDATE OR DELETE OR NEW********************************************************** */
                    // search with sku & Hipstamp ID . 
                    // 1. sku not match :---New entry
                    // 2. sku match but deleted:- delete entry
                    // 3. sku match not deleted:- update the entry
                    
                    $day   = date('Y-m-d', strtotime($character->updated_at));
                    $today = date('Y-m-d');
                    global $product;
                    // echo $character->name . '<br>';
                    $product = get_product_by_sku($character->id);
                    
                        if (is_null($product)) {
                            // 1. sku not match :---New entry
                            
                            $hip_post = array(
                                'post_title' => $character->name,
                                'post_status' => 'publish',
                                'post_type' => 'product',
                                'post_content' => $character->description,
                                'post_excerpt' => $character->description,
                                'post_author' => 1
                            );
                            $post_id  = wp_insert_post($hip_post);
                            
                            $cate = get_term_by('slug', sanitize_title($character->item_specifics_01_country), 'product_cat');
                           //var_dump($cate);
                           $cate = (array)$cate;
                           $cate_count = count($cate);
                           //var_dump( $cate_count);
                            if ( $cate_count == 1) {
                                $cat     = wp_insert_term($character->item_specifics_01_country, 'product_cat', array(
                                    'description' => $character->item_specifics_01_country,
                                    'slug' => strtolower($character->item_specifics_01_country)
                                ));
                                $cat = (array)$cat;
                                //var_dump($cat);
                                $cat_id1 = $cat['term_id'];
                            } else {
                               
                                $cat_id1 = $cate['term_id'];
                            }
                            wp_set_object_terms($post_id, $cat_id1, 'product_cat');                    
                            wp_set_object_terms($post_id, 'simple', 'product_type');
                            
                            update_post_meta($post_id, '_visibility', 'visible');
                            update_post_meta($post_id, '_sku', $character->id);
                            update_post_meta($post_id, '_price', $character->current_price);
                            update_post_meta($post_id, '_regular_price', $character->current_price);
                            update_post_meta($post_id, '_manage_stock', "yes");
                            update_post_meta($post_id, '_stock', $character->quantity);
                            attach_image_url3($character->images[0], $post_id);
                           // echo 'Insert is Done!' . '<br>';
                            
                        } 
                       /* else {
                            
                            $pid    = $product;
                             
                            
                            
                            if ($day == $today) {
                                
                                // 3. sku match: not deleted:- update the entry
                                
                                $hip_post = array(
                                    'ID' => $pid,
                                    'post_title' => $character->name,
                                    'post_status' => 'publish',
                                    'post_type' => 'product',
                                    'post_content' => $character->description,
                                    'post_excerpt' => $character->description,
                                    'post_author' => 1
                                );
                                
                                wp_update_post($hip_post);
                                $cate = get_term_by('slug', sanitize_title($character->item_specifics_01_country), 'product_cat');
                                $cate = (array)$cate;
                                 $cate_count = count($cate);

                                if ($cate_count == 1 ) {
                                    $cat     = wp_insert_term($character->item_specifics_01_country, 'product_cat', array(
                                        'description' => $character->item_specifics_01_country,
                                        'slug' => strtolower($character->item_specifics_01_country)
                                    ));
                                    $cat = (array)$cat;
                                    $cat_id1 = $cat['term_id'];
                                   
                                } else {
                                    $cat_id1 = $cate['term_id'];
                                }
                                
                                wp_set_object_terms($pid, $cat_id1, 'product_cat');
                                
                                
                                wp_set_object_terms($pid, 'simple', 'product_type');
                                
                                update_post_meta($pid, '_visibility', 'visible');
                                update_post_meta($pid, '_sku', $character->id);
                                update_post_meta($pid, '_price', $character->current_price);
                                update_post_meta($pid, '_manage_stock', "yes");
                                update_post_meta($pid, '_stock', $character->quantity);
                                update_post_meta($pid, '_regular_price', $character->current_price);
                                
                                if (has_post_thumbnail($pid)) {
                                    $attachment_id = get_post_thumbnail_id($pid);
                                    wp_delete_attachment($attachment_id, true);
                                }
                                attach_image_url3($character->images[0], $pid);
                                echo 'Modification is Done!' . '<br>';
                                
                            }
                        }*/
                $rec_inc++;
            }
            echo $total_rec.'=='.$rec_inc.'--';
            if($total_rec==$rec_inc){
                update_option( $option_name, $i );
            }
            
            // closed 
            // $url        = 'https://www.hipstamp.com/api/stores/Antonios/listings/closed/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8&limit='.LIMIT.'&page=' . $i . '&sort=started_desc';
            // $data       = file_get_contents($url);
            // $characters = json_decode($data);
            /*foreach ($characters->results as $character) {
                $day   = date('Y-m-d', strtotime($character->updated_at));
                $today = date('Y-m-d');
                
                
                  $product = get_product_by_sku($character->id);


                        if (!is_null($product)) {
                           $pid    = $product;
                        
                          //  if ($day == $today) {
                                $delete = $character->deleted;
                                $closed = $character->closed;
                                $active = $character->active;
                            if($closed == 1 && $active == 1){
                                echo 'closed:-'.$character->name . '<br>';
                                update_post_meta($pid, '_manage_stock', "no");
                                update_post_meta($pid, '_stock', 0); 
                                update_post_meta($pid, '_stock_status', "outofstock");
                                
                              
                            }

                            if($delete == 1){
                                 // woocommerce product permanent delete
                                global $woocommerce;
                                $woocommerce->delete('products/'.$pid, ['force' => true]);

                            }


                        }

            }*/
        }
            
        /*for ($i = 1; $i < 2; $i++) {

            

        }*/
    
    /*}

    }*/


/*function attach_image_url($file, $post_id, $desc = null)
{

    
	
    //	require 'Aws/S3/S3Client';
    //	require 'Aws/S3/Exception/S3Exception';

	// AWS Info
	$bucketName = 'hipstamp';
	$IAM_KEY = 'AKIAJZFME6DPPLT6TSJQ';
	$IAM_SECRET = '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';

	// Connect to AWS
	try {
		// You may need to change the region. It will say in the URL when the bucket is open
		// and on creation. us-east-2 is Ohio, us-east-1 is North Virgina
		$s3 = S3Client::factory(
			array(
				'credentials' => array(
					'key' => $IAM_KEY,
					'secret' => $IAM_SECRET
				),
				'version' => 'latest',
				'region'  => 'us-east-2'
			)
		);
	} catch (Exception $e) {
		// We use a die, so if this fails. It stops here. Typically this is a REST call so this would
		// return a json object.
		die("Error: " . $e->getMessage());
	}

	
	$fileURL = $file; // Change this

	// For this, I would generate a unqiue random string for the key name. But you can do whatever.
	$keyName = basename($fileURL);
	$pathInS3 = 'https://s3.us-east-2.amazonaws.com/' . $bucketName . '/' . $keyName;
	
	// Add it to S3 
	try {
		// You need a local copy of the image to upload.
		// My solution: http://stackoverflow.com/questions/21004691/downloading-a-file-and-saving-it-locally-with-php
		if (!file_exists('/tmp/tmpfile')) {
			mkdir('/tmp/tmpfile');
		}
				
		$tempFilePath = '/tmp/tmpfile/' . basename($fileURL);
		$tempFile = fopen($tempFilePath, "w") or die("Error: Unable to open file.");
		$fileContents = file_get_contents($fileURL);
		$tempFile = file_put_contents($tempFilePath, $fileContents);

		$s3->putObject(
			array(
				'Bucket'=>$bucketName,
				'Key' =>  $keyName,
				'SourceFile' => $tempFilePath,
				'StorageClass' => 'REDUCED_REDUNDANCY'
			)
		);

		// WARNING: You are downloading a file to your local server then uploading
		// it to the S3 Bucket. You should delete it from this server.
		// $tempFilePath - This is the local file path.

	} catch (S3Exception $e) {
		die('Error:' . $e->getMessage());
	} catch (Exception $e) {
		die('Error:' . $e->getMessage());
    }
    $wp_filetype = wp_check_filetype($keyName, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($keyName),
        'post_content' => '',
        'guid'=>$pathInS3,
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $keyName, $post_id );
    add_post_meta($post_id, '_thumbnail_id', $attach_id, true);
    //  set_post_thumbnail( $post_id, $attach_id );
        echo 'OK';
    //  add_post_meta($post_id, '_thumbnail_id', $attach_id, true);

    /*   require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');
    if (!empty($file)) {
        $tmp = download_url($file);
        preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $file, $matches);
        $file_array['name']     = basename($matches[0]);
        $file_array['tmp_name'] = $tmp;
        if (is_wp_error($tmp)) {
            @unlink($file_array['tmp_name']);
            $file_array['tmp_name'] = '';
        }
        $id = media_handle_sideload($file_array, $post_id, $desc);
        if (is_wp_error($id)) {
            @unlink($file_array['tmp_name']);
        }
        add_post_meta($post_id, '_thumbnail_id', $id, true);
    }
    
}*/


// function get_product_by_sku($sku)
// {
  
//     global $wpdb;
    
//     $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));
    
//     if ($product_id)
//         return new WC_Product($product_id);
    
//     return null;
// }
