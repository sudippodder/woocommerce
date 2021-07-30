<?php


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class WC_Deposits_Add_To_Cart
 */
class WC_Deposits_Add_To_Cart
{


    private $booking_cost = null;
    private $appointment_cost = null;


    /**
     * WC_Deposits_Add_To_Cart constructor.
     * @param $wc_deposits
     */
    public function __construct(&$wc_deposits)
    {
        // Add the required styles
        add_action('wc_deposits_enqueue_product_scripts', array($this, 'enqueue_scripts'));
        add_action('wc_deposits_enqueue_product_scripts', array($this, 'enqueue_inline_styles'));
        add_filter('woocommerce_bookings_booking_cost_string', array($this, 'calculate_bookings_cost'));
        add_filter('booking_form_calculated_booking_cost', array($this, 'get_booking_cost'));

        //appointments plugin
        add_filter('woocommerce_appointments_appointment_cost_html', array($this, 'calculate_appointment_cost_html'));
        add_filter('appointment_form_calculated_appointment_cost', array($this, 'get_appointment_cost'), 100);
        // Hook the add to cart form

        add_action('woocommerce_single_variation', array($this, 'before_add_to_cart_button'), 999);
        add_action('woocommerce_before_add_to_cart_button', array($this, 'before_add_to_cart_button'), 999);
        add_filter('woocommerce_add_cart_item_data', array($this, 'add_cart_item_data'), 10, 3);


    }

    /**
     * @brief Load the deposit-switch logic
     *
     * @return void
     */
    public function enqueue_scripts()
    {


        global $post;
        $product = wc_get_product($post->ID);

        if (!$product)
            return;

        if ($product->get_type() === 'variable') {


            $tax_display = get_option('wc_deposits_tax_display') === 'yes';
            $tax_handling = get_option('wc_deposits_taxes_handling');
            $tax = 0;
            $enqueue_scripts = false;
            $variations_array = array();

            foreach ($product->get_children() as $variation_id) {


                $variation = wc_get_product($variation_id);
                if (!is_object($variation)) {
                    continue;
                }

                $deposit_enabled = wc_deposits_is_product_deposit_enabled($variation_id);
                if ($deposit_enabled) {
                    //if any variation has deposit enabled ,set enqueue scripts flag to true
                    $enqueue_scripts = true;
                }


                $variation_amount_type = wc_deposits_get_product_deposit_amount_type($variation_id);
                $variation_deposit_amount = wc_deposits_get_product_deposit_amount($variation_id);
                if (!$deposit_enabled || $variation_deposit_amount <= 0) {
                    continue;
                }

                if ($tax_display && $tax_handling === 'deposit') {
                    $tax = wc_get_price_including_tax($variation) - wc_get_price_excluding_tax($variation);
                } elseif ($tax_display && $tax_handling === 'split') {
                    $tax_total = $tax = wc_get_price_including_tax($variation) - wc_get_price_excluding_tax($variation);
                    $tax = $tax_total * $variation_deposit_amount / 100;
                }


                if ($variation_amount_type === 'fixed') {
                    $amount = $variation_deposit_amount + $tax;
                    $amount_html = wc_price($amount);
                } else {


                    $amount = floatval(wc_get_price_excluding_tax($variation)) * (floatval($variation_deposit_amount) / 100.0) + $tax;
                    $amount_html = wc_price($amount);
                }

                // a last precaution is to check deposit total against variation total
                if (floatval($variation->get_price()) > floatval($amount)) {
                    $deposit_forced = wc_deposits_is_product_deposit_forced($variation->get_id());
                    $variations_array[$variation_id] = array('forced' => $deposit_forced, 'amount' => $amount_html);
                }

            }
            if ($enqueue_scripts) {

                wp_enqueue_script('wc-deposits-add-to-cart', WC_ADVANCE_PLUGIN_URL . '/assets/js/add-to-cart.js');

                $message_deposit = get_option('wc_deposits_message_deposit');
                $message_full_amount = get_option('wc_deposits_message_full_amount');

                $message_deposit = stripslashes($message_deposit);
                $message_full_amount = stripslashes($message_full_amount);


                $script_args = array(
                    'message' => array(
                        'deposit' => __($message_deposit, 'woocommerce-deposits'),
                        'full' => __($message_full_amount, 'woocommerce-deposits')
                    ),
                    'variations' => $variations_array
                );


                wp_localize_script('wc-deposits-add-to-cart', 'wc_deposits_add_to_cart_options', $script_args);
            }


        } else {

            $deposit_enabled = wc_deposits_is_product_deposit_enabled($post->ID);
            if ($deposit_enabled) {
                wp_enqueue_script('wc-deposits-add-to-cart', WC_ADVANCE_PLUGIN_URL . '/assets/js/add-to-cart.js');

                $message_deposit = get_option('wc_deposits_message_deposit');
                $message_full_amount = get_option('wc_deposits_message_full_amount');

                $message_deposit = stripslashes($message_deposit);
                $message_full_amount = stripslashes($message_full_amount);

                $script_args = array(
                    'message' => array(
                        'deposit' => __($message_deposit, 'woocommerce-deposits'),
                        'full' => __($message_full_amount, 'woocommerce-deposits')
                    )
                );


                wp_localize_script('wc-deposits-add-to-cart', 'wc_deposits_add_to_cart_options', $script_args);
            }
        }


    }


    /**
     * @brief Enqueues front-end styles
     *
     * @return void
     */
    public function enqueue_inline_styles()
    {
        // prepare inline styles
        $colors = get_option('wc_deposits_deposit_buttons_colors');
        $fallback_colors = wc_deposits_woocommerce_frontend_colours();

        $gstart = $colors['primary'] ? $colors['primary'] : $fallback_colors['primary'];
        $secondary = $colors['secondary'] ? $colors['secondary'] : $fallback_colors['secondary'];
        $highlight = $colors['highlight'] ? $colors['highlight'] : $fallback_colors['highlight'];
        $gend = wc_deposits_adjust_colour($gstart, 15);


        $style = "
            #wc-deposits-options-form input.input-radio:enabled ~ label { color: {$secondary}; }
            #wc-deposits-options-form div a.wc-deposits-switcher {
              background-color: {$gstart};
              background: -moz-gradient(center top, {$gstart} 0%, {$gend} 100%);
              background: -moz-linear-gradient(center top, {$gstart} 0%, {$gend} 100%);
              background: -webkit-gradient(linear, left top, left bottom, from({$gstart}), to({$gend}));
              background: -webkit-linear-gradient({$gstart}, {$gend});
              background: -o-linear-gradient({$gstart}, {$gend});
              background: linear-gradient({$gstart}, {$gend});
            }
            #wc-deposits-options-form .amount { color: {$highlight}; }
            #wc-deposits-options-form .deposit-option { display: inline; }
          ";
        echo '<style>' . $style . '</style>';

    }

    /**
     * get the updated booking cost and saves it to be used for html generation
     * @param $cost
     * @return mixed
     */
    public function get_appointment_cost($cost)
    {

        $this->appointment_cost = $cost;

        return $cost;

    }

    /**
     * get the updated booking cost and saves it to be used for html generation
     * @param $cost
     * @return mixed
     */
    public function get_booking_cost($cost)
    {

        $this->booking_cost = $cost;

        return $cost;

    }

    /**
     * @brief calculates new booking deposit on booking total change
     * @param $html
     * @return string
     */
    public function calculate_bookings_cost($html)
    {

        $posted = array();

        parse_str($_POST['form'], $posted);

        $product_id = $posted['add-to-cart'];
        $product = wc_get_product($product_id);
        $amount_type = wc_deposits_get_product_deposit_amount_type($product_id);
        $deposit_amount = wc_deposits_get_product_deposit_amount($product_id);
        $deposits_enable_per_person = $product->get_meta('_wc_deposits_enable_per_person', true);



        if (version_compare(WC_BOOKINGS_VERSION, '1.15.0', '>=')) {

            $booking_data = wc_bookings_get_posted_data( $posted, $product );
            $cost = WC_Bookings_Cost_Calculation::calculate_booking_cost( $booking_data, $product );

            if ( is_wp_error( $cost ) ) {
                return;
            }


            if ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) {
                if ( function_exists( 'wc_get_price_excluding_tax' ) ) {
                    $booking_cost = wc_get_price_including_tax( $product, array( 'price' => $cost ) );
                } else {
                    $booking_cost = $product->get_price_including_tax( 1, $cost );
                }
            } else {
                if ( function_exists( 'wc_get_price_excluding_tax' ) ) {
                    $booking_cost = wc_get_price_excluding_tax( $product, array( 'price' => $cost ) );
                } else {
                    $booking_cost = $product->get_price_excluding_tax( 1, $cost );
                }
            }

        } else{
            $booking_cost = $this->booking_cost;
        }



        if ($product->get_type() === 'booking') {
            $amount = $booking_cost;
            if ($product->has_persons() && $deposits_enable_per_person == 'yes') {

                if ($product->has_person_types()) {

                    $persons = 0;

                    $person_types = array_keys($product->get_person_types());

                    foreach ($person_types as $type) {

                        if (isset($posted['wc_bookings_field_persons_' . $type])) {
                            $persons += intval($posted['wc_bookings_field_persons_' . $type]);
                        }
                    }


                } else {
                    $persons = $posted['wc_bookings_field_persons'];

                }


                if ($amount_type === 'fixed') {
                    //					$deposit = $deposit_amount * $persons;
                    $deposit = $deposit_amount;
                } else { // percent
                    $deposit = $deposit_amount / 100.0 * $amount;
                }
            } else {
                if ($amount_type === 'fixed') {
                    $deposit = $deposit_amount;
                } else { // percent
                    $deposit = $deposit_amount / 100.0 * $amount;
                }
            }
        }

        $deposit_html = wc_price($deposit);
        $script = '<script type="text/javascript">
                var deposit_html = \'' . $deposit_html . '\'
            jQuery("#deposit-amount .amount").html(deposit_html);
               
                </script>';

        return $html . $script;

    }

    /**
     * @param $html
     * @return string
     */
    public function calculate_appointment_cost_html($html)
    {


        $posted = array();

        parse_str($_POST['form'], $posted);

        $product_id = $posted['add-to-cart'];
        $product = wc_get_product($product_id);
        $amount_type = wc_deposits_get_product_deposit_amount_type($product_id);
        $deposit_amount = wc_deposits_get_product_deposit_amount($product_id);
        $appointment_cost = $this->appointment_cost;
        if ($product->get_type() === 'appointment') {
            $amount = $appointment_cost;
            if ($amount_type === 'percent') {
                $deposit = $deposit_amount / 100.0 * $amount;
            } else {
                $deposit = $deposit_amount;
            }

        }

        $deposit_html = wc_price(floatval($deposit));
        $script = '<script type="text/javascript">
                var deposit_html = \'' . $deposit_html . '\'
                jQuery("#deposit-amount .amount").html(deposit_html);
                </script>';

        return $html . $script;

    }


    /**
     * @brief deposit calculation and display
     */
    public function before_add_to_cart_button()
    {
        //deposit already queued
        if(is_product() && did_action('wc_deposits_enqueue_product_scripts')) return;
        //user restriction
        if (is_user_logged_in()) {

            $disabled_user_roles = get_option('wc_deposits_disable_deposit_for_user_roles', array());
            if (!empty($disabled_user_roles)) {

                foreach ($disabled_user_roles as $disabled_user_role) {

                    if (wc_current_user_has_role($disabled_user_role)) return;

                }

            }
        } else {
            $allow_deposit_for_guests = get_option('wc_deposits_restrict_deposits_for_logged_in_users_only', 'no');

            if ($allow_deposit_for_guests !== 'no') return;
        }

        global $product;

        $product_id = $product->get_id();
        //if product is variable , check variations override for product deposit
        if ($product->get_type() === 'variable') {

            $deposit_enabled = wc_deposits_is_product_deposit_enabled($product_id);


            if (!$deposit_enabled) {
                foreach ($product->get_children() as $variation_id) {

                    //if not enabled on global level , check in overrides


                    $variation = wc_get_product($variation_id);
                    if (!is_object($variation)) {
                        continue;

                    }


                    //check override
                    $override = $variation->get_meta('_wc_deposits_override_product_settings', true) === 'yes';

                    if ($override) {
                        $variation_deposit_enabled = wc_deposits_is_product_deposit_enabled($variation_id);

                        if ($variation_deposit_enabled) {
                            //at least 1 variation has deposit enabled
                            $deposit_enabled = true;
                            continue;
                        }
                    }
                }

            }

        } else {
            $deposit_enabled = wc_deposits_is_product_deposit_enabled($product_id);
        }



        if ($product && $deposit_enabled) {


            $product_type = $product->get_type();
            $amount_type = wc_deposits_get_product_deposit_amount_type($product_id);
            $force_deposit = wc_deposits_is_product_deposit_forced($product_id);
            $deposit_amount = wc_deposits_get_product_deposit_amount($product_id);
            $deposits_enable_per_person = $product->get_meta('_wc_deposits_enable_per_person', true);

            $tax_display = get_option('wc_deposits_tax_display') === 'yes';
            $tax_handling = get_option('wc_deposits_taxes_handling');
            $woocommerce_prices_include_tax = get_option('woocommerce_prices_include_tax');
            $tax = 0;


            if ($tax_display && $tax_handling === 'deposit') {
                $tax = wc_get_price_including_tax($product) - wc_get_price_excluding_tax($product);
            } elseif ($tax_display && $tax_handling === 'split') {

                $tax_total = $tax = wc_get_price_including_tax($product) - wc_get_price_excluding_tax($product);
                $deposit_percentage = $deposit_amount * 100 / ($product->get_price());

                if ($amount_type === 'percent') {
                    $deposit_percentage = $deposit_amount;
                }
                $tax = $tax_total * $deposit_percentage / 100;

            }


            $deposit_amount = floatval($deposit_amount);


            //amount
            if ($amount_type === 'fixed') {


                if ($woocommerce_prices_include_tax === 'yes') {
                    $amount = $deposit_amount;

                } else {
                    $amount = $deposit_amount + $tax;

                }


            } else {
                //percentage price calculation

                if ($product->get_type() === 'variable' || $product->get_type() === 'composite' || $product->get_type() === 'booking') {
                    $amount = $deposit_amount;

                } elseif ($product->get_type() === 'subscription' && class_exists('WC_Subscriptions_Product')) {
                    $total_signup_fee = WC_Subscriptions_Product::get_sign_up_fee($product);
                    $amount = $total_signup_fee * ($deposit_amount / 100.0);
                } else {

                    if ($woocommerce_prices_include_tax === 'yes') {
                        $amount = $product->get_price() * ($deposit_amount / 100.0);
                    } else {
                        $amount = $product->get_price() * ($deposit_amount / 100.0) + $tax;
                    }
                }


            }


            $amount  = round($amount,wc_get_price_decimals());


            if($product_type !== 'variable' && $amount > $product->get_price() ){

                //debug information
                return;
            }
            //suffix
            if ($amount_type === 'fixed') {

                if ($product->get_type() === 'booking' && $product->has_persons() && $deposits_enable_per_person === 'yes') {
                    $suffix = __('per person', 'woocommerce-deposits');
                } elseif ($product_type === 'booking') {
                    $suffix = __('per booking', 'woocommerce-deposits');
                } elseif (!$product->is_sold_individually()) {
                    $suffix = __('per item', 'woocommerce-deposits');
                } else {
                    $suffix = '';
                }

            } else {


                if ($product->get_type() === 'booking' || $product->get_type() === 'composite') {
                    $amount = '<span class=\'amount\'>' . round($deposit_amount, wc_get_price_decimals()) . '%' . '</span>';

                }

                if (!$product->is_sold_individually()) {
                    $suffix = __('per item', 'woocommerce-deposits');
                } else {
                    $suffix = '';
                }
            }








        $default_checked = get_option('wc_deposits_default_option', 'deposit');
        $basic_buttons = get_option('wc_deposits_use_basic_radio_buttons', true) === 'yes';
        $deposit_text = get_option('wc_deposits_button_deposit');
        $full_text = get_option('wc_deposits_button_full_amount');
        $deposit_option_text = get_option('wc_deposits_deposit_option_text');

        if ($deposit_text === false) {

            $deposit_text = __('Pay Deposit', 'woocommerce-deposits');

        }
        if ($full_text === false) {
            $full_text = __('Full Amount', 'woocommerce-deposits');

        }

        if ($deposit_option_text === false) {
            $deposit_option_text = __('Deposit Option', 'woocommerce-deposits');
        }

        $deposit_text = stripslashes($deposit_text);
        $full_text = stripslashes($full_text);
        $deposit_option_text = stripslashes($deposit_option_text);


        $args = array(
            'deposit_info' => array(
                //raw amount before calculations
                'type' => $amount_type,
                'amount' => $deposit_amount,
            ),
            'product' => $product,
            'suffix' => $suffix,
            'force_deposit' => $force_deposit ? 'yes' : 'no',
            'deposit_amount' => $amount,
            'basic_buttons' => $basic_buttons,
            'deposit_text' => $deposit_text,
            'full_text' => $full_text,
            'deposit_option_text' => $deposit_option_text,
            'default_checked' => $default_checked


        );

        }
        if($product &&  $deposit_enabled){

            wc_get_template('single-product/wc-deposits-product-slider.php', $args, '', WC_ADVANCE_TEMPLATE_PATH);
        }

    }

    /**
     * @param $cart_item_meta
     * @param $product_id
     * @param $variation_id
     * @return mixed
     */
    public
    function add_cart_item_data($cart_item_meta, $product_id, $variation_id)
    {




        //user restriction
        if (is_user_logged_in()) {

            $disabled_user_roles = get_option('wc_deposits_disable_deposit_for_user_roles', array());
            if (!empty($disabled_user_roles)) {

                foreach ($disabled_user_roles as $disabled_user_role) {

                    if (wc_current_user_has_role($disabled_user_role)) return $cart_item_meta;

                }

            }
        } else {
            $allow_deposit_for_guests = get_option('wc_deposits_restrict_deposits_for_logged_in_users_only', 'no');

            if ($allow_deposit_for_guests !== 'no') return $cart_item_meta;
        }


        $product = wc_get_product($product_id);

        if ($product->get_type() === 'variable') {

            $deposit_enabled = wc_deposits_is_product_deposit_enabled($variation_id);
            $force_deposit = wc_deposits_is_product_deposit_forced($variation_id);
        } else {
            $deposit_enabled = wc_deposits_is_product_deposit_enabled($product_id);
            $force_deposit = wc_deposits_is_product_deposit_forced($product_id);
        }


        if ($deposit_enabled) {
            $default = get_option('wc_deposits_default_option');
            if (!isset($_POST[$product_id . '-deposit-radio'])) {
                $_POST[$product_id . '-deposit-radio'] = $default ? $default : 'deposit';
            }

            if (isset($variation_id)) {
                $_POST[$variation_id . '-deposit-radio'] = $_POST[$product_id . '-deposit-radio'];
            }

            $cart_item_meta['deposit'] = array(

                'enable' => $force_deposit ? 'yes' : ($_POST[$product_id . '-deposit-radio'] === 'full' ? 'no' : 'yes')
            );
        }
        return $cart_item_meta;
    }

    /**
     * @param $product_id
     * @return bool
     * @deprecated since version 2.3.3
     */
    function is_product_deposit_forced($product_id)
    {

        $product = wc_get_product($product_id);
        return $product->get_meta('_wc_deposits_force_deposit', true) === 'yes';
    }
}

