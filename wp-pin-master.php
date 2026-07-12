<?php
/**
 * Plugin Name:       WP Pin Master
 * Plugin URI:        https://www.xstheme.com/wp-pin-master/
 * Description:       Pinterest "Pin It" buttons on your images — hover to pin, follow buttons, and board widgets.
 * Version:           2.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            xstheme
 * Author URI:        https://www.xstheme.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-pin-master
 * Domain Path:       /languages
 *
 * @package Pin_Master
 */

namespace Pin_Master;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PIN_MASTER_VERSION', '2.0.0' );
define( 'PIN_MASTER_FILE', __FILE__ );
define( 'PIN_MASTER_DIR', plugin_dir_path( __FILE__ ) );
define( 'PIN_MASTER_URL', plugin_dir_url( __FILE__ ) );
define( 'PIN_MASTER_INCLUDES', PIN_MASTER_DIR . 'includes' );
define( 'PIN_MASTER_ASSETS', PIN_MASTER_URL . 'assets' );
define( 'PIN_MASTER_BUILD', PIN_MASTER_URL . 'build' );
define( 'PIN_MASTER_OPTION', 'pin_master_options' );

require_once PIN_MASTER_INCLUDES . '/class-plugin.php';

/**
 * Main plugin instance.
 *
 * @return Plugin
 */
function pin_master() {
	return Plugin::init();
}

pin_master();
