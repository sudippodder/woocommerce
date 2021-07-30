<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Woo_Conditional_Shipping_Filters' ) ) {
class Woo_Conditional_Shipping_Filters {
  public static function filter_weight( $condition, $package ) {
		$package_weight = self::calculate_package_weight( $package );

		if ( isset( $condition['value'] ) && ! empty( $condition['value'] ) ) {
			$weight = self::parse_number( $condition['value'] );

			return ! self::compare_numeric_value( $package_weight, $weight, $condition['operator'] );
		}

		return FALSE;
  }

  public static function filter_height_total( $condition, $package ) {
		$package_height = self::calculate_package_height( $package );

		if ( isset( $condition['value'] ) && ! empty( $condition['value'] ) ) {
			$height = self::parse_number( $condition['value'] );

			return ! self::compare_numeric_value( $package_height, $height, $condition['operator'] );
		}

		return FALSE;
  }

  public static function filter_length_total( $condition, $package ) {
		$package_length = self::calculate_package_length( $package );

		if ( isset( $condition['value'] ) && ! empty( $condition['value'] ) ) {
			$length = self::parse_number( $condition['value'] );

			return ! self::compare_numeric_value( $package_length, $length, $condition['operator'] );
		}

		return FALSE;
  }

  public static function filter_width_total( $condition, $package ) {
		$package_width = self::calculate_package_width( $package );

		if ( isset( $condition['value'] ) && ! empty( $condition['value'] ) ) {
			$width = self::parse_number( $condition['value'] );

			return ! self::compare_numeric_value( $package_width, $width, $condition['operator'] );
		}

		return FALSE;
	}
	
  public static function filter_volume( $condition, $package ) {
		$package_volume = self::calculate_package_volume( $package );

		if ( isset( $condition['value'] ) && ! empty( $condition['value'] ) ) {
			$volume = self::parse_number( $condition['value'] );

			return ! self::compare_numeric_value( $package_volume, $volume, $condition['operator'] );
		}

		return FALSE;
	}

  public static function filter_subtotal( $condition, $package ) {
		$cart = WC()->cart;
		$cart_subtotal = $cart->subtotal;

		if ( isset( $condition['subtotal_includes_coupons'] ) && $condition['subtotal_includes_coupons'] && method_exists( $cart, 'get_discount_total' ) ) {
			$cart_subtotal -= ( floatval( $cart->get_discount_total() ) + floatval( $cart->get_discount_tax() ) );
		}

		if ( isset( $condition['value'] ) && ! empty( $condition['value'] ) ) {
			$subtotal = self::parse_number( $condition['value'] );

			return ! self::compare_numeric_value( $cart_subtotal, $subtotal, $condition['operator'] );
		}

		return FALSE;
  }

  public static function filter_products( $condition, $package ) {
		if ( isset( $condition['product_ids'] ) && ! empty( $condition['product_ids'] ) ) {
			$condition_product_ids = self::merge_product_children_ids( $condition['product_ids'] );

			$products = self::get_cart_products();

			if ( ! empty( $products ) ) {
				$product_ids = array_keys( $products );
				return ! self::group_comparison( $product_ids, $condition_product_ids, $condition['operator'] );
			} else {
				error_log( "No products in the order" );
			}
		}

		return FALSE;
  }

	/**
	 * Get product IDs in the cart
	 */
	private static function get_cart_products() {
		$products = array();

		foreach ( WC()->cart->get_cart() as $key => $item ) {
			if ( isset( $item['data'] ) ) {
				if ( isset( $item['variation_id'] ) && ! empty( $item['variation_id'] ) ) {
					$products[$item['variation_id']] = $item['data'];
				} else if ( isset( $item['product_id'] ) && ! empty( $item['product_id'] ) ) {
					$products[$item['product_id']] = $item['data'];
				}
			}
		}

		return $products;
	}

	/**
	 * Merge children IDs for parent product IDs
	 */
	private static function merge_product_children_ids( $product_ids ) {
		$args = array(
			'post_type' => array( 'product_variation' ),
			'post_parent__in' => $product_ids,
			'fields' => 'ids'
		);
		$children_ids = get_posts( $args );

		return array_merge( $children_ids, $product_ids );
	}

	/**
	 * Calculate cart weight
	 */
	private static function calculate_package_weight($package) {
		$total_weight = 0;

		foreach ( $package['contents'] as $key => $data ) {
			$product = $data['data'];

			if ( ! $product->needs_shipping() ) {
				continue;
			}

			$item_weight = $product->get_weight();

			if ( $item_weight ) {
				$total_weight += $item_weight * $data['quantity'];
			}
		}

		return $total_weight;
	}

	/**
	 * Calculate cart volume
	 */
	private static function calculate_package_volume($package) {
		$total_volume = 0;

		foreach ( $package['contents'] as $key => $data ) {
			$product = $data['data'];

			if ( ! $product->needs_shipping() ) {
				continue;
			}

			$length = $product->get_length();
			$width = $product->get_width();
			$height = $product->get_height();

			if ( is_numeric ( $length ) && is_numeric( $width ) && is_numeric( $height ) ) {
				$volume = $length * $width * $height;
				$total_volume += $volume * $data['quantity'];
			}
		}

		return $total_volume;
	}

	/**
	 * Calculate cart height
	 */
	private static function calculate_package_height($package) {
		$total = 0;

		foreach ( $package['contents'] as $key => $data ) {
			$product = $data['data'];

			if ( ! $product->needs_shipping() || ! $product->has_dimensions() ) {
				continue;
			}

			$item_height = $product->get_height();

			if ( $item_height ) {
				$total += floatval( $item_height ) * $data['quantity'];
			}
		}

		return $total;
	}

	/**
	 * Calculate cart length
	 */
	private static function calculate_package_length($package) {
		$total = 0;

		foreach ( $package['contents'] as $key => $data ) {
			$product = $data['data'];

			if ( ! $product->needs_shipping() || ! $product->has_dimensions() ) {
				continue;
			}

			$length = $product->get_length();

			if ( $length ) {
				$total += floatval( $length ) * $data['quantity'];
			}
		}

		return $total;
	}

	/**
	 * Calculate cart width
	 */
	private static function calculate_package_width($package) {
		$total = 0;

		foreach ( $package['contents'] as $key => $data ) {
			$product = $data['data'];

			if ( ! $product->needs_shipping() || ! $product->has_dimensions() ) {
				continue;
			}

			$width = $product->get_width();

			if ( $width ) {
				$total += floatval( $width ) * $data['quantity'];
			}
		}

		return $total;
	}

	/**
	 * Parse string number into float
	 */
	private static function parse_number($number) {
		$number = str_replace( ',', '.', $number );

		if ( is_numeric( $number ) ) {
			return floatval( $number );
		}

		return FALSE;
	}

	/**
	 * Compare value with given operator
	 */
	private static function compare_numeric_value( $a, $b, $operator ) {
		switch ( $operator ) {
			case 'gt':
				return $a > $b;
			case 'gte':
				return $a >= $b;
			case 'lt':
				return $a < $b;
			case 'lte':
				return $a <= $b;
		}

		error_log( "Invalid operator given" );

		return NULL;
	}

	/**
	 * Check inclusiveness or exclusiveness in an array
	 */
	private static function group_comparison( $a, $b, $operator ) {
		$a = array_unique( $a );
		$b = array_unique( $b );

		switch ( $operator ) {
			case 'in':
				return count( array_intersect( $a, $b ) ) > 0;
			case 'notin':
				return count( array_intersect( $a, $b ) ) == 0;
			case 'exclusive':
				return count( array_diff( $a, $b ) ) == 0;
			case 'allin':
				return count( array_diff( $b, $a ) ) == 0;
		}

		error_log( "Invalid operator given in group comparison" );

		return NULL;
	}

	/**
	 * Check is / is not in an array
	 */
	private static function is_array_comparison( $needle, $haystack, $operator ) {
		if ( $operator == 'is' ) {
			return in_array( $needle, $haystack );
		} else if ( $operator == 'isnot' ) {
			return ! in_array( $needle, $haystack );
		}

		error_log( "Invalid operator given in array comparison" );

		return NULL;
	}
}
}
