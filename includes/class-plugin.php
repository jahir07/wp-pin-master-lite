<?php
/**
 * The core plugin container.
 *
 * @package Pin_Master
 */

namespace Pin_Master;

use Pin_Master\Classes\Assets;
use Pin_Master\Classes\Frontend;
use Pin_Master\Admin\Settings_Controller;
use Pin_Master\Admin\Settings_Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The core plugin container. Boots every service and exposes the
 * extension surface (actions/filters) that addons such as
 * WP Pin Master Pro build on. See docs/hooks.md.
 */
final class Plugin {

	/**
	 * Minimum PHP version required.
	 *
	 * @var string
	 */
	private $min_php = '7.4';

	/**
	 * Service container.
	 *
	 * @var array
	 */
	private $container = array();

	/**
	 * Set up hooks. Private — use init().
	 */
	private function __construct() {
		if ( ! $this->is_supported_php() ) {
			add_action( 'admin_notices', array( $this, 'php_version_notice' ) );
			return;
		}

		register_activation_hook( PIN_MASTER_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( PIN_MASTER_FILE, array( $this, 'deactivate' ) );

		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
	}

	/**
	 * Whether the current PHP version is supported.
	 *
	 * @return bool
	 */
	public function is_supported_php() {
		return version_compare( PHP_VERSION, $this->min_php, '>=' );
	}

	/**
	 * Admin notice shown when PHP is too old.
	 */
	public function php_version_notice() {
		printf(
			'<div class="notice notice-error"><p>%s</p></div>',
			esc_html(
				sprintf(
					/* translators: 1: required PHP version, 2: current PHP version */
					__( 'WP Pin Master requires PHP %1$s or newer. You are running %2$s.', 'wp-pin-master' ),
					$this->min_php,
					PHP_VERSION
				)
			)
		);
	}

	/**
	 * Singleton accessor.
	 *
	 * @return Plugin
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new Plugin();
		}

		return $instance;
	}

	/**
	 * Fetch a service from the container.
	 *
	 * @param string $key Service key.
	 * @return mixed|null
	 */
	public function get( $key ) {
		return $this->container[ $key ] ?? null;
	}

	/**
	 * Register or replace a service in the container. Addons may use
	 * this during the `pin_master_register_services` action.
	 *
	 * @param string $key      Service key.
	 * @param mixed  $instance Service instance.
	 */
	public function set( $key, $instance ) {
		$this->container[ $key ] = $instance;
	}

	/**
	 * Magic getter for container services.
	 *
	 * @param string $prop Service key.
	 * @return mixed
	 */
	public function __get( $prop ) {
		return $this->get( $prop );
	}

	/**
	 * Magic isset for container services.
	 *
	 * @param string $prop Service key.
	 * @return bool
	 */
	public function __isset( $prop ) {
		return isset( $this->container[ $prop ] );
	}

	/**
	 * Load the plugin once all plugins are loaded.
	 */
	public function init_plugin() {
		$this->includes();
		$this->init_hooks();

		/**
		 * Fires when WP Pin Master core has loaded. Addons boot here.
		 *
		 * @param Plugin $plugin The core plugin instance.
		 */
		do_action( 'pin_master_loaded', $this );
	}

	/**
	 * Activation: record install time/version, seed defaults.
	 */
	public function activate() {
		if ( ! get_option( 'wp_pin_master_installed' ) ) {
			update_option( 'wp_pin_master_installed', time() );
		}

		update_option( 'wp_pin_master_version', PIN_MASTER_VERSION );

		// One-time courtesy copy from the legacy 1.x option, run through the
		// schema sanitizer because the 1.x shapes differ. Safe to remove
		// before a public release.
		$legacy = get_option( 'wppml_options' );
		if ( is_array( $legacy ) && ! get_option( PIN_MASTER_OPTION ) ) {
			require_once PIN_MASTER_INCLUDES . '/class-options.php';
			require_once PIN_MASTER_INCLUDES . '/admin/class-settings-schema.php';
			update_option( PIN_MASTER_OPTION, Admin\Settings_Schema::sanitize( $legacy ) );
		}

		/** Fires after WP Pin Master is activated. */
		do_action( 'pin_master_activated' );
	}

	/**
	 * Deactivation hook.
	 */
	public function deactivate() {
		/** Fires after WP Pin Master is deactivated. */
		do_action( 'pin_master_deactivated' );
	}

	/**
	 * Include required files.
	 */
	public function includes() {
		require_once PIN_MASTER_INCLUDES . '/class-options.php';
		require_once PIN_MASTER_INCLUDES . '/class-assets.php';
		require_once PIN_MASTER_INCLUDES . '/widgets/class-follow-widget.php';
		require_once PIN_MASTER_INCLUDES . '/widgets/class-board-widget.php';
		require_once PIN_MASTER_INCLUDES . '/class-frontend.php';
		require_once PIN_MASTER_INCLUDES . '/admin/class-settings-schema.php';
		require_once PIN_MASTER_INCLUDES . '/admin/class-settings-page.php';
		require_once PIN_MASTER_INCLUDES . '/admin/class-settings-controller.php';

		if ( did_action( 'elementor/loaded' ) ) {
			require_once PIN_MASTER_INCLUDES . '/class-elementor.php';
		}
	}

	/**
	 * Wire core hooks.
	 */
	public function init_hooks() {
		add_action( 'init', array( $this, 'init_classes' ) );
		add_action( 'init', array( $this, 'localization_setup' ) );
		add_action( 'rest_api_init', array( $this, 'init_rest' ) );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
	}

	/**
	 * Register the plugin widgets.
	 */
	public function register_widgets() {
		register_widget( Classes\Follow_Widget::class );
		register_widget( Classes\Board_Widget::class );
	}

	/**
	 * Instantiate core services.
	 */
	public function init_classes() {
		$this->container['assets'] = new Assets();

		if ( $this->is_request( 'frontend' ) ) {
			$this->container['frontend'] = new Frontend();
		}

		if ( is_admin() ) {
			$this->container['settings_page'] = new Settings_Page();
		}

		if ( did_action( 'elementor/loaded' ) ) {
			$this->container['elementor'] = new Classes\Elementor();
		}

		/**
		 * Fires after core services are registered. Addons may add or
		 * replace container entries via $plugin->set().
		 *
		 * @param Plugin $plugin The core plugin instance.
		 */
		do_action( 'pin_master_register_services', $this );
	}

	/**
	 * Register REST routes.
	 */
	public function init_rest() {
		$controller = new Settings_Controller();
		$controller->register_routes();
		$this->container['settings_rest'] = $controller;
	}

	/**
	 * Load the text domain.
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'wp-pin-master', false, dirname( plugin_basename( PIN_MASTER_FILE ) ) . '/languages/' );
	}

	/**
	 * What type of request is this?
	 *
	 * @param string $type admin, ajax, rest, cron or frontend.
	 * @return bool
	 */
	public function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();

			case 'ajax':
				return defined( 'DOING_AJAX' );

			case 'rest':
				return defined( 'REST_REQUEST' );

			case 'cron':
				return defined( 'DOING_CRON' );

			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}

		return false;
	}
}
