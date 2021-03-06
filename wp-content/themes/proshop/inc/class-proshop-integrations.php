<?php
/**
 * Proshop_Integrations Class
 *
 * @author   WooThemes
 * @since    2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Proshop_Integrations' ) ) :

class Proshop_Integrations {

	/**
	 * Setup class.
	 *
	 * @since 1.0
	 */
	public function __construct() {
        add_action( 'after_switch_theme', 				array( $this, 'edit_theme_mods' ) );
        add_action( 'customize_register', 				array( $this, 'edit_controls' ), 99 );
		add_action( 'customize_register',               array( $this, 'edit_extension_default_settings' ),      99 );
		add_action( 'init',								array( $this, 'default_theme_mod_values' ) );
	}

	/**
	 * Returns an array with storefront extension options
	 * @return array
	 */
	public function extension_defaults() {
		return array(
            // Storefront Designer
			'sd_content_background_color'				=> '#ffffff',
		);
	}

	/**
	 * Set Customizer settings for extensions.
	 * @return void
	 */
	public function edit_extension_default_settings( $wp_customize ) {
		// Set default values for settings in customizer
		foreach ( Proshop_Integrations::extension_defaults() as $mod => $val ) {
			$setting = $wp_customize->get_setting( $mod );

			if ( is_object( $setting ) ) {
				$setting->default = $val;
			}
		}
	}

	/**
	 * Returns a default theme_mod value if there is none set.
	 * @uses extension_defaults()
	 * @return void
	 */
	public function default_theme_mod_values() {
		foreach ( Proshop_Integrations::extension_defaults() as $mod => $val ) {
			add_filter( 'theme_mod_' . $mod, function( $setting ) use ( $val ) {
				return $setting ? $setting : $val;
			});
		}
	}

	/**
	 * Remove unused/incompatible controls
	 * @return void
	 */
	public function edit_controls( $wp_customize ) {
		$wp_customize->remove_control( 'sd_header_layout' );
		$wp_customize->remove_control( 'sd_button_flat' );
		$wp_customize->remove_control( 'sd_button_shadows' );
		$wp_customize->remove_control( 'sd_button_background_style' );
		$wp_customize->remove_control( 'sd_button_rounded' );
		$wp_customize->remove_control( 'sd_button_size' );
		$wp_customize->remove_control( 'sd_header_layout_divider_after' );
		$wp_customize->remove_control( 'sd_button_divider_1' );
		$wp_customize->remove_control( 'sd_button_divider_2' );
	}

	/**
	 * Set / remove theme mods that are incompatible with this theme
	 * @return void
	 */
	 public function edit_theme_mods() {
		 remove_theme_mod( 'sd_header_layout' );
		remove_theme_mod( 'sd_button_flat' );
		remove_theme_mod( 'sd_button_shadows' );
		remove_theme_mod( 'sd_button_background_style' );
		remove_theme_mod( 'sd_button_rounded' );
		remove_theme_mod( 'sd_button_size' );
		remove_theme_mod( 'sd_header_layout_divider_after' );
		remove_theme_mod( 'sd_button_divider_1' );
		remove_theme_mod( 'sd_button_divider_2' );
	}
}

endif;

return new Proshop_Integrations();