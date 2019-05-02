<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Framework admin enqueue style and scripts
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'wppml_admin_enqueue_scripts' ) ) {
  function wppml_admin_enqueue_scripts() {

    // admin utilities
    wp_enqueue_media();

    // wp core styles
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'wp-jquery-ui-dialog' );

    // framework core styles
    wp_enqueue_style( 'pin-master', WPPML_URI .'panel/assets/css/pm-framework.min.css', array(), '1.0.0', 'all' );
    wp_enqueue_style( 'font-awesome', WPPML_URI .'panel/assets/css/font-awesome.min.css', array(), '4.7.0', 'all' );
    wp_enqueue_style( 'pm-font', WPPML_ASSETS .'/css/style.min.css', array(), '4.7.0', 'all' );
    
    if ( is_rtl() ) {
        wp_enqueue_style( 'pm-framework-rtl', WPPML_URI .'panel/assets/css/pm-framework-rtl.css', array(), '1.0.0', 'all' );
    }

    // wp core scripts
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'jquery-ui-dialog' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'jquery-ui-accordion' );

    // framework core scripts
    wp_enqueue_script( 'pm-plugins',    WPPML_URI .'panel/assets/js/pm-plugins.min.js',    array(), '1.0.0', true );
    wp_enqueue_script( 'pm-panel',  WPPML_URI .'panel/assets/js/pm-panel.min.js',  array( 'pm-plugins' ), '1.0.0', true );

}
add_action( 'admin_enqueue_scripts', 'wppml_admin_enqueue_scripts' );
}
