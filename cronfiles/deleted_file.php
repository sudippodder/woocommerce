<?php 
$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );
$file = $wp_load."/cronfiles/deletedfile.txt" ;

$tt = parse_str($argv[1],$output);

$page_no = $output['page'];
$w_content = 'Page no : '.$page_no.'';


define('LIMIT',100);

if($page_no!=''){
    $i = $page_no;
    //save page number to option
    $option_name = 'page_no' ;
    
   
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
                        $w_content = 'Product Deails : '.$character->name.' ('.$character->id.') ( Deleted : '.$character->deleted.')';
                        
                        write_txt_file($file,$w_content);
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
                        
                    $rec_inc++;
                }
                echo $total_rec.'=='.$rec_inc.'--';      
    
}


