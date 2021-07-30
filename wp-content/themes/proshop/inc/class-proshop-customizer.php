<?php
/**
 * Proshop Customizer Class
 *
 * @author   WooThemes
 * @since    2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Proshop_Customizer' ) ) :

class Proshop_Customizer {

	/**
	 * Setup class.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$theme 					= wp_get_theme( 'storefront' );
		$storefront_version 	= $theme['Version'];

		add_action( 'wp_enqueue_scripts',                  array( $this, 'add_customizer_css' ), 1000 );
		add_action( 'customize_register',                  array( $this, 'customize_register' ) );
		add_filter( 'storefront_custom_background_args',   array( $this, 'set_background' ) );
		add_action( 'wp',                                  array( $this, 'proshop_storefront_woocommerce_customiser' ), 99 );
		add_action( 'wp_enqueue_scripts',                  array( $this, 'proshop_storefront_hamburger_menu_customizer_css' ), 999 );
		add_filter( 'storefront_custom_header_args',       array( $this, 'proshop_custom_header_defaults' ) );
		add_filter( 'storefront_setting_default_values',   array( $this, 'proshop_defaults' ), 1000 );
		add_filter( 'storefront_default_background_color', array( $this, 'default_background_color' ) );
	}

	/**
	 * Returns an array with default storefront and extension options
	 * @return array
	 */
	public function proshop_defaults( $defaults = array() ) {
		$defaults['background_color']                       = 'e8e8e8';
		$defaults['storefront_heading_color']               = '#444444';
		$defaults['storefront_footer_heading_color']        = '#ffffff';
		$defaults['storefront_header_background_color']     = '#232c3a';
		$defaults['storefront_footer_background_color']     = '#000000';
		$defaults['storefront_header_link_color']           = '#ffffff';
		$defaults['storefront_header_text_color']           = '#ffffff';
		$defaults['storefront_footer_link_color']           = '#ffffff';
		$defaults['storefront_text_color']                  = '#666666';
		$defaults['storefront_footer_text_color']           = '#e8e8e8';
		$defaults['storefront_accent_color']                = '#00a5bf';
		$defaults['storefront_button_background_color']     = '#232c3a';
		$defaults['storefront_button_text_color']           = '#ffffff';
		$defaults['storefront_button_alt_background_color'] = '#d84b2f';
		$defaults['storefront_button_alt_text_color']       = '#ffffff';
		$defaults['proshop_navigation_color']               = '#444444';
		$defaults['proshop_navigation_background_color']    = '#ffffff';

		return $defaults;
	}

	/**
	 * Default background color.
	 * @param string $color Default color.
	 * @return string
	 */
	public function default_background_color( $color ) {
		return 'e8e8e8';
	}

	/**
	 * Add custom CSS based on settings in Storefront core
	 * @return void
	 */
	public function add_customizer_css() {
		$primary_nav_color 				= get_theme_mod( 'proshop_navigation_color' );
		$primary_nav_bg_color 			= get_theme_mod( 'proshop_navigation_background_color' );
		$header_link_color 				= get_theme_mod( 'storefront_header_link_color' );
		$header_background_color 		= get_theme_mod( 'storefront_header_background_color' );
		$header_text_color 				= get_theme_mod( 'storefront_header_text_color' );
		$accent_color 			 		= get_theme_mod( 'storefront_accent_color' );
		$content_bg_color				= get_theme_mod( 'sd_content_background_color' );
		$content_frame 					= get_theme_mod( 'sd_fixed_width' );
		$bg_color 						= storefront_get_content_background_color();

		$button_alt_background_color 	= get_theme_mod( 'storefront_button_alt_background_color' );
		$button_alt_text_color 			= get_theme_mod( 'storefront_button_alt_text_color' );

		$style = '
			.p-primary-navigation,
			.main-navigation ul.menu ul,
			.sticky-wrapper,
			.sd-sticky-navigation,
			.sd-sticky-navigation:before,
			.sd-sticky-navigation:after,
			.main-navigation ul.nav-menu ul {
				background-color: ' . $primary_nav_bg_color . ' !important;
			}

			.main-navigation ul.nav-menu .smm-active ul {
				background-color: transparent !important;
			}

			.main-navigation ul li a,
			.main-navigation ul li a:hover,
			.main-navigation ul li:hover > a {
				color: ' . $primary_nav_color .';
			}

			.site-title a:hover,
			a.cart-contents:hover,
			.site-header-cart .widget_shopping_cart a:hover,
			.site-header-cart:hover > li > a {
				color: ' . $header_link_color . ';
			}

			.main-navigation ul li.smm-active li ul.products li.product h3,
			.main-navigation ul li.smm-active li ul.products li.product h2,
			.main-navigation ul li.smm-active li ul.products li.product woocommerce-loop-product__title,
			.main-navigation ul li.smm-active li a,
			.main-navigation ul li.smm-active .widget h3.widget-title,
			.main-navigation ul li.smm-active li:hover a {
				color: ' . $primary_nav_color . ' !important;
			}

			.main-navigation li.current-menu-item > a,
			.main-navigation ul li a:hover {
				color: ' . storefront_adjust_color_brightness( $primary_nav_color, 50 ) . ' !important;
			}

			ul.products li.product.product-category h3,
			ul.products li.product.product-category h2,
			ul.products li.product.product-category .woocommerce-loop-product__title {
				background-color: ' . $button_alt_background_color . ';
			}

			ul.products li.product.product-category:hover h3,
			ul.products li.product.product-category:hover h2,
			ul.products li.product.product-category:hover .woocommerce-loop-product__title {
				background-color: ' . storefront_adjust_color_brightness( $button_alt_background_color, -15 ) . ';
			}

			ul.products li.product.product-category h3,
			ul.products li.product.product-category h3 mark,
			ul.products li.product.product-category h2,
			ul.products li.product.product-category h2 mark,
			ul.products li.product.product-category .woocommerce-loop-product__title,
			ul.products li.product.product-category .woocommerce-loop-product__title mark {
				color: ' . $button_alt_text_color . ';
			}

			.storefront-product-section .section-title span,
			.storefront-product-section .section-title span:before,
			.storefront-product-section .section-title span:after,
			#respond {
				background-color: ' . storefront_adjust_color_brightness( $bg_color, 10 ) . ';
			}

			.storefront-product-section .section-title span:before,
			.storefront-product-section .section-title span:after,
			.storefront-product-section .section-title span,
			.widget-area .widget:before,
			.widget-area .widget:after,

			.widget-area .widget {
				border-color: ' . storefront_adjust_color_brightness( $bg_color, 18 ) . '
			}

			.widget-area .widget,
			.widget-area .widget:before,
			.widget-area .widget:after {
				background-color: ' . storefront_adjust_color_brightness( $bg_color, 10 ) . ';
			}

			ul.products li.product img,
			ul.products li.product .price,
			ul.products li.product .price:after,
			.single-product .images img,
			input[type="text"], input[type="email"], input[type="url"], input[type="password"], input[type="search"], textarea, .input-text {
				background-color: ' . storefront_adjust_color_brightness( $bg_color, 15 ) . ';
			}

			ul.products li.product .price:before {
				border-left-color: ' . storefront_adjust_color_brightness( $bg_color, 15 ) . ';
			}

			.rtl ul.products li.product .price:before {
				border-right-color: ' . storefront_adjust_color_brightness( $bg_color, 15 ) . ';
				border-left-color: transparent;

			}

			.woocommerce-active .site-header .site-search input[type=search] {
				box-shadow: 0 0 0 3px ' . $accent_color . ';
			}

			.woocommerce-active .site-header .site-search .widget_product_search form:before {
				color: ' . $header_link_color . ';
			}

			.smm-mega-menu {
				background-color: ' . $primary_nav_bg_color . ';
			}

			@media screen and (min-width: 768px) {
				.p-primary-navigation {
					border-top-color: ' . $header_background_color . ';
				}

				.woocommerce-active .site-header .site-header-cart a.cart-contents:after,
				.woocommerce-active .site-header .site-search .widget_product_search form:before,
				.widget-area .widget .widget-title:after,
				.main-navigation ul.menu li.current-menu-item > a:before,
				.main-navigation ul.nav-menu li.current-menu-item > a:before {
					background-color: ' . $accent_color . ';
				}

				.woocommerce-active .site-header .site-header-cart a.cart-contents:hover:after {
					background-color: ' . storefront_adjust_color_brightness( $accent_color, 5 ) . ';
				}

				.storefront-product-section.storefront-product-categories .columns-3 ul.products li.product:after,
				.storefront-product-section.storefront-product-categories .columns-3 ul.products li.product:before {
					background-color: ' . $bg_color . ';
					background-image: url(' . get_background_image() . ');
				}

				.site-header-cart .widget_shopping_cart a.button {
					background-color: ' . storefront_adjust_color_brightness( $header_background_color, -15 ) . ';
				}
			}
			';

		wp_add_inline_style( 'storefront-child-style', $style );
	}

	/**
	 * Proshop background settings
	 * @return array $args the modified args.
	 */
	public function set_background( $args ) {
		$args['default-image'] 		= get_stylesheet_directory_uri() . '/assets/images/texture.png';
		$args['default-attachment'] = 'fixed';

		return $args;
	}

	/**
	 * Set up proshop customizer controls/settings
	 */
	public function customize_register( $wp_customize ) {
		$wp_customize->get_setting( 'background_color' )->transport 	= 'refresh';

		/**
		 * Primary navigation color
		 */
		$wp_customize->add_setting( 'proshop_navigation_color', array(
			'default'           => apply_filters( 'proshop_default_navigation_color', '#444444' ),
			'sanitize_callback' => 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'proshop_navigation_color', array(
			'label'	   => __( 'Primary navigation link color', 'storefront' ),
			'section'  => 'header_image',
			'settings' => 'proshop_navigation_color',
			'priority' => 40,
		) ) );

		/**
		 * Primary navigation background color
		 */
		$wp_customize->add_setting( 'proshop_navigation_background_color', array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'proshop_navigation_background_color', array(
			'label'	   => __( 'Primary navigation background color', 'storefront' ),
			'section'  => 'header_image',
			'settings' => 'proshop_navigation_background_color',
			'priority' => 41,
		) ) );
	}

	/**
	 * Storefront WooCommerce Customiser compatibility tweaks
	 */
	public function proshop_storefront_woocommerce_customiser() {
		if ( class_exists( 'Storefront_WooCommerce_Customiser' ) ) {
			$cart_link = get_theme_mod( 'swc_header_cart', true );

			if ( false == $cart_link ) {
				remove_action( 'storefront_header', 'storefront_header_cart', 40 );
			} else {
				add_action( 'storefront_header', 'storefront_header_cart', 40 );
			}
		}
	}

	/**
	 * Storefront Hamburger Menu tweaks
	 */
	public function proshop_storefront_hamburger_menu_customizer_css() {
		$primary_nav_bg_color = get_theme_mod( 'proshop_navigation_background_color' );

		$wc_style = '
			@media screen and (max-width: 768px) {
				.main-navigation div.menu,
				.main-navigation .handheld-navigation {
					background-color: ' . $primary_nav_bg_color . ' !important;
				}
			}
		';

		wp_add_inline_style( 'storefront-woocommerce-style', $wc_style );
	}

	/**
	 * Sets the default header image
	 */
	public static function proshop_custom_header_defaults( $args ) {
		$args['default-image']  = get_stylesheet_directory_uri() . '/assets/images/header.jpg';
		$args['height']         = 1000;

		return $args;

		var_dump( $args );
	}
}

endif;

return new Proshop_Customizer();