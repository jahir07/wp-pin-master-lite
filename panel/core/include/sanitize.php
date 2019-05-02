<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Text sanitize
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'wppml_sanitize_text' ) ) {
  function wppml_sanitize_text( $value, $field ) {
    return wp_filter_nohtml_kses( $value );
  }
  add_filter( 'wppml_sanitize_text', 'wppml_sanitize_text', 10, 2 );
}

/**
 *
 * Textarea sanitize
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'wppml_sanitize_textarea' ) ) {
  function wppml_sanitize_textarea( $value ) {

    global $allowedposttags;
    return wp_kses( $value, $allowedposttags );

  }
  add_filter( 'wppml_sanitize_textarea', 'wppml_sanitize_textarea' );
}

/**
 *
 * Checkbox sanitize
 * Do not touch, or think twice.
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'wppml_sanitize_checkbox' ) ) {
  function wppml_sanitize_checkbox( $value ) {

    if( ! empty( $value ) && $value == 1 ) {
      $value = true;
    }

    if( empty( $value ) ) {
      $value = false;
    }

    return $value;

  }
  add_filter( 'wppml_sanitize_checkbox', 'wppml_sanitize_checkbox' );
  add_filter( 'wppml_sanitize_switcher', 'wppml_sanitize_checkbox' );
}

/**
 *
 * Image select sanitize
 * Do not touch, or think twice.
 *
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'wppml_sanitize_image_select' ) ) {
  function wppml_sanitize_image_select( $value ) {

    if( isset( $value ) && is_array( $value ) ) {
      if( count( $value ) ) {
        $value = $value;
      } else {
        $value = $value[0];
      }
    } else if ( empty( $value ) ) {
      $value = '';
    }

    return $value;

  }
  add_filter( 'wppml_sanitize_image_select', 'wppml_sanitize_image_select' );
}

/**
 *
 * Group sanitize
 * Do not touch, or think twice.
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'wppml_sanitize_group' ) ) {
  function wppml_sanitize_group( $value ) {
    return ( empty( $value ) ) ? '' : $value;
  }
  add_filter( 'wppml_sanitize_group', 'wppml_sanitize_group' );
}

/**
 *
 * Title sanitize
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'wppml_sanitize_title' ) ) {
  function wppml_sanitize_title( $value ) {
    return sanitize_title( $value );
  }
  add_filter( 'wppml_sanitize_title', 'wppml_sanitize_title' );
}

/**
 *
 * Text clean
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'wppml_sanitize_clean' ) ) {
  function wppml_sanitize_clean( $value ) {
    return $value;
  }
  add_filter( 'wppml_sanitize_clean', 'wppml_sanitize_clean', 10, 2 );
}
