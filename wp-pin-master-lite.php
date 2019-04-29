<?php
/*
Plugin Name: WP Pin Master Lite
Plugin URI: http://codextune.com/wp-pin-master
Description: All In One Pinterest WP Plugin.
Author: codextune.com
Author URI: https://www.codextune.com/
Version: 1.0.0
Text Domain: pin-master
Domain Path: /languages
*/


/**
 * Copyright (c) 2018 CodexTune (email: info@codextune.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

namespace Pin_Master;

use Pin_Master\Classes\Assets;
use Pin_Master\Classes\Frontend;
use Pin_Master\Classes\Widgets;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Pin_Master class
 * @since  1.0
 * @class Pin_Master The class that holds the entire Pin_Master plugin
 */
final class Pin_Master {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Minimum PHP version required
     *
     * @var string
     */
    private $min_php = '5.6.0';

    /**
     * Holds various class instances
     *
     * @since 2.6.10
     *
     * @var array
     */
    private $container = array();

    /**
     * Constructor for the Pin_Master class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    public function __construct() {

        $this->define_constants();

        if ( ! $this->is_supported_php() ) {
            return;
        }

        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Check if the PHP version is supported
     *
     * @return bool
     */
    public function is_supported_php() {
        if ( version_compare( PHP_VERSION, $this->min_php, '<=' ) ) {
            return false;
        }

        return true;
    }


    /**
     * Initializes the Pin_Master() class
     *
     * Checks for an existing Pin_Master() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Pin_Master();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing objects
     *
     * @since 1.0
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }


    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants() {

        // Plugin version.
        if ( ! defined( 'PM_VERSION' ) ) {
            define( 'PM_VERSION', $this->version );
        }

        // Plugin Folder Path.
        if ( ! defined( 'PM_DIR' ) ) {
            define( 'PM_DIR', plugin_dir_path( __FILE__ ) );
        }

        // Plugin Folder URL.
        if ( ! defined( 'PM_URI' ) ) {
            define( 'PM_URI', plugin_dir_url( __FILE__ ) );
        }

        // Plugin Root File.
        if ( ! defined( 'PM_FILE' ) ) {
            define( 'PM_FILE', __FILE__ );
        }

        // Plugins Classes
        if ( ! defined( 'PM_CLASSES' ) ) {
            define( 'PM_CLASSES', PM_DIR . 'classes' );
        }

        // Plugins options framework
        if ( ! defined( 'PM_PANEL' ) ) {
            define( 'PM_PANEL', PM_DIR . 'panel' );
        }

        // Plugins options config
        if ( ! defined( 'PM_CONFIG' ) ) {
            define( 'PM_CONFIG', PM_DIR . 'config' );
        }

        // Plugins Assets
        if ( ! defined( 'PM_ASSETS' ) ) {
            define( 'PM_ASSETS', PM_URI . 'assets' );
        }
        
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate() {

        $installed = get_option( 'wp_pin_master_installed' );

        if ( ! $installed ) {
            update_option( 'wp_pin_master_installed', time() );
        }

        update_option( 'wp_pin_master_version', PM_VERSION );
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate() {

    }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes() {

        require_once PM_CLASSES . '/assets.php';
        require_once PM_CLASSES . '/widgets.php';

        if ( $this->is_request( 'admin' ) ) {
            require_once PM_PANEL . '/pm-panel-functions.php';
            require_once PM_PANEL . '/pm-panel-core.php';
        }

        if ( $this->is_request( 'frontend' ) ) {
            require_once PM_CLASSES . '/frontend.php';
        }

        if ( did_action( 'elementor/loaded' ) ) {
            require_once PM_CLASSES . '/elementor.php';
        }
    }

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function init_hooks() {

        // initialize classes
        add_action( 'init', [ $this, 'init_classes' ] );

        // Localize our plugin
        add_action( 'init', [ $this, 'localization_setup' ] );
    }

    /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes() {
        $this->container['assets'] = new Assets();

        if ( $this->is_request( 'frontend' ) ) {
            $this->container['frontend'] = new Frontend();
        }
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'pint-master', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
            return is_admin();

            case 'ajax' :
            return defined( 'DOING_AJAX' );

            case 'rest' :
            return defined( 'REST_REQUEST' );

            case 'cron' :
            return defined( 'DOING_CRON' );

            case 'frontend' :
            return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }




} // Pin_Master

/**
 * Load wp_pin_master Plugin when all plugins loaded
 *
 * @return void
 */
function wp_pin_master() {
    return Pin_Master::init();
}

// Lets Play :)
wp_pin_master();
