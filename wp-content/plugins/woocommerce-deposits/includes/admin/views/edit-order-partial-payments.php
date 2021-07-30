<?php
if ($order && $order->get_type() !== 'wcdp_payment') {
    $payment_schedule = $order->get_meta('_wc_deposits_payment_schedule', true);

    if (empty($payment_schedule)) {
        ?>
        <div><h4><?php _e('No payment schedule found.', 'woocommerce-deposits'); ?></h4></div>
        <?php
    } else {
        ?>
        <table style="width:100%; text-align:left;">
            <thead>
            <tr>
                <th><?php _e('Payment', 'woocommerce-deposits'); ?> </th>
                <th><?php _e('Payment ID', 'woocommerce-deposits'); ?> </th>
                <th><?php _e('Payment method', 'woocommerce-deposits'); ?> </th>
                <th><?php _e('Status', 'woocommerce-deposits'); ?> </th>
                <th><?php _e('Amount', 'woocommerce-deposits'); ?> </th>
                <th><?php _e('Actions', 'woocommerce-deposits'); ?> </th>

            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($payment_schedule as $timestamp => $payment) {

                $title = '';


                if (isset($payment['title'])) {

                    $title = $payment['title'];
                } else {

                    if (!is_numeric($timestamp)) {

                        if ($timestamp === 'unlimited') {
                            $title = __('Second Payment', 'woocommerce-deposits');
                        } elseif ($timestamp === 'deposit') {
                            $title = __('Deposit', 'woocommerce-deposits');
                        } else {
                            $title = $timestamp;
                        }

                    } else {
                        $title = date('Y-M-d', $timestamp);
                    }
                }

                $title = apply_filters('wc_deposits_partial_payment_title', $title, $payment);

                $payment_order = false;
                if (isset($payment['id']) && !empty($payment['id'])) $payment_order = wc_get_order($payment['id']);

                $gateway = $payment_order ? $payment_order->get_payment_method_title() : '-';
                $payment_id = $payment_order ? '<a href="' . esc_url($payment_order->get_edit_order_url()) . '">' . $payment_order->get_order_number() . '</a>' : '-';
                $status = $payment_order ? wc_get_order_status_name($payment_order->get_status()) : '-';
                $amount = $payment_order ? $payment_order->get_total() : $payment['total'];
                $price_args = array('currency' => $payment_order->get_currency());

                $actions = array();

                if ($payment_order) {
                    $actions['view'] = '<a class="button btn" href="';
                    $actions['view'] .= $payment_order ? esc_url($payment_order->get_edit_order_url()) . '">' : '#">';
                    $actions['view'] .= __('View', 'woocommerce-deposits') . '</a>';
                }

                $actions = apply_filters('wc_deposits_admin_partial_payment_actions', $actions, $payment_order, $order->get_id());

                ?>
                <tr>
                    <td><? echo $title; ?></td>
                    <td><?php echo $payment_id; ?></td>
                    <td><?php echo $gateway; ?></td>
                    <td><?php echo $status; ?></td>
                    <td><?php echo wc_price($amount,$price_args); ?></td>
                    <td>
                        <?php foreach ($actions as $action) {
                            echo $action . "\n\n\n";
                        } ?>

                    </td>


                </tr>
                <?php
            }
            ?>


            </tbody>

        </table>

        <?php

    }

    ?>

    <script>
        jQuery(document).ready(function ($) {
            function reload_metabox() {

                $('#wc_deposits_partial_payments').block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });

                var data = {
                    action: 'wc_deposits_reload_partial_payments_metabox',
                    order_id: woocommerce_admin_meta_boxes.post_id,
                    security: wc_deposits_data.security
                };

                $.ajax(
                    {
                        url: wc_deposits_data.ajax_url,
                        data: data,
                        type: 'POST',
                        success: function (res) {
                            if (res.success) {


                                $('#wc_deposits_partial_payments div.inside').empty().append(res.data.html);

                                $('#woocommerce-order-items').unblock();
                                $('#wc_deposits_partial_payments').unblock().trigger('wc_deposits_recalculated');
                                $('#wc_deposits_partial_payments').trigger('wc_deposits_recalculated');

                            }
                        }

                    }
                );

            }

            $('button.button.button-primary.save-action').on('items_saved', function (e) {
                window.setTimeout(function () {
                    reload_metabox();
                }, 1500);
            });

        });

    </script>
    <?php
}
