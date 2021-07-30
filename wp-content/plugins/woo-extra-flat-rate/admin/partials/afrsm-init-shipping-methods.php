<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Class AFRSM_Shipping_Method.
 *
 * WooCommerce Advanced flat rate shipping method class.
 */

if ( class_exists( 'AFRSM_Shipping_Method' ) ) {
    return;
    // Stop if the class already exists
}

class AFRSM_Shipping_Method extends WC_Shipping_Method
{
    private static  $admin_object = null ;
    /**
     * Constructor
     *
     * @since 3.0.0
     */
    public function __construct()
    {
        $get_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
        $post_title = ( isset( $get_id ) ? get_the_title( $get_id ) : '' );
        $shipping_method_id = ( isset( $get_id ) && !empty($get_id) ? $get_id : 'advanced_flat_rate_shipping' );
        $shipping_method_title = ( !empty($post_title) ? $post_title : esc_html__( 'Advanced Flat Rate Shipping', 'advanced-flat-rate-shipping-for-woocommerce' ) );
        $this->id = $shipping_method_id;
        $this->title = __( 'Advanced Flat Rate Shipping', 'advanced-flat-rate-shipping-for-woocommerce' );
        $this->method_title = __( $shipping_method_title, 'advanced-flat-rate-shipping-for-woocommerce' );
        $this->afrsm_shipping_init();
        // Save settings
        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
        self::$admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( '', '' );
    }
    
    /**
     * Init
     *
     * @since 3.0.0
     */
    function afrsm_shipping_init()
    {
        $this->afrsm_shipping_init_form_fields();
        $this->init_settings();
    }
    
    /**
     * Init form fields.
     *
     * @since 3.0.0
     */
    public function afrsm_shipping_init_form_fields()
    {
        $this->form_fields = array(
            'advanced_flat_rate_shipping_table' => array(
            'type' => 'advanced_flat_rate_shipping_table',
        ),
        );
    }
    
    /**
     * List all shipping method
     *
     * @since 3.0.0
     */
    public function afrsm_shipping_generate_advanced_flat_rate_shipping_table_html()
    {
        ob_start();
        require plugin_dir_path( __FILE__ ) . 'afrsm-pro-list-page.php';
        return ob_get_clean();
    }
    
    /**
     * Calculate shipping.
     *
     * @param array $package List containing all products for this method.
     *
     * @return bool false if $matched_shipping_methods is false then it will return false
     * @since 3.0.0
     *
     * @uses get_default_language()
     * @uses afrsm_match_methods()
     * @uses WC_Cart::get_cart()
     * @uses afrsm_allow_customer()
     * @uses afrsm_forceall()
     * @uses afrsm_fees_per_qty_on_ap_rules_off()
     * @uses afrsm_cart_subtotal_before_discount_cost()
     * @uses afrsm_cart_subtotal_after_discount_cost()
     * @uses afrsm_evaluate_cost()
     * @uses afrsm_get_package_item_qty()
     * @uses afrsm_find_shipping_classes()
     * @uses get_term_by()
     * @uses WC_Shipping_Method::add_rate()
     *
     */
    public function calculate_shipping( $package = array() )
    {
        global  $sitepress ;
        $default_lang = self::$admin_object->afrsm_pro_get_default_langugae_with_sitpress();
        $matched_shipping_methods = $this->afrsm_shipping_match_methods( $package, $sitepress, $default_lang );
        if ( false === $matched_shipping_methods || !is_array( $matched_shipping_methods ) || empty($matched_shipping_methods) ) {
            return false;
        }
        $cart_array = self::$admin_object->afrsm_pro_get_cart();
        $getSortOrder = get_option( 'sm_sortable_order_' . $default_lang );
        $sort_order = array();
        
        if ( !empty($getSortOrder) ) {
            foreach ( $getSortOrder as $getSortOrder_id ) {
                settype( $getSortOrder_id, 'integer' );
                if ( in_array( $getSortOrder_id, $matched_shipping_methods, true ) ) {
                    $sort_order[] = $getSortOrder_id;
                }
            }
            unset( $matched_shipping_methods );
            $matched_shipping_methods = $sort_order;
        }
        
        /**
         * match shipping methods
         */
        if ( !empty($matched_shipping_methods) ) {
            // ordering issue and highest, smallest, forceall shipping issue code
            foreach ( $matched_shipping_methods as $main_shipping_method_id_val ) {
                
                if ( !empty($main_shipping_method_id_val) || $main_shipping_method_id_val !== 0 ) {
                    
                    if ( !empty($sitepress) ) {
                        $shipping_method_id_val = apply_filters(
                            'wpml_object_id',
                            $main_shipping_method_id_val,
                            'wc_afrsm',
                            true,
                            $default_lang
                        );
                    } else {
                        $shipping_method_id_val = $main_shipping_method_id_val;
                    }
                    
                    $shipping_title = get_the_title( $shipping_method_id_val );
                    $shipping_rate = array(
                        'id'    => 'advanced_flat_rate_shipping' . ':' . $shipping_method_id_val,
                        'label' => __( $shipping_title, 'advanced-flat-rate-shipping-for-woocommerce' ),
                        'cost'  => 0,
                    );
                    $cart_based_qty = '0';
                    if ( !empty($cart_array) ) {
                        foreach ( $cart_array as $value ) {
                            
                            if ( !empty($value['variation_id']) || 0 !== $value['variation_id'] ) {
                                $product_id_lan = $value['variation_id'];
                            } else {
                                $product_id_lan = $value['product_id'];
                            }
                            
                            $_product = wc_get_product( $product_id_lan );
                            
                            if ( !$_product->is_virtual( 'yes' ) ) {
                                
                                if ( !empty($sitepress) ) {
                                    $product_id_lan = intval( apply_filters(
                                        'wpml_object_id',
                                        $product_id_lan,
                                        'product',
                                        true,
                                        $default_lang
                                    ) );
                                } else {
                                    $product_id_lan = intval( $product_id_lan );
                                }
                                
                                $cart_based_qty += $value['quantity'];
                            }
                        
                        }
                    }
                    // Calculate the costs
                    $has_costs = false;
                    // True when a cost is set. False if all costs are blank strings.
                    $costs = get_post_meta( $shipping_method_id_val, 'sm_product_cost', true );
                    $cost_args = array(
                        'qty'  => $this->afrsm_shipping_get_package_item_qty( $package ),
                        'cost' => $package['contents_cost'],
                    );
                    $costs = $this->afrsm_shipping_evaluate_cost( $costs, $cost_args );
                    $cost = $costs;
                    $sm_taxable = get_post_meta( $shipping_method_id_val, 'sm_select_taxable', true );
                    $sm_extra_cost_calculation_type = get_post_meta( $shipping_method_id_val, 'sm_extra_cost_calculation_type', true );
                    
                    if ( '' !== $cost ) {
                        $has_costs = true;
                        $cost_args = array(
                            'qty'  => $this->afrsm_shipping_get_package_item_qty( $package ),
                            'cost' => $package['contents_cost'],
                        );
                        $shipping_rate['cost'] = $this->afrsm_shipping_evaluate_cost( $cost, $cost_args );
                    }
                    
                    // Add shipping class costs
                    $found_shipping_classes = $this->afrsm_shipping_find_shipping_classes( $package );
                    $highest_class_cost = 0;
                    
                    if ( !empty($found_shipping_classes) ) {
                        foreach ( $found_shipping_classes as $shipping_class => $products ) {
                            $shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
                            $shipping_extra_id = '';
                            if ( false !== $shipping_class_term ) {
                                
                                if ( !empty($sitepress) ) {
                                    $shipping_extra_id = apply_filters(
                                        'wpml_object_id',
                                        $shipping_class_term->term_id,
                                        'product_shipping_class',
                                        true,
                                        $default_lang
                                    );
                                } else {
                                    $shipping_extra_id = $shipping_class_term->term_id;
                                }
                            
                            }
                            $sm_extra_cost = get_post_meta( $shipping_method_id_val, 'sm_extra_cost', true );
                            $class_cost_string = ( isset( $sm_extra_cost[$shipping_extra_id] ) && !empty($sm_extra_cost[$shipping_extra_id]) ? $sm_extra_cost[$shipping_extra_id] : '' );
                            if ( '' === $class_cost_string ) {
                                continue;
                            }
                            $has_costs = true;
                            $class_cost = $this->afrsm_shipping_evaluate_cost( $class_cost_string, array(
                                'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
                                'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) ),
                            ) );
                            
                            if ( 'per_class' === $sm_extra_cost_calculation_type ) {
                                $shipping_rate['cost'] += $class_cost;
                            } else {
                                $highest_class_cost = ( $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost );
                            }
                        
                        }
                        if ( 'per_order' === $sm_extra_cost_calculation_type && $highest_class_cost ) {
                            $shipping_rate['cost'] += $highest_class_cost;
                        }
                    }
                    
                    // apply for tax
                    
                    if ( 'no' === $sm_taxable ) {
                        $shipping_rate['taxes'] = false;
                    } else {
                        $shipping_rate['taxes'] = '';
                    }
                    
                    $shipping_rate['cost'] = $this->afrsm_price_format( $shipping_rate['cost'] );
                    
                    if ( $has_costs ) {
                        if ( $shipping_rate['cost'] < 0 ) {
                            //customize label of shipping method
                            $shipping_rate['label'] = $shipping_rate['label'];
                        }
                        $this->add_rate( $shipping_rate );
                        //apply rate in cart
                    }
                    
                    do_action(
                        'woocommerce_' . $this->id . '_shipping_add_rate',
                        $this,
                        $shipping_rate,
                        $package
                    );
                }
            
            }
        }
    }
    
    /**
     * Match methods.
     *
     * Check all created AFRSM shipping methods have a matching condition group.
     *
     * @param array|object $package List of shipping package data.
     * @param string $sitepress
     * @param string $default_lang
     *
     * @return array $matched_methods   List of all matched shipping methods.
     *
     * @uses afrsm_shipping_match_conditions()
     * @uses Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin::afrsm_pro_get_shipping_method()
     *
     * @since 3.0.0
     *
     * @uses get_posts()
     */
    public function afrsm_shipping_match_methods( $package, $sitepress, $default_lang )
    {
        $matched_methods = array();
        $sm_args = array(
            'post_type'        => 'wc_afrsm',
            'posts_per_page'   => -1,
            'orderby'          => 'menu_order',
            'order'            => 'ASC',
            'suppress_filters' => false,
        );
        $get_all_shipping = new WP_Query( $sm_args );
        if ( $get_all_shipping->have_posts() ) {
            while ( $get_all_shipping->have_posts() ) {
                $get_all_shipping->the_post();
                
                if ( !empty($sitepress) ) {
                    $sm_post_id = apply_filters(
                        'wpml_object_id',
                        get_the_ID(),
                        'wc_afrsm',
                        true,
                        $default_lang
                    );
                } else {
                    $sm_post_id = get_the_ID();
                }
                
                
                if ( !empty($sitepress) ) {
                    
                    if ( version_compare( ICL_SITEPRESS_VERSION, '3.2', '>=' ) ) {
                        $language_information = apply_filters( 'wpml_post_language_details', null, $sm_post_id );
                    } else {
                        $language_information = wpml_get_language_information( $sm_post_id );
                    }
                    
                    $post_id_language_code = $language_information['language_code'];
                } else {
                    $post_id_language_code = self::$admin_object->afrsm_pro_get_default_langugae_with_sitpress();
                }
                
                
                if ( $post_id_language_code === $default_lang ) {
                    $is_match = $this->afrsm_shipping_match_conditions( $sm_post_id, $package );
                    // Add to matched methods array
                    if ( true === $is_match ) {
                        $matched_methods[] = $sm_post_id;
                    }
                }
            
            }
        }
        // reset custom query
        wp_reset_query();
        update_option( 'matched_method', $matched_methods );
        return $matched_methods;
    }
    
    /**
     * Match conditions.
     *
     * Check if conditions match, if all conditions in one condition group
     * matches it will return TRUE and the shipping method will display.
     *
     * @param array $sm_post_data
     * @param array $package List of shipping package data.
     *
     * @return BOOL TRUE if all the conditions in one of the condition groups matches true.
     * @since 1.0.0
     *
     */
    public function afrsm_shipping_match_conditions( $sm_post_data, $package = array() )
    {
        if ( empty($sm_post_data) ) {
            return false;
        }
        
        if ( !empty($sm_post_data) ) {
            $final_condition_flag = apply_filters( 'afrsm_condition_match_rules', $sm_post_data, $package );
            if ( $final_condition_flag ) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get items in package.
     *
     * @param array|object $package
     *
     * @return int $total_quantity
     * @since 1.0.0
     *
     */
    public function afrsm_shipping_get_package_item_qty( $package )
    {
        $total_quantity = 0;
        foreach ( $package['contents'] as $values ) {
            if ( $values['quantity'] > 0 && $values['data']->needs_shipping() ) {
                $total_quantity += $values['quantity'];
            }
        }
        return $total_quantity;
    }
    
    /**
     * Evaluate a cost from a sum/string.
     *
     * @param string $shipping_cost_sum
     * @param array $args
     *
     * @return string $shipping_cost_sum if shipping cost is empty then it will return 0
     * @since 1.0.0
     *
     * @uses wc_get_price_decimal_separator()
     * @uses WC_Eval_Math_Extra::evaluate()
     *
     */
    protected function afrsm_shipping_evaluate_cost( $shipping_cost_sum, $args = array() )
    {
        include_once plugin_dir_path( __FILE__ ) . 'class-wc-extra-flat-eval-math.php';
        // Allow 3rd parties to process shipping cost arguments
        $args = apply_filters(
            'woocommerce_evaluate_shipping_cost_args',
            $args,
            $shipping_cost_sum,
            $this
        );
        $locale = localeconv();
        $decimals = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'] );
        $this->fee_cost = $args['cost'];
        // Expand shortcodes
        add_shortcode( 'fee', array( $this, 'fee' ) );
        $shipping_cost_sum = do_shortcode( str_replace( array( '[qty]', '[cost]' ), array( $args['qty'], $args['cost'] ), $shipping_cost_sum ) );
        remove_shortcode( 'fee', array( $this, 'fee' ) );
        // Remove whitespace from string
        $shipping_cost_sum = preg_replace( '/\\s+/', '', $shipping_cost_sum );
        // Remove locale from string
        $shipping_cost_sum = str_replace( $decimals, '.', $shipping_cost_sum );
        // Trim invalid start/end characters
        $shipping_cost_sum = rtrim( ltrim( $shipping_cost_sum, "\t\n\r\0\v+*/" ), "\t\n\r\0\v+-*/" );
        // Do the math
        return ( $shipping_cost_sum ? WC_Eval_Math_Extra::evaluate( $shipping_cost_sum ) : 0 );
    }
    
    /**
     * Finds and returns shipping classes and the products with said class.
     *
     * @param array|object $package
     *
     * @return array $found_shipping_classes
     * @since 1.0.0
     *
     */
    public function afrsm_shipping_find_shipping_classes( $package )
    {
        $found_shipping_classes = array();
        foreach ( $package['contents'] as $item_id => $values ) {
            
            if ( $values['data']->needs_shipping() ) {
                $found_class = $values['data']->get_shipping_class();
                
                if ( !empty($found_class) ) {
                    if ( !isset( $found_shipping_classes[$found_class] ) ) {
                        $found_shipping_classes[$found_class] = array();
                    }
                    $found_shipping_classes[$found_class][$item_id] = $values;
                }
            
            }
        
        }
        return $found_shipping_classes;
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
     * @uses trigger_error()
     *
     */
    public function afrsm_shipping_fee_array_column( array $input, $columnKey, $indexKey = null )
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
     * Work out fee ( shortcode ).
     *
     * @param array $atts
     *
     * @return string $calculated_fee
     * @since 1.0.0
     *
     * @uses afrsm_shipping_string_sanitize
     *
     */
    public function fee( $atts )
    {
        $atts = shortcode_atts( array(
            'percent' => '',
            'min_fee' => '',
            'max_fee' => '',
        ), $atts );
        $atts['percent'] = $this->afrsm_shipping_string_sanitize( $atts['percent'] );
        $atts['min_fee'] = $this->afrsm_shipping_string_sanitize( $atts['min_fee'] );
        $atts['max_fee'] = $this->afrsm_shipping_string_sanitize( $atts['max_fee'] );
        $calculated_fee = 0;
        if ( $atts['percent'] ) {
            $calculated_fee = $this->fee_cost * (floatval( $atts['percent'] ) / 100);
        }
        if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] ) {
            $calculated_fee = $atts['min_fee'];
        }
        if ( $atts['max_fee'] && $calculated_fee > $atts['max_fee'] ) {
            $calculated_fee = $atts['max_fee'];
        }
        return $calculated_fee;
    }
    
    /**
     * Sanitize string
     *
     * @param mixed $string
     *
     * @return string $result
     * @since 1.0.0
     *
     */
    public function afrsm_shipping_string_sanitize( $string )
    {
        $result = preg_replace( "/[^ A-Za-z0-9_=.*()+\\-\\[\\]\\/]+/", '', html_entity_decode( $string, ENT_QUOTES ) );
        return $result;
    }
    
    /**
     * Price format
     *
     * @param string $price
     *
     * @return string $price
     * @since  1.3.3
     *
     */
    public function afrsm_price_format( $price )
    {
        $price = floatval( $price );
        return $price;
    }

}