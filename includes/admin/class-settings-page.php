<?php
/**
 * Settings screen registration.
 *
 * @package Pin_Master
 */

namespace Pin_Master\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the admin menu page and mounts the React settings app.
 */
class Settings_Page {

	const MENU_SLUG = 'wp-pin-master-lite';

	/**
	 * Hook registration.
	 */
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
		return apply_filters( 'pin_master_settings_title', __( 'WP Pin Master', 'wp-pin-master-lite' ) );
	}

	/**
	 * The admin menu icon: a 20x20 Pinterest-pin glyph as an inline SVG.
	 * WP recognizes the base64 SVG form and sizes/colors it like a Dashicon.
	 *
	 * @return string
	 */
	private function menu_icon() {
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill="#a7aaad" d="M10 1.5a8.5 8.5 0 0 0-3.1 16.4c-.07-.72-.14-1.83.03-2.62l.98-4.17s-.25-.5-.25-1.24c0-1.16.67-2.03 1.51-2.03.71 0 1.06.54 1.06 1.18 0 .72-.46 1.79-.69 2.79-.2.83.42 1.51 1.24 1.51 1.49 0 2.63-1.57 2.63-3.83 0-2-1.44-3.4-3.5-3.4-2.38 0-3.78 1.79-3.78 3.63 0 .72.28 1.49.62 1.91a.25.25 0 0 1 .06.24l-.23.95c-.04.15-.12.19-.28.11-1.06-.49-1.72-2.04-1.72-3.28 0-2.67 1.94-5.13 5.6-5.13 2.94 0 5.22 2.09 5.22 4.89 0 2.92-1.84 5.27-4.4 5.27-.86 0-1.66-.44-1.94-.97l-.53 2.01c-.19.74-.71 1.66-1.05 2.22A8.5 8.5 0 1 0 10 1.5z"/></svg>';

		return 'data:image/svg+xml;base64,' . base64_encode( $svg ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Admin menu SVG icon, the form WP core expects.
	}

	/**
	 * Register the top-level menu page.
	 */
	public function register_menu() {
		add_menu_page(
			self::title(),
			__( 'Pin Master', 'wp-pin-master-lite' ),
			'manage_options',
			self::MENU_SLUG,
			array( $this, 'render' ),
			$this->menu_icon(),
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
					'schema'     => Settings_Schema::get(),
					'title'      => self::title(),
					'version'    => PIN_MASTER_VERSION,
					'assetsUrl'  => PIN_MASTER_ASSETS,
					'isPro'      => (bool) apply_filters( 'pin_master_is_pro', false ),
					'upgradeUrl' => 'https://www.xstheme.com/wp-pin-master-pro/',
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
