<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$plugin_name = AFRSM_PRO_PLUGIN_NAME;
$plugin_version = AFRSM_PRO_PLUGIN_VERSION;
$afrsm_admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( '', '' );
?>
<div id="dotsstoremain">
    <div class="all-pad">
        <header class="dots-header">
            <div class="dots-logo-main">
                <img src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/advance-flat-rate.png' ) ;
?>">
            </div>
            <div class="dots-header-right">
                <div class="logo-detail">
                    <strong><?php 
esc_html_e( $plugin_name, 'advanced-flat-rate-shipping-for-woocommerce' );
?></strong>
                    <span>
                        <?php 
esc_html_e( AFRSM_PRO_PREMIUM_VERSION, 'advanced-flat-rate-shipping-for-woocommerce' );
?>&nbsp;<?php 
echo  esc_html__( $plugin_version, 'advanced-flat-rate-shipping-for-woocommerce' ) ;
?>
                    </span>
                </div>
                <div class="button-group">
                    <div class="button-dots-left">
						<?php 
?>
                                <span>
                                <a target="_blank"
                                   href="<?php 
echo  esc_url( 'www.thedotstore.com/advanced-flat-rate-shipping-method-for-woocommerce' ) ;
?>">
                                    <img src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/upgrade_new.png' ) ;
?>">
                                </a>
                            </span>
								<?php 
?>
                    </div>
                    <div class="button-dots">
                        <span class="support_dotstore_image">
                            <a target="_blank" href="<?php 
echo  esc_url( 'http://www.thedotstore.com/support/' ) ;
?>">
                                <img src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/support_new.png' ) ;
?>">
                            </a>
                        </span>
                    </div>
                </div>
            </div>
			
			<?php 
$current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
$afrsm_admin_object->afrsm_pro_menus( $current_page );
?>
        </header>