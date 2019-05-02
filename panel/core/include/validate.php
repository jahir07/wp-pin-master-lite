<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Email validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'wppml_validate_email' ) ) {
  function wppml_validate_email( $value, $field ) {

    if ( ! sanitize_email( $value ) ) {
      return esc_html__( 'Please write a valid email address!', 'pin-master' );
    }

  }
  add_filter( 'wppml_validate_email', 'wppml_validate_email', 10, 2 );
}

/**
 *
 * Numeric validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'wppml_validate_numeric' ) ) {
  function wppml_validate_numeric( $value, $field ) {

    if ( ! is_numeric( $value ) ) {
      return esc_html__( 'Please write a numeric data!', 'pin-master' );
    }

  }
  add_filter( 'wppml_validate_numeric', 'wppml_validate_numeric', 10, 2 );
}

/**
 *
 * Required validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'wppml_validate_required' ) ) {
  function wppml_validate_required( $value ) {
    if ( empty( $value ) ) {
      return esc_html__( 'Fatal Error! This field is required!', 'pin-master' );
    }
  }
  add_filter( 'wppml_validate_required', 'wppml_validate_required' );
}
