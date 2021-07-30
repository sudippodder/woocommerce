<?php
/**
 * ProShop engine room
 *
 * @package proshop
 */

$theme           = wp_get_theme( 'proshop' );
$proshop_version = $theme['Version'];

 /**
  * Load the individual classes required by this theme
  */
include_once( 'inc/class-proshop.php' );
include_once( 'inc/class-proshop-customizer.php' );
include_once( 'inc/class-proshop-structure.php' );
include_once( 'inc/class-proshop-integrations.php' );
include_once( 'inc/storefront-template-functions.php' );
include_once( 'inc/woocommerce/storefront-woocommerce-template-functions.php' );

/**
 * Do not add custom code / snippets here.
 * While Child Themes are generally recommended for customisations, in this case it is not
 * wise. Modifying this file means that your changes will be lost when an automatic update
 * of this theme is performed. Instead, add your customisations to a plugin such as
 * https://github.com/woothemes/theme-customisations
 */

add_action( 'wp_enqueue_scripts', 'wpse_my_style' );
function wpse_my_style(){
	wp_enqueue_style( 'bootstrap-min', get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css' );
	wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/assets/css/custom.css' );
	wp_enqueue_style( 'wpb-fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );
}    


//add_action( 'wp_enqueue_scripts', 'wpb_load_fa' );
add_action('wp_enqueue_scripts','my_theme_scripts_function');
function my_theme_scripts_function() {
  wp_enqueue_script( 'bootstrap-script', get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js');
  wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/assets/js/custom.js');
  wp_enqueue_script( 'proshop', get_template_directory_uri() . '/assets/js/equal.js' );

  wp_localize_script(
        'custom-script',
        'ajax_obj',
        array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
    );
}

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);


register_sidebar( array(
    'id'          => 'product-sidebar',
    'name'        => __( 'product Search' ),
    'before_widget' => '<div id="%1$s" class="widget woocommerce widget_product_search">',
    'after_widget' => "</div>",
    'description' => __( 'This search is beside the logo.' ),
) );


register_nav_menus(
	array(
		//'primary' => __( 'Primary Menu', 'topcat-lite' ),
		'social'  => __( 'Social Menu', 'topcat-lite' ),
	)
);
function topcat_lite_social_menu() {
	if ( has_nav_menu( 'social' ) ) {
		wp_nav_menu(
			array(
				'theme_location'  => 'social',
				'container'       => 'div',
				'container_id'    => 'menu-social',
				'container_class' => 'menu-social',
				'menu_id'         => 'menu-social-items',
				'menu_class'      => 'menu-items',
				'depth'           => 1,
				'link_before'     => '<span class="screen-reader-text">',
				'link_after'      => '</span>',
				'fallback_cb'     => '',
			)
		);
	}
}
add_action( 'init', 'custom_remove_footer_credit', 10 );


register_nav_menus(
	array(
		'menu-1' => __( 'Primary' ),
	)
);



function custom_remove_footer_credit () {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
    add_action( 'storefront_footer', 'custom_storefront_credit', 20 );
} 
 
function custom_storefront_credit() {
    ?>
    <div class="footer-bottom">
	    <div class="row">
	    	<div class="col-lg-8 col-8 col-sm-6 topcat_lite_social_menu">
	    		<?php topcat_lite_social_menu(); ?>
	    	</div>
	    	<div class="col-lg-4 col-xl-4 col-sm-6 copyright">Copyright <?php echo date("Y"); ?> All Rights Reserved</div>
	    </div>
	</div>
    <?php
}


remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

function pagination($pages = '', $range = 4)
{  
     $showitems = ($range * 2)+1;  
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   
 
     if(1 != $pages)
     {
         //echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         //echo "</div>\n";
     }
}

// Hipstamp API call after order placed

function mysite_woocommerce_order_status_completed( $order_id ) {

$order = wc_get_order($order_id);
foreach ( $order->get_items() as $item_id => $item ) {

    // Get the product object
    $product = $item->get_product();

    // Get the product Id
    $product_id = $product->get_id();

    $product_sku = $product->get_sku();
    $stock_quantity = $product->get_stock_quantity();

?>
    <script>
    jQuery.ajax({
        type: 'POST',
        url: 'https://www.hipstamp.com/api/listings/<?php echo $product_sku; ?>?api_key=pr_ba0e3eddf53e363fba637df4eb4f8&_method=PUT',
        data: {
            'quantity': '<?php echo $stock_quantity;  ?>', 
        }, success: function (result) {
		    //   alert('Success');
               console.log('Success');
        },
        error: function () {
          //  alert(error);
            console.log(error);
        }
    });	
    </script>
<?php
}

}
//add_action( 'woocommerce_order_status_completed', 'mysite_woocommerce_order_status_completed', 10, 1 );
add_action('woocommerce_thankyou', 'mysite_woocommerce_order_status_completed', 10, 1);

function setPostViews($postID) {
    $countKey = 'post_views_count';
    $count = get_post_meta($postID, $countKey, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $countKey);
        add_post_meta($postID, $countKey, '0');
    }else{
        $count++;
        update_post_meta($postID, $countKey, $count);
    }
}


function cron_my_hipstamp_event_b6d82022() {
    // do stuff
    $file = "./newfile.txt" ;

date_default_timezone_set('Australia/Melbourne');
$date = date('m/d/Y h:i:s a', time());

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

add_action( 'my_hipstamp_event', 'cron_my_hipstamp_event_b6d82022', 10, 0 );




function ajax_request_product_category() {
 
    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST) && $_REQUEST['category_id']!='') {
     
        $category_id = $_REQUEST['category_id'];
         
        $child_html_root = '';
        $args = array(
											'post_type' => 'product',
											'posts_per_page' => -1,
										'tax_query' => array(
															array(
																'taxonomy' => 'product_cat',
																'field' => 'id',
																'terms' => $category_id,
															),
														),
									);

									$loop = new WP_Query($args);
									$child_html_root .= '<ul class="product_list">';
									if($loop->have_posts()) {
										while ( $loop->have_posts() ) {

										$loop->the_post();
										// do something
										$child_html_root .= '<li><a href="'.get_the_permalink().'"> '.get_the_title().' </a></li>';
										
										}
									}
                                    $child_html_root .= '</ul>';
  
       
         echo $child_html_root;
       
     
    }else{
        return false;
    }
     
   
   die();
}
 
add_action( 'wp_ajax_ajax_request_product_category', 'ajax_request_product_category' );
 
// If you wanted to also use the function for non-logged in users (in a theme for example)
add_action( 'wp_ajax_nopriv_ajax_request_product_category', 'ajax_request_product_category' );





function wisdom_sort_plugins_by_slug( $query ) {
    global $pagenow;
    // Get the post type
    $post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
    if ( is_admin() && $pagenow=='edit.php' && $post_type == 'product' && isset( $_GET['s'] ) && $_GET['s'] !='' ) {
       $product_id = get_product_by_sku($_GET['s']);
       if($product_id!='' && is_numeric($product_id)){
        $query->query_vars['post__in'] = array($product_id);
        }
     
  } 
  

  }
  add_filter( 'parse_request', 'wisdom_sort_plugins_by_slug' );


  
  function get_product_by_sku($sku)
{
  
    global $wpdb;
    
    $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));
    
    if ($product_id)
        return $product_id;
    
    return null;
}





function attach_image_url3($file, $post_id, $desc = null)
{
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        $file_name = basename($file);
        //$only_file_name = strstr($file_name,'.',true);
        $only_file_name = $file_name;
        $uploaddir = wp_upload_dir();
        $uploadfile = $uploaddir['path'] . '/' . $only_file_name;
      //var_dump($file);
        if (!empty($file)) {
            //$contents= file_get_contents($file);
            $arrContextOptions=array(
                "ssl"=>array(
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                    ),
                );  
            $contents = file_get_contents($file, false, stream_context_create($arrContextOptions));
            $savefile = fopen($uploadfile, 'wb');
            $write_file = fwrite($savefile, $contents);
            fclose($savefile);
            $wp_filetype = wp_check_filetype(basename($only_file_name), null );
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => $only_file_name,
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment( $attachment, $uploadfile );
            $imagenew = get_post( $attach_id );
            $fullsizepath = get_attached_file( $imagenew->ID );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            $media = media_sideload_image($uploadfile, $post_id);
            //if(strpos($media, $attachment[0]) !== false){
              $check_post_meta = get_post_meta( $post_id,'_thumbnail_id' );

              if(!empty($check_post_meta)){
                $attached_log = update_post_meta( $post_id, '_thumbnail_id', $attach_id);
              }else{
                $attached_log = add_post_meta($post_id, '_thumbnail_id', $attach_id, true);
              }
              
               echo $attached_log;
               // only want one image
                
            //}
        }else{
            $attach_id = 141409;
            $check_post_meta = get_post_meta( $post_id,'_thumbnail_id' );

              if(!empty($check_post_meta)){
                $attached_log = update_post_meta( $post_id, '_thumbnail_id', $attach_id);
              }else{
                $attached_log = add_post_meta($post_id, '_thumbnail_id', $attach_id, true);
              }
        }
        
}










add_action('admin_head','sync_javascript_file');
function sync_javascript_file(){
    if(isset($_GET['post_type']) && $_GET['post_type']=='product'){
    ?>
    <script>
        function synk_with_server(post_id){
            console.log(post_id);

            var ajaxUrl = "<?php echo admin_url('admin-ajax.php')?>";

            jQuery("#loading_"+post_id).css("display","block");

            // Disable the button, temp.
            
            $.post(ajaxUrl, {
                action: "more_post_ajax_sync",
                post_id: post_id
            })
            .success(function(posts) {
                
                console.log(posts);
                jQuery("#loading_"+post_id).css("display","none");
                //alert('Please reload to check Sync result.');
            });


        }
    </script>
    <style>
    span.clone .loading{
        background-image: url('<?php echo get_template_directory_uri();?>/ajax-loader1.gif');
        background-repeat: no-repeat;
        width: 30px;
        height: 30px;
    }
    </style>
    <?php
    }
}

add_action('wp_ajax_nopriv_more_post_ajax_sync', 'more_post_ajax_sync'); 
add_action('wp_ajax_more_post_ajax_sync', 'more_post_ajax_sync');

function more_post_ajax_sync(){
    
    $sku_id = get_post_meta($_POST['post_id'], '_sku',true);
    
            $url = 'https://www.hipstamp.com/api/listings/'.$_POST['post_id'].'/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8';
            $data       =  file_get_contents($url);
            $characters =  json_decode($data);
            $char_length = count($characters); 
       
        if($sku_id!=''){
            $surl = 'https://www.hipstamp.com/api/listings/'.$sku_id.'/?api_key=pr_ba0e3eddf53e363fba637df4eb4f8';
            $sdata       =  file_get_contents($surl);
            $scharacters =  json_decode($sdata);
            $schar_length = count($scharacters); 
        }


       if($char_length>0){

                $character = $characters->results[0];
                
                $product = get_product_by_sku($character->id);  
                if($product==''){
                    $product = $_POST['post_id'];
                }
                
                
                $pid    = $product;
                                    
                                        
                                        
            
                    
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
            
       }else if($schar_length>0){
           
                $character = $scharacters->results[0];
                $product = get_product_by_sku($character->id);  
                if($product==''){
                    $product = $_POST['post_id'];
                }
                $pid    = $product;
                            
                    
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
       }
       
           

    die(0);
}


add_action('admin_init','duplicate_post_admin_init_product');

function duplicate_post_admin_init_product(){

    add_filter('post_row_actions', 'duplicate_post_make_duplicate_link_row',10,2);
    add_filter('page_row_actions', 'duplicate_post_make_duplicate_link_row',10,2);
        
}

function duplicate_post_make_duplicate_link_row($actions, $post) {
    if(isset($_GET['post_type']) && $_GET['post_type']=='product'){
		$actions['clone'] = '<a href="javascript:synk_with_server('. $post->ID .');" title="'
				. esc_attr__("Sync this item", 'duplicate-post')
				. '">' .  esc_html__('Sync', 'duplicate-post') . '</a><div style="display:none;" class="loading" id="loading_'.$post->ID.'"></div>';
		
    }
	return $actions;
}




function update_product_with_object_and_id($character,$pid='',$page_number=''){

    $generate_sku_id = trim($character->id);
    $generate_sku_id = (int)$generate_sku_id;

    $get_product_id = get_product_by_sku($generate_sku_id);
    //$file = "/var/www/html/site01/cronfiles/id_list.txt" ;
    //$write_content = date("Y-m-d h:i:sa"). '--'. $generate_sku_id.'--'.$get_product_id.'-----'.$page_number;
    //write_txt_file($file, $write_content );

    if (is_null($get_product_id) && $generate_sku_id!='') {
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
        update_post_meta($post_id, '_sku', $generate_sku_id);
        update_post_meta($post_id, '_price', $character->current_price);
        update_post_meta($post_id, '_regular_price', $character->current_price);
        update_post_meta($post_id, '_manage_stock', "yes");
        update_post_meta($post_id, '_stock', $character->quantity);
        update_post_meta($post_id, 'custom_field_check_for_api', 1);
        

        attach_image_url3($character->images[0], $post_id);
        return 'insert';
    }else{
        $pid = $get_product_id;
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
        update_post_meta($pid, '_sku', $generate_sku_id);
        update_post_meta($pid, '_price', $character->current_price);
        update_post_meta($pid, '_manage_stock', "yes");
        update_post_meta($pid, '_stock', $character->quantity);
        update_post_meta($pid, '_regular_price', $character->current_price);
        update_post_meta($pid, 'custom_field_check_for_api', 1);
        if (has_post_thumbnail($pid)) {
            $attachment_id = get_post_thumbnail_id($pid);
            wp_delete_attachment($attachment_id, true);
        }
    
    
        attach_image_url3($character->images[0], $pid);
        return 'update';
    }

}


function write_txt_file($file,$data = ''){
    date_default_timezone_set('Australia/Melbourne');
    if($data==''){
        $date = date('m/d/Y h:i:s a', time());
    }else{
        $date = $data;
    }
    
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
        return $allContent;
   // echo $allContent;
}

function cfwc_save_custom_field( $post_id ) {
    $product = wc_get_product( $post_id );
    $product->update_meta_data( 'custom_field_check_for_api', 1 );
    $sttatus = $product->save();
    $meta_value = $product->get_meta('custom_field_check_for_api');
    return $sttatus;
    //return $meta_value;
    //var_dump($meta_value);
}



add_action('init','antoni_hook_delete');

function antoni_hook_delete(){

 remove_action('storefront_footer','storefront_footer_widgets',10);
}



add_action('storefront_footer','antoni_footer_widget',11);

function antoni_footer_widget(){ 

  $rows    = intval( apply_filters( 'storefront_footer_widget_rows', 1 ) );
    $regions = intval( apply_filters( 'storefront_footer_widget_columns', 4 ) );

    for ( $row = 1; $row <= $rows; $row++ ) :

      // Defines the number of active columns in this footer row.
      for ( $region = $regions; 0 < $region; $region-- ) {
        if ( is_active_sidebar( 'footer-' . esc_attr( $region + $regions * ( $row - 1 ) ) ) ) {
          $columns = $region;
          break;
        }
      }

      if ( isset( $columns ) ) :
        ?>
        <div class=<?php echo '"footer-top"'; ?>>
          <div class="row">
            <?php
            for ( $column = 1; $column <= $columns; $column++ ) :
              $footer_n = $column + $regions * ( $row - 1 );

              if ( is_active_sidebar( 'footer-' . esc_attr( $footer_n ) ) ) :
                ?>
              <div class="block col-xl-3 col-lg-3 footer-widget-<?php echo esc_attr( $column ); ?>">
                <?php dynamic_sidebar( 'footer-' . esc_attr( $footer_n ) ); ?>
              </div>



              
                <?php
              endif;
            endfor;
            ?>
             <div class="block col-xl-3 col-lg-3 footer-widget-box footer-widget-4">
                <div id="text-6" class="widget widget_text">
                  <span class="gamma widget-title">HELP</span>      
                  <div class="textwidget">
                    <ul>
                      <li><a href="#">Help</a></li>
                      <li><a href="<?php echo site_url('/contact'); ?>">Contact</a></li>
                    </ul>
                  </div>
                </div>
                <div id="text-7" class="widget widget_text">
                  <span class="gamma widget-title">PAYMENTS</span>      
                  <div class="textwidget">
                    <p>
                      <img class="alignnone size-full wp-image-680" src="<?php echo get_stylesheet_directory_uri().'/assets/images/paypal.png'?>" alt="">
                    </p>
                  </div>
                </div>            
              </div>
              
            </div>
          </div><!-- .footer-widgets.row-<?php echo esc_attr( $row ); ?> -->
        <?php
        unset( $columns );
      endif;
    endfor; ?>
    
 <?php }
add_action('most_popular_search','most_popular_search');
 function most_popular_search($args=[]){
    ?>
    <div class="most_popular_searches">
        <div class="col-full">
            <h2><?php the_field('most_popular_searches_heading','30'); ?></h2>
            <div class="view_all_wrap"><a href="<?php echo site_url().'/browse/';?>">View all <i class="fa fa-arrow-right" aria-hidden="true"></i></a></div>
            <div class="row">
                <?php 
                   
                    $loop = new WP_Query( 
                        array( 
                            'post_type' => 'product',
                            'posts_per_page' => 8, 
                            'meta_key'=>'post_views_count',
                            'orderby'=>'meta_value',
                            'order' => 'DESC', ) );
                    // $loop = $wpdb->get_results("SELECT SQL_CALC_FOUND_ROWS p.ID
                    // 						FROM wp_posts p 
                    // 						WHERE EXISTS (SELECT 1
                    // 							FROM wp_postmeta pm
                    // 							WHERE p.ID = pm.post_id AND
                    // 									pm.meta_key = 'post_views_count'
                    // 							)
                    // 						AND p.post_type = 'product'
                    // 						AND (p.post_status = 'publish'
                    // 						OR p.post_status = 'acf-disabled'
                    // 						OR p.post_status = 'private')
                    // 						GROUP BY p.ID
                                            
                    // 						LIMIT 0, 8",OBJECT_K);		
                    if ( $loop->have_posts() ) :
                        while ( $loop->have_posts() ) : $loop->the_post(); 
                         global $product;
                        ?>
                            <div class="col-xl-3 col-lg-3 col-md-4 search_product_box_wrap">
                                <div class="search_product_box">
                                    <div class="pimage">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php if ( has_post_thumbnail() ) { ?>
                                                <?php the_post_thumbnail(); ?>
                                            <?php } else { ?>
                                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/product-default-image.jpg" />
                                            <?php } ?>
                                        </a>
                                    </div>
                                    <div class="ptitle">
                                        <h3><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
                                    </div>

                                    <div class="product-price-cart-wrap">
                                        <div class="product-price">
                                            <?php if ( $price_html = $product->get_price_html() ) : ?>
                                                <span class="price"><?php echo $price_html; ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="product-add-to-cart text-center rounded-circle">
                                            <?php 
                                                echo apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
                                                sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                                esc_url( $product->add_to_cart_url() ),
                                                esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                                                esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
                                                isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                                                esc_html( $product->add_to_cart_text() )
                                                ),$product, $args );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        endwhile; 
                    endif;
                    wp_reset_query();
                ?>
            </div>
        </div>
    </div>
    <?php
 }
 add_action('recently_listed_items','recently_listed_items');
 function recently_listed_items($args=[]){
     ?>
    <div class="recently_listed_items">
        <div class="col-full">
            <h1><?php the_field('recently_listed_items_heading','30'); ?></h1>
            <div class="view_all_wrap">
                <div class="list-grid-view">
                    <i class="icon-grid-icon grid-view list-grid-view-icon active"></i>
                    <i class="fa fa-bars list-view list-grid-view-icon"></i>

                </div>
                <a href="<?php echo site_url().'/browse/';?>">View all <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            </div>

            <?php 
            global $wpdb;
            //$product
            $loop = new WP_Query( array( 'post_type' => 'product','posts_per_page' => 5, 'order' => 'DESC') );
            if ( $loop->have_posts() ) :
                while ( $loop->have_posts() ) : $loop->the_post(); 
                global $product;
                ?>
                    <div class="pindex">
                        <div class="pimage">
                            <a href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) { ?>
                                    <?php the_post_thumbnail(); ?>
                                <?php } else { ?>
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/product-default-image.jpg" />
                                <?php } ?>
                            </a>
                        </div>
                        <div class="ptitle">
                            <h3><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
                        </div>

                        <div class="product-price-cart-wrap">
                            <div class="product-price">
                                <?php
                                
                                if ( $price_html = $product->get_price_html() ) : ?>
                                    <span class="price"><?php echo $price_html; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="product-add-to-cart text-center rounded-circle">
                                <?php 
                                    echo apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
                                    sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                    esc_url( $product->add_to_cart_url() ),
                                    esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                                    esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
                                    isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                                    esc_html( $product->add_to_cart_text() )
                                    ),$product, $args );
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; endif;
            wp_reset_query();
            ?>
        </div><!-- .col-full -->
    </div>
     <?php
 }