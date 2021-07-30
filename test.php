
<?php
ini_set('display_errors', 1);
$wp_load = '/var/www/html/site01';
require('./wp-load.php');
global $woocommerce, $wpdb, $product;

$cmm = wp_get_schedules();
var_dump($cmm);
die();
$p = $wpdb->prefix;
$sql = "select meta_id,meta_value, meta_key,count(*) from " . $p . "postmeta 
where  meta_key = '_sku' 
and meta_value != ''
group by meta_value 
having count(meta_value) > 1  ";
$res = $wpdb->get_results($sql);
foreach ($res as $box) {
    var_dump($box);
}
echo count($res);
echo '<pre>';
var_dump($res);
echo '</pre>';



function wh_deleteProduct($id, $force = FALSE)
{
    $product = wc_get_product($id);

    if (empty($product))
        return new WP_Error(999, sprintf(__('No %s is associated with #%d', 'woocommerce'), 'product', $id));

    // If we're forcing, then delete permanently.
    if ($force) {
        if ($product->is_type('variable')) {
            foreach ($product->get_children() as $child_id) {
                $child = wc_get_product($child_id);
                $child->delete(true);
            }
        } elseif ($product->is_type('grouped')) {
            foreach ($product->get_children() as $child_id) {
                $child = wc_get_product($child_id);
                $child->set_parent_id(0);
                $child->save();
            }
        }

        $product->delete(true);
        $result = $product->get_id() > 0 ? false : true;
    } else {
        $product->delete();
        $result = 'trash' === $product->get_status();
    }

    if (!$result) {
        return new WP_Error(999, sprintf(__('This %s cannot be deleted', 'woocommerce'), 'product'));
    }

    // Delete parent product transients.
    if ($parent_id = wp_get_post_parent_id($id)) {
        wc_delete_product_transients($parent_id);
    }
    return true;
}

wh_deleteProduct(79, true);
