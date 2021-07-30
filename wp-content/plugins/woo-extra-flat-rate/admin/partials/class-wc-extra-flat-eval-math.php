<?php
// If this file is called directly, abort.
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	/**
	 * Class WC_Eval_Math_Extra. Supports basic math only (removed eval function).
	 *
	 * Based on EvalMath by Miles Kaufman Copyright (C) 2005 Miles Kaufmann http://www.twmagic.com/.
	 */
	if ( ! class_exists( 'WC_Eval_Math_Extra' ) ) {
		
		class WC_Eval_Math_Extra {
			
			/**
			 * Last error.
			 *
			 * @var string
			 */
			public static $last_error = null;
			
			/**
			 * Variables (and constants).
			 *
			 * @var array
			 */
			public static $v = array( 'e' => 2.71, 'pi' => 3.14 );
			
			/**
			 * User-defined functions.
			 *
			 * @var array
			 */
			public static $f = array();
			
			/**
			 * Constants.
			 *
			 * @var array
			 */
			public static $vb = array( 'e', 'pi' );
			
			/**
			 * Built-in functions.
			 *
			 * @var array
			 */
			public static $fb = array();
			
			/**
			 * Evaluate maths string.
			 *
			 * @param string $expr
			 *
			 * @return mixed
			 */
			public static function evaluate( $expr ) {
				
				self::$last_error = null;
				$expr             = trim( $expr );
				
				if ( ';' === substr( $expr, - 1, 1 ) ) {
					$expr = substr( $expr, 0, strlen( $expr ) - 1 ); // strip semicolons at the end
				}
				
				// is it a variable assignment?
				if ( preg_match( '/^\s*([a-z]\w*)\s*=\s*(.+)$/', $expr, $matches ) ) {
					if ( in_array( $matches[1], self::$vb, true ) ) { // make sure we're not assigning to a constant
						return wp_die( sprintf( esc_html_x( 'cannot assign to constant %d', esc_attr( $matches[1] ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
					}
					$tmp = self::pfx( self::nfx( $matches[2] ) );
					if ( $tmp === false ) {
						return false;
					} // get the result and make sure it's good
					self::$v[ $matches[1] ] = $tmp; // if so, stick it in the variable array
					
					return self::$v[ $matches[1] ]; // and return the resulting value
					// is it a function assignment?
				} elseif ( preg_match( '/^\s*([a-z]\w*)\s*\(\s*([a-z]\w*(?:\s*,\s*[a-z]\w*)*)\s*\)\s*=\s*(.+)$/', $expr, $matches ) ) {
					$fnn = $matches[1]; // get the function name
					if ( in_array( $matches[1], self::$fb, true ) ) { // make sure it isn't built in
						return wp_die( sprintf( esc_html_x( 'cannot redefine built-in function %d()', esc_attr( $matches[1] ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
					}
					$args  = explode( ",", preg_replace( "/\s+/", "", $matches[2] ) ); // get the arguments
					$stack = self::nfx( $matches[3] );
					if ( $stack === false ) {
						return false;
					} // see if it can be converted to postfix
					for ( $i = 0; $i < count( $stack ); $i ++ ) { // freeze the state of the non-argument variables
						$token = $stack[ $i ];
						if ( preg_match( '/^[a-z]\w*$/', $token ) and ! in_array( $token, $args, true ) ) {
							if ( array_key_exists( $token, self::$v ) ) {
								$stack[ $i ] = self::$v[ $token ];
							} else {
								return wp_die( sprintf( esc_html_x( 'undefined variable %d in function definition', esc_attr( $token ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
							}
						}
					}
					self::$f[ $fnn ] = array( 'args' => $args, 'func' => $stack );
					
					return true;
				} else {
					return self::pfx( self::nfx( $expr ) ); // straight up evaluation, woo
				}
			}
			
			/**
			 * Convert infix to postfix notation.
			 *
			 * @param string $expr
			 *
			 * @return string
			 */
			private static function nfx( $expr ) {
				
				$index  = 0;
				$stack  = new WC_Eval_Math_Stack_Extra;
				$output = array(); // postfix form of expression, to be passed to pfx()
				// $expr = trim(strtolower($expr));
				$expr = trim( $expr );
				
				$ops   = array( '+', '-', '*', '/', '^', '_' );
				$ops_r = array( '+' => 0, '-' => 0, '*' => 0, '/' => 0, '^' => 1 ); // right-associative operator?
				$ops_p = array( '+' => 0, '-' => 0, '*' => 1, '/' => 1, '_' => 1, '^' => 2 ); // operator precedence
				
				$expecting_op = false; // we use this in syntax-checking the expression
				// and determining when a - is a negation
				
				if ( preg_match( "/[^\w\s+*^\/()\.,-]/", $expr, $matches ) ) { // make sure the characters are all good
					return self::trigger( "illegal character '{$matches[0]}'" );
				}
				
				while ( 1 ) { // 1 Infinite Loop ;)
					$op = substr( $expr, $index, 1 ); // get the first character at the current index
					// find out if we're currently at the beginning of a number/variable/function/parenthesis/operand
					$ex = preg_match( '/^([A-Za-z]\w*\(?|\d+(?:\.\d*)?|\.\d+|\()/', substr( $expr, $index ), $match );
					//===============
					if ( $op === '-' and ! $expecting_op ) { // is it a negation instead of a minus?
						$stack->push( '_' ); // put a negation on the stack
						$index ++;
					} elseif ( $op === '_' ) { // we have to explicitly deny this, because it's legal on the stack
						return wp_die( esc_html__( "illegal character '_'", 'advanced-flat-rate-shipping-for-woocommerce' ) );
						//===============
					} elseif ( ( in_array( $op, $ops, true ) or $ex ) and $expecting_op ) { // are we putting an operator on the stack?
						if ( $ex ) { // are we expecting an operator but have a number/variable/function/opening parethesis?
							$op = '*';
							$index --; // it's an implicit multiplication
						}
						// heart of the algorithm:
						$o2 = $stack->last();
						while ( $stack->count > 0 and ( $o2 ) and in_array( $o2, $ops, true ) and ( $ops_r[ $op ] ? $ops_p[ $op ] < $ops_p[ $o2 ] : $ops_p[ $op ] <= $ops_p[ $o2 ] ) ) {
							$output[] = $stack->pop(); // pop stuff off the stack into the output
						}
						// many thanks: http://en.wikipedia.org/wiki/Reverse_Polish_notation#The_algorithm_in_detail
						$stack->push( $op ); // finally put OUR operator onto the stack
						$index ++;
						$expecting_op = false;
						//===============
					} elseif ( $op === ')' and $expecting_op ) { // ready to close a parenthesis?
						$o2 = $stack->pop();
						while ( '(' !== $o2 ) { // pop off the stack back to the last (
							if ( is_null( $o2 ) ) {
								return wp_die( esc_html__( "unexpected ')'", 'advanced-flat-rate-shipping-for-woocommerce' ) );
							} else {
								$output[] = $o2;
							}
						}
						if ( preg_match( "/^([A-Za-z]\w*)\($/", $stack->last( 2 ), $matches ) ) { // did we just close a function?
							$fnn       = $matches[1]; // get the function name
							$arg_count = $stack->pop(); // see how many arguments there were (cleverly stored on the stack, thank you)
							$output[]  = $stack->pop(); // pop the function and push onto the output
							if ( in_array( $fnn, self::$fb, true ) ) { // check the argument count
								if ( $arg_count > 1 ) {
									return wp_die( sprintf( esc_html_x( 'too many arguments (%d given, 1 expected)', esc_attr( $arg_count ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
								}
							} elseif ( array_key_exists( $fnn, self::$f ) ) {
								if ( $arg_count !== count( self::$f[ $fnn ]['args'] ) ) {
									return wp_die( sprintf( esc_html_x( 'wrong number of arguments (%d given, %d expected', esc_attr( $arg_count ), esc_attr( count( self::$f[ $fnn ]['args'] ) ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
								}
							} else { // did we somehow push a non-function on the stack? this should never happen
								return wp_die( esc_html__( "internal error", 'advanced-flat-rate-shipping-for-woocommerce' ) );
							}
						}
						$index ++;
						//===============
					} elseif ( $op === ',' and $expecting_op ) { // did we just finish a function argument?
						$o2 = $stack->pop();
						while ( '(' !== $o2 ) {
							if ( is_null( $o2 ) ) {
								return wp_die( esc_html__( "unexpected ','", 'advanced-flat-rate-shipping-for-woocommerce' ) );
							} else {
								$output[] = $o2;
							} // pop the argument expression stuff and push onto the output
						}
						// make sure there was a function
						if ( ! preg_match( "/^([A-Za-z]\w*)\($/", $stack->last( 2 ), $matches ) ) {
							return wp_die( esc_html__( "unexpected ','", 'advanced-flat-rate-shipping-for-woocommerce' ) );
						}
						$stack->push( $stack->pop() + 1 ); // increment the argument count
						$stack->push( '(' ); // put the ( back on, we'll need to pop back to it again
						$index ++;
						$expecting_op = false;
						//===============
					} elseif ( $op === '(' and ! $expecting_op ) {
						$stack->push( '(' ); // that was easy
						$index ++;
						//===============
					} elseif ( $ex and ! $expecting_op ) { // do we now have a function/variable/number?
						$expecting_op = true;
						$val          = $match[1];
						if ( preg_match( "/^([A-Za-z]\w*)\($/", $val, $matches ) ) { // may be func, or variable w/ implicit multiplication against parentheses...
							if ( in_array( $matches[1], self::$fb, true ) or array_key_exists( $matches[1], self::$f ) ) { // it's a func
								$stack->push( $val );
								$stack->push( 1 );
								$stack->push( '(' );
								$expecting_op = false;
							} else { // it's a var w/ implicit multiplication
								$val      = $matches[1];
								$output[] = $val;
							}
						} else { // it's a plain old var or num
							$output[] = $val;
						}
						$index += strlen( $val );
						//===============
					} elseif ( $op === ')' ) { // miscellaneous error checking
						return wp_die( esc_html__( "unexpected ')'", 'advanced-flat-rate-shipping-for-woocommerce' ) );
					} elseif ( in_array( $op, $ops, true ) and ! $expecting_op ) {
						return wp_die( sprintf( esc_html_x( 'undefined variable %s', esc_attr( $op ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
					} else { // I don't even want to know what you did to get here
						return wp_die( "an unexpected error occured" );
					}
					if ( $index === strlen( $expr ) ) {
						if ( in_array( $op, $ops, true ) ) { // did we end with an operator? bad.
							return self::trigger( "operator '$op' lacks operand" );
						} else {
							break;
						}
					}
					while ( substr( $expr, $index, 1 ) === ' ' ) { // step the index past whitespace (pretty much turns whitespace
						$index ++;                             // into implicit multiplication if no operator is there)
					}
				}
				while ( ! is_null( $op = $stack->pop() ) ) { // pop everything off the stack and push onto output
					if ( $op === '(' ) {
						return wp_die( esc_html__( "expecting ')'", 'advanced-flat-rate-shipping-for-woocommerce' ) );
					} // if there are (s on the stack, ()s were unbalanced
					$output[] = $op;
				}
				
				return $output;
			}
			
			/**
			 * Evaluate postfix notation.
			 *
			 * @param mixed $tokens
			 * @param array $vars
			 *
			 * @return mixed
			 */
			private static function pfx( $tokens, $vars = array() ) {
				if ( $tokens === false ) {
					return false;
				}
				
				$stack = new WC_Eval_Math_Stack_Extra;
				
				foreach ( $tokens as $token ) { // nice and easy
					// if the token is a binary operator, pop two values off the stack, do the operation, and push the result back on
					if ( in_array( $token, array( '+', '-', '*', '/', '^' ), true ) ) {
						$op2 = $stack->pop();
						$op1 = $stack->pop();
						if ( is_null( $op2 ) ) {
							return wp_die( esc_html__( "internal error", 'advanced-flat-rate-shipping-for-woocommerce' ) );
						}
						if ( is_null( $op1 ) ) {
							return wp_die( esc_html__( "internal error", 'advanced-flat-rate-shipping-for-woocommerce' ) );
						}
						switch ( $token ) {
							case '+':
								$stack->push( $op1 + $op2 );
								break;
							case '-':
								$stack->push( $op1 - $op2 );
								break;
							case '*':
								$stack->push( $op1 * $op2 );
								break;
							case '/':
								if ( $op2 === 0 ) {
									return wp_die( esc_html__( "division by zero", 'advanced-flat-rate-shipping-for-woocommerce' ) );
								}
								$stack->push( $op1 / $op2 );
								break;
							case '^':
								$stack->push( pow( $op1, $op2 ) );
								break;
						}
						// if the token is a unary operator, pop one value off the stack, do the operation, and push it back on
					} elseif ( $token === "_" ) {
						$stack->push( - 1 * $stack->pop() );
						// if the token is a function, pop arguments off the stack, hand them to the function, and push the result back on
					} elseif ( ! preg_match( "/^([a-z]\w*)\($/", $token, $matches ) ) {
						if ( is_numeric( $token ) ) {
							$stack->push( $token );
						} elseif ( array_key_exists( $token, self::$v ) ) {
							$stack->push( self::$v[ $token ] );
						} elseif ( array_key_exists( $token, $vars ) ) {
							$stack->push( $vars[ $token ] );
						} else {
							return wp_die( sprintf( esc_html_x( 'undefined variable %d', esc_attr( $token ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
						}
					}
				}
				// when we're out of tokens, the stack should have a single element, the final result
				if ( 1 !== $stack->count ) {
					return wp_die( esc_html__( "internal error", 'advanced-flat-rate-shipping-for-woocommerce' ) );
				}
				
				return $stack->pop();
			}
			
		}
		
	}
	
	/**
	 * Class WC_Eval_Math_Stack_Extra.
	 */
	if ( ! class_exists( 'WC_Eval_Math_Stack_Extra' ) ) {
		
		class WC_Eval_Math_Stack_Extra {
			
			/**
			 * Stack array.
			 *
			 * @var array
			 */
			public $stack = array();
			
			/**
			 * Stack counter.
			 *
			 * @var integer
			 */
			public $count = 0;
			
			/**
			 * Push value into stack.
			 *
			 * @param mixed $val
			 */
			public function push( $val ) {
				$this->stack[ $this->count ] = $val;
				$this->count ++;
			}
			
			/**
			 * Pop value from stack.
			 *
			 * @return mixed
			 */
			public function pop() {
				if ( $this->count > 0 ) {
					$this->count --;
					
					return $this->stack[ $this->count ];
				}
				
				return null;
			}
			
			/**
			 * Get last value from stack.
			 *
			 * @param int $n
			 *
			 * @return mixed
			 */
			public function last( $n = 1 ) {
				$key = $this->count - $n;
				
				return array_key_exists( $key, $this->stack ) ? $this->stack[ $key ] : null;
			}
			
		}
		
	}