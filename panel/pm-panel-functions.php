<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * WP Pin Master Panel constants
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
defined( 'WPPML_OPTION' )     or  define( 'WPPML_OPTION',     'wppml_options' );
defined( 'WPPML_CUSTOMIZE' )  or  define( 'WPPML_CUSTOMIZE',  'wppml_customize_options' );

/**
 *
 * Panel path finder
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'wppml_get_path_locate' ) ) {
  function wppml_get_path_locate() {

    $dirname        = wp_normalize_path( dirname( __FILE__ ) );
    $plugin_dir     = wp_normalize_path( WP_PLUGIN_DIR );
    $located_plugin = ( preg_match( '#'. preg_replace( '/[^A-Za-z]/', '', $plugin_dir ) .'#', preg_replace( '/[^A-Za-z]/', '', $dirname ) ) ) ? true : false;
    $directory      = ( $located_plugin ) ? $plugin_dir : get_template_directory();
    $directory_uri  = ( $located_plugin ) ? WP_PLUGIN_URL : get_template_directory_uri();
    $basename       = str_replace( wp_normalize_path( $directory ), '', $dirname );
    $dir            = $directory . $basename;
    $uri            = $directory_uri . $basename;

    return apply_filters( 'wppml_get_path_locate', array(
      'basename' => wp_normalize_path( $basename ),
      'dir'      => wp_normalize_path( $dir ),
      'uri'      => $uri
    ) );

  }
}

/**
 *
 * Panel set paths
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 *
 */
$get_path = wppml_get_path_locate();

defined( 'WPPML_BASENAME' )  or  define( 'WPPML_BASENAME',  $get_path['basename'] );

/**
 *
 * Panel locate template and override files
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'wppml_locate_template' ) ) {
  function wppml_locate_template( $template_name ) {

    $located      = '';
    $dir_plugin   = wp_normalize_path( WP_PLUGIN_DIR );
    
    $dir_child    = get_stylesheet_directory();
    $dir_template = WPPML_BASENAME .'/'. $template_name;

    $located = $dir_plugin . $dir_template;

    $located = apply_filters( 'wppml_locate_template', $located, $template_name );

    if ( ! empty( $located ) ) {
      load_template( $located, true );
    }

    return $located;

  }
}



/**
 *
 * Get all option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wppml_get_all_option' ) ) {
  function wppml_get_all_option() {
    return get_option( WPPML_OPTION );
  }
}

/**
 *
 * Multi language option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wppml_get_multilang_option' ) ) {
  function wppml_get_multilang_option( $option_name = '', $default = '' ) {

    $value     = wppml_get_option( $option_name, $default );
    $languages = wppml_language_defaults();
    $default   = $languages['default'];
    $current   = $languages['current'];

    if ( is_array( $value ) && is_array( $languages ) && isset( $value[$current] ) ) {
      return  $value[$current];
    } else if ( $default != $current ) {
      return  '';
    }

    return $value;

  }
}

/**
 *
 * Multi language value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wppml_get_multilang_value' ) ) {
  function wppml_get_multilang_value( $value = '', $default = '' ) {

    $languages = wppml_language_defaults();
    $default   = $languages['default'];
    $current   = $languages['current'];

    if ( is_array( $value ) && is_array( $languages ) && isset( $value[$current] ) ) {
      return  $value[$current];
    } else if ( $default != $current ) {
      return  '';
    }

    return $value;

  }
}

/**
 *
 * Get customize option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wppml_get_customize_option' ) ) {
  function wppml_get_customize_option( $option_name = '', $default = '' ) {

    $options = apply_filters( 'wppml_get_customize_option', get_option( WPPML_CUSTOMIZE ), $option_name, $default );

    if( ! empty( $option_name ) && ! empty( $options[$option_name] ) ) {
      return $options[$option_name];
    } else {
      return ( ! empty( $default ) ) ? $default : null;
    }

  }
}

/**
 *
 * Set customize option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wppml_set_customize_option' ) ) {
  function wppml_set_customize_option( $option_name = '', $new_value = '' ) {

    $options = apply_filters( 'wppml_set_customize_option', get_option( WPPML_CUSTOMIZE ), $option_name, $new_value );

    if( ! empty( $option_name ) ) {
      $options[$option_name] = $new_value;
      update_option( WPPML_CUSTOMIZE, $options );
    }

  }
}

/**
 *
 * Get all customize option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wppml_get_all_customize_option' ) ) {
  function wppml_get_all_customize_option() {
    return get_option( WPPML_CUSTOMIZE );
  }
}

/**
 *
 * WPML plugin is activated
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wppml_is_wpml_activated' ) ) {
  function wppml_is_wpml_activated() {
    if ( class_exists( 'SitePress' ) ) { return true; } else { return false; }
  }
}

/**
 *
 * qTranslate plugin is activated
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wppml_is_qtranslate_activated' ) ) {
  function wppml_is_qtranslate_activated() {
    if ( function_exists( 'qtrans_getSortedLanguages' ) ) { return true; } else { return false; }
  }
}

/**
 *
 * Polylang plugin is activated
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wppml_is_polylang_activated' ) ) {
  function wppml_is_polylang_activated() {
    if ( class_exists( 'Polylang' ) ) { return true; } else { return false; }
  }
}

/**
 *
 * Get language defaults
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wppml_language_defaults' ) ) {
  function wppml_language_defaults() {
    $multilang = array();
    if( wppml_is_wpml_activated() || wppml_is_qtranslate_activated() || wppml_is_polylang_activated() ) {
      if( wppml_is_wpml_activated() ) {
        global $sitepress;
        $multilang['default']   = $sitepress->get_default_language();
        $multilang['current']   = $sitepress->get_current_language();
        $multilang['languages'] = $sitepress->get_active_languages();
      } else if( wppml_is_polylang_activated() ) {
        global $polylang;
        $current    = pll_current_language();
        $default    = pll_default_language();
        $current    = ( empty( $current ) ) ? $default : $current;
        $poly_langs = $polylang->model->get_languages_list();
        $languages  = array();
        foreach ( $poly_langs as $p_lang ) {
          $languages[$p_lang->slug] = $p_lang->slug;
        }
        $multilang['default']   = $default;
        $multilang['current']   = $current;
        $multilang['languages'] = $languages;
      } else if( wppml_is_qtranslate_activated() ) {
        global $q_config;
        $multilang['default']   = $q_config['default_language'];
        $multilang['current']   = $q_config['language'];
        $multilang['languages'] = array_flip( qtrans_getSortedLanguages() );
      }
    }
    $multilang = apply_filters( 'wppml_language_defaults', $multilang );
    return ( ! empty( $multilang ) ) ? $multilang : false;
  }
}