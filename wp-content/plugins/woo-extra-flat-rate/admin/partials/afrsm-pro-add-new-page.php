<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-header.php';
$afrsm_admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( '', '' );
$afrsm_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro( '', '' );
$get_action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
/*
 * save all posted data method define in class-advanced-flat-rate-shipping-for-woocommerce-admin
 */

if ( isset( $_POST['submitFee'] ) && !empty($_POST['submitFee']) ) {
    $post_wpnonce = filter_input( INPUT_POST, 'afrsm_pro_conditions_save', FILTER_SANITIZE_STRING );
    $post_retrieved_nonce = ( isset( $post_wpnonce ) ? sanitize_text_field( wp_unslash( $post_wpnonce ) ) : '' );
    
    if ( !wp_verify_nonce( $post_retrieved_nonce, 'afrsm_pro_save_action' ) ) {
        die( 'Failed security check' );
    } else {
        $post_data = $_POST;
        $afrsm_admin_object->afrsm_pro_fees_conditions_save( $post_data );
    }

}

/*
 * edit all posted data method define in class-advanced-flat-rate-shipping-for-woocommerce-admin
 */

if ( isset( $get_action ) && 'edit' === $get_action ) {
    $get_wpnonce = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
    $get_retrieved_nonce = ( isset( $get_wpnonce ) ? sanitize_text_field( wp_unslash( $get_wpnonce ) ) : '' );
    if ( !wp_verify_nonce( $get_retrieved_nonce, 'afrsmnonce' ) ) {
        die( 'Failed security check' );
    }
    $get_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
    $get_post_id = ( isset( $get_id ) ? sanitize_text_field( wp_unslash( $get_id ) ) : '' );
    $sm_status = get_post_status( $get_post_id );
    $sm_title = __( get_the_title( $get_post_id ), 'advanced-flat-rate-shipping-for-woocommerce' );
    $sm_cost = get_post_meta( $get_post_id, 'sm_product_cost', true );
    $sm_tooltip_desc = get_post_meta( $get_post_id, 'sm_tooltip_desc', true );
    $sm_is_taxable = get_post_meta( $get_post_id, 'sm_select_taxable', true );
    $sm_metabox = get_post_meta( $get_post_id, 'sm_metabox', true );
    
    if ( is_serialized( $sm_metabox ) ) {
        $sm_metabox = maybe_unserialize( $sm_metabox );
    } else {
        $sm_metabox = $sm_metabox;
    }
    
    $sm_extra_cost = get_post_meta( $get_post_id, 'sm_extra_cost', true );
    
    if ( is_serialized( $sm_extra_cost ) ) {
        $sm_extra_cost = maybe_unserialize( $sm_extra_cost );
    } else {
        $sm_extra_cost = $sm_extra_cost;
    }
    
    $sm_extra_cost_calc_type = get_post_meta( $get_post_id, 'sm_extra_cost_calculation_type', true );
} else {
    $get_post_id = '';
    $sm_status = '';
    $sm_title = '';
    $sm_cost = '';
    $sm_tooltip_desc = '';
    $sm_is_taxable = '';
    $sm_metabox = array();
    $sm_extra_cost = array();
    $sm_extra_cost_calc_type = '';
}

$sm_status = ( !empty($sm_status) && 'publish' === $sm_status || empty($sm_status) ? 'checked' : '' );
$sm_title = ( !empty($sm_title) ? esc_attr( stripslashes( $sm_title ) ) : '' );
$sm_cost = ( '' !== $sm_cost ? esc_attr( stripslashes( $sm_cost ) ) : '' );
$sm_tooltip_desc = ( !empty($sm_tooltip_desc) ? $sm_tooltip_desc : '' );
$submit_text = __( 'Save changes', 'advanced-flat-rate-shipping-for-woocommerce' );
// Shipping Rules Condition
?>
    <div class="text-condtion-is" style="display:none;">
        <select class="text-condition">
            <option value="is_equal_to"><?php 
esc_html_e( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' );
?></option>
            <option value="less_equal_to"><?php 
esc_html_e( 'Less or Equal to ( <= )', 'advanced-flat-rate-shipping-for-woocommerce' );
?></option>
            <option value="less_then"><?php 
esc_html_e( 'Less than ( < )', 'advanced-flat-rate-shipping-for-woocommerce' );
?></option>
            <option value="greater_equal_to"><?php 
esc_html_e( 'Greater or Equal to ( >= )', 'advanced-flat-rate-shipping-for-woocommerce' );
?></option>
            <option value="greater_then"><?php 
esc_html_e( 'Greater than ( > )', 'advanced-flat-rate-shipping-for-woocommerce' );
?></option>
            <option value="not_in"><?php 
esc_html_e( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' );
?></option>
        </select>
        <select class="select-condition">
            <option value="is_equal_to"><?php 
esc_html_e( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' );
?></option>
            <option value="not_in"><?php 
esc_html_e( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' );
?></option>
        </select>
    </div>
    <div class="default-country-box" style="display:none;">
		<?php 
echo  wp_kses( $afrsm_admin_object->afrsm_pro_get_country_list(), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() ) ;
?>
    </div>

    <div class="afrsm-section-left">
        <div class="afrsm-main-table res-cl">
            <h2><?php 
esc_html_e( 'Shipping Method Configuration', 'advanced-flat-rate-shipping-for-woocommerce' );
?></h2>
            <form method="POST" name="feefrm" action="">
				<?php 
wp_nonce_field( 'afrsm_pro_save_action', 'afrsm_pro_conditions_save' );
?>
                <input type="hidden" name="post_type" value="wc_afrsm">
                <input type="hidden" name="fee_post_id" value="<?php 
echo  esc_attr( $get_post_id ) ;
?>">
                <table class="form-table table-outer shipping-method-table">
                    <tbody>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="onoffswitch"><?php 
esc_html_e( 'Status', 'advanced-flat-rate-shipping-for-woocommerce' );
?></label>
                        </th>
                        <td class="forminp">
                            <label class="switch">
                                <input type="checkbox" name="sm_status"
                                       value="on" <?php 
echo  esc_attr( $sm_status ) ;
?>>
                                <div class="slider round"></div>
                            </label>
                            <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
                            <p class="description" style="display:none;">
								<?php 
esc_html_e( 'Enable or Disable this shipping method using this button (This method will be visible to customers only if it is enabled).', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                            </p>
                        </td>
                    </tr>
					<?php 
?>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="fee_settings_product_fee_title"><?php 
esc_html_e( 'Shipping Method Name (For public view)', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                                <span class="required-star">*</span>
                            </label>
                        </th>
                        <td class="forminp">
                            <input type="text" name="fee_settings_product_fee_title" class="text-class"
                                   id="fee_settings_product_fee_title" value="<?php 
echo  esc_attr( $sm_title ) ;
?>"
                                   required="1"
                                   placeholder="<?php 
esc_html_e( 'Enter product fees title', 'advanced-flat-rate-shipping-for-woocommerce' );
?>">
                            <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
                            <p class="description" style="display:none;">
								<?php 
esc_html_e( 'This name will be visible to the customer at the time of checkout. This should convey the purpose of the charges you are applying to the order. For example "Ground Shipping", "Express Shipping Flat Rate", "Christmas Next Day Shipping" etc', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="sm_product_cost"><?php 
esc_html_e( 'Shipping Charge', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                                (<?php 
echo  esc_html_e( get_woocommerce_currency_symbol() ) ;
?>)
                                <span class="required-star">*</span>
                            </label>
                        </th>

                        <td class="forminp">
                            <div class="product_cost_left_div">
                                <input type="text" name="sm_product_cost" required="1" class="text-class"
                                       id="sm_product_cost" value="<?php 
echo  esc_attr( $sm_cost ) ;
?>"
                                       placeholder="<?php 
echo  esc_attr( get_woocommerce_currency_symbol() ) ;
?>">
                                <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
                                <p class="description" style="display:none;">
									<?php 
$html = sprintf(
    '%s<br>%s<br>%s<br>%s<br>%s<br><br>%s<br>%s<br>%s<br>%s<br>%s',
    esc_html__( 'When customer select this shipping method the amount will be added to the cart subtotal. You can enter fixed amount or make it dynamic using below parameters:', 'advanced-flat-rate-shipping-for-woocommerce' ),
    esc_html__( '[qty] -> total number of items in cart,', 'advanced-flat-rate-shipping-for-woocommerce' ),
    esc_html__( '[cost] -> cost of items,', 'advanced-flat-rate-shipping-for-woocommerce' ),
    esc_html__( '[fee percent=10 min_fee=20] -> Percentage based fee,', 'advanced-flat-rate-shipping-for-woocommerce' ),
    esc_html__( '[fee percent=10 max_fee=20] -> Percentage based fee.', 'advanced-flat-rate-shipping-for-woocommerce' ),
    esc_html__( 'Below are some examples: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
    esc_html__( 'i. 10.00  -> To add flat 10.00 shipping charge.', 'advanced-flat-rate-shipping-for-woocommerce' ),
    esc_html__( 'ii. 10.00 * [qty] -> To charge 10.00 per quantity in the cart. It will be 50.00 if the cart has 5 quantity.', 'advanced-flat-rate-shipping-for-woocommerce' ),
    esc_html__( 'iii. [fee percent=10 min_fee=20] -> This means charge 10 percent of cart subtotal, minimum 20 charge will be applicable.', 'advanced-flat-rate-shipping-for-woocommerce' ),
    esc_html__( 'iv. [fee percent=10 max_fee=20] -> This means charge 10 percent of cart subtotal greater than max_fee then maximum 20 charge will be applicable.', 'advanced-flat-rate-shipping-for-woocommerce' )
);
echo  wp_kses_post( $html ) ;
?>
                                </p>
                            </div>
							<?php 
?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="sm_tooltip_desc"><?php 
esc_html_e( 'Tooltip Description', 'advanced-flat-rate-shipping-for-woocommerce' );
?></label>
                        </th>
                        <td class="forminp">
                            <textarea name="sm_tooltip_desc" rows="3" cols="70" id="sm_tooltip_desc"
                                      placeholder="<?php 
esc_html_e( 'Enter tooltip short description', 'advanced-flat-rate-shipping-for-woocommerce' );
?>"><?php 
echo  wp_kses_post( $sm_tooltip_desc ) ;
?></textarea>

                            <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
							<?php 
?>
                                    <p class="description" style="display:none;">
										<?php 
esc_html_e( 'Not for dropdown shipping method', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                                    </p>
									<?php 
?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="sm_select_taxable"><?php 
esc_html_e( 'Is Amount Taxable?', 'advanced-flat-rate-shipping-for-woocommerce' );
?></label>
                        </th>
                        <td class="forminp">
                            <select name="sm_select_taxable" id="sm_select_taxable" class="afrsm_select">
                                <option value="no" <?php 
echo  ( isset( $sm_is_taxable ) && 'no' === $sm_is_taxable ? 'selected="selected"' : '' ) ;
?>><?php 
esc_html_e( 'No', 'advanced-flat-rate-shipping-for-woocommerce' );
?></option>
                                <option value="yes" <?php 
echo  ( isset( $sm_is_taxable ) && 'yes' === $sm_is_taxable ? 'selected="selected"' : '' ) ;
?>><?php 
esc_html_e( 'Yes', 'advanced-flat-rate-shipping-for-woocommerce' );
?></option>
                            </select>
                        </td>
                    </tr>
					<?php 
?>
                    </tbody>
                </table>
				<?php 
$all_shipping_classes = WC()->shipping->get_shipping_classes();

if ( !empty($all_shipping_classes) ) {
    ?>
                        <div class="sub-title">
                            <h2><?php 
    esc_html_e( 'Additional Shipping Charges Based on Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></h2>
                        </div>
                        <div class="tap">
                            <table class="form-table table-outer shipping-method-table">
                                <tbody>
                                <tr valign="top">
                                    <td class="forminp" colspan="2">
										<?php 
    $html = sprintf(
        '%s<a href=%s>%s</a>.',
        esc_html__( 'These costs can optionally be added based on the ', 'advanced-flat-rate-shipping-for-woocommerce' ),
        esc_url( add_query_arg( array(
        'page'    => 'wc-settings',
        'tab'     => 'shipping',
        'section' => 'classes',
    ), admin_url( 'admin.php' ) ) ),
        esc_html__( 'product shipping class', 'advanced-flat-rate-shipping-for-woocommerce' )
    );
    echo  wp_kses_post( $html ) ;
    ?>
                                    </td>
                                </tr>
								<?php 
    foreach ( $all_shipping_classes as $key => $shipping_class ) {
        $shipping_extra_cost = ( isset( $sm_extra_cost["{$shipping_class->term_id}"] ) && '' !== $sm_extra_cost["{$shipping_class->term_id}"] ? $sm_extra_cost["{$shipping_class->term_id}"] : "" );
        ?>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row">
                                                <label for="extra_cost_<?php 
        echo  esc_attr( $shipping_class->term_id ) ;
        ?>">
													<?php 
        echo  sprintf( esc_html__( '"%s" shipping class cost', 'advanced-flat-rate-shipping-for-woocommerce' ), esc_html( $shipping_class->name ) ) ;
        ?>
                                                </label>
                                            </th>
                                            <td class="forminp">
                                                <input type="text"
                                                       name="sm_extra_cost[<?php 
        echo  esc_attr( $shipping_class->term_id ) ;
        ?>]"
                                                       class="text-class"
                                                       id="extra_cost_<?php 
        echo  esc_attr( $shipping_class->term_id ) ;
        ?>"
                                                       value="<?php 
        echo  esc_attr( $shipping_extra_cost ) ;
        ?>"
                                                       placeholder="<?php 
        echo  esc_attr( get_woocommerce_currency_symbol() ) ;
        ?>">
                                            </td>
                                        </tr>
									<?php 
    }
    ?>
                                <tr valign="top">
                                    <th class="titledesc" scope="row">
                                        <label for="sm_extra_cost_calculation_type"><?php 
    esc_html_e( 'Calculation type', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></label>
                                    </th>
                                    <td class="forminp">
                                        <select name="sm_extra_cost_calculation_type"
                                                id="sm_extra_cost_calculation_type">
                                            <option value="per_class" <?php 
    selected( $sm_extra_cost_calc_type, 'per_class' );
    ?>>
												<?php 
    esc_html_e( 'Per class: Charge shipping for each shipping class individually', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?>
                                            </option>
                                            <option value="per_order" <?php 
    selected( $sm_extra_cost_calc_type, 'per_order' );
    ?>>
												<?php 
    esc_html_e( 'Per order: Charge shipping for the most expensive shipping class', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?>
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
					<?php 
}

?>
                <div class="shipping-method-rules">
                    <div class="sub-title">
                        <h2><?php 
esc_html_e( 'Shipping Method Rules', 'advanced-flat-rate-shipping-for-woocommerce' );
?></h2>
                        <div class="tap">
                            <a id="fee-add-field" class="button button-primary button-large"
                               href="javascript:;"><?php 
esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                        </div>
						<?php 
?>
                    </div>
                    <div class="tap">
                        <table id="tbl-shipping-method"
                               class="tbl_product_fee table-outer tap-cas form-table shipping-method-table">
                            <tbody>
							<?php 
$attribute_taxonomies = wc_get_attribute_taxonomies();
$attribute_taxonomies_name = wc_get_attribute_taxonomy_names();

if ( isset( $sm_metabox ) && !empty($sm_metabox) ) {
    $i = 2;
    foreach ( $sm_metabox as $key => $productfees ) {
        $fees_conditions = ( isset( $productfees['product_fees_conditions_condition'] ) ? $productfees['product_fees_conditions_condition'] : '' );
        $condition_is = ( isset( $productfees['product_fees_conditions_is'] ) ? $productfees['product_fees_conditions_is'] : '' );
        $condtion_value = ( isset( $productfees['product_fees_conditions_values'] ) ? $productfees['product_fees_conditions_values'] : array() );
        ?>
                                        <tr id="row_<?php 
        echo  esc_attr( $i ) ;
        ?>" valign="top">
                                            <th class="titledesc th_product_fees_conditions_condition" scope="row">
                                                <select rel-id="<?php 
        echo  esc_attr( $i ) ;
        ?>"
                                                        id="product_fees_conditions_condition_<?php 
        echo  esc_attr( $i ) ;
        ?>"
                                                        name="fees[product_fees_conditions_condition][]"
                                                        id="product_fees_conditions_condition"
                                                        class="product_fees_conditions_condition">
                                                    <optgroup
                                                            label="<?php 
        esc_html_e( 'Location Specific', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?>">
                                                        <option value="country" <?php 
        echo  ( 'country' === $fees_conditions ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?></option>
														<?php 
        ?>
                                                    </optgroup>
                                                    <optgroup
                                                            label="<?php 
        esc_html_e( 'Product Specific', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?>">
                                                        <option value="product" <?php 
        echo  ( 'product' === $fees_conditions ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Cart contains product', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?></option>
														<?php 
        ?>
                                                        <option value="category" <?php 
        echo  ( 'category' === $fees_conditions ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Cart contains category\'s product', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?></option>
                                                        <option value="tag" <?php 
        echo  ( 'tag' === $fees_conditions ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Cart contains tag\'s product', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?></option>
														<?php 
        ?>
                                                    </optgroup>
													<?php 
        ?>
                                                    <optgroup
                                                            label="<?php 
        esc_html_e( 'User Specific', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?>">
                                                        <option value="user" <?php 
        echo  ( 'user' === $fees_conditions ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'User', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?></option>
														<?php 
        ?>
                                                    </optgroup>
                                                    <optgroup
                                                            label="<?php 
        esc_html_e( 'Cart Specific', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?>">
														<?php 
        $currency_symbol = get_woocommerce_currency_symbol();
        $currency_symbol = ( !empty($currency_symbol) ? '(' . $currency_symbol . ')' : '' );
        $weight_unit = get_option( 'woocommerce_weight_unit' );
        $weight_unit = ( !empty($weight_unit) ? '(' . $weight_unit . ')' : '' );
        ?>
                                                        <option value="cart_total" <?php 
        echo  ( 'cart_total' === $fees_conditions ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Cart Subtotal (Before Discount) ', 'advanced-flat-rate-shipping-for-woocommerce' );
        echo  esc_html( $currency_symbol ) ;
        ?></option>
														<?php 
        ?>
                                                        <option value="quantity" <?php 
        echo  ( 'quantity' === $fees_conditions ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?></option>
														<?php 
        ?>
                                                    </optgroup>
													<?php 
        ?>
                                                </select>
                                            </th>
                                            <td class="select_condition_for_in_notin">
												<?php 
        
        if ( 'cart_total' === $fees_conditions || 'cart_totalafter' === $fees_conditions || 'quantity' === $fees_conditions || 'weight' === $fees_conditions ) {
            ?>
                                                    <select name="fees[product_fees_conditions_is][]"
                                                            class="product_fees_conditions_is_<?php 
            echo  esc_attr( $i ) ;
            ?>">
                                                        <option value="is_equal_to" <?php 
            echo  ( 'is_equal_to' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' );
            ?></option>
                                                        <option value="less_equal_to" <?php 
            echo  ( 'less_equal_to' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Less or Equal to ( <= )', 'advanced-flat-rate-shipping-for-woocommerce' );
            ?></option>
                                                        <option value="less_then" <?php 
            echo  ( 'less_then' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Less than ( < )', 'advanced-flat-rate-shipping-for-woocommerce' );
            ?></option>
                                                        <option value="greater_equal_to" <?php 
            echo  ( 'greater_equal_to' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Greater or Equal to ( >= )', 'advanced-flat-rate-shipping-for-woocommerce' );
            ?></option>
                                                        <option value="greater_then" <?php 
            echo  ( 'greater_then' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Greater than ( > )', 'advanced-flat-rate-shipping-for-woocommerce' );
            ?></option>
                                                        <option value="not_in" <?php 
            echo  ( 'not_in' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' );
            ?></option>
                                                    </select>
												<?php 
        } else {
            ?>
                                                    <select name="fees[product_fees_conditions_is][]"
                                                            class="product_fees_conditions_is_<?php 
            echo  esc_attr( $i ) ;
            ?>">
                                                        <option value="is_equal_to" <?php 
            echo  ( 'is_equal_to' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' );
            ?></option>
                                                        <option value="not_in" <?php 
            echo  ( 'not_in' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' );
            ?> </option>
                                                    </select>
												<?php 
        }
        
        ?>
                                            </td>
                                            <td class="condition-value" id="column_<?php 
        echo  esc_attr( $i ) ;
        ?>">
												<?php 
        $html = '';
        
        if ( 'country' === $fees_conditions ) {
            $html .= $afrsm_admin_object->afrsm_pro_get_country_list( $i, $condtion_value );
        } elseif ( 'product' === $fees_conditions ) {
            $html .= $afrsm_admin_object->afrsm_pro_get_product_list( $i, $condtion_value );
        } elseif ( 'category' === $fees_conditions ) {
            $html .= $afrsm_admin_object->afrsm_pro_get_category_list( $i, $condtion_value );
        } elseif ( 'tag' === $fees_conditions ) {
            $html .= $afrsm_admin_object->afrsm_pro_get_tag_list( $i, $condtion_value );
        } elseif ( 'user' === $fees_conditions ) {
            $html .= $afrsm_admin_object->afrsm_pro_get_user_list( $i, $condtion_value );
        } elseif ( 'cart_total' === $fees_conditions ) {
            $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values price-class" value = "' . esc_attr( $condtion_value ) . '">';
        } elseif ( 'quantity' === $fees_conditions ) {
            $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . esc_attr( $condtion_value ) . '">';
        }
        
        echo  wp_kses( $html, Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() ) ;
        ?>
                                                <input type="hidden"
                                                       name="condition_key[value_<?php 
        echo  esc_attr( $i ) ;
        ?>]"
                                                       value="">
                                            </td>
                                            <td>
                                                <a id="fee-delete-field" rel-id="<?php 
        echo  esc_attr( $i ) ;
        ?>"
                                                   class="delete-row" href="javascript:;" title="Delete"><i
                                                            class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
										<?php 
        $i++;
    }
    ?>
									<?php 
} else {
    $i = 1;
    ?>
                                    <tr id="row_1" valign="top">
                                        <th class="titledesc th_product_fees_conditions_condition" scope="row">
                                            <select rel-id="1" id="product_fees_conditions_condition_1"
                                                    name="fees[product_fees_conditions_condition][]"
                                                    id="product_fees_conditions_condition"
                                                    class="product_fees_conditions_condition">
                                                <optgroup
                                                        label="<?php 
    esc_html_e( 'Location Specific', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?>">
                                                    <option value="country"><?php 
    esc_html_e( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></option>
													<?php 
    ?>
                                                </optgroup>
                                                <optgroup
                                                        label="<?php 
    esc_html_e( 'Product Specific', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?>">
                                                    <option value="product"><?php 
    esc_html_e( 'Cart contains product', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></option>
													<?php 
    ?>
                                                    <option value="category"><?php 
    esc_html_e( 'Cart contains category\'s product', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></option>
                                                    <option value="tag"><?php 
    esc_html_e( 'Cart contains tag\'s product', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></option>
													<?php 
    ?>
                                                </optgroup>
												<?php 
    ?>
                                                <optgroup
                                                        label="<?php 
    esc_html_e( 'User Specific', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?>">

                                                    <option value="user"><?php 
    esc_html_e( 'User', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></option>
													<?php 
    ?>
                                                </optgroup>
                                                <optgroup
                                                        label="<?php 
    esc_html_e( 'Cart Specific', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?>">
													<?php 
    $get_woocommerce_currency_symbol = get_woocommerce_currency_symbol();
    $woocommerce_weight_unit = get_option( 'woocommerce_weight_unit' );
    $currency_symbol = ( !empty($get_woocommerce_currency_symbol) ? '(' . $get_woocommerce_currency_symbol . ')' : '' );
    $weight_unit = ( !empty($woocommerce_weight_unit) ? '(' . $woocommerce_weight_unit . ')' : '' );
    ?>
                                                    <option value="cart_total"><?php 
    esc_html_e( 'Cart Subtotal (Before Discount) ', 'advanced-flat-rate-shipping-for-woocommerce' );
    echo  esc_html( $currency_symbol ) ;
    ?></option>
													<?php 
    ?>
                                                    <option value="quantity"><?php 
    esc_html_e( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></option>
													<?php 
    ?>
                                                </optgroup>
												<?php 
    ?>
                                            </select>
                                        <td class="select_condition_for_in_notin">
                                            <select name="fees[product_fees_conditions_is][]"
                                                    class="product_fees_conditions_is product_fees_conditions_is_1">
                                                <option value="is_equal_to"><?php 
    esc_html_e( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></option>
                                                <option value="not_in"><?php 
    esc_html_e( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></option>
                                            </select>
                                        </td>
                                        <td id="column_1" class="condition-value">
											<?php 
    echo  wp_kses( $afrsm_admin_object->afrsm_pro_get_country_list( 1 ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() ) ;
    ?>
                                            <input type="hidden" name="condition_key[value_1][]" value="">
                                        </td>
                                    </tr>
								<?php 
}

?>
                            </tbody>
                        </table>
                        <input type="hidden" name="total_row" id="total_row" value="<?php 
echo  esc_attr( $i ) ;
?>">
                    </div>
                </div>
				<?php 
?>
                <p class="submit">
                    <input type="submit" name="submitFee" class="button button-primary button-large"
                           value="<?php 
echo  esc_attr( $submit_text ) ;
?>">
                </p>
            </form>
        </div>

    </div>
<?php 
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php';