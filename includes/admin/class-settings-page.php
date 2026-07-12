<?php
namespace Pin_Master\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the admin menu page and mounts the React settings app.
 */
class Settings_Page {

	const MENU_SLUG = 'wp-pin-master';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * The settings app page title.
	 *
	 * @return string
	 */
	public static function title() {
		/**
		 * Filter the settings page title (Pro adds its badge).
		 *
		 * @param string $title Page title.
		 */
		return apply_filters( 'pin_master_settings_title', __( 'WP Pin Master', 'wp-pin-master' ) );
	}

	/**
	 * Register the top-level menu page.
	 */
	public function register_menu() {
		add_menu_page(
			self::title(),
			__( 'Pin Master', 'wp-pin-master' ),
			'manage_options',
			self::MENU_SLUG,
			array( $this, 'render' ),
			PIN_MASTER_ASSETS . '/images/pinicon.png',
			66
		);
	}

	/**
	 * Render the app mount point.
	 */
	public function render() {
		echo '<div id="pin-master-settings" class="pin-master-settings-wrap"></div>';
	}

	/**
	 * Enqueue the settings app on our screen only.
	 *
	 * @param string $hook_suffix Current admin page.
	 */
	public function enqueue( $hook_suffix ) {
		if ( 'toplevel_page_' . self::MENU_SLUG !== $hook_suffix ) {
			return;
		}

		$asset_file = PIN_MASTER_DIR . 'build/settings.asset.php';
		$asset      = file_exists( $asset_file )
			? require $asset_file
			: array(
				'dependencies' => array(),
				'version'      => PIN_MASTER_VERSION,
			);

		wp_enqueue_script(
			'pin-master-settings',
			PIN_MASTER_BUILD . '/settings.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_enqueue_style( 'wp-components' );

		$style = PIN_MASTER_DIR . 'build/style-settings.css';
		if ( file_exists( $style ) ) {
			wp_enqueue_style(
				'pin-master-settings',
				PIN_MASTER_BUILD . '/style-settings.css',
				array( 'wp-components' ),
				$asset['version']
			);
		}

		wp_add_inline_script(
			'pin-master-settings',
			'window.pinMasterSettings = ' . wp_json_encode(
				array(
					'schema'    => Settings_Schema::get(),
					'title'     => self::title(),
					'assetsUrl' => PIN_MASTER_ASSETS,
					'isPro'     => (bool) apply_filters( 'pin_master_is_pro', false ),
					'upgradeUrl' => 'https://www.codextune.com/downloads/wp-pin-master-pro/',
				)
			) . ';',
			'before'
		);

		/**
		 * Fires when the settings screen assets are enqueued. Addons load
		 * their settings-app extensions (custom field renderers) here.
		 */
		do_action( 'pin_master_settings_assets' );
	}
}
