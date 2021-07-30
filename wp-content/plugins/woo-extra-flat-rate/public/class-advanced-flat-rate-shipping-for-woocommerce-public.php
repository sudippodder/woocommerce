<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/public
 * @author     Multidots <inquiry@multidots.in>
 */
class Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version     The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function afrsm_pro_enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/advanced-flat-rate-shipping-for-woocommerce-public.css',
            array(),
            $this->version,
            'all'
        );
        wp_enqueue_style(
            'font-awesome-min',
            plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css',
            array(),
            $this->version
        );
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function afrsm_pro_enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/advanced-flat-rate-shipping-for-woocommerce-public.js',
            array( 'jquery' ),
            $this->version,
            false
        );
    }
    
    /**
     * This function return the template from this plugin, if it exists
     *
     * @param string $template
     * @param string $template_name that is only the filename
     * @param string $template_path
     *
     * @return string
     * @since    1.0.0
     *
     */
    public function afrsm_pro_wc_locate_template_sm_conditions( $template, $template_name, $template_path )
    {
        global  $woocommerce ;
        $_template = $template;
        if ( !$template_path ) {
            $template_path = $woocommerce->template_url;
        }
        $plugin_path = advanced_flat_rate_shipping_for_woocommerce_pro_plugin_path() . '/woocommerce/';
        $template = locate_template( array( $template_path . $template_name, $template_name ) );
        // Modification: Get the template from this plugin, if it exists
        if ( !$template && file_exists( $plugin_path . $template_name ) ) {
            $template = $plugin_path . $template_name;
        }
        if ( !$template ) {
            $template = $_template;
        }
        // Return what we found
        return $template;
    }
    
    /**
     * Price Format
     *
     * @param float $price price would be display here
     *
     * @return float $price
     * @since  3.6.1
     */
    public function afrswp_fraction_price_format( $price )
    {
        $args = array(
            'decimal_separator'  => wc_get_price_decimal_separator(),
            'thousand_separator' => wc_get_price_thousand_separator(),
            'decimals'           => wc_get_price_decimals(),
            'price_format'       => get_woocommerce_price_format(),
        );
        $price = floatval( $price );
        $price = number_format(
            $price,
            $args['decimals'],
            $args['decimal_separator'],
            $args['thousand_separator']
        );
        return $price;
    }

}