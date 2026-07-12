<?php
/**
 * Central options access.
 *
 * @package Pin_Master
 */

namespace Pin_Master;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Central options access. All reads go through get() so defaults are
 * always merged and addons can filter them in one place.
 */
class Options {

	/**
	 * Default option values.
	 *
	 * @return array
	 */
	public static function defaults() {
		$defaults = array(
			'show_button'            => 'hover',
			'image_selector'         => 'article',
			'pin_text_data_collect'  => array( 'post_title' ),
			'button_position'        => 'top-left',
			'pin_image'              => 'default',
			'pin_image_button'       => 'round',
			'pin_button_width'       => 45,
			'pin_button_height'      => 45,
			'pin_font_size'          => 20,
			'pin_font_color'         => '#ffffff',
			'pin_bg_color'           => '#e60023',
			'pin_font_color_hover'   => '#ffffff',
			'pin_bg_color_hover'     => '#ad081b',
			'pin_space'              => array(
				'top'    => 20,
				'right'  => 20,
				'bottom' => 20,
				'left'   => 20,
			),
			'where_show'             => array( 'front', 'home', 'single', 'page', 'archive', 'search', 'category' ),
			'where_show_cpt'         => array(),
			'min_image_width'        => 200,
			'min_image_height'       => 150,
			'min_image_width_pixel'  => 200,
			'min_image_height_pixel' => 120,
		);

		/**
		 * Filter the default option values.
		 *
		 * @param array $defaults Default options.
		 */
		return apply_filters( 'pin_master_default_options', $defaults );
	}

	/**
	 * Saved options merged over defaults. Never returns false or a
	 * partial array, so downstream code can index keys safely.
	 *
	 * @return array
	 */
	public static function get() {
		$saved = get_option( PIN_MASTER_OPTION );

		if ( ! is_array( $saved ) ) {
			$saved = array();
		}

		return array_merge( self::defaults(), $saved );
	}

	/**
	 * Map the semantic image_selector option to a CSS selector.
	 *
	 * @param string $value Option value (article|sidebar|all).
	 * @return string CSS selector.
	 */
	public static function image_selector( $value ) {
		$map = array(
			'article' => '.pm_container img',
			'sidebar' => '.pm_container img, aside img, .widget img, .sidebar img, .right-sidebar img, .left-sidebar img',
			'all'     => 'img',
		);

		return $map[ $value ] ?? $map['article'];
	}
}
