<?php
/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Homepage
 *
 * @package storefront
 */

get_header(); $args=[];?>

	<div id="primary" class="content-area">
		<article id="post-<?php the_ID(); ?>" <?php post_class('shop_page'); ?>>
			
			<?php
			do_action('recently_listed_items');
			do_action('most_popular_search');
			//recently_listed_items($args);
			//most_popular_search($args);
			/* ?>							
			<div class="most_popular_searches">
				<div class="col-full">
					<h2><?php the_field('most_popular_searches_heading','30'); ?></h2>
					<div class="view_all_wrap"><a href="<?php echo site_url().'/browse/';?>">View all <i class="fa fa-arrow-right" aria-hidden="true"></i></a></div>
					<div class="row">
						<?php global $product;


						
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
					        while ( $loop->have_posts() ) : $loop->the_post(); ?>
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
					        <?php endwhile; endif;
					    wp_reset_query();
						?>
					</div>

				</div><!-- .col-full -->
			</div> <?php */ ?>
		</article>
	</div><!-- #primary -->
<?php
get_footer();
