<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/admin
 * @author     Multidots <inquiry@multidots.in>
 */
class Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin
{
    const  afrsm_shipping_post_type = 'wc_afrsm' ;
    const  afrsm_zone_post_type = 'wc_afrsm_zone' ;
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
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @param string $hook display current page name
     *
     * @since    1.0.0
     *
     */
    public function afrsm_pro_enqueue_styles( $hook )
    {
        
        if ( false !== strpos( $hook, 'dotstore-plugins_page_afrsm' ) ) {
            wp_enqueue_style(
                $this->plugin_name . 'select2-min',
                plugin_dir_url( __FILE__ ) . 'css/select2.min.css',
                array(),
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-jquery-ui-css',
                plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-timepicker-min-css',
                plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.min.css',
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'font-awesome',
                plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'main-style',
                plugin_dir_url( __FILE__ ) . 'css/style.css',
                array(),
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'media-css',
                plugin_dir_url( __FILE__ ) . 'css/media.css',
                array(),
                'all'
            );
        }
    
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @param string $hook display current page name
     *
     * @since    1.0.0
     *
     */
    public function afrsm_pro_enqueue_scripts( $hook )
    {
        global  $wp ;
        wp_enqueue_style( 'wp-jquery-ui-dialog' );
        wp_enqueue_script( 'jquery-ui-accordion' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        
        if ( false !== strpos( $hook, 'dotstore-plugins_page_afrsm' ) ) {
            wp_enqueue_script(
                $this->plugin_name . '-select2-full-min',
                plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js',
                array( 'jquery', 'jquery-ui-datepicker' ),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name . '-tablesorter-js',
                plugin_dir_url( __FILE__ ) . 'js/jquery.tablesorter.js',
                array( 'jquery' ),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name . '-timepicker-js',
                plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.js',
                array( 'jquery' ),
                $this->version,
                false
            );
            $current_url = home_url( add_query_arg( $wp->query_vars, $wp->request ) );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/advanced-flat-rate-shipping-for-woocommerce-admin.js',
                array(
                'jquery',
                'jquery-ui-dialog',
                'jquery-ui-accordion',
                'jquery-ui-sortable',
                'select2'
            ),
                $this->version,
                false
            );
            wp_localize_script( $this->plugin_name, 'coditional_vars', array(
                'ajaxurl'                        => admin_url( 'admin-ajax.php' ),
                'ajax_icon'                      => esc_url( plugin_dir_url( __FILE__ ) . '/images/ajax-loader.gif' ),
                'plugin_url'                     => plugin_dir_url( __FILE__ ),
                'dsm_ajax_nonce'                 => wp_create_nonce( 'dsm_nonce' ),
                'country'                        => esc_html__( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_contains_product'          => esc_html__( 'Cart contains product', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_contains_category_product' => esc_html__( 'Cart contains category\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_contains_tag_product'      => esc_html__( 'Cart contains tag\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'user'                           => esc_html__( 'User', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_subtotal_before_discount'  => esc_html__( 'Cart Subtotal (Before Discount)', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'quantity'                       => esc_html__( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'weight'                         => esc_html__( 'Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'coupon'                         => esc_html__( 'Coupon', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'shipping_class'                 => esc_html__( 'Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'equal_to'                       => esc_html__( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'not_equal_to'                   => esc_html__( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'less_or_equal_to'               => esc_html__( 'Less or Equal to ( <= )', 'woocommerce-conditional-product-fees-for-checkout' ),
                'less_than'                      => esc_html__( 'Less then ( < )', 'woocommerce-conditional-product-fees-for-checkout' ),
                'greater_or_equal_to'            => esc_html__( 'greater or Equal to ( >= )', 'woocommerce-conditional-product-fees-for-checkout' ),
                'greater_than'                   => esc_html__( 'greater then ( > )', 'woocommerce-conditional-product-fees-for-checkout' ),
                'validation_length1'             => esc_html__( 'Please enter 3 or more characters', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'select_category'                => esc_html__( 'Select Category', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'delete'                         => esc_html__( 'Delete', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'validation_length2'             => esc_html__( 'Please enter', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'validation_length3'             => esc_html__( 'or more characters', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'location_specific'              => esc_html__( 'Location Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'product_specific'               => esc_html__( 'Product Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'user_specific'                  => esc_html__( 'User Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_specific'                  => esc_html__( 'Cart Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'checkout_specific'              => esc_html__( 'Checkout Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'success_msg1'                   => esc_html__( 'Shipping method order saved successfully', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'success_msg2'                   => esc_html__( 'Your settings successfully saved.', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'warning_msg1'                   => sprintf( __( '<p><b style="color: red;">Note: </b>If entered price is more than total shipping price than Message looks like: <b>Shipping Method Name: Curreny Symbole like($) -60.00 Price </b> and if shipping minus price is more than total price than it will set Total Price to Zero(0).</p>', 'advanced-flat-rate-shipping-for-woocommerce' ) ),
                'note'                           => esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'click_here'                     => esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'current_url'                    => $current_url,
                'doc_url'                        => "https://www.thedotstore.com/docs/plugin/advanced-flat-rate-shipping-method-for-woocommerce/",
                'list_page_url'                  => add_query_arg( array(
                'page' => 'afrsm-start-page',
            ), admin_url( 'admin.php' ) ),
            ) );
        }
    
    }
    
    /*
     * Shipping method Pro Menu
     *
     * @since 3.0.0
     */
    public function afrsm_pro_dot_store_menu_shipping_method_pro()
    {
        global  $GLOBALS ;
        if ( empty($GLOBALS['admin_page_hooks']['dots_store']) ) {
            add_menu_page(
                'DotStore Plugins',
                __( 'DotStore Plugins' ),
                'null',
                'dots_store',
                array( $this, 'dot_store_menu_page' ),
                AFRSM_PRO_PLUGIN_URL . 'admin/images/menu-icon.png',
                25
            );
        }
        add_submenu_page(
            'dots_store',
            'Advanced Flat Rate Shipping For WooCommerce',
            'Advanced Flat Rate Shipping For WooCommerce',
            'manage_options',
            'afrsm-pro-list',
            array( $this, 'afrsm_pro_fee_list_page' )
        );
        add_submenu_page(
            'dots_store',
            'Add Shipping Method',
            'Add Shipping Method',
            'manage_options',
            'afrsm-pro-add-shipping',
            array( $this, 'afrsm_pro_add_new_fee_page' )
        );
        add_submenu_page(
            'dots_store',
            'Edit Shipping Method',
            'Edit Shipping Method',
            'manage_options',
            'afrsm-pro-edit-shipping',
            array( $this, 'afrsm_pro_edit_fee_page' )
        );
        add_submenu_page(
            'dots_store',
            'Premium Version',
            'Premium Version',
            'manage_options',
            'afrsm-premium',
            array( $this, 'premium_version_afrsm_page' )
        );
        add_submenu_page(
            'dots_store',
            'Getting Started',
            'Getting Started',
            'manage_options',
            'afrsm-pro-get-started',
            array( $this, 'afrsm_pro_get_started_page' )
        );
        add_submenu_page(
            'dots_store',
            'Quick info',
            'Quick info',
            'manage_options',
            'afrsm-pro-information',
            array( $this, 'afrsm_pro_information_page' )
        );
    }
    
    /**
     * Shipping List Page
     *
     * @since    1.0.0
     */
    public function afrsm_pro_fee_list_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-list-page.php';
    }
    
    /**
     * Add new shipping method Page
     *
     * @since    1.0.0
     */
    public function afrsm_pro_add_new_fee_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-add-new-page.php';
    }
    
    /**
     * Edit shipping method Page
     *
     * @since    1.0.0
     */
    public function afrsm_pro_edit_fee_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-add-new-page.php';
    }
    
    /**
     * Quick guide page
     *
     * @since    1.0.0
     */
    public function afrsm_pro_get_started_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-get-started-page.php';
    }
    
    /**
     * Plugin information page
     *
     * @since    1.0.0
     */
    public function afrsm_pro_information_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-information-page.php';
    }
    
    /**
     * Premium version info page
     *
     */
    public function premium_version_afrsm_page()
    {
        require_once plugin_dir_path( __FILE__ ) . '/partials/afrsm-premium-version-page.php';
    }
    
    /**
     * Redirect to shipping list page
     *
     * @since    1.0.0
     */
    public function afrsm_pro_redirect_shipping_function()
    {
        $get_section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_STRING );
        
        if ( isset( $get_section ) && !empty($get_section) && 'advanced_flat_rate_shipping' === $get_section ) {
            wp_safe_redirect( add_query_arg( array(
                'page' => 'afrsm-pro-list',
            ), admin_url( 'admin.php' ) ) );
            exit;
        }
    
    }
    
    /**
     * Redirect to quick start guide after plugin activation
     *
     * @uses afrsm_pro_register_post_type()
     *
     * @since    1.0.0
     */
    public function afrsm_pro_welcome_shipping_method_screen_do_activation_redirect()
    {
        $this->afrsm_pro_register_post_type();
        // if no activation redirect
        if ( !get_transient( '_welcome_screen_afrsm_pro_mode_activation_redirect_data' ) ) {
            return;
        }
        // Delete the redirect transient
        delete_transient( '_welcome_screen_afrsm_pro_mode_activation_redirect_data' );
        // if activating from network, or bulk
        $activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_STRING );
        if ( is_network_admin() || isset( $activate_multi ) ) {
            return;
        }
        // Redirect to extra cost welcome  page
        wp_safe_redirect( add_query_arg( array(
            'page' => 'afrsm-pro-list',
        ), admin_url( 'admin.php' ) ) );
        exit;
    }
    
    /**
     * Register post type
     *
     * @since    1.0.0
     */
    public function afrsm_pro_register_post_type()
    {
        register_post_type( self::afrsm_shipping_post_type, array(
            'labels' => array(
            'name'          => __( 'Advance Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'singular_name' => __( 'Advance Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' ),
        ),
        ) );
    }
    
    /**
     * Remove submenu from admin screeen
     *
     * @since    1.0.0
     */
    public function afrsm_pro_remove_admin_submenus()
    {
        remove_submenu_page( 'dots_store', 'afrsm-pro-add-shipping' );
        remove_submenu_page( 'dots_store', 'afrsm-pro-edit-shipping' );
        remove_submenu_page( 'dots_store', 'afrsm-premium' );
        remove_submenu_page( 'dots_store', 'afrsm-pro-get-started' );
        remove_submenu_page( 'dots_store', 'afrsm-pro-information' );
    }
    
    /**
     * Match condition based on shipping list
     *
     * @param int $sm_post_id
     * @param array|object $package
     *
     * @return bool True if $final_condition_flag is 1, false otherwise. if $sm_status is off then also return false.
     * @since    1.0.0
     *
     * @uses afrsm_pro_get_default_langugae_with_sitpress()
     * @uses afrsm_pro_get_woo_version_number()
     * @uses WC_Cart::get_cart()
     * @uses afrsm_pro_match_country_rules()
     * @uses afrsm_pro_match_state_rules__premium_only()
     * @uses afrsm_pro_match_postcode_rules__premium_only()
     * @uses afrsm_pro_match_zone_rules__premium_only()
     * @uses afrsm_pro_match_variable_products_rule__premium_only()
     * @uses afrsm_pro_match_simple_products_rule()
     * @uses afrsm_pro_match_category_rule()
     * @uses afrsm_pro_match_tag_rule()
     * @uses afrsm_pro_match_sku_rule__premium_only()
     * @uses afrsm_pro_match_user_rule()
     * @uses afrsm_pro_match_user_role_rule__premium_only()
     * @uses afrsm_pro_match_coupon_rule__premium_only()
     * @uses afrsm_pro_match_cart_subtotal_before_discount_rule()
     * @uses afrsm_pro_match_cart_subtotal_after_discount_rule__premium_only()
     * @uses afrsm_pro_match_cart_total_cart_qty_rule()
     * @uses afrsm_pro_match_cart_total_weight_rule__premium_only()
     * @uses afrsm_pro_match_shipping_class_rule__premium_only()
     *
     */
    public function afrsm_pro_condition_match_rules( $sm_post_id, $package = array() )
    {
        if ( empty($sm_post_id) ) {
            return false;
        }
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        
        if ( !empty($sitepress) ) {
            $sm_post_id = apply_filters(
                'wpml_object_id',
                $sm_post_id,
                'wc_afrsm',
                true,
                $default_lang
            );
        } else {
            $sm_post_id = $sm_post_id;
        }
        
        $wc_curr_version = $this->afrsm_pro_get_woo_version_number();
        $is_passed = array();
        $final_is_passed_general_rule = array();
        $new_is_passed = array();
        $final_condition_flag = array();
        $cart_array = $this->afrsm_pro_get_cart();
        $cart_main_product_ids_array = $this->afrsm_pro_get_main_prd_id( $sitepress, $default_lang );
        $cart_product_ids_array = $this->afrsm_pro_get_prd_var_id( $sitepress, $default_lang );
        $sm_status = get_post_status( $sm_post_id );
        $get_condition_array = get_post_meta( $sm_post_id, 'sm_metabox', true );
        $general_rule_match = 'all';
        if ( isset( $sm_status ) && 'off' === $sm_status ) {
            return false;
        }
        
        if ( !empty($get_condition_array) || '' !== $get_condition_array || null !== $get_condition_array ) {
            $country_array = array();
            $product_array = array();
            $category_array = array();
            $tag_array = array();
            $user_array = array();
            $cart_total_array = array();
            $quantity_array = array();
            foreach ( $get_condition_array as $key => $value ) {
                if ( array_search( 'country', $value, true ) ) {
                    $country_array[$key] = $value;
                }
                if ( array_search( 'product', $value, true ) ) {
                    $product_array[$key] = $value;
                }
                if ( array_search( 'category', $value, true ) ) {
                    $category_array[$key] = $value;
                }
                if ( array_search( 'tag', $value, true ) ) {
                    $tag_array[$key] = $value;
                }
                if ( array_search( 'user', $value, true ) ) {
                    $user_array[$key] = $value;
                }
                if ( array_search( 'cart_total', $value, true ) ) {
                    $cart_total_array[$key] = $value;
                }
                if ( array_search( 'quantity', $value, true ) ) {
                    $quantity_array[$key] = $value;
                }
                //Check if is country exist
                
                if ( is_array( $country_array ) && isset( $country_array ) && !empty($country_array) && !empty($cart_product_ids_array) ) {
                    $country_passed = $this->afrsm_pro_match_country_rules( $country_array, $general_rule_match );
                    
                    if ( 'yes' === $country_passed ) {
                        $is_passed['has_fee_based_on_country'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_country'] = 'no';
                    }
                
                }
                
                //Check if is product exist
                
                if ( is_array( $product_array ) && isset( $product_array ) && !empty($product_array) && !empty($cart_product_ids_array) ) {
                    $product_passed = $this->afrsm_pro_match_simple_products_rule( $cart_product_ids_array, $product_array, $general_rule_match );
                    
                    if ( 'yes' === $product_passed ) {
                        $is_passed['has_fee_based_on_product'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_product'] = 'no';
                    }
                
                }
                
                //Check if is Category exist
                
                if ( is_array( $category_array ) && isset( $category_array ) && !empty($category_array) && !empty($cart_main_product_ids_array) ) {
                    $category_passed = $this->afrsm_pro_match_category_rule( $cart_main_product_ids_array, $category_array, $general_rule_match );
                    
                    if ( 'yes' === $category_passed ) {
                        $is_passed['has_fee_based_on_category'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_category'] = 'no';
                    }
                
                }
                
                //Check if is tag exist
                
                if ( is_array( $tag_array ) && isset( $tag_array ) && !empty($tag_array) && !empty($cart_main_product_ids_array) ) {
                    $tag_passed = $this->afrsm_pro_match_tag_rule( $cart_main_product_ids_array, $tag_array, $general_rule_match );
                    
                    if ( 'yes' === $tag_passed ) {
                        $is_passed['has_fee_based_on_tag'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_tag'] = 'no';
                    }
                
                }
                
                //Check if is user exist
                
                if ( is_array( $user_array ) && isset( $user_array ) && !empty($user_array) && !empty($cart_product_ids_array) ) {
                    $user_passed = $this->afrsm_pro_match_user_rule( $user_array, $general_rule_match );
                    
                    if ( 'yes' === $user_passed ) {
                        $is_passed['has_fee_based_on_user'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_user'] = 'no';
                    }
                
                }
                
                //Check if is Cart Subtotal (Before Discount) exist
                
                if ( is_array( $cart_total_array ) && isset( $cart_total_array ) && !empty($cart_total_array) && !empty($cart_product_ids_array) ) {
                    $cart_total_before_passed = $this->afrsm_pro_match_cart_subtotal_before_discount_rule( $wc_curr_version, $cart_total_array, $general_rule_match );
                    
                    if ( 'yes' === $cart_total_before_passed ) {
                        $is_passed['has_fee_based_on_cart_total_before'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_cart_total_before'] = 'no';
                    }
                
                }
                
                //Check if is quantity exist
                
                if ( is_array( $quantity_array ) && isset( $quantity_array ) && !empty($quantity_array) && !empty($cart_product_ids_array) ) {
                    $quantity_passed = $this->afrsm_pro_match_cart_total_cart_qty_rule( $cart_array, $quantity_array, $general_rule_match );
                    
                    if ( 'yes' === $quantity_passed ) {
                        $is_passed['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            
            }
            
            if ( isset( $is_passed ) && !empty($is_passed) && is_array( $is_passed ) ) {
                $fnispassed = array();
                foreach ( $is_passed as $val ) {
                    if ( '' !== $val ) {
                        $fnispassed[] = $val;
                    }
                }
                
                if ( 'all' === $general_rule_match ) {
                    
                    if ( in_array( 'no', $fnispassed, true ) ) {
                        $final_is_passed_general_rule['passed'] = 'no';
                    } else {
                        $final_is_passed_general_rule['passed'] = 'yes';
                    }
                
                } else {
                    
                    if ( in_array( 'yes', $fnispassed, true ) ) {
                        $final_is_passed_general_rule['passed'] = 'yes';
                    } else {
                        $final_is_passed_general_rule['passed'] = 'no';
                    }
                
                }
            
            }
        
        }
        
        
        if ( empty($final_is_passed_general_rule) || '' === $final_is_passed_general_rule || null === $final_is_passed_general_rule ) {
            $new_is_passed['passed'] = 'no';
        } else {
            
            if ( !empty($final_is_passed_general_rule) && in_array( 'no', $final_is_passed_general_rule, true ) ) {
                $new_is_passed['passed'] = 'no';
            } else {
                
                if ( empty($final_is_passed_general_rule) && in_array( '', $final_is_passed_general_rule, true ) ) {
                    $new_is_passed['passed'] = 'no';
                } else {
                    if ( !empty($final_is_passed_general_rule) && in_array( 'yes', $final_is_passed_general_rule, true ) ) {
                        $new_is_passed['passed'] = 'yes';
                    }
                }
            
            }
        
        }
        
        if ( isset( $new_is_passed ) && !empty($new_is_passed) && is_array( $new_is_passed ) ) {
            
            if ( !in_array( 'no', $new_is_passed, true ) ) {
                $final_condition_flag[] = 'yes';
            } else {
                $final_condition_flag[] = 'no';
            }
        
        }
        
        if ( empty($final_condition_flag) && $final_condition_flag === '' ) {
            return false;
        } else {
            
            if ( !empty($final_condition_flag) && in_array( 'no', $final_condition_flag, true ) ) {
                return false;
            } else {
                
                if ( empty($final_condition_flag) && in_array( '', $final_condition_flag, true ) ) {
                    return false;
                } else {
                    if ( !empty($final_condition_flag) && in_array( 'yes', $final_condition_flag, true ) ) {
                        return true;
                    }
                }
            
            }
        
        }
    
    }
    
    /**
     * Match country rules
     *
     * @param array $country_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     *
     * @uses WC_Customer::get_shipping_country()
     *
     * @since    3.4
     *
     */
    public function afrsm_pro_match_country_rules( $country_array, $general_rule_match )
    {
        $selected_country = WC()->customer->get_shipping_country();
        $is_passed = array();
        foreach ( $country_array as $key => $country ) {
            
            if ( 'is_equal_to' === $country['product_fees_conditions_is'] ) {
                if ( !empty($country['product_fees_conditions_values']) ) {
                    
                    if ( in_array( $selected_country, $country['product_fees_conditions_values'], true ) ) {
                        $is_passed[$key]['has_fee_based_on_country'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_country'] = 'no';
                    }
                
                }
                if ( empty($country['product_fees_conditions_values']) ) {
                    $is_passed[$key]['has_fee_based_on_country'] = 'yes';
                }
            }
            
            if ( 'not_in' === $country['product_fees_conditions_is'] ) {
                if ( !empty($country['product_fees_conditions_values']) ) {
                    
                    if ( in_array( $selected_country, $country['product_fees_conditions_values'], true ) || in_array( 'all', $country['product_fees_conditions_values'], true ) ) {
                        $is_passed[$key]['has_fee_based_on_country'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_country'] = 'yes';
                    }
                
                }
            }
        }
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_country', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match simple products rules
     *
     * @param array $cart_product_ids_array
     * @param array $product_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @since    3.4
     *
     * @uses afrsm_pro_fee_array_column_admin()
     *
     */
    public function afrsm_pro_match_simple_products_rule( $cart_product_ids_array, $product_array, $general_rule_match )
    {
        $is_passed = array();
        foreach ( $product_array as $key => $product ) {
            if ( 'is_equal_to' === $product['product_fees_conditions_is'] ) {
                if ( !empty($product['product_fees_conditions_values']) ) {
                    foreach ( $product['product_fees_conditions_values'] as $product_id ) {
                        settype( $product_id, 'integer' );
                        
                        if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
                            $is_passed[$key]['has_fee_based_on_product'] = 'yes';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_product'] = 'no';
                        }
                    
                    }
                }
            }
            if ( 'not_in' === $product['product_fees_conditions_is'] ) {
                if ( !empty($product['product_fees_conditions_values']) ) {
                    foreach ( $product['product_fees_conditions_values'] as $product_id ) {
                        settype( $product_id, 'integer' );
                        
                        if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
                            $is_passed[$key]['has_fee_based_on_product'] = 'no';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_product'] = 'yes';
                        }
                    
                    }
                }
            }
        }
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_product', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match category rules
     *
     * @param array $cart_product_ids_array
     * @param array $category_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @since    3.4
     *
     * @uses afrsm_pro_fee_array_column_admin()
     * @uses WC_Product class
     * @uses WC_Product::is_virtual()
     * @uses wp_get_post_terms()
     * @uses afrsm_pro_array_flatten()
     *
     */
    public function afrsm_pro_match_category_rule( $cart_product_ids_array, $category_array, $general_rule_match )
    {
        $is_passed = array();
        $cart_category_id_array = array();
        foreach ( $cart_product_ids_array as $product ) {
            $cart_product_category = wp_get_post_terms( $product, 'product_cat', array(
                'fields' => 'ids',
            ) );
            if ( isset( $cart_product_category ) && !empty($cart_product_category) && is_array( $cart_product_category ) ) {
                $cart_category_id_array[] = $cart_product_category;
            }
        }
        $get_cat_all = array_unique( $this->afrsm_pro_array_flatten( $cart_category_id_array ) );
        foreach ( $category_array as $key => $category ) {
            if ( 'is_equal_to' === $category['product_fees_conditions_is'] ) {
                if ( !empty($category['product_fees_conditions_values']) ) {
                    foreach ( $category['product_fees_conditions_values'] as $category_id ) {
                        settype( $category_id, 'integer' );
                        
                        if ( in_array( $category_id, $get_cat_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_category'] = 'yes';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_category'] = 'no';
                        }
                    
                    }
                }
            }
            if ( 'not_in' === $category['product_fees_conditions_is'] ) {
                if ( !empty($category['product_fees_conditions_values']) ) {
                    foreach ( $category['product_fees_conditions_values'] as $category_id ) {
                        settype( $category_id, 'integer' );
                        
                        if ( in_array( $category_id, $get_cat_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_category'] = 'no';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_category'] = 'yes';
                        }
                    
                    }
                }
            }
        }
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_category', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match tag rules
     *
     * @param array $cart_product_ids_array
     * @param array $tag_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @since    3.4
     *
     * @uses afrsm_pro_fee_array_column_admin()
     * @uses WC_Product class
     * @uses WC_Product::is_virtual()
     * @uses wp_get_post_terms()
     * @uses afrsm_pro_array_flatten()
     *
     */
    public function afrsm_pro_match_tag_rule( $cart_product_ids_array, $tag_array, $general_rule_match )
    {
        $tagid = array();
        $is_passed = array();
        foreach ( $cart_product_ids_array as $product ) {
            $cart_product_tag = wp_get_post_terms( $product, 'product_tag', array(
                'fields' => 'ids',
            ) );
            if ( isset( $cart_product_tag ) && !empty($cart_product_tag) && is_array( $cart_product_tag ) ) {
                $tagid[] = $cart_product_tag;
            }
        }
        $get_tag_all = array_unique( $this->afrsm_pro_array_flatten( $tagid ) );
        foreach ( $tag_array as $key => $tag ) {
            if ( 'is_equal_to' === $tag['product_fees_conditions_is'] ) {
                if ( !empty($tag['product_fees_conditions_values']) ) {
                    foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
                        settype( $tag_id, 'integer' );
                        
                        if ( in_array( $tag_id, $get_tag_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'yes';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'no';
                        }
                    
                    }
                }
            }
            if ( 'not_in' === $tag['product_fees_conditions_is'] ) {
                if ( !empty($tag['product_fees_conditions_values']) ) {
                    foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
                        settype( $tag_id, 'integer' );
                        
                        if ( in_array( $tag_id, $get_tag_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'no';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'yes';
                        }
                    
                    }
                }
            }
        }
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_tag', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match user rules
     *
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @uses get_current_user_id()
     * @since    3.4
     *
     * @uses is_user_logged_in()
     */
    public function afrsm_pro_match_user_rule( $user_array, $general_rule_match )
    {
        if ( !is_user_logged_in() ) {
            return false;
        }
        $current_user_id = get_current_user_id();
        $is_passed = array();
        foreach ( $user_array as $key => $user ) {
            $user['product_fees_conditions_values'] = array_map( 'intval', $user['product_fees_conditions_values'] );
            if ( 'is_equal_to' === $user['product_fees_conditions_is'] ) {
                
                if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
                    $is_passed[$key]['has_fee_based_on_user'] = 'yes';
                } else {
                    $is_passed[$key]['has_fee_based_on_user'] = 'no';
                }
            
            }
            if ( 'not_in' === $user['product_fees_conditions_is'] ) {
                
                if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
                    $is_passed[$key]['has_fee_based_on_user'] = 'no';
                } else {
                    $is_passed[$key]['has_fee_based_on_user'] = 'yes';
                }
            
            }
        }
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_user', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match rule based on cart subtotal before discount
     *
     * @param string $wc_curr_version
     * @param array $cart_total_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     *
     * @since    3.4
     *
     * @uses WC_Cart::get_subtotal()
     *
     */
    public function afrsm_pro_match_cart_subtotal_before_discount_rule( $wc_curr_version, $cart_total_array, $general_rule_match )
    {
        global  $woocommerce, $woocommerce_wpml ;
        
        if ( $wc_curr_version >= 3.0 ) {
            $total = $this->afrsm_pro_get_cart_subtotal();
        } else {
            $total = $woocommerce->cart->subtotal;
        }
        
        
        if ( isset( $woocommerce_wpml ) && !empty($woocommerce_wpml->multi_currency) ) {
            $new_total = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $total );
        } else {
            $new_total = $total;
        }
        
        $is_passed = array();
        foreach ( $cart_total_array as $key => $cart_total ) {
            settype( $cart_total['product_fees_conditions_values'], 'float' );
            if ( 'is_equal_to' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] === $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            if ( 'less_equal_to' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] >= $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            if ( 'less_then' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] > $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            if ( 'greater_equal_to' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] <= $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            
            if ( 'greater_then' === $cart_total['product_fees_conditions_is'] ) {
                $cart_total['product_fees_conditions_values'];
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] < $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            
            if ( 'not_in' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $new_total === $cart_total['product_fees_conditions_values'] ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    }
                
                }
            }
        }
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_cart_total', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match rule based on total cart quantity
     *
     * @param array $cart_array
     * @param array $quantity_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @since    3.4
     *
     * @uses WC_Cart::get_cart()
     *
     */
    public function afrsm_pro_match_cart_total_cart_qty_rule( $cart_array, $quantity_array, $general_rule_match )
    {
        $quantity_total = 0;
        foreach ( $cart_array as $woo_cart_item ) {
            if ( !$woo_cart_item['data']->is_virtual() ) {
                $quantity_total += $woo_cart_item['quantity'];
            }
        }
        $is_passed = array();
        foreach ( $quantity_array as $key => $quantity ) {
            settype( $quantity['product_fees_conditions_values'], 'integer' );
            if ( 'is_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'less_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] >= $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'less_then' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] > $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'greater_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] <= $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'greater_then' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] < $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'not_in' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    }
                
                }
            }
        }
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_quantity', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Find unique id based on given array
     *
     * @param array $is_passed
     * @param string $has_fee_based
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @since    3.6
     *
     */
    public function afrsm_pro_check_all_passed_general_rule( $is_passed, $has_fee_based, $general_rule_match )
    {
        $main_is_passed = 'no';
        $flag = array();
        
        if ( !empty($is_passed) ) {
            foreach ( $is_passed as $key => $is_passed_value ) {
                
                if ( 'yes' === $is_passed_value[$has_fee_based] ) {
                    $flag[$key] = true;
                } else {
                    $flag[$key] = false;
                }
            
            }
            
            if ( 'any' === $general_rule_match ) {
                
                if ( in_array( true, $flag, true ) ) {
                    $main_is_passed = 'yes';
                } else {
                    $main_is_passed = 'no';
                }
            
            } else {
                
                if ( in_array( false, $flag, true ) ) {
                    $main_is_passed = 'no';
                } else {
                    $main_is_passed = 'yes';
                }
            
            }
        
        }
        
        return $main_is_passed;
    }
    
    /**
     * Find unique id based on given array
     *
     * @param array $array
     *
     * @return array $result if $array is empty it will return false otherwise return array as $result
     * @since    1.0.0
     *
     */
    public function afrsm_pro_array_flatten( $array )
    {
        if ( !is_array( $array ) ) {
            return false;
        }
        $result = array();
        foreach ( $array as $key => $value ) {
            
            if ( is_array( $value ) ) {
                $result = array_merge( $result, $this->afrsm_pro_array_flatten( $value ) );
            } else {
                $result[$key] = $value;
            }
        
        }
        return $result;
    }
    
    /**
     * Display array column
     *
     * @param array $input
     * @param int $columnKey
     * @param int $indexKey
     *
     * @return array $array It will return array if any error generate then it will return false
     * @since  1.0.0
     *
     */
    public function afrsm_pro_fee_array_column_admin( array $input, $columnKey, $indexKey = null )
    {
        $array = array();
        foreach ( $input as $value ) {
            
            if ( !isset( $value[$columnKey] ) ) {
                wp_die( sprintf( esc_html_x( 'Key %d does not exist in array', esc_attr( $columnKey ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
                return false;
            }
            
            
            if ( is_null( $indexKey ) ) {
                $array[] = $value[$columnKey];
            } else {
                
                if ( !isset( $value[$indexKey] ) ) {
                    wp_die( sprintf( esc_html_x( 'Key %d does not exist in array', esc_attr( $indexKey ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
                    return false;
                }
                
                
                if ( !is_scalar( $value[$indexKey] ) ) {
                    wp_die( sprintf( esc_html_x( 'Key %d does not contain scalar value', esc_attr( $indexKey ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
                    return false;
                }
                
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        
        }
        return $array;
    }
    
    /**
     * Remove WooCommerce currency symbol
     *
     * @param float $price
     *
     * @return float $new_price2
     * @since  1.0.0
     *
     * @uses get_woocommerce_currency_symbol()
     *
     */
    public function afrsm_pro_remove_currency_symbol( $price )
    {
        $wc_currency_symbol = get_woocommerce_currency_symbol();
        $new_price = str_replace( $wc_currency_symbol, '', $price );
        $new_price2 = (double) preg_replace( '/[^.\\d]/', '', $new_price );
        return $new_price2;
    }
    
    /*
     * Get WooCommerce version number
     *
     * @since 1.0.0
     *
     * @return string if file is not exists then it will return null
     */
    function afrsm_pro_get_woo_version_number()
    {
        // If get_plugins() isn't available, require it
        if ( !function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        // Create the plugins folder and file variables
        $plugin_folder = get_plugins( '/' . 'woocommerce' );
        $plugin_file = 'woocommerce.php';
        // If the plugin version number is set, return it
        
        if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
            return $plugin_folder[$plugin_file]['Version'];
        } else {
            return null;
        }
    
    }
    
    /**
     * Save shipping order in shipping list section
     *
     * @since 1.0.0
     */
    public function afrsm_pro_sm_sort_order()
    {
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $get_smOrderArray = filter_input(
            INPUT_GET,
            'smOrderArray',
            FILTER_SANITIZE_NUMBER_INT,
            FILTER_REQUIRE_ARRAY
        );
        $smOrderArray = ( !empty($get_smOrderArray) ? array_map( 'sanitize_text_field', wp_unslash( $get_smOrderArray ) ) : '' );
        if ( isset( $smOrderArray ) && !empty($smOrderArray) ) {
            update_option( 'sm_sortable_order_' . $default_lang, $smOrderArray );
        }
        wp_die();
    }
    
    /**
     * Save master settings data
     *
     * @since 1.0.0
     */
    public function afrsm_pro_save_master_settings()
    {
        $get_shipping_display_mode = filter_input( INPUT_GET, 'shipping_display_mode', FILTER_SANITIZE_STRING );
        $get_chk_enable_logging = filter_input( INPUT_GET, 'chk_enable_logging', FILTER_SANITIZE_STRING );
        $shipping_display_mode = ( !empty($get_shipping_display_mode) ? sanitize_text_field( wp_unslash( $get_shipping_display_mode ) ) : '' );
        if ( isset( $shipping_display_mode ) && !empty($shipping_display_mode) ) {
            update_option( 'md_woocommerce_shipping_method_format', $shipping_display_mode );
        }
        if ( isset( $get_chk_enable_logging ) && !empty($get_chk_enable_logging) ) {
            update_option( 'chk_enable_logging', $get_chk_enable_logging );
        }
        wp_die();
    }
    
    /**
     * Display textfield and multiselect dropdown based on country, state, zone and etc
     *
     * @return string $html
     * @since 1.0.0
     *
     * @uses afrsm_pro_get_country_list()
     * @uses afrsm_pro_get_states_list__premium_only()
     * @uses afrsm_pro_get_zones_list__premium_only()
     * @uses afrsm_pro_get_product_list()
     * @uses afrsm_pro_get_varible_product_list__premium_only()
     * @uses afrsm_pro_get_category_list()
     * @uses afrsm_pro_get_tag_list()
     * @uses afrsm_pro_get_sku_list__premium_only()
     * @uses afrsm_pro_get_user_list()
     * @uses afrsm_pro_get_user_role_list__premium_only()
     * @uses afrsm_pro_get_coupon_list__premium_only()
     * @uses afrsm_pro_get_advance_flat_rate_class__premium_only()
     * @uses Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags()
     *
     */
    public function afrsm_pro_product_fees_conditions_values_ajax()
    {
        $get_condition = filter_input( INPUT_GET, 'condition', FILTER_SANITIZE_STRING );
        $get_count = filter_input( INPUT_GET, 'count', FILTER_SANITIZE_NUMBER_INT );
        $condition = ( isset( $get_condition ) ? sanitize_text_field( $get_condition ) : '' );
        $count = ( isset( $get_count ) ? sanitize_text_field( $get_count ) : '' );
        $html = '';
        
        if ( 'country' === $condition ) {
            $html .= wp_json_encode( $this->afrsm_pro_get_country_list( $count, [], true ) );
        } elseif ( 'product' === $condition ) {
            $html .= wp_json_encode( $this->afrsm_pro_get_product_list( $count, [], true ) );
        } elseif ( 'category' === $condition ) {
            $html .= wp_json_encode( $this->afrsm_pro_get_category_list( $count, [], true ) );
        } elseif ( 'tag' === $condition ) {
            $html .= wp_json_encode( $this->afrsm_pro_get_tag_list( $count, [], true ) );
        } elseif ( 'user' === $condition ) {
            $html .= wp_json_encode( $this->afrsm_pro_get_user_list( $count, [], true ) );
        } elseif ( 'cart_total' === $condition ) {
            $html .= 'input';
        } elseif ( 'quantity' === $condition ) {
            $html .= 'input';
        }
        
        echo  wp_kses( $html, Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() ) ;
        wp_die();
        // this is required to terminate immediately and return a proper response
    }
    
    /**
     * Get country list
     *
     * @param string $count
     * @param array $selected
     *
     * @return string $html
     * @uses WC_Countries() class
     *
     * @since  1.0.0
     *
     */
    public function afrsm_pro_get_country_list( $count = '', $selected = array(), $json = false )
    {
        $countries_obj = new WC_Countries();
        $getCountries = $countries_obj->__get( 'countries' );
        $html = '<select name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_country" multiple="multiple">';
        if ( !empty($getCountries) ) {
            foreach ( $getCountries as $code => $country ) {
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $code, $selected, true ) ? 'selected=selected' : '' );
                $html .= '<option value="' . esc_attr( $code ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $country ) . '</option>';
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->afrsm_pro_convert_array_to_json( $getCountries );
        }
        return $html;
    }
    
    /**
     * Get product list
     *
     * @param string $count
     * @param array $selected
     *
     * @return string $html
     * @uses afrsm_pro_get_default_langugae_with_sitpress()
     *
     * @since  1.0.0
     *
     */
    public function afrsm_pro_get_product_list( $count = '', $selected = array(), $json = false )
    {
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $get_all_products = new WP_Query( array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ) );
        $html = '<select id="product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_product product_fees_conditions_values_' . esc_attr( $count ) . '" multiple="multiple">';
        if ( isset( $get_all_products->posts ) && !empty($get_all_products->posts) ) {
            foreach ( $get_all_products->posts as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                
                if ( !$_product->is_virtual( 'yes' ) ) {
                    
                    if ( !empty($sitepress) ) {
                        $new_product_id = apply_filters(
                            'wpml_object_id',
                            $get_all_product->ID,
                            'product',
                            true,
                            $default_lang
                        );
                    } else {
                        $new_product_id = $get_all_product->ID;
                    }
                    
                    $selected = array_map( 'intval', $selected );
                    $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_product_id, $selected, true ) ? 'selected=selected' : '' );
                    if ( '' !== $selectedVal ) {
                        $html .= '<option value="' . esc_attr( $new_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $new_product_id ) . ' - ' . esc_html( get_the_title( $new_product_id ) ) . '</option>';
                    }
                }
            
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return [];
        }
        return $html;
    }
    
    /**
     * Get variable product list in Advance pricing rules
     *
     * @param string $count
     * @param array $selected
     *
     * @return string $html
     * @uses WC_Product::is_type()
     *
     * @since  3.4
     *
     * @uses afrsm_pro_get_default_langugae_with_sitpress()
     * @uses wc_get_product()
     */
    public function afrsm_pro_get_product_options( $count = '', $selected = array() )
    {
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $get_all_products = new WP_Query( array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ) );
        $baselang_variation_product_ids = array();
        $defaultlang_simple_product_ids = array();
        $html = '';
        if ( isset( $get_all_products->posts ) && !empty($get_all_products->posts) ) {
            foreach ( $get_all_products->posts as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                
                if ( !$_product->is_virtual( 'yes' ) ) {
                    
                    if ( $_product->is_type( 'variable' ) ) {
                        $variations = $_product->get_available_variations();
                        foreach ( $variations as $value ) {
                            
                            if ( !empty($sitepress) ) {
                                $defaultlang_variation_product_id = apply_filters(
                                    'wpml_object_id',
                                    $value['variation_id'],
                                    'product',
                                    true,
                                    $default_lang
                                );
                            } else {
                                $defaultlang_variation_product_id = $value['variation_id'];
                            }
                            
                            $baselang_variation_product_ids[] = $defaultlang_variation_product_id;
                        }
                    }
                    
                    
                    if ( $_product->is_type( 'simple' ) ) {
                        
                        if ( !empty($sitepress) ) {
                            $defaultlang_simple_product_id = apply_filters(
                                'wpml_object_id',
                                $get_all_product->ID,
                                'product',
                                true,
                                $default_lang
                            );
                        } else {
                            $defaultlang_simple_product_id = $get_all_product->ID;
                        }
                        
                        $defaultlang_simple_product_ids[] = $defaultlang_simple_product_id;
                    }
                
                }
            
            }
        }
        $baselang_product_ids = array_merge( $baselang_variation_product_ids, $defaultlang_simple_product_ids );
        if ( isset( $baselang_product_ids ) && !empty($baselang_product_ids) ) {
            foreach ( $baselang_product_ids as $baselang_product_id ) {
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $baselang_product_id, $selected, true ) ? 'selected=selected' : '' );
                if ( '' !== $selectedVal ) {
                    $html .= '<option value="' . esc_attr( $baselang_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $baselang_product_id ) . ' - ' . esc_html( get_the_title( $baselang_product_id ) ) . '</option>';
                }
            }
        }
        return $html;
    }
    
    /**
     * Get category list in Advance pricing rules
     *
     * @param array $selected
     *
     * @return string $html
     * @since  3.4
     *
     * @uses afrsm_pro_get_default_langugae_with_sitpress()
     *
     */
    public function afrsm_pro_get_category_options( $selected = array(), $json )
    {
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $filter_category_list = [];
        $taxonomy = 'product_cat';
        $post_status = 'publish';
        $orderby = 'name';
        $hierarchical = 1;
        $empty = 0;
        $args = array(
            'post_type'      => 'product',
            'post_status'    => $post_status,
            'taxonomy'       => $taxonomy,
            'orderby'        => $orderby,
            'hierarchical'   => $hierarchical,
            'hide_empty'     => $empty,
            'posts_per_page' => -1,
        );
        $get_all_categories = get_categories( $args );
        $html = '';
        if ( isset( $get_all_categories ) && !empty($get_all_categories) ) {
            foreach ( $get_all_categories as $get_all_category ) {
                
                if ( $get_all_category ) {
                    
                    if ( !empty($sitepress) ) {
                        $new_cat_id = apply_filters(
                            'wpml_object_id',
                            $get_all_category->term_id,
                            'product_cat',
                            true,
                            $default_lang
                        );
                    } else {
                        $new_cat_id = $get_all_category->term_id;
                    }
                    
                    $category = get_term_by( 'id', $new_cat_id, 'product_cat' );
                    $parent_category = get_term_by( 'id', $category->parent, 'product_cat' );
                    
                    if ( !empty($selected) ) {
                        $selected = array_map( 'intval', $selected );
                        $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_cat_id, $selected, true ) ? 'selected=selected' : '' );
                        
                        if ( $category->parent > 0 ) {
                            $html .= '<option value=' . $category->term_id . ' ' . $selectedVal . '>' . '' . $parent_category->name . '->' . $category->name . '</option>';
                        } else {
                            $html .= '<option value=' . $category->term_id . ' ' . $selectedVal . '>' . $category->name . '</option>';
                        }
                    
                    } else {
                        
                        if ( $category->parent > 0 ) {
                            $filter_category_list[$category->term_id] = $parent_category->name . '->' . $category->name;
                        } else {
                            $filter_category_list[$category->term_id] = $category->name;
                        }
                    
                    }
                
                }
            
            }
        }
        
        if ( true === $json ) {
            return wp_json_encode( $this->afrsm_pro_convert_array_to_json( $filter_category_list ) );
        } else {
            return $html;
        }
    
    }
    
    /**
     * Get category list in Shipping Method Rules
     *
     * @param string $count
     * @param array $selected
     *
     * @return string $html
     * @uses get_term_by()
     *
     * @since  1.0.0
     *
     * @uses afrsm_pro_get_default_langugae_with_sitpress()
     * @uses get_categories()
     */
    public function afrsm_pro_get_category_list( $count = '', $selected = array(), $json = false )
    {
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $filter_categories = [];
        $taxonomy = 'product_cat';
        $post_status = 'publish';
        $orderby = 'name';
        $hierarchical = 1;
        $empty = 0;
        $args = array(
            'post_type'      => 'product',
            'post_status'    => $post_status,
            'taxonomy'       => $taxonomy,
            'orderby'        => $orderby,
            'hierarchical'   => $hierarchical,
            'hide_empty'     => $empty,
            'posts_per_page' => -1,
        );
        $get_all_categories = get_categories( $args );
        $html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_cat_product" multiple="multiple">';
        if ( isset( $get_all_categories ) && !empty($get_all_categories) ) {
            foreach ( $get_all_categories as $get_all_category ) {
                
                if ( $get_all_category ) {
                    
                    if ( !empty($sitepress) ) {
                        $new_cat_id = apply_filters(
                            'wpml_object_id',
                            $get_all_category->term_id,
                            'product_cat',
                            true,
                            $default_lang
                        );
                    } else {
                        $new_cat_id = $get_all_category->term_id;
                    }
                    
                    $selected = array_map( 'intval', $selected );
                    $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_cat_id, $selected, true ) ? 'selected=selected' : '' );
                    $category = get_term_by( 'id', $new_cat_id, 'product_cat' );
                    $parent_category = get_term_by( 'id', $category->parent, 'product_cat' );
                    
                    if ( $category->parent > 0 ) {
                        $html .= '<option value=' . esc_attr( $category->term_id ) . ' ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $parent_category->name ) . '->' . esc_html( $category->name ) . '</option>';
                        $filter_categories[$category->term_id] = '#' . $parent_category->name . '->' . $category->name;
                    } else {
                        $html .= '<option value=' . esc_attr( $category->term_id ) . ' ' . esc_attr( $selectedVal ) . '>' . esc_html( $category->name ) . '</option>';
                        $filter_categories[$category->term_id] = $category->name;
                    }
                
                }
            
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->afrsm_pro_convert_array_to_json( $filter_categories );
        }
        return $html;
    }
    
    /**
     * Get tag list in Shipping Method Rules
     *
     * @param string $count
     * @param array $selected
     *
     * @return string $html
     * @since  1.0.0
     *
     * @uses afrsm_pro_get_default_langugae_with_sitpress()
     * @uses get_term_by()
     *
     */
    public function afrsm_pro_get_tag_list( $count = '', $selected = array(), $json = false )
    {
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $filter_tags = [];
        $taxonomy = 'product_tag';
        $orderby = 'name';
        $hierarchical = 1;
        $empty = 0;
        $args = array(
            'post_type'        => 'product',
            'post_status'      => 'publish',
            'taxonomy'         => $taxonomy,
            'orderby'          => $orderby,
            'hierarchical'     => $hierarchical,
            'hide_empty'       => $empty,
            'posts_per_page'   => -1,
            'suppress_filters' => false,
        );
        $get_all_tags = get_categories( $args );
        $html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_tag_product" multiple="multiple">';
        if ( isset( $get_all_tags ) && !empty($get_all_tags) ) {
            foreach ( $get_all_tags as $get_all_tag ) {
                
                if ( $get_all_tag ) {
                    
                    if ( !empty($sitepress) ) {
                        $new_tag_id = apply_filters(
                            'wpml_object_id',
                            $get_all_tag->term_id,
                            'product_tag',
                            true,
                            $default_lang
                        );
                    } else {
                        $new_tag_id = $get_all_tag->term_id;
                    }
                    
                    $selected = array_map( 'intval', $selected );
                    $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_tag_id, $selected, true ) ? 'selected=selected' : '' );
                    $tag = get_term_by( 'id', $new_tag_id, 'product_tag' );
                    $html .= '<option value="' . esc_attr( $tag->term_id ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $tag->name ) . '</option>';
                    $filter_tags[$tag->term_id] = $tag->name;
                }
            
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->afrsm_pro_convert_array_to_json( $filter_tags );
        }
        return $html;
    }
    
    /**
     * Get user list in Shipping Method Rules
     *
     * @param string $count
     * @param array $selected
     *
     * @return string $html
     * @since  1.0.0
     *
     */
    public function afrsm_pro_get_user_list( $count = '', $selected = array(), $json = false )
    {
        $filter_users = [];
        $get_all_users = get_users();
        $html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_user" multiple="multiple">';
        if ( isset( $get_all_users ) && !empty($get_all_users) ) {
            foreach ( $get_all_users as $get_all_user ) {
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $get_all_user->data->ID, $selected, true ) ? 'selected=selected' : '' );
                $html .= '<option value="' . esc_attr( $get_all_user->data->ID ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $get_all_user->data->user_login ) . '</option>';
                $filter_users[$get_all_user->data->ID] = $get_all_user->data->user_login;
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->afrsm_pro_convert_array_to_json( $filter_users );
        }
        return $html;
    }
    
    /**
     * Display product list based product specific option
     *
     * @return string $html
     * @uses afrsm_pro_get_default_langugae_with_sitpress()
     * @uses Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags()
     *
     * @since  1.0.0
     *
     */
    public function afrsm_pro_product_fees_conditions_values_product_ajax()
    {
        global  $sitepress ;
        $json = true;
        $filter_product_list = [];
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $request_value = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_STRING );
        $post_value = ( isset( $request_value ) ? sanitize_text_field( $request_value ) : '' );
        $baselang_product_ids = array();
        function afrsm_pro_posts_where( $where, $wp_query )
        {
            global  $wpdb ;
            $search_term = $wp_query->get( 'search_pro_title' );
            
            if ( !empty($search_term) ) {
                $search_term_like = $wpdb->esc_like( $search_term );
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
            }
            
            return $where;
        }
        
        $product_args = array(
            'post_type'        => 'product',
            'posts_per_page'   => -1,
            'search_pro_title' => $post_value,
            'post_status'      => 'publish',
            'orderby'          => 'title',
            'order'            => 'ASC',
        );
        add_filter(
            'posts_where',
            'afrsm_pro_posts_where',
            10,
            2
        );
        $get_wp_query = new WP_Query( $product_args );
        remove_filter(
            'posts_where',
            'afrsm_pro_posts_where',
            10,
            2
        );
        $get_all_products = $get_wp_query->posts;
        if ( isset( $get_all_products ) && !empty($get_all_products) ) {
            foreach ( $get_all_products as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                if ( !$_product->is_virtual( 'yes' ) ) {
                    
                    if ( $_product->is_type( 'simple' ) ) {
                        
                        if ( !empty($sitepress) ) {
                            $defaultlang_product_id = apply_filters(
                                'wpml_object_id',
                                $get_all_product->ID,
                                'product',
                                true,
                                $default_lang
                            );
                        } else {
                            $defaultlang_product_id = $get_all_product->ID;
                        }
                        
                        $baselang_product_ids[] = $defaultlang_product_id;
                    }
                
                }
            }
        }
        $html = '';
        if ( isset( $baselang_product_ids ) && !empty($baselang_product_ids) ) {
            foreach ( $baselang_product_ids as $baselang_product_id ) {
                $html .= '<option value="' . esc_attr( $baselang_product_id ) . '">' . '#' . esc_html( $baselang_product_id ) . ' - ' . esc_html( get_the_title( $baselang_product_id ) ) . '</option>';
                $filter_product_list[] = array( $baselang_product_id, get_the_title( $baselang_product_id ) );
            }
        }
        
        if ( $json ) {
            echo  wp_json_encode( $filter_product_list ) ;
            wp_die();
        }
        
        echo  wp_kses( $html, Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() ) ;
        wp_die();
    }
    
    /**
     * Delete multiple shipping method
     *
     * @return string $result
     * @uses wp_delete_post()
     *
     * @since  1.0.0
     *
     */
    public function afrsm_pro_wc_multiple_delete_shipping_method()
    {
        check_ajax_referer( 'dsm_nonce', 'nonce' );
        $result = 0;
        $get_allVals = filter_input(
            INPUT_GET,
            'allVals',
            FILTER_SANITIZE_NUMBER_INT,
            FILTER_REQUIRE_ARRAY
        );
        $allVals = ( !empty($get_allVals) ? array_map( 'sanitize_text_field', wp_unslash( $get_allVals ) ) : array() );
        if ( !empty($allVals) ) {
            foreach ( $allVals as $val ) {
                wp_delete_post( $val );
                $result = 1;
            }
        }
        echo  (int) $result ;
        wp_die();
    }
    
    /**
     * Count total shipping method
     *
     * @return int $count_method
     * @since    3.5
     *
     */
    public static function afrsm_pro_sm_count_method()
    {
        $shipping_method_args = array(
            'post_type'      => self::afrsm_shipping_post_type,
            'post_status'    => array( 'publish', 'draft' ),
            'posts_per_page' => -1,
            'orderby'        => 'ID',
            'order'          => 'DESC',
        );
        $sm_post_query = new WP_Query( $shipping_method_args );
        $shipping_method_list = $sm_post_query->posts;
        return count( $shipping_method_list );
    }
    
    /**
     * Save shipping method
     *
     * @param array $post
     *
     * @return bool false if post is empty otherwise it will redirect to shipping method list
     * @since  1.0.0
     *
     * @uses update_post_meta()
     *
     */
    function afrsm_pro_fees_conditions_save( $post )
    {
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        if ( empty($post) ) {
            return false;
        }
        $action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
        $post_type = filter_input( INPUT_POST, 'post_type', FILTER_SANITIZE_STRING );
        $afrsm_pro_conditions_save = filter_input( INPUT_POST, 'afrsm_pro_conditions_save', FILTER_SANITIZE_STRING );
        
        if ( isset( $post_type ) && self::afrsm_shipping_post_type === sanitize_text_field( $post['post_type'] ) && wp_verify_nonce( sanitize_text_field( $afrsm_pro_conditions_save ), 'afrsm_pro_save_action' ) ) {
            $method_id = filter_input( INPUT_POST, 'fee_post_id', FILTER_SANITIZE_NUMBER_INT );
            $fees = filter_input(
                INPUT_POST,
                'fees',
                FILTER_SANITIZE_STRING,
                FILTER_REQUIRE_ARRAY
            );
            $sm_status = filter_input( INPUT_POST, 'sm_status', FILTER_SANITIZE_STRING );
            $fee_settings_product_fee_title = filter_input( INPUT_POST, 'fee_settings_product_fee_title', FILTER_SANITIZE_STRING );
            $get_condition_key = filter_input(
                INPUT_POST,
                'condition_key',
                FILTER_SANITIZE_STRING,
                FILTER_REQUIRE_ARRAY
            );
            $get_sm_product_cost = filter_input( INPUT_POST, 'sm_product_cost', FILTER_SANITIZE_STRING );
            $get_sm_tooltip_desc = filter_input( INPUT_POST, 'sm_tooltip_desc', FILTER_SANITIZE_STRING );
            $get_sm_select_taxable = filter_input( INPUT_POST, 'sm_select_taxable', FILTER_SANITIZE_STRING );
            $get_sm_extra_cost = filter_input(
                INPUT_POST,
                'sm_extra_cost',
                FILTER_SANITIZE_STRING,
                FILTER_REQUIRE_ARRAY
            );
            $get_sm_extra_cost_calculation_type = filter_input( INPUT_POST, 'sm_extra_cost_calculation_type', FILTER_SANITIZE_STRING );
            $sm_product_cost = ( isset( $get_sm_product_cost ) ? sanitize_text_field( $get_sm_product_cost ) : '' );
            $sm_tooltip_desc = ( isset( $get_sm_tooltip_desc ) ? sanitize_textarea_field( $get_sm_tooltip_desc ) : '' );
            $sm_select_taxable = ( isset( $get_sm_select_taxable ) ? sanitize_text_field( $get_sm_select_taxable ) : '' );
            $sm_extra_cost = ( isset( $get_sm_extra_cost ) ? array_map( 'sanitize_text_field', $get_sm_extra_cost ) : array() );
            $sm_extra_cost_calculation_type = ( isset( $get_sm_extra_cost_calculation_type ) ? sanitize_text_field( $get_sm_extra_cost_calculation_type ) : '' );
            $shipping_method_count = self::afrsm_pro_sm_count_method();
            settype( $method_id, 'integer' );
            
            if ( isset( $sm_status ) ) {
                $post_status = 'publish';
            } else {
                $post_status = 'draft';
            }
            
            
            if ( '' !== $method_id && 0 !== $method_id ) {
                $fee_post = array(
                    'ID'          => $method_id,
                    'post_title'  => sanitize_text_field( $fee_settings_product_fee_title ),
                    'post_status' => $post_status,
                    'menu_order'  => $shipping_method_count + 1,
                    'post_type'   => self::afrsm_shipping_post_type,
                );
                $method_id = wp_update_post( $fee_post );
            } else {
                $fee_post = array(
                    'post_title'  => sanitize_text_field( $fee_settings_product_fee_title ),
                    'post_status' => $post_status,
                    'menu_order'  => $shipping_method_count + 1,
                    'post_type'   => self::afrsm_shipping_post_type,
                );
                $method_id = wp_insert_post( $fee_post );
            }
            
            
            if ( '' !== $method_id && 0 !== $method_id ) {
                
                if ( $method_id > 0 ) {
                    $feesArray = array();
                    $conditions_values_array = array();
                    $condition_key = ( isset( $get_condition_key ) ? $get_condition_key : array() );
                    $fees_conditions = $fees['product_fees_conditions_condition'];
                    $conditions_is = $fees['product_fees_conditions_is'];
                    $conditions_values = ( isset( $fees['product_fees_conditions_values'] ) && !empty($fees['product_fees_conditions_values']) ? $fees['product_fees_conditions_values'] : array() );
                    $size = count( $fees_conditions );
                    foreach ( array_keys( $condition_key ) as $key ) {
                        if ( !array_key_exists( $key, $conditions_values ) ) {
                            $conditions_values[$key] = array();
                        }
                    }
                    uksort( $conditions_values, 'strnatcmp' );
                    foreach ( $conditions_values as $v ) {
                        $conditions_values_array[] = $v;
                    }
                    for ( $i = 0 ;  $i < $size ;  $i++ ) {
                        $feesArray[] = array(
                            'product_fees_conditions_condition' => $fees_conditions[$i],
                            'product_fees_conditions_is'        => $conditions_is[$i],
                            'product_fees_conditions_values'    => $conditions_values_array[$i],
                        );
                    }
                    update_post_meta( $method_id, 'sm_product_cost', $sm_product_cost );
                    update_post_meta( $method_id, 'sm_tooltip_desc', $sm_tooltip_desc );
                    update_post_meta( $method_id, 'sm_select_taxable', $sm_select_taxable );
                    update_post_meta( $method_id, 'sm_metabox', $feesArray );
                    update_post_meta( $method_id, 'sm_extra_cost', $sm_extra_cost );
                    update_post_meta( $method_id, 'sm_extra_cost_calculation_type', $sm_extra_cost_calculation_type );
                    
                    if ( 'edit' !== $action ) {
                        $getSortOrder = get_option( 'sm_sortable_order_' . $default_lang );
                        
                        if ( !empty($getSortOrder) ) {
                            foreach ( $getSortOrder as $getSortOrder_id ) {
                                settype( $getSortOrder_id, 'integer' );
                            }
                            array_unshift( $getSortOrder, $method_id );
                        }
                        
                        update_option( 'sm_sortable_order_' . $default_lang, $getSortOrder );
                    }
                
                }
            
            } else {
                echo  '<div class="updated error"><p>' . esc_html__( 'Error saving shipping method.', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</p></div>' ;
                return false;
            }
            
            $afrsmnonce = wp_create_nonce( 'afrsmnonce' );
            wp_safe_redirect( add_query_arg( array(
                'page'     => 'afrsm-pro-list',
                '_wpnonce' => esc_attr( $afrsmnonce ),
            ), admin_url( 'admin.php' ) ) );
            exit;
        }
    
    }
    
    /**
     * Review message in footer
     *
     * @return string
     * @since  1.0.0
     *
     */
    public function afrsm_pro_admin_footer_review()
    {
        $url = '';
        $url = esc_url( 'https://wordpress.org/plugins/woo-extra-flat-rate/#reviews' );
        echo  sprintf( wp_kses( __( 'If you like <strong>Advanced Flat Rate Shipping For WooCommerce</strong> plugin, please leave us &#9733;&#9733;&#9733;&#9733;&#9733; ratings on <a href="%1$s" target="_blank">DotStore</a>.', 'advanced-flat-rate-shipping-for-woocommerce' ), array(
            'strong' => array(),
            'a'      => array(
            'href' => array(),
        ),
        ) ), $url ) ;
    }
    
    /**
     * Clone shipping method
     *
     * @return string true if current_shipping_id is empty then it will give message.
     * @uses get_post()
     * @uses wp_get_current_user()
     * @uses wp_insert_post()
     *
     * @since  3.4
     *
     */
    public function afrsm_pro_clone_shipping_method()
    {
        /* Check for post request */
        $get_current_shipping_id = filter_input( INPUT_GET, 'current_shipping_id', FILTER_SANITIZE_NUMBER_INT );
        $get_post_id = ( isset( $get_current_shipping_id ) ? absint( $get_current_shipping_id ) : '' );
        
        if ( empty($get_post_id) ) {
            echo  sprintf( wp_kses( __( '<strong>No post to duplicate has been supplied!</strong>', 'advanced-flat-rate-shipping-for-woocommerce' ), array(
                'strong' => array(),
            ) ) ) ;
            wp_die();
        }
        
        /* End of if */
        /* Get the original post id */
        
        if ( !empty($get_post_id) || '' !== $get_post_id ) {
            /* Get all the original post data */
            $post = get_post( $get_post_id );
            /* Get current user and make it new post user (duplicate post) */
            $current_user = wp_get_current_user();
            $new_post_author = $current_user->ID;
            /* If post data exists, duplicate the data into new duplicate post */
            
            if ( isset( $post ) && null !== $post ) {
                /* New post data array */
                $args = array(
                    'comment_status' => $post->comment_status,
                    'ping_status'    => $post->ping_status,
                    'post_author'    => $new_post_author,
                    'post_content'   => $post->post_content,
                    'post_excerpt'   => $post->post_excerpt,
                    'post_name'      => $post->post_name,
                    'post_parent'    => $post->post_parent,
                    'post_password'  => $post->post_password,
                    'post_status'    => 'draft',
                    'post_title'     => $post->post_title . '-duplicate',
                    'post_type'      => self::afrsm_shipping_post_type,
                    'to_ping'        => $post->to_ping,
                    'menu_order'     => $post->menu_order,
                );
                /* Duplicate the post by wp_insert_post() function */
                $new_post_id = wp_insert_post( $args );
                /* Duplicate all post meta-data */
                $post_meta_data = get_post_meta( $get_post_id );
                if ( 0 !== count( $post_meta_data ) ) {
                    foreach ( $post_meta_data as $meta_key => $meta_data ) {
                        if ( '_wp_old_slug' === $meta_key ) {
                            continue;
                        }
                        $meta_value = maybe_unserialize( $meta_data[0] );
                        update_post_meta( $new_post_id, $meta_key, $meta_value );
                    }
                }
            }
            
            $afrsmnonce = wp_create_nonce( 'afrsmnonce' );
            $redirect_url = add_query_arg( array(
                'page'     => 'afrsm-pro-edit-shipping',
                'id'       => $new_post_id,
                'action'   => 'edit',
                '_wpnonce' => esc_attr( $afrsmnonce ),
            ), admin_url( 'admin.php' ) );
            echo  wp_json_encode( array( true, $redirect_url ) ) ;
        }
        
        wp_die();
    }
    
    /**
     * Change shipping status from list of shipping method
     *
     * @since  3.4
     *
     * @uses update_post_meta()
     *
     * if current_shipping_id is empty then it will give message.
     */
    public function afrsm_pro_change_status_from_list_section()
    {
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        /* Check for post request */
        $get_current_shipping_id = filter_input( INPUT_GET, 'current_shipping_id', FILTER_SANITIZE_NUMBER_INT );
        
        if ( !empty($sitepress) ) {
            $get_current_shipping_id = apply_filters(
                'wpml_object_id',
                $get_current_shipping_id,
                'product',
                true,
                $default_lang
            );
        } else {
            $get_current_shipping_id = $get_current_shipping_id;
        }
        
        $get_current_value = filter_input( INPUT_GET, 'current_value', FILTER_SANITIZE_STRING );
        $get_post_id = ( isset( $get_current_shipping_id ) ? absint( $get_current_shipping_id ) : '' );
        
        if ( empty($get_post_id) ) {
            echo  '<strong>' . esc_html__( 'Something went wrong', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</strong>' ;
            wp_die();
        }
        
        $current_value = ( isset( $get_current_value ) ? sanitize_text_field( $get_current_value ) : '' );
        
        if ( 'true' === $current_value ) {
            $post_args = array(
                'ID'          => $get_post_id,
                'post_status' => 'publish',
                'post_type'   => self::afrsm_shipping_post_type,
            );
            $post_update = wp_update_post( $post_args );
            update_post_meta( $get_post_id, 'sm_status', 'on' );
        } else {
            $post_args = array(
                'ID'          => $get_post_id,
                'post_status' => 'draft',
                'post_type'   => self::afrsm_shipping_post_type,
            );
            $post_update = wp_update_post( $post_args );
            update_post_meta( $get_post_id, 'sm_status', 'off' );
        }
        
        
        if ( !empty($post_update) ) {
            echo  esc_html__( 'Shipping status changed successfully.', 'advanced-flat-rate-shipping-for-woocommerce' ) ;
        } else {
            echo  esc_html__( 'Something went wrong', 'advanced-flat-rate-shipping-for-woocommerce' ) ;
        }
        
        wp_die();
    }
    
    /**
     * Get default site language
     *
     * @return string $default_lang
     *
     * @since  3.4
     *
     */
    public function afrsm_pro_get_default_langugae_with_sitpress()
    {
        global  $sitepress ;
        
        if ( !empty($sitepress) ) {
            $default_lang = $sitepress->get_current_language();
        } else {
            $default_lang = $this->afrsm_pro_get_current_site_language();
        }
        
        return $default_lang;
    }
    
    /**
     * Get AFRSM shipping method
     *
     * @param string $args
     *
     * @return string $default_lang
     *
     * @since  3.4
     *
     */
    public static function afrsm_pro_get_shipping_method( $args )
    {
        $sm_args = array(
            'post_type'        => self::afrsm_shipping_post_type,
            'posts_per_page'   => -1,
            'orderby'          => 'menu_order',
            'order'            => 'ASC',
            'suppress_filters' => false,
        );
        if ( 'not_list' === $args ) {
            $sm_args['post_status'] = 'publish';
        }
        $get_all_shipping = new WP_Query( $sm_args );
        $get_all_shipping = $get_all_shipping->get_posts();
        return $get_all_shipping;
    }
    
    /**
     * Convert array to json
     *
     * @param array $arr
     *
     * @return array $filter_data
     * @since 1.0.0
     *
     */
    public function afrsm_pro_convert_array_to_json( $arr )
    {
        $filter_data = [];
        foreach ( $arr as $key => $value ) {
            $option = [];
            $option['name'] = $value;
            $option['attributes']['value'] = $key;
            $filter_data[] = $option;
        }
        return $filter_data;
    }
    
    /**
     * Get product id and variation id from cart
     *
     * @param string $sitepress
     * @param string $default_lang
     *
     * @return array $cart_main_product_ids_array
     * @uses afrsm_pro_get_cart();
     *
     * @since 1.0.0
     *
     */
    public function afrsm_pro_get_main_prd_id( $sitepress, $default_lang )
    {
        $cart_array = $this->afrsm_pro_get_cart();
        $cart_main_product_ids_array = array();
        foreach ( $cart_array as $woo_cart_item ) {
            $_product = wc_get_product( $woo_cart_item['product_id'] );
            if ( !$_product->is_virtual( 'yes' ) ) {
                
                if ( !empty($sitepress) ) {
                    $cart_main_product_ids_array[] = apply_filters(
                        'wpml_object_id',
                        $woo_cart_item['product_id'],
                        'product',
                        true,
                        $default_lang
                    );
                } else {
                    $cart_main_product_ids_array[] = $woo_cart_item['product_id'];
                }
            
            }
        }
        return $cart_main_product_ids_array;
    }
    
    /**
     * Get product id and variation id from cart
     *
     * @param string $sitepress
     * @param string $default_lang
     *
     * @return array $cart_product_ids_array
     * @uses afrsm_pro_get_cart();
     *
     * @since 1.0.0
     *
     */
    public function afrsm_pro_get_prd_var_id( $sitepress, $default_lang )
    {
        $cart_array = $this->afrsm_pro_get_cart();
        $cart_product_ids_array = array();
        foreach ( $cart_array as $woo_cart_item ) {
            $_product = wc_get_product( $woo_cart_item['product_id'] );
            $_product_simp_var_id = 'product_id';
            
            if ( !$_product->is_virtual( 'yes' ) ) {
                if ( $_product->is_type( 'variable' ) ) {
                    
                    if ( !empty($sitepress) ) {
                        $cart_product_ids_array[] = apply_filters(
                            'wpml_object_id',
                            $woo_cart_item[$_product_simp_var_id],
                            'product',
                            true,
                            $default_lang
                        );
                    } else {
                        $cart_product_ids_array[] = $woo_cart_item[$_product_simp_var_id];
                    }
                
                }
                if ( $_product->is_type( 'simple' ) ) {
                    
                    if ( !empty($sitepress) ) {
                        $cart_product_ids_array[] = apply_filters(
                            'wpml_object_id',
                            $woo_cart_item['product_id'],
                            'product',
                            true,
                            $default_lang
                        );
                    } else {
                        $cart_product_ids_array[] = $woo_cart_item['product_id'];
                    }
                
                }
            }
        
        }
        return $cart_product_ids_array;
    }
    
    /**
     * Get product id and variation id from cart
     *
     * @return array $cart_array
     * @since 1.0.0
     *
     */
    public function afrsm_pro_get_cart()
    {
        $cart_array = WC()->cart->get_cart();
        return $cart_array;
    }
    
    /**
     * Get current site langugae
     *
     * @return string $default_lang
     * @since 1.0.0
     *
     */
    public function afrsm_pro_get_current_site_language()
    {
        $get_site_language = get_bloginfo( 'language' );
        
        if ( false !== strpos( $get_site_language, '-' ) ) {
            $get_site_language_explode = explode( '-', $get_site_language );
            $default_lang = $get_site_language_explode[0];
        } else {
            $default_lang = $get_site_language;
        }
        
        return $default_lang;
    }
    
    /**
     * Remove section from shipping settings because we have added new menu in woocommece section
     *
     * @param array $sections
     *
     * @return array $sections
     *
     * @since    1.0.0
     */
    public function afrsm_pro_remove_section( $sections )
    {
        unset( $sections['advanced_flat_rate_shipping'], $sections['forceall'] );
        return $sections;
    }
    
    /**
     * Get cart subtotal
     *
     * @return float $subtotal
     *
     * @since    3.6
     */
    public function afrsm_pro_get_cart_subtotal()
    {
        $get_customer = WC()->cart->get_customer();
        $get_customer_vat_exempt = WC()->customer->get_is_vat_exempt();
        $tax_display_cart = WC()->cart->tax_display_cart;
        $wc_prices_include_tax = wc_prices_include_tax();
        $tax_enable = wc_tax_enabled();
        $cart_subtotal = 0;
        
        if ( true === $tax_enable ) {
            
            if ( true === $wc_prices_include_tax ) {
                
                if ( 'incl' === $tax_display_cart && !($get_customer && $get_customer_vat_exempt) ) {
                    $cart_subtotal += WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
                } else {
                    $cart_subtotal += WC()->cart->get_subtotal();
                }
            
            } else {
                
                if ( 'incl' === $tax_display_cart && !($get_customer && $get_customer_vat_exempt) ) {
                    $cart_subtotal += WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
                } else {
                    $cart_subtotal += WC()->cart->get_subtotal();
                }
            
            }
        
        } else {
            $cart_subtotal += WC()->cart->get_subtotal();
        }
        
        return $cart_subtotal;
    }
    
    /**
     * Fetch slug based on id
     *
     * @since    3.6.1
     */
    public function afrsm_pro_fetch_slug( $id_array, $condition )
    {
        $return_array = array();
        if ( !empty($id_array) ) {
            foreach ( $id_array as $key => $ids ) {
                if ( !empty($ids) ) {
                    
                    if ( 'product' === $condition || 'variableproduct' === $condition || 'cpp' === $condition || 'zone' === $condition ) {
                        $get_posts = get_post( $ids );
                        if ( !empty($get_posts) ) {
                            $return_array[] = $get_posts->post_name;
                        }
                    } elseif ( 'category' === $condition || 'cpc' === $condition ) {
                        $term = get_term( $ids, 'product_cat' );
                        if ( !empty($term) ) {
                            $return_array[] = $term->slug;
                        }
                    } elseif ( 'tag' === $condition ) {
                        $tag = get_term( $ids, 'product_tag' );
                        if ( !empty($tag) ) {
                            $return_array[] = $tag->slug;
                        }
                    } elseif ( 'shipping_class' === $condition ) {
                        $shipping_class = get_term( $key, 'product_shipping_class' );
                        if ( !empty($shipping_class) ) {
                            $return_array[$shipping_class->slug] = $ids;
                        }
                    } elseif ( 'cpsc' === $condition ) {
                        $return_array[] = $ids;
                    } elseif ( 'cpp' === $condition ) {
                        $cpp_posts = get_post( $ids );
                        if ( !empty($cpp_posts) ) {
                            $return_array[] = $cpp_posts->post_name;
                        }
                    } else {
                        $return_array[] = $ids;
                    }
                
                }
            }
        }
        return $return_array;
    }
    
    /**
     * Fetch id based on slug
     *
     * @since    3.6.1
     */
    public function afrsm_pro_fetch_id( $slug_array, $condition )
    {
        $return_array = array();
        if ( !empty($slug_array) ) {
            foreach ( $slug_array as $key => $slugs ) {
                if ( !empty($slugs) ) {
                    
                    if ( 'product' === $condition ) {
                        $post = get_page_by_path( $slugs, OBJECT, 'product' );
                        
                        if ( !empty($post) ) {
                            $id = $post->ID;
                            $return_array[] = $id;
                        }
                    
                    } elseif ( 'variableproduct' === $condition ) {
                        $args = array(
                            'post_type' => 'product_variation',
                            'fields'    => 'ids',
                            'name'      => $slugs,
                        );
                        $variable_posts = get_posts( $args );
                        if ( !empty($variable_posts) ) {
                            foreach ( $variable_posts as $val ) {
                                $return_array[] = $val;
                            }
                        }
                    } elseif ( 'category' === $condition || 'cpc' === $condition ) {
                        $term = get_term_by( 'slug', $slugs, 'product_cat' );
                        if ( !empty($term) ) {
                            $return_array[] = $term->term_id;
                        }
                    } elseif ( 'tag' === $condition ) {
                        $term_tag = get_term_by( 'slug', $slugs, 'product_tag' );
                        if ( !empty($term_tag) ) {
                            $return_array[] = $term_tag->term_id;
                        }
                    } elseif ( 'shipping_class' === $condition || 'cpsc' === $condition ) {
                        $shipping_class = get_term_by( 'slug', $key, 'product_shipping_class' );
                        if ( !empty($shipping_class) ) {
                            $return_array[$shipping_class->term_id] = $slugs;
                        }
                    } elseif ( 'cpp' === $condition ) {
                        $args = array(
                            'post_type' => array( 'product_variation', 'product' ),
                            'name'      => $slugs,
                        );
                        $variable_posts = get_posts( $args );
                        if ( !empty($variable_posts) ) {
                            foreach ( $variable_posts as $val ) {
                                $return_array[] = $val->ID;
                            }
                        }
                    } elseif ( 'zone' === $condition ) {
                        $post = get_page_by_path( $slugs, OBJECT, 'wc_afrsm_zone' );
                        
                        if ( !empty($post) ) {
                            $id = $post->ID;
                            $return_array[] = $id;
                        }
                    
                    } else {
                        $return_array[] = $slugs;
                    }
                
                }
            }
        }
        return $return_array;
    }
    
    /**
     * Plugins URL
     *
     * @since    3.6.1
     */
    public function afrsm_pro_plugins_url(
        $id,
        $page,
        $tab,
        $action,
        $nonce
    )
    {
        $query_args = array();
        if ( '' !== $page ) {
            $query_args['page'] = $page;
        }
        if ( '' !== $tab ) {
            $query_args['tab'] = $tab;
        }
        if ( '' !== $action ) {
            $query_args['action'] = $action;
        }
        if ( '' !== $id ) {
            $query_args['id'] = $id;
        }
        if ( '' !== $nonce ) {
            $query_args['_wpnonce'] = wp_create_nonce( 'afrsmnonce' );
        }
        return esc_url( add_query_arg( $query_args, admin_url( 'admin.php' ) ) );
    }
    
    /**
     * Create a menu for plugin.
     *
     * @param string $current current page.
     *
     * @since    3.6.1
     */
    public function afrsm_pro_menus( $current = 'afrsm-pro-list' )
    {
        $afrsm_action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
        $afrsm_wpnonce = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
        
        if ( 'edit' === $afrsm_action && $current === 'afrsm-pro-edit-shipping' ) {
            $shipping_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );
            $menu_title = __( 'Edit Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' );
            $menu_url = $this->afrsm_pro_plugins_url(
                $shipping_id,
                'afrsm-pro-edit-shipping',
                '',
                'edit',
                $afrsm_wpnonce
            );
            $menu_slug = 'afrsm-pro-edit-shipping';
        } else {
            $menu_title = __( 'Add Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' );
            $menu_url = $this->afrsm_pro_plugins_url(
                '',
                'afrsm-pro-add-shipping',
                '',
                '',
                ''
            );
            $menu_slug = 'afrsm-pro-add-shipping';
        }
        
        $wpfp_menus = array(
            'main_menu' => array(
            'pro_menu'  => array(
            'afrsm-pro-list'          => array(
            'menu_title' => __( 'Manage Shipping Methods', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-list',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-list',
            '',
            '',
            ''
        ),
        ),
            $menu_slug                => array(
            'menu_title' => $menu_title,
            'menu_slug'  => $menu_slug,
            'menu_url'   => $menu_url,
        ),
            'afrsm-wc-shipping-zones' => array(
            'menu_title' => __( 'Manage Shipping Zones', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-wc-shipping-zones',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-wc-shipping-zones',
            '',
            '',
            ''
        ),
            'sub_menu'   => array(
            'afrsm-wc-shipping-zones' => array(
            'menu_title' => __( 'Add Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-wc-shipping-zones',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-wc-shipping-zones&add_zone',
            '',
            '',
            ''
        ),
        ),
        ),
        ),
            'afrsm-pro-import-export' => array(
            'menu_title' => __( 'Import / Export', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-import-export',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-import-export',
            '',
            '',
            ''
        ),
        ),
            'afrsm-pro-get-started'   => array(
            'menu_title' => __( 'About Plugin', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-get-started',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-get-started',
            '',
            '',
            ''
        ),
            'sub_menu'   => array(
            'afrsm-pro-get-started' => array(
            'menu_title' => __( 'Getting Started', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-get-started',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-get-started',
            '',
            '',
            ''
        ),
        ),
            'afrsm-pro-information' => array(
            'menu_title' => __( 'Quick info', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-information',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-information',
            '',
            '',
            ''
        ),
        ),
        ),
        ),
            'dotstore'                => array(
            'menu_title' => __( 'Dotstore', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'dotstore',
            'menu_url'   => 'javascript:void(0)',
            'sub_menu'   => array(
            'woocommerce-plugins' => array(
            'menu_title' => __( 'WooCommerce Plugins', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'woocommerce-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/go/flatrate-pro-new-interface-woo-plugins' ),
        ),
            'wordpress-plugins'   => array(
            'menu_title' => __( 'Wordpress Plugins', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'wordpress-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/go/flatrate-pro-new-interface-wp-plugins' ),
        ),
            'contact-support'     => array(
            'menu_title' => __( 'Contact Support', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'contact-support',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/support/' ),
        ),
        ),
        ),
        ),
            'free_menu' => array(
            'afrsm-pro-list'        => array(
            'menu_title' => __( 'Manage Shipping Methods', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-list',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-list',
            '',
            '',
            ''
        ),
        ),
            $menu_slug              => array(
            'menu_title' => $menu_title,
            'menu_slug'  => $menu_slug,
            'menu_url'   => $menu_url,
        ),
            'afrsm-pro-get-started' => array(
            'menu_title' => __( 'About Plugin', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-get-started',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-get-started',
            '',
            '',
            ''
        ),
            'sub_menu'   => array(
            'afrsm-pro-get-started' => array(
            'menu_title' => __( 'Getting Started', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-get-started',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-get-started',
            '',
            '',
            ''
        ),
        ),
            'afrsm-pro-information' => array(
            'menu_title' => __( 'Quick info', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-information',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-information',
            '',
            '',
            ''
        ),
        ),
        ),
        ),
            'afrsm-premium'         => array(
            'menu_title' => __( 'Premium Version', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-premium',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-premium',
            '',
            '',
            ''
        ),
        ),
            'dotstore'              => array(
            'menu_title' => __( 'Dotstore', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'dotstore',
            'menu_url'   => 'javascript:void(0)',
            'sub_menu'   => array(
            'woocommerce-plugins' => array(
            'menu_title' => __( 'WooCommerce Plugins', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'woocommerce-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/go/flatrate-pro-new-interface-woo-plugins' ),
        ),
            'wordpress-plugins'   => array(
            'menu_title' => __( 'Wordpress Plugins', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'wordpress-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/go/flatrate-pro-new-interface-wp-plugins' ),
        ),
            'contact-support'     => array(
            'menu_title' => __( 'Contact Support', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'contact-support',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/support/' ),
        ),
        ),
        ),
        ),
        ),
        );
        ?>
            <div class="dots-menu-main">
                <nav>
                    <ul>
						<?php 
        $main_current = $current;
        $sub_current = $current;
        foreach ( $wpfp_menus['main_menu'] as $main_menu_slug => $main_wpfp_menu ) {
            if ( 'free_menu' === $main_menu_slug || 'common_menu' === $main_menu_slug ) {
                foreach ( $main_wpfp_menu as $menu_slug => $wpfp_menu ) {
                    if ( 'afrsm-pro-information' === $main_current ) {
                        $main_current = 'afrsm-pro-get-started';
                    }
                    $class = ( $menu_slug === $main_current ? 'active' : '' );
                    ?>
                                            <li>
                                                <a class="dotstore_plugin <?php 
                    echo  esc_attr( $class ) ;
                    ?>"
                                                   href="<?php 
                    echo  esc_url( $wpfp_menu['menu_url'] ) ;
                    ?>">
													<?php 
                    esc_html_e( $wpfp_menu['menu_title'], 'advanced-flat-rate-shipping-for-woocommerce' );
                    ?>
                                                </a>
												<?php 
                    
                    if ( isset( $wpfp_menu['sub_menu'] ) && !empty($wpfp_menu['sub_menu']) ) {
                        ?>
                                                    <ul class="sub-menu">
														<?php 
                        foreach ( $wpfp_menu['sub_menu'] as $sub_menu_slug => $wpfp_sub_menu ) {
                            $sub_class = ( $sub_menu_slug === $sub_current ? 'active' : '' );
                            ?>

                                                            <li>
                                                                <a class="dotstore_plugin <?php 
                            echo  esc_attr( $sub_class ) ;
                            ?>"
                                                                   href="<?php 
                            echo  esc_url( $wpfp_sub_menu['menu_url'] ) ;
                            ?>">
																	<?php 
                            esc_html_e( $wpfp_sub_menu['menu_title'], 'advanced-flat-rate-shipping-for-woocommerce' );
                            ?>
                                                                </a>
                                                            </li>
														<?php 
                        }
                        ?>
                                                    </ul>
												<?php 
                    }
                    
                    ?>
                                            </li>
											<?php 
                }
            }
        }
        ?>
                    </ul>
                </nav>
            </div>
			<?php 
    }

}