<?php
namespace Pin_Master\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers frontend scripts and styles from the wp-scripts build.
 */
class Assets {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register' ), 5 );
	}

	/**
	 * Register the frontend bundle. Enqueueing is decided by Frontend.
	 */
	public function register() {
		$asset_file = PIN_MASTER_DIR . 'build/frontend.asset.php';
		$asset      = file_exists( $asset_file )
			? require $asset_file
			: array(
				'dependencies' => array( 'jquery' ),
				'version'      => PIN_MASTER_VERSION,
			);

		wp_register_script(
			'pin-master-frontend',
			PIN_MASTER_BUILD . '/frontend.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_register_style(
			'pin-master-style',
			PIN_MASTER_BUILD . '/style-frontend.css',
			array(),
			$asset['version']
		);
	}
}
