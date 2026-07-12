<?php
/**
 * Settings REST controller.
 *
 * @package Pin_Master
 */

namespace Pin_Master\Admin;

use Pin_Master\Options;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * REST controller for the settings app.
 *
 * Routes (namespace pin-master/v1, cookie auth + X-WP-Nonce):
 * - GET  /settings          Current settings (merged over defaults).
 * - POST /settings          Save settings (schema-sanitized).
 * - GET  /settings/export   Full settings blob for JSON download.
 * - POST /settings/import   Import a previously exported blob.
 */
class Settings_Controller {

	const REST_NAMESPACE = 'pin-master/v1';

	/**
	 * Register REST routes.
	 */
	public function register_routes() {
		register_rest_route(
			self::REST_NAMESPACE,
			'/settings',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_settings' ),
					'permission_callback' => array( $this, 'permission' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'save_settings' ),
					'permission_callback' => array( $this, 'permission' ),
					'args'                => array(
						'settings' => array(
							'type'     => 'object',
							'required' => true,
						),
					),
				),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE,
			'/settings/export',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'export_settings' ),
				'permission_callback' => array( $this, 'permission' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE,
			'/settings/import',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'import_settings' ),
				'permission_callback' => array( $this, 'permission' ),
				'args'                => array(
					'settings' => array(
						'type'     => 'object',
						'required' => true,
					),
				),
			)
		);
	}

	/**
	 * Only administrators may manage settings.
	 *
	 * @return bool|WP_Error
	 */
	public function permission() {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		return new WP_Error(
			'pin_master_forbidden',
			__( 'You are not allowed to manage WP Pin Master settings.', 'wp-pin-master-lite' ),
			array( 'status' => rest_authorization_required_code() )
		);
	}

	/**
	 * GET /settings
	 *
	 * @return WP_REST_Response
	 */
	public function get_settings() {
		return rest_ensure_response(
			array(
				'settings' => Options::get(),
			)
		);
	}

	/**
	 * POST /settings
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public function save_settings( WP_REST_Request $request ) {
		$clean = Settings_Schema::sanitize( (array) $request->get_param( 'settings' ) );

		update_option( PIN_MASTER_OPTION, $clean );

		return rest_ensure_response(
			array(
				'saved'    => true,
				'settings' => Options::get(),
			)
		);
	}

	/**
	 * GET /settings/export
	 *
	 * @return WP_REST_Response
	 */
	public function export_settings() {
		return rest_ensure_response(
			array(
				'plugin'   => 'wp-pin-master-lite',
				'version'  => PIN_MASTER_VERSION,
				'exported' => gmdate( 'c' ),
				'settings' => Options::get(),
			)
		);
	}

	/**
	 * POST /settings/import
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function import_settings( WP_REST_Request $request ) {
		$settings = $request->get_param( 'settings' );

		if ( ! is_array( $settings ) || empty( $settings ) ) {
			return new WP_Error(
				'pin_master_bad_import',
				__( 'The import file does not contain WP Pin Master settings.', 'wp-pin-master-lite' ),
				array( 'status' => 400 )
			);
		}

		$clean = Settings_Schema::sanitize( $settings );

		update_option( PIN_MASTER_OPTION, $clean );

		return rest_ensure_response(
			array(
				'imported' => true,
				'settings' => Options::get(),
			)
		);
	}
}
