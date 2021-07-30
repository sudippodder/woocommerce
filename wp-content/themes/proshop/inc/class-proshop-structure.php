<?php
/**
 * Proshop Structure Class
 *
 * @author   WooThemes
 * @since    2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Proshop_Structure' ) ) :

class Proshop_Structure {

	/**
	 * Setup class.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		add_action( 'storefront_header', array( $this, 'primary_navigation_wrapper' ),       45 );
		add_action( 'storefront_header', array( $this, 'primary_navigation_wrapper_close' ), 65 );

		add_action( 'wp', array( $this, 'custom_storefront_markup' ) );
	}

	/**
	 * Primary navigation wrapper
	 * @return void
	 */
	public function primary_navigation_wrapper() {
		echo '<section class="p-primary-navigation p-primary-navigation">';
	}

	/**
	 * Primary navigation wrapper close
	 * @return void
	 */
	public function primary_navigation_wrapper_close() {
		echo '</section>';
	}

	/**
	 * Secondary navigation wrapper
	 * @return void
	 */
	public static function secondary_navigation_wrapper() {
		echo '<section class="p-secondary-navigation">';
	}

	/**
	 * Secondary navigation wrapper close
	 * @return void
	 */
	public static function secondary_navigation_wrapper_close() {
		echo '</section>';
	}

	/**
	 * Custom markup tweaks
	 * @return void
	 */
	public function custom_storefront_markup() {
		remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
		add_action( 'storefront_header', 'storefront_secondary_navigation',    5 );
		add_action( 'storefront_header', array( 'Proshop_Structure', 'secondary_navigation_wrapper' ),       4 );
		add_action( 'storefront_header', array( 'Proshop_Structure', 'secondary_navigation_wrapper_close' ), 6 );

		if ( storefront_is_woocommerce_activated() ) {
			remove_action( 'storefront_header', 'storefront_header_cart', 60 );
			add_action( 'storefront_header', 'storefront_header_cart',    40 );
		}
	}

}

endif;

return new Proshop_Structure();