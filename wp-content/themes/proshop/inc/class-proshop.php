<?php
/**
 * Proshop Class
 *
 * @author   WooThemes
 * @since    2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Proshop' ) ) :

class Proshop {

	/**
	 * Setup class.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		add_filter( 'body_class',                            array( $this, 'body_classes' ) );

		add_filter( 'storefront_woocommerce_args',           array( $this, 'woocommerce_support' ) );

		add_action( 'wp_enqueue_scripts',                    array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts',                    array( $this, 'enqueue_child_styles' ), 99 );

		add_filter( 'storefront_product_categories_args',    array( $this, 'proshop_homepage_section_title' ), 999 );
		add_filter( 'storefront_featured_products_args',     array( $this, 'proshop_homepage_section_title' ), 999 );
		add_filter( 'storefront_recent_products_args',       array( $this, 'proshop_homepage_section_title' ), 999 );
		add_filter( 'storefront_popular_products_args',      array( $this, 'proshop_homepage_section_title' ), 999 );
		add_filter( 'storefront_on_sale_products_args',      array( $this, 'proshop_homepage_section_title' ), 999 );
		add_filter( 'storefront_best_selling_products_args', array( $this, 'proshop_homepage_section_title' ), 999 );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	public function body_classes( $classes ) {
		global $storefront_version;

		if ( version_compare( $storefront_version, '2.3.0', '>=' ) ) {
			$classes[] = 'storefront-2-3';
		}

		return $classes;
	}

	/**
	 * Override Storefront default theme settings for WooCommerce.
	 * @return array the modified arguments
	 */
	public function woocommerce_support( $args ) {
		$args['single_image_width']    = 458;
		$args['thumbnail_image_width'] = 356;

		return $args;
	}

	/**
	 * Enqueue Storefront Styles
	 * @return void
	 */
	public function enqueue_styles() {
		global $storefront_version;

		wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css', $storefront_version );
	}

	/**
	 * Enqueue Storechild Styles
	 * @return void
	 */
	public function enqueue_child_styles() {
		global $storefront_version, $proshop_version;

		wp_style_add_data( 'storefront-child-style', 'rtl', 'replace' );

		wp_enqueue_style( 'droid-sans', '//fonts.googleapis.com/css?family=Droid+Sans:400,700', array( 'storefront-child-style' ) );
		wp_enqueue_style( 'exo-2', '//fonts.googleapis.com/css?family=Exo+2:800italic', array( 'storefront-child-style' ) );
		wp_enqueue_style( 'oswald', '//fonts.googleapis.com/css?family=Oswald', array( 'storefront-child-style' ) );
		wp_enqueue_style( 'ubuntu-mono', '//fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic', array( 'storefront-child-style' ) );

		wp_enqueue_script( 'proshop', get_stylesheet_directory_uri() . '/assets/js/proshop.min.js', array( 'jquery' ), $proshop_version );
	}

	/**
	 * Wrap homepage section titles inside a `span`.
	 * @param  array $args homepage section arguments
	 * @return array       homepage section arguments
	 */
	public function proshop_homepage_section_title( $args ) {
	    $storefront             = wp_get_theme( 'storefront' );
	    $storefront_version     = $storefront['Version'];

	    // Only set the title if the storefront version is greater than 1.4.5
	    if ( version_compare( $storefront_version, '1.4.5' ) == 1 ) {
	        $title          = $args['title'];
	        $args['title']  = '<span>' . $title . '</span>';
	    }

	    return $args;
	}

}

endif;

return new Proshop();