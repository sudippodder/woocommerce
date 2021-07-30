<?php
/*
Plugin Name: Custom Attribute For post type
Plugin URI: 
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Custom Make
Version: 1
Author URI: Woo
*/
//woocommerce_ >>  customaction_
ini_set('display_errors', 1);
define('POST', 'page');
define('PRE', 'custom');
include(plugin_dir_path(__FILE__) . 'class/attribute.php');
include(plugin_dir_path(__FILE__) . 'function.php');
add_action('admin_menu', 'custom_attribute_submenu', 9);

function custom_attribute_submenu()
{
    add_submenu_page('edit.php?post_type=' . POST, __('Attributes', 'woocommerce'), __('Attributes', 'woocommerce'), 'manage_product_terms', 'product_attributes', 'custom_attribute_page');
}
function custom_attribute_page()
{
    WC_Custom_Admin_Attributes::output();
}
