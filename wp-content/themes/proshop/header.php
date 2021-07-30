<?php
/*
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
<link rel="profile" href="http://gmpg.org/xfn/11">

<link href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'storefront_before_header' ); ?>

	<header class="site-header site-top">
		<div class="header-top">
			<div class="col-full">
				<div class="row">
					<div class="col-12 text-right">
						<?php if ( is_user_logged_in() ) { ?>
						    <a href="<?php the_permalink(33) ;?>" title="<?php _e('My Account',''); ?>"><i class="fa fa-user" aria-hidden="true"></i> <?php _e('My Account',''); ?></a>
						    <a href="<?php echo wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) );?>" title="<?php _e('Sign Out',''); ?>"><i class="fa fa-unlock" aria-hidden="true"></i> <?php _e('Sign Out',''); ?></a>
						<?php } else { ?>
						    <a href="<?php echo wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) );?>" title="<?php _e('Sign In',''); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/login-icon.png" /> <?php _e('Sign In',''); ?></a>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>

		<div class="header-bottom">
			<span class="welcome-text text-center">WELCOME TO</span>
			<div class="col-full">
				<div class="row">
					<div class="col-xl-2 col-lg-2 col-sm-3 logo-wrap">
						<?php storefront_site_title_or_logo(); ?>
					</div>
					<div class="col-xl-6 col-lg-6 col-sm-9">
					    <div class="product-sidebar site-search">
					    	<div class="widget woocommerce widget_product_search">
							    	<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							    		<span class="search-list"> 
							    			<?php if (class_exists('WooCommerce')) : ?>
										  <?php 
										  if(isset($_REQUEST['product_cat']) && !empty($_REQUEST['product_cat']))
										  {
										   $optsetlect=$_REQUEST['product_cat'];
										  }
										 else{
										  $optsetlect=0;  
										  }
										         $args = array(
										                    'show_option_all' => esc_html__( 'CATAGORIES', 'woocommerce' ),
										                    'hierarchical' => 1,
										                    'class' => 'cat',
										                    'echo' => 1,
										                    'value_field' => 'slug',
										                    'selected' => $optsetlect
										                );
										          $args['taxonomy'] = 'product_cat';
										          $args['name'] = 'product_cat';              
										          $args['class'] = 'cate-dropdown hidden-xs';
										          wp_dropdown_categories($args);

										   ?>
										   <input type="hidden" value="product" name="post_type">
											<?php endif; ?>	
							    		</span>
										<input type="search" id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="search-field" placeholder="<?php echo esc_attr__( 'Search for items', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
										<button type="submit" value="<?php echo esc_attr_x( 'Search for items', 'submit button', 'woocommerce' ); ?>"><?php echo esc_html_x( 'Search for items', 'submit button', 'woocommerce' ); ?></button>
										
									</form>
							</div>
							<?php if ( is_active_sidebar( 'product-sidebar' ) ) : ?>
					        	<?php //dynamic_sidebar( 'product-sidebar' ); ?>
					        <?php endif; ?>
					    </div>	
					</div>
					<div class="col-xl-4 col-lg-4 header-right">
						<div class="wishlist-search-signup">
							<?php 
								global $woocommerce;
								$cart_url = esc_url( wc_get_cart_url() );
							?>
							<?php if ( is_user_logged_in() ) { ?>
							<?php } else { ?>
							    <a class="signup-button pull-right" href="<?php echo wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) );?>" title="<?php _e('Sign Up',''); ?>"><?php _e('Sign Up',''); ?></a>
							<?php } ?>
							<a href="<?php echo $cart_url; ?>" class="header-cart-count text-center rounded-circle pull-right">
								<i class="fa fa-shopping-cart" aria-hidden="true"></i><sup class="mini-cart-count text-center rounded-circle"><?php echo WC()->cart->get_cart_contents_count(); ?></sup></a>
							<a href="<?php the_permalink(763) ;?>" class="wishlist text-center rounded-circle pull-right"><i class="fa fa-heart" aria-hidden="true"></i></a>
						</div>
						<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'storefront' ); ?>">
							<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"><span><?php echo esc_attr( apply_filters( 'storefront_menu_toggle_text', __( 'Menu', 'storefront' ) ) ); ?></span></button>
								<?php
								wp_nav_menu(
									array(
										'theme_location'  => 'primary',
										'container_class' => 'primary-navigation',
									)
								);

								wp_nav_menu(
									array(
										'theme_location'  => 'handheld',
										'container_class' => 'handheld-navigation',
									)
								);
								?>
							</nav><!-- #site-navigation -->
					</div>

				</div>
			</div>
		</div>
	</header><!-- #masthead -->

	<?php if ( is_front_page() ) : ?>
		<section class="banner">
			<?php 
				$frontbannerimage = get_field('front_banner_image','30');
				$frontbannercontent = get_field('front_banner_content','30');
			?>
			<div class="col-full">
				<div class="banner_content">
					<?php echo $frontbannercontent; ?>
					<img src="<?php echo $frontbannerimage['url']; ?>" alt="<?php echo $frontbannerimage['alt']; ?>" />
				</div>
			</div>
		</section>
	<?php else : ?>
	<?php endif ; ?>

	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action( 'storefront_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">
		

		<?php
		do_action( 'storefront_content_top' );
