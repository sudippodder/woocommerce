<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-header.php';
?>
    <div class="afrsm-section-left">
        <div class="afrsm-main-table res-cl">
            <h2><?php 
esc_html_e( 'Thanks For Installing Advanced Flat Rate Shipping For WooCommerce', 'advanced-flat-rate-shipping-for-woocommerce' );
?></h2>
            <table class="table-outer">
                <tbody>
                <tr>
                    <td class="fr-2">
                        <p class="block gettingstarted">
                            <strong><?php 
esc_html_e( 'Getting Started', 'advanced-flat-rate-shipping-for-woocommerce' );
?></strong>
                        </p>
                        <p class="block textgetting">
							<?php 
esc_html_e( 'Create/manage multiple shipping rules as per your needs.', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
                        </p>
                        <p class="block textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Step 1: </strong>Setup Shipping Method Configuration with Shipping Method Rules.', 'advanced-flat-rate-shipping-for-woocommerce' ), array(
    'strong' => array(),
) ) ) ;
?>
                            <span class="gettingstarted">
                                <?php 
?>
                                        <img src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/free_Getting_Started_01.png' ) ;
?>">
		                                <?php 
?>
                            </span>
                        </p>
                        <p class="block gettingstarted textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Step 2: </strong>You can see list of all shipping methods.', 'advanced-flat-rate-shipping-for-woocommerce' ), array(
    'strong' => array(),
) ) ) ;
?>
                            <span class="gettingstarted">
                                <?php 
?>
                                        <img src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/free_Getting_Started_02.png' ) ;
?>">
		                                <?php 
?>
                            </span>
                        </p>
                        <p class="block gettingstarted textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Step 3: </strong>Enable shipping method on the cart/checkout page if rule is satisfied.', 'advanced-flat-rate-shipping-for-woocommerce' ), array(
    'strong' => array(),
) ) ) ;
?>
                            <span class="gettingstarted">
                                <?php 
?>
                                        <img src="<?php 
echo  esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/free_Getting_Started_03.png' ) ;
?>">
		                                <?php 
?>
                            </span>
                        </p>
                        <p class="block gettingstarted textgetting">
							<?php 
echo  sprintf( wp_kses( __( '<strong>Important Note: </strong>This plugin is only compatible with WooCommerce version 3.0 and more.', 'advanced-flat-rate-shipping-for-woocommerce' ), array(
    'strong' => array(),
) ) ) ;
?>
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

<?php 
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php';