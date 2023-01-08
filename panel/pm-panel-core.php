<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access pages directly.


if ( ! function_exists( 'wppml_panel_init' ) && ! class_exists( 'PinMaster' ) ) {
	add_action( 'init', 'wppml_panel_init', 10 );

	function wppml_panel_init() {

		// active modules
		defined( 'WPPML_ACTIVE_PANEL' ) or define( 'WPPML_ACTIVE_PANEL', true );

		// helpers
		require_once WPPML_PANEL . '/core/include/fallback.php';
		require_once WPPML_PANEL . '/core/include/helpers.php';
		require_once WPPML_PANEL . '/core/include/actions.php';
		require_once WPPML_PANEL . '/core/include/enqueue.php';
		require_once WPPML_PANEL . '/core/include/sanitize.php';
		require_once WPPML_PANEL . '/core/include/validate.php';

		// core classes
		require_once WPPML_PANEL . '/core/classes/abstract.php';
		require_once WPPML_PANEL . '/core/classes/options.php';
		require_once WPPML_PANEL . '/core/classes/framework.php';

		// settings
		require_once WPPML_PANEL . '/settings.php';
	}
}
