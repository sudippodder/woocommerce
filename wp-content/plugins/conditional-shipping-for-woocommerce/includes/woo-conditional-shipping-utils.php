<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get rulesets
 */
function woo_conditional_shipping_get_rulesets( $only_enabled = false ) {
	$args = array(
		'post_status' => array( 'publish' ),
		'post_type' => 'wcs_ruleset',
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order' => 'ASC',
	);

  $posts = get_posts( $args );
  
  $rulesets = array();
  foreach ( $posts as $post ) {
    $ruleset = new Woo_Conditional_Shipping_Ruleset( $post->ID );

    if ( ! $only_enabled || $ruleset->get_enabled() ) {
      $rulesets[] = $ruleset;
    }
  }

  return $rulesets;
}

/**
 * Get a list of operators
 */
function woo_conditional_shipping_operators() {
  return array(
    'gt' => __( 'greater than', 'woo-conditional-shipping' ),
    'gte' => __( 'greater than or equal', 'woo-conditional-shipping' ),
    'lt' => __( 'less than', 'woo-conditional-shipping' ),
    'lte' => __( 'less than or equal', 'woo-conditional-shipping' ),
    'in' => __( 'includes', 'woo-conditional-shipping' ),
    'exclusive' => __( 'includes (exclusive)', 'woo-conditional-shipping' ),
    'notin' => __( 'excludes', 'woo-conditional-shipping' ),
    'allin' => __( 'all present', 'woo-conditional-shipping' ),
    'is' => __( 'is', 'woo-conditional-shipping' ),
    'isnot' => __( 'is not', 'woo-conditional-shipping' ),
    'exists' => __( 'is not empty', 'woo-conditional-shipping' ),
    'notexists' => __( 'is empty', 'woo-conditional-shipping' ),
    'contains' => __( 'contains', 'woo-conditional-shipping' ),
    'loggedin' => __( 'logged in', 'woo-conditional-shipping' ),
    'loggedout' => __( 'logged out', 'woo-conditional-shipping' ),
  );
}

/**
 * Get a list of filter groups
 */
function woo_conditional_shipping_filter_groups() {
  return apply_filters( 'woo_conditional_shipping_filters', array(
    'cart' => array(
      'title' => __( 'Cart', 'woo-conditional-shipping' ),
      'filters' => array(
        'subtotal' => array(
          'title' => __( 'Subtotal', 'woo-conditional-shipping' ),
          'operators' => array( 'gt', 'gte', 'lt', 'lte' ),
        ),
        'products' => array(
          'title' => __( 'Products', 'woo-conditional-shipping' ),
          'operators' => array( 'in', 'notin', 'exclusive', 'allin' ),
        ),
      )
    ),
    'package_measurements' => array(
      'title' => __( 'Package Measurements', 'woo-conditional-shipping' ),
      'filters' => array(
        'weight' => array(
          'title' => sprintf( __( 'Total Weight (%s)', 'woo-conditional-shipping' ), get_option( 'woocommerce_weight_unit' ) ),
          'operators' => array( 'gt', 'gte', 'lt', 'lte' ),
        ),
        'height_total' => array(
          'title' => sprintf( __( 'Total Height (%s)', 'woo-conditional-shipping' ), get_option( 'woocommerce_dimension_unit' ) ),
          'operators' => array( 'gt', 'gte', 'lt', 'lte' ),
        ),
        'length_total' => array(
          'title' => sprintf( __( 'Total Length (%s)', 'woo-conditional-shipping' ), get_option( 'woocommerce_dimension_unit' ) ),
          'operators' => array( 'gt', 'gte', 'lt', 'lte' ),
        ),
        'width_total' => array(
          'title' => sprintf( __( 'Total Width (%s)', 'woo-conditional-shipping' ), get_option( 'woocommerce_dimension_unit' ) ),
          'operators' => array( 'gt', 'gte', 'lt', 'lte' ),
        ),
        'volume' => array(
          'title' => sprintf( __( 'Total Volume (%s&sup3;)', 'woo-conditional-shipping' ), get_option( 'woocommerce_dimension_unit' ) ),
          'operators' => array( 'gt', 'gte', 'lt', 'lte' ),
        ),
      )
    ),
  ) );
}

/**
 * Get a list of actions
 */
function woo_conditional_shipping_actions() {
  return apply_filters( 'woo_conditional_shipping_actions', array(
    'enable_shipping_methods' => array(
      'title' => __( 'Enable shipping methods', 'woo-conditional-shipping' ),
    ),
    'disable_shipping_methods' => array(
      'title' => __( 'Disable shipping methods', 'woo-conditional-shipping' ),
    ),
  ) );
}

/**
 * Get shipping method options
 */
function woo_conditional_shipping_get_shipping_method_options() {
  $shipping_zones = WC_Shipping_Zones::get_zones();
  $shipping_zones[] = new WC_Shipping_Zone( 0 );

  $options = array();

  foreach ( $shipping_zones as $shipping_zone ) {
    if ( is_array( $shipping_zone ) && isset( $shipping_zone['zone_id'] ) ) {
      $shipping_zone = WC_Shipping_Zones::get_zone( $shipping_zone['zone_id'] );
    } else if ( ! is_object( $shipping_zone ) ) {
      // Skip
      continue;
    }

    $zone_id = $shipping_zone->get_id();
    $options[$zone_id] = array(
      'title' => $shipping_zone->get_zone_name(),
      'options' => array(),
    );

    foreach ( $shipping_zone->get_shipping_methods() as $instance_id => $shipping_method ) {
      $options[$zone_id]['options'][$instance_id] = array(
        'title' => $shipping_method->title,
      );
    }
  }

  // Remove zones with no shipping methods
  $options = array_filter( $options, function( $option ) {
    return ! empty( $option['options'] );
  } );

  return $options;
}

/**
 * Get product attribute options
 */
function woo_conditional_product_attr_options() {
  $options = array();

  $taxonomies = wc_get_attribute_taxonomies();

  foreach ( $taxonomies as $key => $taxonomy ) {
    $options[$taxonomy->attribute_id] = array(
      'label' => $taxonomy->attribute_label,
      'attrs' => array(),
    );

    $taxonomy_id = wc_attribute_taxonomy_name( $taxonomy->attribute_name );
    if ( taxonomy_exists( $taxonomy_id ) ) {
      $terms = get_terms( $taxonomy_id, 'hide_empty=0' );

      foreach ( $terms as $term ) {
        $attribute_id = sprintf( 'pa_%s:%s', $taxonomy->attribute_name, $term->slug );
        $options[$taxonomy->attribute_id]['attrs'][$attribute_id] = $term->name;
      }
    }
  }

  return $options;
}

/**
 * Get shipping class options
 */
function woo_conditional_shipping_get_shipping_class_options() {
  $shipping_classes = WC()->shipping->get_shipping_classes();
  $shipping_class_options = array();
  foreach ( $shipping_classes as $shipping_class ) {
    $shipping_class_options[$shipping_class->term_id] = $shipping_class->name;
  }

  return $shipping_class_options;
}

/**
 * Get category options
 */
function woo_conditional_shipping_get_category_options() {
  $categories = get_terms( 'product_cat', array(
    'hide_empty' => false,
  ) );
  $category_options = array();
  foreach ( $categories as $category ) {
    $category_options[$category->term_id] = $category->name;
  }

  return $category_options;
}

/**
 * Get coupon options
 */
function woo_conditional_shipping_get_coupon_options() {
  $args = array(
    'posts_per_page' => 100, // Only get 100 latest coupons for performance reasons
    'orderby' => 'ID',
    'order' => 'desc',
    'post_type' => 'shop_coupon',
    'post_status' => 'publish',
  );

  $coupons = get_posts( $args );

  $options = array(
    '_all' => __( '- All coupons -', 'woo-conditional-shipping' ),
  );
  foreach ( $coupons as $coupon ) {
    $options[$coupon->ID] = $coupon->post_title;
  }

  // Order by code / title
  asort( $options );

  return $options;
}

/**
 * Load all roles to be used in a select field
 */
function woo_conditional_shipping_role_options() {
  $options = array();

  if ( function_exists( 'get_editable_roles' ) ) {
    $editable_roles = array_reverse( get_editable_roles() );

    foreach ( $editable_roles as $role => $details ) {
      $name = translate_user_role( $details['name'] );
      $options[$role] = $name;
    }
  }

  return $options;
}

/**
 * Options for weekday filter
 */
function woo_conditional_shipping_weekdays_options() {
  $options = array();

  for ( $i = 0; $i < 7; $i++ ) {
    $timestamp = strtotime( 'monday' ) + $i * 86400;

    $options[$i + 1] = date_i18n( 'l', $timestamp );
  }

  return $options;
}

/**
 * Options for time hours filter
 */
function woo_conditional_shipping_time_hours_options() {
  $options = array();

  for ( $i = 0; $i < 24; $i++ ) {
    $timestamp = strtotime( 'monday midnight' ) + $i * 3600;

    $options[$i] = date_i18n( 'H', $timestamp );
  }

  return $options;
}

/**
 * Options for time minutes filter
 */
function woo_conditional_shipping_time_mins_options() {
  $options = array();

  for ( $i = 0; $i < 60; $i++ ) {
    $timestamp = strtotime( 'monday midnight' ) + $i * 60;

    $options[$i] = date_i18n( 'i', $timestamp );
  }

  return $options;
}
