
<?php
///site01 > woocommerce
$path = 'site01';
ini_set('display_errors',1);
$wp_load = '/var/www/html/site01';
require( $wp_load . '/wp-load.php' );

global $woocommerce, $wpdb, $product;
$p = $wpdb->prefix;

//update_option("check_cron",time());
define('LIMIT',100);
$tt = parse_str($argv[1],$output);
$page_no = $output['page'];

function read_file(){
    $file = "/var/www/html/site01/test.txt" ;
    date_default_timezone_set('Australia/Melbourne');
    //$date = date('m/d/Y h:i:s a', time());
    $date = $add_content;
    $linecount = 0;
    $handle = fopen($file, "r") ;
    $allContent = array();
        while(!feof($handle)){
        $line = fgets($handle);
        if($line){
            $line = str_replace("\n", "", $line);
            $allContent[] .= (int)$line;
        }
            $linecount++;
        }

    //$allContent[] = $date;
    //$handle = fopen($file, "w") ;
    //$allContent = implode("\n",$allContent);
    //fwrite($handle, $allContent );
    fclose($handle); 
    return $allContent;
}

//---------------------first run this
// $update = "UPDATE `wp_posts` SET `post_status` = 'draft' WHERE post_type='product'";
// $wpdb->get_results($update);
//---------------------first run this

$read_file = read_file();
$total = count($read_file);
echo  $total;
die();
$idList = implode(',',$read_file);
//and post_status='draft'

for ($i = 1; $i <= 1; $i++){ 
    $sql = "SELECT * FROM `wp_posts` WHERE post_type='product' and post_status='draft' and ID in(".$idList.") limit 0,500";
    //echo $sql;
    $res = $wpdb->get_results($sql);
    echo count($res);

    foreach($res as $box){
        //var_dump($box);
        $mid = (int)$box->ID;
        //$update = "UPDATE `wp_posts` SET `post_status` = 'publish' WHERE ID='".$mid."'";
        echo $update;
        echo '<br>';
        //$r = $wpdb->get_results($update);
        
        //var_dump($r);
        //die();
        //wh_deleteProduct($mid,true);
    }

    echo 'Done';
}
// $sql = "select post_id,meta_value, meta_key,count(*) from ".$p."postmeta 
// where  meta_key = '_sku' 
// and meta_value != ''
// group by meta_value 
// having count(meta_value) > 1  ";
// $res = $wpdb->get_results($sql);
// echo count($res);

// foreach($res as $box){
//     var_dump($box);
//     $mid = (int)$box->post_id;
//     var_dump($mid);
//     //wh_deleteProduct($mid,true);
// }




function wh_deleteProduct($id, $force = FALSE)
{
    $product = wc_get_product($id);

    if(empty($product))
        return new WP_Error(999, sprintf(__('No %s is associated with #%d', 'woocommerce'), 'product', $id));

    // If we're forcing, then delete permanently.
    if ($force)
    {
        if ($product->is_type('variable'))
        {
            foreach ($product->get_children() as $child_id)
            {
                $child = wc_get_product($child_id);
                $child->delete(true);
            }
        }
        elseif ($product->is_type('grouped'))
        {
            foreach ($product->get_children() as $child_id)
            {
                $child = wc_get_product($child_id);
                $child->set_parent_id(0);
                $child->save();
            }
        }

        $product->delete(true);
        $result = $product->get_id() > 0 ? false : true;
    }
    else
    {
        $product->delete();
        $result = 'trash' === $product->get_status();
    }

    if (!$result)
    {
        return new WP_Error(999, sprintf(__('This %s cannot be deleted', 'woocommerce'), 'product'));
    }

    // Delete parent product transients.
    if ($parent_id = wp_get_post_parent_id($id))
    {
        wc_delete_product_transients($parent_id);
    }
    return true;
}



?>
