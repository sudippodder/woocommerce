<?php
// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>
    <div class="afrsm-section-left">
        <div class="afrsm-main-table res-cl">
            <div class="afrsm-premium-features">
                <div class="section section-odd clear">
                    <h1><?php esc_html_e( 'Premium Features', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h1>
                    <div class="landing-container pro-master-settings">
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'When multiple shipping methods are visible on cart page', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <ul>
                                <li>
                                    <b><?php esc_html_e( 'Allow customer to choose:', 'advanced-flat-rate-shipping-for-woocommerce' ) ?></b> <?php esc_html_e( 'Let\'s customer choose one shipping method from available shipping methods', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                </li>
                                <li>
                                    <b><?php esc_html_e( 'Apply Highest:', 'advanced-flat-rate-shipping-for-woocommerce' ) ?></b> <?php esc_html_e( 'Shipping method with the highest cost would be displayed from the available shipping methods', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                </li>
                                <li>
                                    <b><?php esc_html_e( 'Apply smallest:', 'advanced-flat-rate-shipping-for-woocommerce' ) ?></b> <?php esc_html_e( 'Shipping method with the lowest cost would be displayed from the available shipping methods', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                </li>
                                <li>
                                    <b><?php esc_html_e( 'Force all:', 'advanced-flat-rate-shipping-for-woocommerce' ) ?></b> <?php esc_html_e( 'All the shipping methods are forcefully invoked with shipping charge as summed up of all shipping methods', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                </li>
                            </ul>
                        </div>
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_13.png' ); ?>"
                                 alt="<?php esc_html_e( 'When multiple shipping methods are visible on cart page', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="section section-even clear">
                    <div class="landing-container">
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_01.png' ); ?>"
                                 alt="<?php esc_html_e( 'Shipping method Based On Country, State and Zipcode', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method Based On Country, State and Zipcode', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'Using this feature you can apply shipping rule for a country, state(s) or Zipcode(s). With this option you can create "International flat-rate shipping" method for your WooCommerce store.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'For example, if your store in the USA and you want to create shipping method for Alabama and Alaska state with specific postcodes.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                    </div>
                </div>
                <div class="section section-odd clear">
                    <div class="landing-container">
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method Based On Custom Zone', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'You can create custom shipping zone as per your requirements. You can apply multiple shipping methods based on that different custom zones.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p>
                                <b><?php esc_html_e( 'Create shipping zone as per below:', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></b>
                            </p>
                            <ul>
                                <li><?php esc_html_e( 'Countries based shipping zone', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'State and Counties based shipping zone', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                                <li><?php esc_html_e( 'Post Codes / Zips based shipping zone', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></li>
                            </ul>
                        </div>
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_12.png' ); ?>"
                                 alt="<?php esc_html_e( 'Shipping method Based On Custom Zone', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="section section-even clear">
                    <div class="landing-container">
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_04.png' ); ?>"
                                 alt="<?php esc_html_e( 'Shipping method based on Tag', 'advanced-flat-rate-shipping-for-woocommerce'
							     ); ?>"/>
                        </div>
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method based on Tag', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'Using this feature you can create shipping method for specific tag\'s products.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'For example, you can create "Tag-based shipping" for $10. This method should be visible when the cart has any product having "Tag1" tag.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                    </div>
                </div>
                <div class="section section-odd clear">
                    <div class="landing-container">
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method based on SKU', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'Using this feature you can create shipping method for specific SKU\'s products.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'For example, you can create "SKU based shipping" for $12. This method should be visible when the cart has any product having "woo-single1" SKU.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_05.png' ); ?>"
                                 alt="<?php esc_html_e( 'Shipping method based on SKU', 'advanced-flat-rate-shipping-for-woocommerce'
							     ); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="section section-even clear">
                    <div class="landing-container">
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_06.png' ); ?>"
                                 alt="<?php esc_html_e( 'Shipping method for specific users', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method for specific users', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'Using this feature you can create shipping method for specific users.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'For example, you have created shipping method for "John" user with $18 charge. When John is logged in and place some order then for all the orders shipping method would be displayed.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                    </div>
                </div>
                <div class="section section-odd clear">
                    <div class="landing-container">
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method based on User Role', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'Using this feature, shipping method based is visible for specific user role/group.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'For example, you have created shipping method for "Editor" role. Now, when any user with role "Editor" is logged in and place an order then this shipping method is visible.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_07.png' ); ?>"
                                 alt="<?php esc_html_e( 'Shipping method based on User Role', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="section section-even clear">
                    <div class="landing-container">
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_09.png' ); ?>"
                                 alt="<?php esc_html_e( 'Shipping method based on total cart quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method based on total cart quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'This shipping method allows you to create shipping method based on total quantity of cart. There are multiple conditions (like =, !=, <, <=, >, >=) available for this parameter.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'For example, if you have created shipping method like quantity >= 5. When total quantity of cart is greater than 5 then shipping method is visible.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                    </div>
                </div>
                <div class="section section-odd clear">
                    <div class="landing-container">
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Shipping method based on total cart\'s weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'This shipping method allows you to create shipping method based on total weight of cart. There are multiple conditions (like =, !=, <, <=, >, >=) available for this parameter.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'For example, if you have created shipping method like weight != 5. When total weight of cart is not equal to 5 then shipping method is visible.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_10.png' ); ?>"
                                 alt="<?php esc_html_e( 'Shipping method based on total cart\'s weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="section section-even clear">
                    <div class="landing-container">
                        <div class="col-1">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/features_11.png' ); ?>"
                                 alt="<?php esc_html_e( 'Additional shipping charges based on shipping class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"/>
                        </div>
                        <div class="col-2">
                            <div class="section-title">
                                <h2><?php esc_html_e( 'Additional shipping charges based on shipping class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                            </div>
                            <p><?php esc_html_e( 'This option allows a user to add extra cost based on shipping classes. It provides all shipping classes which are already used for the product. It displays all shipping classes list with a text box to add cost.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                            <p><?php esc_html_e( 'The shipping class cost will be added to the shipping charge. For example, if you set $49 as a shipping charge and "Poster class" shipping cost would be $10. Now when cart having a product that has poster class then total shipping charge would be $59(49 + 10).', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
                        </div>
                    </div>
                </div>
                <div class="section section-cta section-odd">
                    <div class="landing-container afsrm_upgrade_to_pro">
                        <div class="afrsm-wishlist-cta">
                            <p><?php esc_html_e( "Upgrade to the PREMIUM VERSION to increase your affiliate program bonus!", 'advanced-flat-rate-shipping-for-woocommerce' ) ?></p>
                            <a target="_blank"
                               href="<?php echo esc_url( 'www.thedotstore.com/advanced-flat-rate-shipping-method-for-woocommerce' ); ?>">
                                <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/upgrade_new.png' ); ?>">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' ); ?>