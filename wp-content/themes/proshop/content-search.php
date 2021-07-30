
	<?php			
				$post_thumbnail_id     = get_post_thumbnail_id();
				$product_thumbnail     = wp_get_attachment_image_src($post_thumbnail_id, $size = 'shop-feature');
				$product_thumbnail_alt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
				?>
		<div class="col-xl-3 col-lg-3 col-md-4 browse_product_box_wrap">
			<div class="search_product_box browse_product_box">
				<div class="pimage">
					<a href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) { ?>
							<?php the_post_thumbnail(); ?>
						<?php } else { ?>
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assetes/images/product-default-image.jpg" />
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