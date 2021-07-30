<?php

get_header(); ?>

	<div id="primary" class="content-area">
		<article id="post-<?php the_ID(); ?>" <?php post_class('shop_page'); ?>>
			<?php 
			
			
			if( is_shop() || is_product_category() ) : ?>
				<div class="product_categories">

					<h2>Categories</h2>
 
					<?php 
					$get_parent_cats = array(
						'parent' => '0', //get top level categories only
						'taxonomy'=>'product_cat',
						'hide_empty' => false
					); 

					$all_categories = get_categories( $get_parent_cats );//get parent categories 
					foreach( $all_categories as $single_category ){
						//for each category, get the ID
						$catID = $single_category->cat_ID;
						//var_dump($single_category->category_count);
						
						$get_children_cats = array(
							'child_of' => $catID, //get children of this parent using the catID variable from earlier
							'taxonomy'=>'product_cat',
							'hide_empty' => false
						);
						wp_reset_postdata();
						$child_cats = get_categories( $get_children_cats );//get children of parent category
						$cck = 0;
						$child_html_root ='';
						if(count($child_cats)>0){

							$child_html_root .= '<ul class="children">';
								foreach( $child_cats as $child_cat ){
									//for each child category, get the ID
									$childID = $child_cat->cat_ID;

									//for each child category, give us the link and name
									
									wp_reset_postdata();
									$args = array(
													'post_type' => 'product',
													'posts_per_page' => -1,
													'tax_query' => array(
																		array(
																		'taxonomy' => 'product_cat',
																		'field' => 'id',
																		'terms' => $childID,
																		),
																	),
												);
									$child_html = '';
									$loop = new WP_Query($args);
									$child_html .= "<ul class='product_list'>";
									$ck = 0;
									if($loop->have_posts()) {
										while ( $loop->have_posts() ) {
										$loop->the_post();
										// do something
										$child_html .= "<li><a href=".get_the_permalink().">".get_the_title()."</a></li>";
										$ck++;
										}
									}
									$child_html .= "</ul>";
									

									$child_html_root .= '<span class="category-list-name '.($ck > 0 ? 'child_plus' : '').'">' . $child_cat->name . '</span>';
									$child_html_root .= $child_html;

								}
								$child_html_root .= '</ul></li>';
						}else{
							//$catID
							wp_reset_postdata();
							// $args = array(
							// 				'post_type' => 'product',
							// 				'posts_per_page' => -1,
							// 			'tax_query' => array(
							// 								array(
							// 									'taxonomy' => 'product_cat',
							// 									'field' => 'id',
							// 									'terms' => $catID,
							// 								),
							// 							),
							// 		);

							// 		$loop = new WP_Query($args);
							// 		$child_html_root .= "<ul class='product_list'>";
							// 		if($loop->have_posts()) {
							// 			while ( $loop->have_posts() ) {

							// 			$loop->the_post();
							// 			// do something
							// 			$child_html_root .= "<li><a href=".get_the_permalink().">".get_the_title()."</a></li>";
							// 			$cck++;
							// 			}
							// 		}
							//		$child_html_root .= "</ul>";
						}
						$category_link = get_category_link( $catID );
						//echo '<li><span class="category-list-name '.($cck > 0 || count($child_cats) > 0?'child_plus':'').'" onclick="javascript:document.location.href=\''.$category_link.'\'" >' . $single_category->name . '</span>'; //category name & link
						//onclick="javascript:document.location.href=\''.$category_link.'\'"
						echo '<li><span class="category-list-name '.($single_category->category_count > 0?'child_plus':'').'"  '.($single_category->category_count > 0?' rel="'.$catID.'"':'').' >' . $single_category->name . '</span>'; //category name & link
						//loading
						echo $child_html_root;
					} ?>

				</div>

				<div class="recently_listed_items browse_listed_items">
					<div class="col-full">
								<h1><?php the_field('browse_heading','753'); ?></h1>
								<?php woocommerce_result_count(); ?>
								<div class="view_all_wrap">
									<div class="list-grid-view">
										<i class="icon-grid-icon grid-view list-grid-view-icon active"></i>
										<i class="fa fa-bars list-view list-grid-view-icon"></i>

									</div>
									<div class="sort-by">
										<div class="product-sorting">
											<?php woocommerce_content(); ?>
										</div>

									</div>
								</div>
								<?php
								if(isset($_GET['s']) && $_GET['s']!=''){
								?>
								<div class="row">

											<?php
											global $post;
													$terms = wp_get_post_terms( $post->ID, 'product_cat' );
												$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
												
												if( is_product_category() ){
													$category = get_queried_object(); 
													//echo $category->term_id; 

													//var_dump(get_the_produc);
													
													






													$args = array(
														'post_type'      => 'product',
														'post_status'    => 'publish',
														'paged' => $paged,
														'posts_per_page' => 16,
															'tax_query'             => array(
																	array(
																		'taxonomy'      => 'product_cat',
																		'field' => 'term_id', //This is optional, as it defaults to 'term_id'
																		'terms'         => $category->term_id,
																		'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
																	)
															)
														);
												}else{
													$args = array(
														'post_type'      => 'product',
														'post_status'    => 'publish',
														'paged' => $paged,
														'posts_per_page' => 16
														);
												}
												
												
												$featured_product = new WP_Query( $args );
												global $posts;
												if ( have_posts() ) : 
												
													while ( have_posts() ) : the_post();

														get_template_part( 'content', 'search' ); 

													endwhile; ?>
											</div>
											<div class="row pagination-row">
												<div class="pagination">



													<?php 
													global $wp_query;

													$mid_size = 1;

													$current_page = $wp_query->get( 'paged' );

														if ( ! $current_page ) { ?>
													<span>Pre</span>
													<?php } else { ?>
														<?php echo get_previous_posts_link( 'Pre' ); // display newer posts link ?>
													<?php } ?>

													<?php if (function_exists("pagination")) {
															pagination($custom_query->max_num_pages);
														} ?>

													<?php if ( $current_page == $wp_query->max_num_pages ) { ?>
														<span>Next</span>
													<?php } else { ?>	
														<?php echo get_next_posts_link( 'Next'); // display older posts link ?>
													<?php } ?>


												
												</div>
												<?php else: ?>
												<div class="post">

													Sorry, no posts matched your criteria.

												

												</div>

											<?php endif; ?>

											<?php wp_reset_query();
											?>
											</div>
								<?php }else{?>
								<div class="row">

											<?php
											global $post;
											global $product;
													$terms = wp_get_post_terms( $post->ID, 'product_cat' );
												$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
												
												if( is_product_category() ){
													$category = get_queried_object(); 
													//echo $category->term_id; 

													//var_dump(get_the_produc);
													
													






													$args = array(
														'post_type'      => 'product',
														'post_status'    => 'publish',
														'paged' => $paged,
														'posts_per_page' => 16,
															'tax_query'             => array(
																	array(
																		'taxonomy'      => 'product_cat',
																		'field' => 'term_id', //This is optional, as it defaults to 'term_id'
																		'terms'         => $category->term_id,
																		'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
																	)
															)
														);
												}else{
													$args = array(
														'post_type'      => 'product',
														'post_status'    => 'publish',
														'paged' => $paged,
														'posts_per_page' => 16
														);
												}
												
												
												$featured_product = new WP_Query( $args );
												if ( $featured_product->have_posts() ) : 
												
													while ( $featured_product->have_posts() ) : $featured_product->the_post();
																$post_thumbnail_id     = get_post_thumbnail_id();
																$product_thumbnail     = wp_get_attachment_image_src($post_thumbnail_id, $size = 'shop-feature');
																$product_thumbnail_alt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
																?>
														<div class="col-xl-3 col-lg-3 col-md-4 browse_product_box_wrap">
															<div class="search_product_box browse_product_box">
																<div class="pimage" data-sku="<?php echo $product->get_sku(); ?>">
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
																		<?php global $product; if ( $price_html = $product->get_price_html() ) : ?>
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
													<?php endwhile; ?>
											</div>
										<div class="row pagination-row">
												<div class="pagination">



													<?php 
													global $wp_query;

													$mid_size = 1;

													$current_page = $wp_query->get( 'paged' );

														if ( ! $current_page ) { ?>
													<span>Pre</span>
													<?php } else { ?>
														<?php echo get_previous_posts_link( 'Pre' ); // display newer posts link ?>
													<?php } ?>

													<?php if (function_exists("pagination")) {
															pagination($custom_query->max_num_pages);
														} ?>

													<?php if ( $current_page == $wp_query->max_num_pages ) { ?>
														<span>Next</span>
													<?php } else { ?>	
														<?php echo get_next_posts_link( 'Next'); // display older posts link ?>
													<?php } ?>

						
												
												</div>
												<?php else: ?>
												<div class="post">

													Sorry, no posts matched your criteria.

												

												</div>
										
									<?php endif; ?>

										<?php wp_reset_query();
									?>
								</div>
								<?php } ?>

					</div><!-- .col-full -->
				</div>

			
			<?php else : ?>
				<div class="col-full">
					<?php woocommerce_content(); ?>
				</div>
			<?php endif; ?>


					<?php
					if ( is_singular('product') ) {
						setPostViews(get_the_ID()); ?>


									<div class="col-full">

										<div class="wishlist single-wishlist header-cart-count text-center rounded-circle">
											<?php echo do_shortcode('[ti_wishlists_addtowishlist]'); ?>
										</div>
										
									<div class="related-products">
											<h2>Related Products</h2>
										
									<?php global $post;
									// get categories
									$terms = wp_get_post_terms( $post->ID, 'product_cat' );
									foreach ( $terms as $term ) $cats_array[] = $term->term_id;
									$query_args = array( 'post__not_in' => array( $post->ID ), 'posts_per_page' => 5, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product', 'tax_query' => array( 
										array(
										'taxonomy' => 'product_cat',
										'field' => 'id',
										'terms' => $cats_array
										)));
									$r = new WP_Query($query_args);
											
									if ($r->have_posts()) {
										?>
										<div class="row">
										<?php while ($r->have_posts()) : $r->the_post(); global $product; ?>
											<div class="col-xl-3 col-lg-3 col-md-4 browse_product_box_wrap">
												<div class="search_product_box browse_product_box">
													<div class="pimage">
														<?php if (has_post_thumbnail()) the_post_thumbnail(); else echo '<img src="'. woocommerce_placeholder_img_src() .'" alt="Placeholder" />'; ?>
													</div>
													<div class="ptitle">
														<h3>
															<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
																<?php if ( get_the_title() ) the_title(); else the_ID(); ?>
																	
															</a> 
														</h3>
													</div>
													<div class="product-price-cart-wrap">
														<div class="product-price">
															<?php global $product; if ( $price_html = $product->get_price_html() ) : ?>
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

										<?php endwhile; ?>
										</div>

									</div>
									</div>

						<?php
						// Reset the global $the_post as this query will have stomped on it
						wp_reset_query();
					}
					}
					?>


		</article><!-- #post-## -->





	</div><!-- #primary -->

<?php
//do_action( 'storefront_sidebar' );
get_footer();
