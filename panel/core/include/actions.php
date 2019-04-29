<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'pm_get_icons' ) ) {
  function pm_get_icons() {

    do_action( 'pm_add_icons_before' );

    $jsons = apply_filters( 'pm_add_icons_json', glob( PM_PANEL . '/fields/icon/*.json' ) );

    if( ! empty( $jsons ) ) {

      foreach ( $jsons as $path ) {

        $object = pm_get_icon_fonts( 'fields/icon/'. basename( $path ) );

        if( is_object( $object ) ) {

          echo ( count( $jsons ) >= 2 ) ? '<h4 class="pm-icon-title">'. $object->name .'</h4>' : '';

          foreach ( $object->icons as $icon ) {
            echo '<a class="pm-icon-tooltip" data-pm-icon="'. $icon .'" data-title="'. $icon .'"><span class="pm-icon pm-selector"><i class="'. $icon .'"></i></span></a>';
          }

        } else {
          echo '<h4 class="pm-icon-title">'. esc_html__( 'Error! Can not load json file.', 'pin-master' ) .'</h4>';
        }

      }

    }

    do_action( 'pm_add_icons' );
    do_action( 'pm_add_icons_after' );

    die();
  }
  add_action( 'wp_ajax_pm-get-icons', 'pm_get_icons' );
}

/**
 *
 * Export options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'pm_export_options' ) ) {
  function pm_export_options() {

    header('Content-Type: plain/text');
    header('Content-disposition: attachment; filename=backup-options-'. gmdate( 'd-m-Y' ) .'.txt');
    header('Content-Transfer-Encoding: binary');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo pm_encode_string( get_option( PM_OPTION ) );

    die();
  }
  add_action( 'wp_ajax_pm-export-options', 'pm_export_options' );
}

/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'pm_set_icons' ) ) {
  function pm_set_icons() {

    echo '<div id="pm-icon-dialog" class="pm-dialog" title="'. esc_html__( 'Add Pinterest Icon', 'pin-master' ) .'">';
    echo '<div class="pm-dialog-header pm-text-center"><input type="text" placeholder="'. esc_html__( 'Search Pinterest Icon...', 'pin-master' ) .'" class="pm-icon-search" /></div>';
    echo '<div class="pm-dialog-load"><div class="pm-icon-loading">'. esc_html__( 'Loading...', 'pin-master' ) .'</div></div>';
    echo '</div>';

  }
  add_action( 'admin_footer', 'pm_set_icons' );
  add_action( 'customize_controls_print_footer_scripts', 'pm_set_icons' );
}
