<?php
// If this file is called directly, abort.
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	/**
	 * Fired during plugin activation
	 *
	 * @link       http://www.multidots.com
	 * @since      1.0.0
	 *
	 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
	 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/includes
	 */
	
	/**
	 * Fired during plugin activation.
	 *
	 * This class defines all code necessary to run during the plugin's activation.
	 *
	 * @since      1.0.0
	 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
	 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/includes
	 * @author     Multidots <inquiry@multidots.in>
	 */
	class Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Activator {
		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 *
		 * @uses afrsm_pro_data_migration_script()
		 */
		public static function activate() {
			global $wpdb;
			set_transient( '_welcome_screen_afrsm_pro_mode_activation_redirect_data', true, 30 );
			add_option( 'afrsm_version', AFRSM_PRO_PLUGIN_VERSION );
			
			if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) && ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
				wp_die( "<strong>Advanced Flat Rate Shipping For WooCommerce</strong> plugin requires <strong>WooCommerce</strong>. Return to <a href='" . esc_url( get_admin_url( null, 'plugins.php' ) ) . "'>Plugins page</a>." );
			} else {
				update_option( 'chk_enable_logging', 'on' );
				
				$what_to_do_method                      = get_option( 'what_to_do_method' );
				$shipping_method_format                 = get_option( 'md_woocommerce_shipping_method_format' );
				$combine_default_shipping_with_forceall = get_option( 'combine_default_shipping_with_forceall' );
				
				if ( ! empty( $what_to_do_method ) ) {
					update_option( 'what_to_do_method', $what_to_do_method );
					
					if ( 'allow_customer' === $what_to_do_method ) {
						if ( ! empty( $shipping_method_format ) ) {
							update_option( 'md_woocommerce_shipping_method_format', $shipping_method_format );
						}
					} else {
						update_option( 'md_woocommerce_shipping_method_format', 'radio_button_mode' );
					}
				}
				if ( ! empty( $combine_default_shipping_with_forceall ) ) {
					update_option( 'combine_default_shipping_with_forceall', $combine_default_shipping_with_forceall );
				}
				
				$sz_table_name  = "{$wpdb->prefix}wcextraflatrate_shipping_zones";
				$szl_table_name = "{$wpdb->prefix}wcextraflatrate_shipping_zone_locations";
				$sz_flag        = 0;
				$szl_flag       = 0;
				if ( $wpdb->get_var( "SHOW TABLES LIKE '$sz_table_name'" ) === $sz_table_name ) {
					$sz_flag = 1;
				}
				
				if ( $wpdb->get_var( "SHOW TABLES LIKE '$szl_table_name'" ) === $szl_table_name ) {
					$szl_flag = 1;
				}
				
				if ( 0 === $sz_flag && 0 === $szl_flag ) {
					update_option( 'zone_migration', 'done' );
				}
			}
		}
	}