<?php
/**
 * Customizer Utility Functions
 *
 * @package 	Customizer_Library
 * @author		Devin Price, The Theme Foundry
 */

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function customizer_library_customize_preview_js() {

	$path = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( __FILE__ ) ) );

	wp_enqueue_script( 'customizer_library_customizer', $path . '/js/customizer.js', array( 'customize-preview' ), '1.0.0', true );

}
add_action( 'customize_preview_init', 'customizer_library_customize_preview_js' );
