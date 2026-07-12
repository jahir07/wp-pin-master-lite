<?php
/**
 * WP Pin Master uninstall cleanup.
 *
 * @package Pin_Master
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'pin_master_options' );
delete_option( 'wp_pin_master_installed' );
delete_option( 'wp_pin_master_version' );

// Legacy 1.x option.
delete_option( 'wppml_options' );
