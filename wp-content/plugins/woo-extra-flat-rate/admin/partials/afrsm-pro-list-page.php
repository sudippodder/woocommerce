<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$get_action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
$get_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
$get_wpnonce = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
$retrieved_nonce = ( isset( $get_wpnonce ) ? sanitize_text_field( wp_unslash( $get_wpnonce ) ) : '' );
$afrsmnonce = wp_create_nonce( 'afrsmnonce' );
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-header.php';

if ( isset( $get_action ) && 'delete' === sanitize_text_field( wp_unslash( $get_action ) ) ) {
    if ( !wp_verify_nonce( $retrieved_nonce, 'afrsmnonce' ) ) {
        die( 'Failed security check' );
    }
    $get_post_id = sanitize_text_field( $get_id );
    wp_delete_post( $get_post_id );
    wp_redirect( admin_url( '/admin.php?page=afrsm-pro-list' ) );
    exit;
}

$admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( '', '' );
$get_all_sm = $admin_object::afrsm_pro_get_shipping_method( 'list' );
$default_lang = $admin_object->afrsm_pro_get_default_langugae_with_sitpress();
$getSortOrder = get_option( 'sm_sortable_order_' . $default_lang );
?>
    <div class="afrsm-section-left">
        <div class="afrsm-main-table res-cl">
            <div class="product_header_title">
                <h2>
					<?php 
esc_html_e( 'Shipping Methods', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                    <a class="add-new-btn"
                       href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'afrsm-pro-add-shipping',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'Add New Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                    <a id="delete-shipping-method"
                       class="delete-shipping-method"><?php 
esc_html_e( 'Delete (Selected)', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                    <a class="shipping-methods-order"><?php 
esc_html_e( 'Save Order', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </h2>
            </div>
            <table id="shipping-methods-listing" class="table-outer form-table shipping-methods-listing tablesorter">
				<?php 

if ( !empty($get_all_sm) ) {
    ?>
                    <thead>
                    <tr class="afrsm-head">
                        <th class="th_chk"><input type="checkbox" name="check_all" class="condition-check-all"></th>
						<?php 
    ?>
                        <th class="th_st"><?php 
    esc_html_e( 'Shipping Method Name', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></th>
                        <th class="th_amt"><?php 
    esc_html_e( 'Amount', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></th>
                        <th class="th_tax"><?php 
    esc_html_e( 'Taxable', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></th>
                        <th class="th_status"><?php 
    esc_html_e( 'Status', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></th>
                        <th class="th_action"><?php 
    esc_html_e( 'Actions', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?></th>
                    </tr>
                    </thead>

                    <tbody>
					<?php 
    $sort_order = array();
    if ( isset( $getSortOrder ) && !empty($getSortOrder) ) {
        foreach ( $getSortOrder as $sort ) {
            $sort_order[$sort] = array();
        }
    }
    foreach ( $get_all_sm as $carrier_id => $carrier ) {
        $carrier_name = $carrier->ID;
        
        if ( array_key_exists( $carrier_name, $sort_order ) ) {
            $sort_order[$carrier_name][$carrier_id] = $get_all_sm[$carrier_id];
            unset( $get_all_sm[$carrier_id] );
        }
    
    }
    foreach ( $sort_order as $carriers ) {
        $get_all_sm = array_merge( $get_all_sm, $carriers );
    }
    foreach ( $get_all_sm as $sm ) {
        $shipping_title = ( get_the_title( $sm->ID ) ? get_the_title( $sm->ID ) : 'Fee' );
        $shipping_cost = get_post_meta( $sm->ID, 'sm_product_cost', true );
        $sm_is_taxable = get_post_meta( $sm->ID, 'sm_select_taxable', true );
        $shipping_status = get_post_status( $sm->ID );
        $shipping_status_chk = ( !empty($shipping_status) && 'publish' === $shipping_status || empty($shipping_status) ? 'checked' : '' );
        ?>
                            <tr id="<?php 
        echo  esc_attr( $sm->ID ) ;
        ?>">
                                <td width="10%" class="th_chk">
                                    <input type="checkbox" name="multiple_delete_fee[]" class="multiple_delete_fee"
                                           value="<?php 
        echo  esc_attr( $sm->ID ) ;
        ?>">
                                </td>
								<?php 
        ?>
                                <td class="th_st">
                                    <a href="<?php 
        echo  esc_url( add_query_arg( array(
            'page'     => 'afrsm-pro-edit-shipping',
            'id'       => esc_attr( $sm->ID ),
            'action'   => 'edit',
            '_wpnonce' => esc_attr( $afrsmnonce ),
        ), admin_url( 'admin.php' ) ) ) ;
        ?>"><?php 
        esc_html_e( $shipping_title, 'advanced-flat-rate-shipping-for-woocommerce' );
        ?></a>
                                </td>
                                <td class="th_amt">
									<?php 
        
        if ( $shipping_cost > 0 ) {
            echo  esc_html( get_woocommerce_currency_symbol() ) . '&nbsp;' . esc_html( $shipping_cost ) ;
        } else {
            echo  esc_html( $shipping_cost ) ;
        }
        
        ?>
                                </td>
                                <td class="th_tax"><?php 
        echo  esc_html( $sm_is_taxable ) ;
        ?></td>
                                <td class="th_status">
                                    <label class="switch">
                                        <input type="checkbox" name="shipping_status" id="shipping_status_id"
                                               value="on" <?php 
        echo  esc_attr( $shipping_status_chk ) ;
        ?>
                                               data-smid="<?php 
        echo  esc_attr( $sm->ID ) ;
        ?>">
                                        <div class="slider round"></div>
                                    </label>
                                </td>
                                <td class="th_action">
                                    <a class="fee-action-button button-primary"
                                       href="<?php 
        echo  esc_url( add_query_arg( array(
            'page'     => 'afrsm-pro-edit-shipping',
            'id'       => esc_attr( $sm->ID ),
            'action'   => 'edit',
            '_wpnonce' => esc_attr( $afrsmnonce ),
        ), admin_url( 'admin.php' ) ) ) ;
        ?>"><?php 
        esc_html_e( 'Edit', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?></a>
                                    <a class="fee-action-button button-primary"
                                       href="<?php 
        echo  esc_url( add_query_arg( array(
            'page'     => 'afrsm-pro-list',
            'id'       => esc_attr( $sm->ID ),
            'action'   => 'delete',
            '_wpnonce' => esc_attr( $afrsmnonce ),
        ), admin_url( 'admin.php' ) ) ) ;
        ?>"
                                       onclick="return confirm('<?php 
        esc_html_e( 'Are you sure you want to delete this shipping method?', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?>');"><?php 
        esc_html_e( 'Delete', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?></a>
                                    <a class="fee-action-button button-primary" href="javascript:void(0);"
                                       id="clone_shipping_method"
                                       data-attr="<?php 
        echo  esc_attr( $sm->ID ) ;
        ?>"><?php 
        esc_html_e( 'Clone', 'advanced-flat-rate-shipping-for-woocommerce' );
        ?></a>
                                </td>
                            </tr>
						<?php 
    }
    ?>
                    </tbody>
				<?php 
} else {
    ?>
                    <tfoot>
                    <tr class="no_list">
                        <td>
							<?php 
    esc_html_e( 'No shipping method found', 'advanced-flat-rate-shipping-for-woocommerce' );
    ?>
                        </td>
                    </tr>
                    </tfoot>
					<?php 
}

?>
            </table>
        </div>
        <div class="afrsm-mastersettings">
            <div class="mastersettings-title">
                <h2><?php 
esc_html_e( 'Master Settings', 'advanced-flat-rate-shipping-for-woocommerce' );
?></h2>
            </div>
			<?php 
$shipping_method_format = get_option( 'md_woocommerce_shipping_method_format' );
$chk_enable_logging = get_option( 'chk_enable_logging' );
$chk_enable_logging_checked = ( !empty($chk_enable_logging) && 'on' === $chk_enable_logging || empty($chk_enable_logging) ? 'checked' : '' );
?>
            <table class="table-mastersettings table-outer" cellpadding="0" cellspacing="0">
                <tbody>
				<?php 
?>
                <tr valign="top" id="display_mode">
                    <td class="table-whattodo"><?php 
esc_html_e( 'Shipping Display Mode', 'advanced-flat-rate-shipping-for-woocommerce' );
?></td>
                    <td>
                        <select name="shipping_display_mode" id="shipping_display_mode">
                            <option value="radio_button_mode"<?php 
echo  ( isset( $shipping_method_format ) && 'radio_button_mode' === $shipping_method_format ? ' selected=selected' : '' ) ;
?>><?php 
esc_html_e( 'Display shipping methods with radio buttons', 'advanced-flat-rate-shipping-for-woocommerce' );
?></option>
                            <option value="dropdown_mode"<?php 
echo  ( isset( $shipping_method_format ) && 'dropdown_mode' === $shipping_method_format ? ' selected=selected' : '' ) ;
?>><?php 
esc_html_e( 'Display shipping methods in a dropdown', 'advanced-flat-rate-shipping-for-woocommerce' );
?></option>
                        </select>
                    </td>
                </tr>
				<?php 
?>
                <tr valign="top" id="enable_logging">
                    <td class="table-whattodo"><?php 
esc_html_e( 'Enable Logging', 'advanced-flat-rate-shipping-for-woocommerce' );
?></td>
                    <td>
                        <input type="checkbox" name="chk_enable_logging" id="chk_enable_logging"
                               value="on" <?php 
echo  esc_attr( $chk_enable_logging_checked ) ;
?>>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="button-primary" id="save_master_settings"
                              name="save_master_settings"><?php 
esc_html_e( 'Save Master Settings', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>

<?php 
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php';