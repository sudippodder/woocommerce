<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Conditional_Shipping_Frontend {
  private $passed_rules = array();

  /**
   * Constructor
   */
  public function __construct() {
    add_filter( 'woocommerce_package_rates', array( $this, 'filter_shipping_methods' ), 100, 2 );

    // For price related actions we need to use woocommerce_shipping_method_add_rate_args filter
    add_filter( 'woocommerce_shipping_method_add_rate_args', array( $this, 'shipping_method_price_actions' ), 100, 2 );

    // Custom "no shipping methods available" message
    add_filter( 'woocommerce_cart_no_shipping_available_html', array( $this, 'no_shipping_message' ), 100, 1 );
    add_filter( 'woocommerce_no_shipping_available_html', array( $this, 'no_shipping_message' ), 100, 1 );
  }

  /**
   * Adjust shipping method prices
   */
  public function shipping_method_price_actions( $args, $shipping_method ) {
    $rulesets = woo_conditional_shipping_get_rulesets( true );

    // If we cant get instance ID due to non-standard shipping method, we are out of luck
    if ( ! $shipping_method->instance_id ) {
      return $args;
    }

    foreach ( $rulesets as $ruleset ) {
      $passes = $ruleset->validate( $args['package'] );

      if ( $passes ) {
        foreach ( $ruleset->get_actions() as $action ) {
          if ( $shipping_method->instance_id !== false && in_array( $shipping_method->instance_id, (array) $action['shipping_method_ids'] ) ) {
            if ( $action['type'] === 'set_price' ) {
              $args['cost'] = floatval( $action['price'] );
            }

            if ( $action['type'] === 'increase_price' ) {
              $args['cost'] += floatval( $action['price'] );
            }

            if ( $action['type'] === 'decrease_price' ) {
              $args['cost'] -= floatval( $action['price'] );
            }
          }
        }
      }
    }

    return $args;
  }
  
  /**
   * Filter shipping methods
   */
  public function filter_shipping_methods( $rates, $package ) {
    $rulesets = woo_conditional_shipping_get_rulesets( true );
    $this->passed_rules = array();

    $disable_keys = array();
    $enable_keys = array();

    foreach ( $rulesets as $ruleset ) {
      $passes = $ruleset->validate( $package );

      if ( $passes ) {
        $this->passed_rules[] = $ruleset;
      }

      foreach ( $ruleset->get_actions() as $action ) {
       // var_dump($action['type']);
        if ( $action['type'] === 'disable_shipping_methods' ) {
          if ( $passes ) {
            foreach ( $rates as $key => $rate ) {
              $instance_id = $this->get_rate_instance_id( $rate );
  
              if ( $instance_id !== false && in_array( $instance_id, (array) $action['shipping_method_ids'] ) ) {
                $disable_keys[$key] = true;
                unset( $enable_keys[$key] );
              }
            }
          }
        }

        if ( $action['type'] === 'enable_shipping_methods' ) {
          foreach ( $rates as $key => $rate ) {
            $instance_id = $this->get_rate_instance_id( $rate );

            if ( $instance_id !== false && in_array( $instance_id, (array) $action['shipping_method_ids'] ) ) {
              if ( $passes ) {
                $enable_keys[$key] = true;
                unset( $disable_keys[$key] );
              } else {
                $disable_keys[$key] = true;
                unset( $enable_keys[$key] );
              }
            }
          }
        }
      }
    }

    foreach ( $rates as $key => $rate ) {
      if ( isset( $disable_keys[$key] ) && ! isset( $enable_keys[$key] ) ) {
        unset( $rates[$key] );
      }
    }

    return $rates;
  }

  /**
   * Custom "no shipping methods available" message
   */
  public function no_shipping_message( $orig_msg ) {
    $msgs = array();
    $i = 1;

    foreach ( $this->passed_rules as $ruleset ) {
      foreach ( $ruleset->get_actions() as $action ) {
        if ( $action['type'] === 'custom_error_msg' && ! empty( $action['error_msg'] ) ) {
          $msgs[] = sprintf( '<div class="conditional-shipping-custom-error-msg i-%d">%s</div>', $i, htmlspecialchars( $action['error_msg'], ENT_QUOTES, 'UTF-8' ) );
          $i++;
        }
      }
    }

    if ( ! empty( $msgs ) ) {
      return implode( '', $msgs );
    }

    return $orig_msg;
  }

  /**
   * Helper function for getting rate instance ID
   */
  public function get_rate_instance_id( $rate ) {
    $instance_id = false;

    if ( method_exists( $rate, 'get_instance_id' ) && strlen( strval( $rate->get_instance_id() ) ) > 0 ) {
      $instance_id = $rate->get_instance_id();
    } else {
      if ( $rate->method_id == 'oik_weight_zone_shipping' ) {
        $ids = explode( '_', $rate->id );
        $instance_id = end( $ids );
      } else {
        $ids = explode( ':', $rate->id );
        if ( count($ids) >= 2 ) {
          $instance_id = $ids[1];
        }
      }
    }

    return $instance_id;
  }
}
