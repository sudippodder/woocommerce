<?php
/**
 * Created by PhpStorm.
 * User: subash
 * Date: 10/17/16
 * Time: 3:08 PM
 */
if (!defined('ABSPATH')) exit; // Exit if accessed directly
if (!is_admin()) {
    die('Permission Denied!');
}
define('XMLRPC_REQUEST', true);

require_once dirname(__FILE__) . '/../../data/feedcore.php';

$service_name = sanitize_text_field($_POST['service_name']);
$countryCode = sanitize_text_field($_POST['country_code']);

$data = '';
/*if (class_exists('CPF_Taxonomy'))
	$data = CPF_Taxonomy::onLoadTaxonomy(strtolower($_POST['service_name']));*/


if (strlen($data) == 0)
    $data = file_get_contents(dirname(__FILE__) . '/../../feeds/' . strtolower($service_name) . '/categories/' . strtolower($countryCode) . '_categories.txt');

$data = explode("\n", $data);
$searchTerm = strtolower(sanitize_text_field($_POST['partial_data']));

$count = 0;
$canDisplay = true;
if (!empty($searchTerm)) {
    foreach ($data as $this_item) {

        if (strlen($this_item) * strlen($searchTerm) == 0)
            continue;

        if (strpos(strtolower($this_item), $searchTerm) !== false) {

            //Transform item from chicken-scratch into something the system can recognize later
            $option = str_replace(" & ", ".and.", str_replace(" / ", ".in.", trim($this_item)));
            $option = str_replace("'", '', $option);

            //Transform a category from chicken-scratch into something the user can read
            $text = htmlentities(trim($this_item));

            if ($canDisplay)
                echo '<div class="categoryItem" onclick="doSelectCategory_custom(this, \'' . $option . '\', \'' . $service_name . '\')">' . $text . '</div>';
            $count++;
            if ((strlen($searchTerm) < 3) && ($count > 15))
                $canDisplay = false;

        }
    }
} else {
    foreach ($data as $this_item) {
        //Transform item from chicken-scratch into something the system can recognize later
        $option = str_replace(" & ", ".and.", str_replace(" / ", ".in.", trim($this_item)));
        $option = str_replace("'", '', $option);

        //Transform a category from chicken-scratch into something the user can read
        $text = htmlentities(trim($this_item));

        echo '<div class="categoryItem" onclick="doSelectCategory_custom(this, \'' . $option . '\', \'' . $service_name . '\')">' . $text . '</div>';

    }
}


if ($count == 0) {
    //echo 'No matching categories found';
}

if (!$canDisplay)
    echo '<div class="categoryItem">(' . $count . ' results)</div>';
