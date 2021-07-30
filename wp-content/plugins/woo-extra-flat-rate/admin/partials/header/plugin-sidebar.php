<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$image_url = AFRSM_PRO_PLUGIN_URL . 'admin/images/right_click.png';
?>
<div class="dotstore_plugin_sidebar">
	<?php 
?>
            <div class="dotstore_discount_voucher">
                <span class="dotstore_discount_title"><?php 
esc_html_e( 'Discount Voucher', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
                <span class="dotstore-upgrade"><?php 
esc_html_e( 'Upgrade to premium now and get', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
                <strong class="dotstore-OFF"><?php 
esc_html_e( '10% OFF', 'advanced-flat-rate-shipping-for-woocommerce' );
?></strong>
                <span class="dotstore-with-code"><?php 
esc_html_e( 'with code', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
			<b><?php 
esc_html_e( 'DOT10', 'advanced-flat-rate-shipping-for-woocommerce' );
?></b></span>
                <a class="dotstore-upgrade"
                   href="<?php 
echo  esc_url( 'www.thedotstore.com/advanced-flat-rate-shipping-method-for-woocommerce' ) ;
?>"
                   target="_blank"><?php 
esc_html_e( 'Upgrade Now!', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
            </div>
			<?php 
?>
    <div class="dotstore-important-link">
        <div class="video-detail important-link">
            <a href="<?php 
echo  esc_url( 'https://www.youtube.com/watch?v=y3Sh6_Qaen0' ) ;
?>" target="_blank">
                <img width="100%"
                     src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/plugin-videodemo.png' ) ;
?>"
                     alt="<?php 
esc_html_e( 'Advanced Flat Rate Shipping For WooCommerce', 'advanced-flat-rate-shipping-for-woocommerce' );
?>">
            </a>
        </div>
    </div>

    <div class="dotstore-important-link">
        <h2>
            <span class="dotstore-important-link-title"><?php 
esc_html_e( 'Important link', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
        </h2>
        <div class="video-detail important-link">
            <ul>
                <li>
                    <img src="<?php 
echo  esc_url( $image_url ) ;
?>">
                    <a target="_blank"
                       href="<?php 
echo  esc_url( 'www.thedotstore.com/docs/plugin/advanced-flat-rate-shipping-method-for-woocommerce' ) ;
?>"><?php 
esc_html_e( 'Plugin documentation', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </li>
                <li>
                    <img src="<?php 
echo  esc_url( $image_url ) ;
?>">
                    <a target="_blank"
                       href="<?php 
echo  esc_url( 'www.thedotstore.com/support' ) ;
?>"><?php 
esc_html_e( 'Support platform', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </li>
                <li>
                    <img src="<?php 
echo  esc_url( $image_url ) ;
?>">
                    <a target="_blank"
                       href="<?php 
echo  esc_url( 'www.thedotstore.com/suggest-a-feature' ) ;
?>"><?php 
esc_html_e( 'Suggest A Feature', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </li>
                <li>
                    <img src="<?php 
echo  esc_url( $image_url ) ;
?>">
                    <a target="_blank"
                       href="<?php 
echo  esc_url( 'http://www.thedotstore.com/advanced-flat-rate-shipping-method-for-woocommerce#tab-change-log' ) ;
?>"><?php 
esc_html_e( 'Changelog', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </li>
            </ul>
        </div>
    </div>

    <div class="dotstore-important-link">
        <h2>
            <span class="dotstore-important-link-title"><?php 
esc_html_e( 'OUR POPULAR PLUGINS', 'advanced-flat-rate-shipping-for-woocommerce' );
?></span>
        </h2>
        <div class="video-detail important-link">
            <ul>
                <li>
                    <img class="sidebar_plugin_icone"
                         src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/advance-flat-rate-2.png' ) ;
?>">
                    <a target="_blank"
                       href="<?php 
echo  esc_url( 'www.thedotstore.com/advanced-flat-rate-shipping-method-for-woocommerce' ) ;
?>"><?php 
esc_html_e( 'Advanced Flat Rate Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone"
                         src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/wc-conditional-product-fees.png' ) ;
?>">
                    <a target="_blank"
                       href="<?php 
echo  esc_url( 'www.thedotstore.com/woocommerce-conditional-product-fees-checkout' ) ;
?>"><?php 
esc_html_e( 'Conditional Product Fees For WooCommerce Checkout', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone"
                         src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/advance-menu-manager.png' ) ;
?>">
                    <a target="_blank"
                       href="<?php 
echo  esc_url( 'www.thedotstore.com/advance-menu-manager-wordpress' ) ;
?>"><?php 
esc_html_e( 'Advance Menu Manager', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone"
                         src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/wc-enhanced-ecommerce-analytics-integration.png' ) ;
?>">
                    <a target="_blank"
                       href="<?php 
echo  esc_url( 'www.thedotstore.com/woocommerce-enhanced-ecommerce-analytics-integration-with-conversion-tracking' ) ;
?>"><?php 
esc_html_e( 'Enhanced Ecommerce Google Analytics for WooCommerce', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone"
                         src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/advanced-product-size-charts.png' ) ;
?>">
                    <a target="_blank"
                       href="<?php 
echo  esc_url( 'www.thedotstore.com/woocommerce-advanced-product-size-charts' ) ;
?>"><?php 
esc_html_e( 'Advanced Product Size Charts', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone"
                         src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/blockers.png' ) ;
?>">
                    <a target="_blank"
                       href="<?php 
echo  esc_url( 'https://www.thedotstore.com/product/woocommerce-blocker-lite-prevent-fake-orders-blacklist-fraud-customers/' ) ;
?>"><?php 
esc_html_e( 'Blocker – Prevent Fake Orders And Blacklist Fraud Customers for WooCommerce', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
                </li>
            </ul>
        </div>
        <div class="view-button">
            <a class="view_button_dotstore" target="_blank"
               href="<?php 
echo  esc_url( 'www.thedotstore.com/plugins' ) ;
?>"><?php 
esc_html_e( 'VIEW ALL', 'advanced-flat-rate-shipping-for-woocommerce' );
?></a>
        </div>
    </div>
</div>
</div>
</div>