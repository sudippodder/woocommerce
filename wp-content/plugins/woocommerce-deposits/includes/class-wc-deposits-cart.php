<?php


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (class_exists('WC_Deposits_Cart')) return;

/**
 * Class WC_Deposits_Cart
 */
class WC_Deposits_Cart
{
    public $wc_deposits;
    public function __construct(&$wc_deposits)
    {
        // Hook cart functionality

        $this->wc_deposits = $wc_deposits;

        if (!wcdp_checkout_mode()) {
//            woocommerce_cart_loaded_from_session
            add_action('woocommerce_cart_loaded_from_session', array($this, 'cart_loaded_from_session'));
//            add_filter('woocommerce_cart_item_subtotal', array($this, 'cart_item_subtotal'), 10, 3);
            add_filter('woocommerce_get_cart_item_from_session', array($this, 'get_cart_item_from_session'), 10, 2);
            add_action('woocommerce_cart_updated', array($this, 'cart_updated'));
            add_action('woocommerce_after_cart_item_quantity_update', array($this, 'after_cart_item_quantity_update'), 10, 2);
            add_action('woocommerce_cart_totals_after_order_total', array($this, 'cart_totals_after_order_total'));
            add_filter('woocommerce_get_item_data', array($this, 'get_item_data'), 10, 2);
            add_action('woocommerce_add_to_cart', array($this, 'is_sold_individually'), 10, 6);
        }

        //have to set very low priority to make sure all other plugins make calculations first
        add_filter('woocommerce_calculated_total', array($this, 'calculated_total'), 1001, 2);
    }

    function cart_loaded_from_session($cart)
    {

        if (WC()->cart) {

            foreach (WC()->cart->get_cart_contents() as $cart_item_key => $cart_item) {
                $this->update_deposit_meta($cart_item['data'], $cart_item['quantity'], $cart_item, $cart_item_key);

            }
        }
    }

    /**
     * Prevents duplicates if the product is set to be individually sold.
     *
     * @throws Exception if more than 1 item of an individually-sold product is being added to cart.
     */
    public function is_sold_individually($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
    {
        $product = wc_get_product($product_id);


        if ($product->is_sold_individually() && isset($cart_item_data['deposit'])) {
            $item_data = $cart_item_data;

            // Get the two possible values of the cart item key.
            if ($item_data['deposit']['enable'] === 'yes') {
                $key_with_deposit = WC()->cart->generate_cart_id($product_id, $variation_id, $variation, $item_data);
                $item_data['deposit']['enable'] = 'no';

                // The value of cart item key if deposit is disabled.
                $key_without_deposit = WC()->cart->generate_cart_id($product_id, $variation_id, $variation, $item_data);
            } else {
                $key_without_deposit = WC()->cart->generate_cart_id($product_id, $variation_id, $variation, $item_data);
                $item_data['deposit']['enable'] = 'yes';

                // The value of cart item key if deposit is enabled.
                $key_with_deposit = WC()->cart->generate_cart_id($product_id, $variation_id, $variation, $item_data);
            }

            // Check if any of the cart item keys is being added more than once.
            $item_count = 0;
            foreach (WC()->cart->get_cart_contents() as $item) {
                if (($item['key'] === $key_with_deposit || $item['key'] === $key_without_deposit)) {
                    $item_count += $item['quantity'];
                }
            }

            if ($item_count > 1) {
                /* translators: %s: product name */
                throw new Exception(sprintf('<a href="%s" class="button wc-forward">%s</a> %s', wc_get_cart_url(), __('View cart', 'woocommerce'), sprintf(__('You cannot add another "%s" to your cart.', 'woocommerce'), $product->get_name())));
            }
        }
    }


    /**
     * @brief Display deposit info in cart item meta area
     * @param $item_data
     * @param $cart_item
     * @return array
     */
    public function get_item_data($item_data, $cart_item)
    {



        if (isset($cart_item['deposit']) && $cart_item['deposit']['enable'] === 'yes') {

            $product = $cart_item['data'];
            if (!$product) return $item_data;

            $tax_display = get_option('wc_deposits_tax_display') === 'yes';


            $deposit = $cart_item['deposit']['deposit'];
            $tax = $cart_item['deposit']['tax'] * $cart_item['quantity'] ;
            $tax_total = $cart_item['deposit']['tax_total'] * $cart_item['quantity'] ;

            $display_deposit = round($deposit + $tax, wc_get_price_decimals());
            $display_remaining = round($cart_item['deposit']['remaining'] + ($tax_total - $tax), wc_get_price_decimals());


            $deposit_amount_text = __(get_option('wc_deposits_deposit_amount_text'), 'woocommerce-deposits');
            $second_payment_amount_text = __(get_option('wc_deposits_second_payment_amount_text'), 'woocommerce-deposits');


            if (empty($deposit_amount_text)) {
                $deposit_amount_text = __('Deposit Amount', 'woocommerce-deposits');
            }
            if (empty($second_payment_amount_text)) {
                $second_payment_amount_text = __('Second Payment Amount', 'woocommerce-deposits');
            }

            $item_data[] = array(
                'name' => $deposit_amount_text,
                'display' => wc_price($display_deposit),
                'value' => 'wc_deposit_amount',
            );
            $item_data[] = array(
                'name' => $second_payment_amount_text,
                'display' => wc_price($display_remaining),
                'value' => 'wc_deposit_second_payment_amount',
            );


        }

        return $item_data;


    }


    /**
     * @brief Hook the subtotal display and show the deposit and remaining amount
     *
     * @param string $subtotal ...
     * @param array $cart_item ...
     * @param mixed $cart_item_key ...
     * @return string
     */
    public function cart_item_subtotal($subtotal, $cart_item, $cart_item_key)
    {

        $product = $cart_item['data'];
        $deposit_enabled = wc_deposits_is_product_deposit_enabled($product->get_id());

        if ($product->get_type() === 'variation') {

            //check override
            $override = $product->get_meta('_wc_deposits_override_product_settings', true) === 'yes';
            if ($override) {

                $amount_type = $product->get_meta('_wc_deposits_amount_type', true);
                $deposit_amount = floatval($product->get_meta('_wc_deposits_deposit_amount', true));

            } else {
                $parent = wc_get_product($product->get_parent_id());
                $amount_type = $parent->get_meta('_wc_deposits_amount_type', true);
                $deposit_amount = floatval($parent->get_meta('_wc_deposits_deposit_amount', true));
            }

        } else {
            $amount_type = $product->get_meta('_wc_deposits_amount_type', true);
            $deposit_amount = $product->get_meta('_wc_deposits_deposit_amount', true);
        }


        if ($deposit_enabled && !empty($cart_item['deposit']) && $cart_item['deposit']['enable'] === 'yes') {

            $tax_display = get_option('wc_deposits_tax_display') === 'yes';
            $tax_handling = get_option('wc_deposits_taxes_handling');
            $tax = 0;

            if ($tax_display) {

                if ($amount_type === 'fixed') {

                    if ($tax_handling === 'deposit') {
                        $tax = wc_get_price_including_tax($product, array('qty' => $cart_item['quantity'])) - wc_get_price_excluding_tax($product, array('qty' => $cart_item['quantity']));

                    } elseif ($tax_handling === 'split') {
                        $tax_total = $tax = wc_get_price_including_tax($product, array('qty' => $cart_item['quantity'])) - wc_get_price_excluding_tax($product, array('qty' => $cart_item['quantity']));

                        $deposit_percentage = $deposit_amount * 100 / ($product->get_price());
                        $tax = $tax_total * $deposit_percentage / 100;
                    }

                } else {

                    if ($tax_handling === 'deposit') {
                        $tax = wc_get_price_including_tax($product, array('qty' => $cart_item['quantity'])) - wc_get_price_excluding_tax($product, array('qty' => $cart_item['quantity']));
                    } elseif ($tax_handling === 'split') {
                        $tax_total = $tax = wc_get_price_including_tax($product, array('qty' => $cart_item['quantity'])) - wc_get_price_excluding_tax($product, array('qty' => $cart_item['quantity']));
                        $tax = $tax_total * $deposit_amount / 100;
                    }
                }
            }

            $deposit = $cart_item['deposit']['deposit'];


            $woocommerce_prices_include_tax = get_option('woocommerce_prices_include_tax');

            if ($woocommerce_prices_include_tax === 'yes') {
                $display_deposit = $deposit;

            } else {
                $display_deposit = $deposit + $tax;

            }
            $remaining = $cart_item['deposit']['remaining'];
            return wc_price($display_deposit) . ' ' . __('Deposit', 'woocommerce-deposits') . '<br/>(' .
                wc_price($remaining) . ' ' . __('Remaining', 'woocommerce-deposits') . ')';
        } else {
            return $subtotal;
        }
    }

    /**
     * @param $cart_item
     * @param $values
     * @return mixed
     */
    public function get_cart_item_from_session($cart_item, $values)
    {

        if (!empty($values['deposit'])) {
            $cart_item['deposit'] = $values['deposit'];
        }
        return $cart_item;
    }


    /**
     * @brief Calculate Deposit and update cart item meta with new values
     * @param $product
     * @param $quantity
     * @param $cart_item_data
     */
    function update_deposit_meta($product, $quantity, &$cart_item_data, $cart_item_key)
    {

        if ($product) {
            $product_type = $product->get_type();
            $deposit_enabled = wc_deposits_is_product_deposit_enabled($product->get_id());


            if ($deposit_enabled && isset($cart_item_data['deposit']) &&
                $cart_item_data['deposit']['enable'] === 'yes'
            ) {

                if ($product_type === 'variation') {
                    //check override
                    $override = $product->get_meta('_wc_deposits_override_product_settings', true) === 'yes';
                    if ($override) {

                        $amount_type = $product->get_meta('_wc_deposits_amount_type', true);
                        $deposit_amount_meta = floatval($product->get_meta('_wc_deposits_deposit_amount', true));

                    } else {
                        $parent = wc_get_product($product->get_parent_id());
                        $amount_type = $parent->get_meta('_wc_deposits_amount_type', true);
                        $deposit_amount_meta = floatval($parent->get_meta('_wc_deposits_deposit_amount', true));
                    }

                } else {

                    $deposit_amount_meta = $product->get_meta('_wc_deposits_deposit_amount', true);
                    $amount_type = $product->get_meta('_wc_deposits_amount_type', true);

                }
                $amount = 0;

                switch ($product_type) {

                    case 'booking':

                        if (class_exists('WC_Booking')) {

                            $amount = $cart_item_data['booking']['_cost'];

                            if ($product->has_persons() && $product->get_meta('_wc_deposits_enable_per_person', true) == 'yes') {
                                $persons = array_sum($cart_item_data['booking']['_persons']);
                                if ($amount_type === 'fixed') {
                                    $deposit = $deposit_amount_meta * $persons;
                                } else { // percent
                                    $deposit = $deposit_amount_meta / 100.0 * $amount;
                                }
                            } else {
                                if ($amount_type === 'fixed') {
                                    $deposit = $deposit_amount_meta;
                                } elseif ($amount_type === 'percent') {
                                    $deposit = $deposit_amount_meta / 100.0 * $amount;
                                }
                            }


                        } else {


                            $amount = wc_get_price_excluding_tax($product, array('qty' => $quantity));

                            if ($amount_type === 'fixed') {

                                $deposit = floatval($deposit_amount_meta) * $quantity;

                            } else {
                                $deposit = $amount * (floatval($deposit_amount_meta) / 100.0);
                            }
                        }

                        break;
                    case 'subscription' :
                        if (class_exists('WC_Subscriptions_Product')) {

                            $amount = WC_Subscriptions_Product::get_sign_up_fee($product);
                            if ($amount_type === 'fixed') {
                                $deposit = $deposit_amount_meta * $quantity;
                            } else {
                                $deposit = $amount * ($deposit_amount_meta / 100.0);
                            }

                        }
                        break;
                    case 'yith_bundle' :
                        $amount = $product->price_per_item_tot;
                        if ($amount_type === 'fixed') {
                            $deposit = $deposit_amount_meta * $quantity;
                        } else {
                            $deposit = $amount * ($deposit_amount_meta / 100.0);
                        }
                        break;

                    case 'phive_booking':

                        $amount = $cart_item_data['phive_booked_price'];
                        if ($amount_type === 'fixed') {

                        } else {
                            $deposit = $amount * ($deposit_amount_meta / 100.0);
                        }

                        break;
                    default:


                        $amount = wc_get_price_excluding_tax($product, array('qty' => $quantity));

                        if ($amount_type === 'fixed') {

                            $deposit = floatval($deposit_amount_meta) * $quantity;

                        } else {
                            $deposit = $amount * (floatval($deposit_amount_meta) / 100.0);
                        }

                        break;
                }
                $woocommerce_prices_include_tax = get_option('woocommerce_prices_include_tax');


                $tax_handling = get_option('wc_deposits_taxes_handling');
                $tax_total = $cart_item_data['line_tax'] / $quantity;
                $cart_item_data['deposit']['tax_total'] = $tax_total;

                if ($tax_handling === 'deposit') {
                    $cart_item_data['deposit']['tax'] = $tax_total;

                } elseif ($tax_handling === 'split') {

                    if ($woocommerce_prices_include_tax === 'yes') {
                        $deposit_percentage = $deposit * 100 / $cart_item_data['line_total'];
                    } else {
                        $deposit_percentage = $deposit * 100 / $cart_item_data['line_total'];
                    }


                    $cart_item_data['deposit']['tax'] = $tax_total * $deposit_percentage / 100;

                } else {

                    $cart_item_data['deposit']['tax'] = 0;

                }

                if ($deposit < $amount && $deposit > 0) {

                    $discount_percentage = 0;
                    if (floatval(WC()->cart->get_cart_discount_total()) && floatval(WC()->cart->get_subtotal()) > 0) {
                        $discount_percentage = WC()->cart->get_cart_discount_total() / WC()->cart->get_subtotal() * 100;
                    }

                    if ($amount_type === 'percent' && $discount_percentage > 0) {
                        $discount = $deposit / 100 * $discount_percentage;
                        $cart_item_data['deposit']['percent_discount'] = $discount;

                    }


                    $cart_item_data['deposit']['deposit'] = round($deposit, wc_get_price_decimals());
                    $cart_item_data['deposit']['remaining'] = round($amount - $deposit, wc_get_price_decimals());
                    $cart_item_data['deposit']['total'] = round($amount, wc_get_price_decimals());

                } else {
                    $cart_item_data['deposit']['enable'] = 'no';
                }

                WC()->cart->cart_contents[$cart_item_key]['deposit'] = apply_filters('wc_deposits_cart_item_deposit_data', $cart_item_data['deposit'], $cart_item_data);
            }
        }

    }

    /**
     * @brief triggers update deposit for all cart items when cart is updated
     */
    public function cart_updated()
    {


        if (WC()->cart && !empty(WC()->cart->get_cart_contents())) {
            foreach (WC()->cart->get_cart_contents() as $cart_item_key => $cart_item) {

                $this->update_deposit_meta($cart_item['data'], $cart_item['quantity'], $cart_item, $cart_item_key);
            }
        }

    }

    /**
     * @brief triggers update deposit for all cart items when cart is updated
     * @param $cart_item_key
     * @param $quantity
     */
    public function after_cart_item_quantity_update($cart_item_key, $quantity)
    {
        $product = WC()->cart->cart_contents[$cart_item_key]['data'];
        $this->update_deposit_meta($product, $quantity, WC()->cart->cart_contents[$cart_item_key], $cart_item_key);
    }


    /**
     * @brief Calculate total Deposit in cart totals area
     *
     * @param mixed $cart_total ...
     * @param mixed $cart ...
     *
     * @return float
     */
    public function calculated_total($cart_total, $cart)
    {


        //user restriction
        if (is_user_logged_in()) {

            $disabled_user_roles = get_option('wc_deposits_disable_deposit_for_user_roles', array());
            if (!empty($disabled_user_roles)) {

                foreach ($disabled_user_roles as $disabled_user_role) {

                    if (wc_current_user_has_role($disabled_user_role)) return $cart_total;

                }

            }
        } else {
            $allow_deposit_for_guests = get_option('wc_deposits_restrict_deposits_for_logged_in_users_only', 'no');

            if ($allow_deposit_for_guests !== 'no') return $cart_total;
        }


        $cart_original = $cart_total;
        $deposit_amount = 0;
        $deposit_total = 0;
        $full_amount_products = 0;
        $full_amount_taxes = 0;
        $deposit_product_taxes = 0;
        $deposit_enabled = false;
        $deposit_in_cart = false;
        $woocommerce_prices_include_tax = get_option('woocommerce_prices_include_tax');

        if (wcdp_checkout_mode()) {
            $deposit_in_cart = true;
            $deposit_amount = get_option('wc_deposits_checkout_mode_deposit_amount');
            $amount_type = get_option('wc_deposits_checkout_mode_deposit_amount_type');

            foreach (WC()->cart->get_cart_contents() as $cart_item) {

                if ($woocommerce_prices_include_tax === 'yes') {

                    $deposit_total += wc_get_price_including_tax($cart_item['data'], array('qty' => $cart_item['quantity']));

                } else {
                    $deposit_total += wc_get_price_excluding_tax($cart_item['data'], array('qty' => $cart_item['quantity']));
                }

            }

            if ($amount_type === 'percentage') {

                $deposit_amount = (WC()->cart->subtotal_ex_tax * $deposit_amount) / 100;

//
//                if (WC()->cart->discount_cart > 0) {
//                    $deposit_amount = ((WC()->cart->subtotal_ex_tax - WC()->cart->discount_cart) * $deposit_amount) / 100;
//                } else {
//                    $deposit_amount = (WC()->cart->subtotal_ex_tax * $deposit_amount) / 100;
//                }
            }
        } else {

            foreach (WC()->cart->get_cart_contents() as $cart_item_key => &$cart_item) {


                if (isset($cart_item['deposit']) && $cart_item['deposit']['enable'] === 'yes' && isset($cart_item['deposit']['deposit'])) {
                    $deposit_in_cart = true;
                    $product = wc_get_product($cart_item['product_id']);
                    $deposit_amount += $cart_item['deposit']['deposit'];
                    $deposit_product_taxes += $cart_item['deposit']['tax'] * $cart_item['quantity'];
                    $deposit_total += $cart_item['deposit']['total'];
                    if ($product->get_type() === 'subscription' && class_exists('WC_Subscriptions_Product')) {
                        $deposit_amount += WC_Subscriptions_Product::get_price($product);
                    }

                } else {
                    //YITH bundle compatiblity
                    if (isset($cart_item['bundled_by'])) {

                        $bundled_by = $cart->cart_contents[$cart_item['bundled_by']];
                        if (isset($bundled_by['deposit']) && $bundled_by['deposit']['enable'] === 'yes') {

                            if (!(isset($bundled_by['data']->per_items_pricing) && $bundled_by['data']->per_items_pricing)) {
                                $full_amount_products += $cart_item['line_total'];
                            }
                        } else {

                            $full_amount_products += $cart_item['line_total'];
                        }

                    } else {

                        if ($woocommerce_prices_include_tax !== 'yes') {
                            $full_amount_products += $cart_item['line_total'];
                        } else {
                            $full_amount_products += $cart_item['line_total'];
                            $full_amount_taxes += $cart_item['line_tax'];

                        }
                    }
                }


            }
        }

        if ($deposit_in_cart && $deposit_amount < ($deposit_total + $cart->fee_total + $cart->tax_total + $cart->shipping_total)) {
            if (!wcdp_checkout_mode()) {
                $deposit_amount += $full_amount_products;
                $deposit_enabled = true;
            } else {

                if (is_ajax() && isset($_POST['deposit-radio']) && $_POST['deposit-radio'] === 'deposit') {
                    $deposit_enabled = true;

                } elseif (is_ajax() && isset($_POST['deposit-radio']) && $_POST['deposit-radio'] === 'full') {

                    $deposit_enabled = false;
                } else {

                    $deposit_enabled = true;
                }
            }
        }

        $deposit_breakdown = null;

        /*
         * Additional fees handling.
         */
        $fees_handling = get_option('wc_deposits_fees_handling');
        $taxes_handling = get_option('wc_deposits_taxes_handling');
        $shipping_handling = get_option('wc_deposits_shipping_handling');
        $shipping_taxes_handling = get_option('wc_deposits_shipping_taxes_handling');

        // Default option: collect fees with the second payment.
        $deposit_fees = 0.0;
        $deposit_taxes = $full_amount_taxes;
        $deposit_shipping = 0.0;
        $deposit_shipping_taxes = 0.0;


        if (wcdp_checkout_mode()) {
            $division = floatval($cart->subtotal_ex_tax);
            $division = $division == 0 ? 1 : $division;
            $deposit_percentage = $deposit_amount * 100 / floatval($division);

        } else {
            $division = floatval($cart->subtotal_ex_tax);
            $division = $division == 0 ? 1 : $division;
            $deposit_percentage = $deposit_amount * 100 / floatval($division);

        }

        /*
        * Fees handling.
        */

        $fee_taxes = $cart->get_fee_tax();

        switch ($fees_handling) {
            case 'deposit' :


                $deposit_fees = floatval($woocommerce_prices_include_tax === 'yes' ? $cart->fee_total + $fee_taxes : $cart->fee_total);
                break;

            case 'split' :
                $deposit_fees = floatval($woocommerce_prices_include_tax === 'yes' ? $cart->fee_total + $fee_taxes : $cart->fee_total) * $deposit_percentage / 100;
                break;
        }

        /*
         * Taxes handling.
         */
        if (wcdp_checkout_mode()) {
            switch ($taxes_handling) {
                case 'deposit' :
                    $deposit_taxes = $cart->tax_total + $full_amount_taxes;
                    break;

                case 'split' :

                    $deposit_taxes = ($cart->tax_total + $full_amount_taxes) * $deposit_percentage / 100;

                    break;
            }
        } else {
            $deposit_taxes += $deposit_product_taxes;
        }

        /*
         * Shipping handling.
         */
        switch ($shipping_handling) {
            case 'deposit' :
                $deposit_shipping = $cart->shipping_total;
                break;

            case 'split' :
                $deposit_shipping = $cart->shipping_total * $deposit_percentage / 100;
                break;
        }

        /*
         * Shipping taxes handling.
         */
        switch ($shipping_taxes_handling) {
            case 'deposit' :
                $deposit_shipping_taxes = $cart->shipping_tax_total;
                break;

            case 'split' :
                $deposit_shipping_taxes = $cart->shipping_tax_total * $deposit_percentage / 100;
                break;
        }

        // Add fees, taxes, shipping and shipping taxes to the deposit amount.
        $cart_items_deposit_amount = $deposit_amount;

        //discount handling
        if (!wcdp_checkout_mode()) {

            foreach (WC()->cart->get_cart_contents() as $cart_item_key => $cart_item) {
                if (isset($cart_item['deposit']) && $cart_item['deposit']['enable'] === 'yes' && isset($cart_item['deposit']['percent_discount'])) {

//                    $deposit_amount -= $cart_item['deposit']['percent_discount'];
                    $cart_items_deposit_amount -= $cart_item['deposit']['percent_discount'];
                }
            }
        }


        $deposit_amount += $deposit_fees + $deposit_taxes + $deposit_shipping + $deposit_shipping_taxes;

        // Deposit breakdown tooltip.
        $deposit_breakdown = array(
            'cart_items' => $cart_items_deposit_amount,
            'fees' => $deposit_fees,
            'taxes' => $deposit_taxes,
            'shipping' => $deposit_shipping,
            'shipping_taxes' => $deposit_shipping_taxes,
        );


        $discount_from_deposit = get_option('wc_deposits_coupons_handling', 'second_payment');
        if ($discount_from_deposit === 'deposit') {
            $discount_total = WC()->cart->get_cart_discount_total();
            $deposit_amount -= $discount_total;

        } elseif ($discount_from_deposit === 'split') {
            $discount_deposit = WC()->cart->get_cart_discount_total() / 100 * $deposit_percentage;
            $deposit_amount -= $discount_deposit;

        }

        //round decimals according to woocommerce
        $deposit_amount = round($deposit_amount, wc_get_price_decimals());

        $deposit_amount = apply_filters('woocommerce_deposits_cart_deposit_amount', $deposit_amount, $cart_total);
        $second_payment = $cart_total - $deposit_amount;

        // no point of having deposit if second payment as 0 or in negative
        if ($second_payment <= 0) {
            $deposit_enabled = false;
        }
        WC()->cart->deposit_info = array();
        WC()->cart->deposit_info['deposit_enabled'] = $deposit_enabled;
        WC()->cart->deposit_info['deposit_breakdown'] = $deposit_breakdown;
        WC()->cart->deposit_info['deposit_amount'] = $deposit_amount;
        WC()->cart->deposit_info['second_payment'] = $second_payment;

        return $cart_original;

    }

    /**
     * @brief Display Deposit and remaining amount in cart totals area
     */
    public function cart_totals_after_order_total()
    {

        if (isset(WC()->cart->deposit_info['deposit_enabled']) && WC()->cart->deposit_info['deposit_enabled'] === true) :


            $to_pay_text = __(get_option('wc_deposits_to_pay_text'), 'woocommerce-deposits');
            $second_payment_text = __(get_option('wc_deposits_second_payment_text'), 'woocommerce-deposits');


            if ($to_pay_text === false) {
                $to_pay_text = __('To Pay', 'woocommerce-deposits');
            }


            if ($second_payment_text === false) {
                $second_payment_text = __('Second Payment', 'woocommerce-deposits');
            }
            $to_pay_text = stripslashes($to_pay_text);
            $second_payment_text = stripslashes($second_payment_text);


            $deposit_breakdown_tooltip = wc_deposits_deposit_breakdown_tooltip();

            ?>
            <tr class="order-paid">
                <th><?php echo $to_pay_text ?>&nbsp;&nbsp;<?php echo $deposit_breakdown_tooltip; ?>
                </th>
                <td data-title="<?php echo $to_pay_text; ?>">
                    <strong><?php echo wc_price(WC()->cart->deposit_info['deposit_amount']); ?></strong></td>
            </tr>
            <tr class="order-remaining">
                <th><?php echo $second_payment_text; ?></th>
                <td data-title="<?php echo $second_payment_text; ?>">
                    <strong><?php echo wc_price(WC()->cart->deposit_info['second_payment']); ?></strong></td>
            </tr>
        <?php
        endif;
    }


}