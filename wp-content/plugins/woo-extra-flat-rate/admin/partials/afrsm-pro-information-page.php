<?php
// If this file is called directly, abort.
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>

    <div class="afrsm-section-left">
        <div class="afrsm-main-table res-cl">
            <h2><?php esc_html_e( 'Quick info', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
            <table class="table-outer">
                <tbody>
                <tr>
                    <td class="fr-1"><?php esc_html_e( 'Product Type', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                    <td class="fr-2"><?php esc_html_e( 'WooCommerce Plugin', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                </tr>
                <tr>
                    <td class="fr-1"><?php esc_html_e( 'Product Name', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                    <td class="fr-2"><?php esc_html_e( $plugin_name, 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                </tr>
                <tr>
                    <td class="fr-1"><?php esc_html_e( 'Installed Version', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                    <td class="fr-2"><?php esc_html_e( AFRSM_PRO_PREMIUM_VERSION, 'advanced-flat-rate-shipping-for-woocommerce' ); ?>&nbsp;<?php echo esc_html_e( $plugin_version, 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                </tr>
                <tr>
                    <td class="fr-1"><?php esc_html_e( 'License & Terms of use', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                    <td class="fr-2">
                        <a target="_blank" href="<?php echo esc_url( 'www.thedotstore.com/terms-and-conditions' ); ?>">
							<?php esc_html_e( 'Click here', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                        </a>
						<?php esc_html_e( ' to view license and terms of use.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                    </td>
                </tr>
                <tr>
                    <td class="fr-1"><?php esc_html_e( 'Help & Support', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                    <td class="fr-2">
                        <ul>
                            <li>
                                <a href="<?php echo esc_url( add_query_arg( array( 'page' => 'afrsm-pro-get-started' ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Quick Start', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
                            </li>
                            <li><a target="_blank"
                                   href="<?php echo esc_url( 'www.thedotstore.com/docs/plugin/advanced-flat-rate-shipping-method-for-woocommerce' ); ?>"><?php esc_html_e( 'Guide Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
                            </li>
                            <li><a target="_blank"
                                   href="<?php echo esc_url( 'www.thedotstore.com/support' ); ?>"><?php esc_html_e( 'Support Forum', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="fr-1"><?php esc_html_e( 'Localization', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                    <td class="fr-2"><?php esc_html_e( 'English, German', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
<?php
	require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' );