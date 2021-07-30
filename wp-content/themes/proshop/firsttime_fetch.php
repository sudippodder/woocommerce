<?php /*********************FIRST TIME FETCH*********************************************************************** */
       /*      $hip_post = array(
					'post_title'  => $character->name,
					'post_status' => 'publish',
					'post_type'   => 'product',
					'post_content'=>$character->description,
					'post_excerpt' =>$character->description, 
					'post_author'=> 1,
					);
					$post_id = wp_insert_post( $hip_post );
					if($post_id){
						echo 'debal majumder:'.$character->id.'<br>';
						attach_image_url($character->images[0],$post_id);
					//	$attach_id = get_post_meta($product->parent_id, "_thumbnail_id", true);
					//	add_post_meta($post_id, '_thumbnail_id', $attach_id);
					}
				//	$cat = wp_insert_term($character->item_specifics_01_country, 'product_cat',['parent' => 0]);
				//	$cat = wp_insert_term('debu', 'product_cat');
				//	$cat['term_id'] 
				$cate  = get_term_by('name', $character->item_specifics_01_country ,'product_cat');
				if($cate == false){
				$cat = wp_insert_term(
					$character->item_specifics_01_country,
					'product_cat',
					array(
					  'description'	=> $character->item_specifics_01_country,
					  'slug' 		=> strtolower($character->item_specifics_01_country)
					)
				);	
				$cat_id1 = $cat['term_id'];
			}else{
				$cat_id1 = $cate->term_id;
			}	
				wp_set_object_terms( $post_id, $cat_id1 , 'product_cat');   
				
				   wp_set_object_terms($post_id, 'simple', 'product_type');
					

					update_post_meta( $post_id, '_visibility', 'visible' );
				//	update_post_meta( $post_id, '_stock_status', 'instock');
				//	update_post_meta( $post_id, 'total_$cat_id1sales', '0');
				//	update_post_meta( $post_id, '_downloadable', 'yes');
				//	update_post_meta( $post_id, '_virtual', 'yes');
				//	update_post_meta( $post_id, '_regular_price', $character->current_price);
				//	update_post_meta( $post_id, '_sale_price', $character->current_price );
				//	update_post_meta( $post_id, '_purchase_note', "" );
				//	update_post_meta( $post_id, '_featured', "no" );
				//	update_post_meta( $post_id, '_weight', "" );
				//	update_post_meta( $post_id, '_length', "" );
				//	update_post_meta( $post_id, '_width', "" );
				//	update_post_meta( $post_id, '_height', "" );
					update_post_meta($post_id, '_sku', $character->id);
				//	update_post_meta( $post_id, '_product_attributes', array());
				//	update_post_meta( $post_id, '_sale_price_dates_from', "" );
				//	update_post_meta( $post_id, '_sale_price_dates_to', "" );
					update_post_meta( $post_id, '_price', $character->current_price);
				//	update_post_meta( $post_id, '_sold_individually', "" );
					update_post_meta( $post_id, '_manage_stock', "yes" );
				//	update_post_meta( $post_id, '_backorders', "no" );
					update_post_meta( $post_id, '_stock', $character->quantity);
				}
			//	ob_flush();
			//	flush();
			//	sleep(5);
			*/
			/******************************************************************************************************************** */
}

				function attach_image_url($file, $post_id, $desc = null) {
					require_once(ABSPATH . "wp-admin" . '/includes/image.php');
					require_once(ABSPATH . "wp-admin" . '/includes/file.php');
					require_once(ABSPATH . "wp-admin" . '/includes/media.php');
					if ( ! empty($file) ) {
					$tmp = download_url( $file );
					preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $file, $matches);
					$file_array['name'] = basename($matches[0]);
					$file_array['tmp_name'] = $tmp;
					if ( is_wp_error( $tmp ) ) {
					@unlink($file_array['tmp_name']);
					$file_array['tmp_name'] = '';
					}
					$id = media_handle_sideload( $file_array, $post_id, $desc );
					if ( is_wp_error($id) ) {@unlink($file_array['tmp_name']);}
					add_post_meta($post_id, '_thumbnail_id', $id, true);
					}
				}

