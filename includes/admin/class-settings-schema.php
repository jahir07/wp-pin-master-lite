<?php
/**
 * Settings schema and sanitization.
 *
 * @package Pin_Master
 */

namespace Pin_Master\Admin;

use Pin_Master\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Declarative settings schema. Drives both the React settings app
 * (rendered client side) and server-side sanitization in the REST
 * controller, so the two can never drift apart.
 *
 * Field shape:
 * - id        Option key.
 * - type      select | multiselect | number | color | box | dimensions | toggle | text.
 * - label     Field label.
 * - help      Optional help text.
 * - choices   For (multi)select: value => [ label, pro(bool) ]. Pro-locked
 *             choices render disabled in Lite; the sanitizer rejects them
 *             unless an addon unlocks them via the schema filter.
 * - default   Default value.
 * - pro       Whole field locked to Pro.
 * - show_if   [ field_id, operator, value ] conditional display.
 * - min/max   For number fields.
 */
class Settings_Schema {

	/**
	 * The full settings schema: tabs => fields.
	 *
	 * @return array
	 */
	public static function get() {
		$defaults = Options::defaults();

		$cpt_choices = array();
		foreach ( get_post_types(
			array(
				'public'   => true,
				'_builtin' => false,
			),
			'objects'
		) as $cpt ) {
			$cpt_choices[ $cpt->name ] = array(
				'label' => $cpt->labels->singular_name,
			);
		}

		$tabs = array(
			array(
				'id'     => 'general',
				'title'  => __( 'General', 'wp-pin-master' ),
				'fields' => array(
					array(
						'id'      => 'show_button',
						'type'    => 'select',
						'label'   => __( 'Show Pin Button', 'wp-pin-master' ),
						'help'    => __( 'When the Pinterest button appears on images.', 'wp-pin-master' ),
						'default' => $defaults['show_button'],
						'choices' => array(
							'hover'        => array( 'label' => __( 'On hover', 'wp-pin-master' ) ),
							'always_touch' => array(
								'label' => __( 'On touch devices', 'wp-pin-master' ),
								'pro'   => true,
							),
							'always'       => array(
								'label' => __( 'Always visible', 'wp-pin-master' ),
								'pro'   => true,
							),
						),
					),
					array(
						'id'      => 'image_selector',
						'type'    => 'select',
						'label'   => __( 'Which Images', 'wp-pin-master' ),
						'help'    => __( 'Where the pin button is offered.', 'wp-pin-master' ),
						'default' => $defaults['image_selector'],
						'choices' => array(
							'article' => array( 'label' => __( 'Article area only', 'wp-pin-master' ) ),
							'sidebar' => array(
								'label' => __( 'Article area + sidebar', 'wp-pin-master' ),
								'pro'   => true,
							),
							'all'     => array(
								'label' => __( 'All images on the site', 'wp-pin-master' ),
								'pro'   => true,
							),
						),
					),
					array(
						'id'      => 'pin_text_data_collect',
						'type'    => 'multiselect',
						'label'   => __( 'Pin Description Sources', 'wp-pin-master' ),
						'help'    => __( 'Ordered priority list. The first source with data becomes the Pinterest description.', 'wp-pin-master' ),
						'default' => $defaults['pin_text_data_collect'],
						'choices' => array(
							'post_title'           => array( 'label' => __( 'Post title', 'wp-pin-master' ) ),
							'post_excerpt'         => array( 'label' => __( 'Post excerpt', 'wp-pin-master' ) ),
							'img_title'            => array( 'label' => __( 'Image title attribute', 'wp-pin-master' ) ),
							'img_description'      => array( 'label' => __( 'Image description', 'wp-pin-master' ) ),
							'img_caption'          => array( 'label' => __( 'Image caption', 'wp-pin-master' ) ),
							'img_alt'              => array( 'label' => __( 'Image alt attribute', 'wp-pin-master' ) ),
							'site_title'           => array( 'label' => __( 'Site title', 'wp-pin-master' ) ),
							'data_pin_description' => array(
								'label' => __( 'Pinterest description (per image)', 'wp-pin-master' ),
								'pro'   => true,
							),
						),
					),
				),
			),
			array(
				'id'     => 'style',
				'title'  => __( 'Style', 'wp-pin-master' ),
				'fields' => array(
					array(
						'id'      => 'button_position',
						'type'    => 'select',
						'label'   => __( 'Button Position', 'wp-pin-master' ),
						'default' => $defaults['button_position'],
						'choices' => array(
							'top-left'     => array( 'label' => __( 'Top Left', 'wp-pin-master' ) ),
							'top-right'    => array( 'label' => __( 'Top Right', 'wp-pin-master' ) ),
							'bottom-left'  => array( 'label' => __( 'Bottom Left', 'wp-pin-master' ) ),
							'bottom-right' => array( 'label' => __( 'Bottom Right', 'wp-pin-master' ) ),
							'middle'       => array( 'label' => __( 'Center', 'wp-pin-master' ) ),
						),
					),
					array(
						'id'      => 'pin_image',
						'type'    => 'select',
						'label'   => __( 'Button Style', 'wp-pin-master' ),
						'default' => $defaults['pin_image'],
						'choices' => array(
							'old_default' => array( 'label' => __( 'Classic Pinterest button', 'wp-pin-master' ) ),
							'default'     => array( 'label' => __( 'Modern button', 'wp-pin-master' ) ),
							'icon'        => array(
								'label' => __( 'Icon', 'wp-pin-master' ),
								'pro'   => true,
							),
							'custom'      => array(
								'label' => __( 'Custom image', 'wp-pin-master' ),
								'pro'   => true,
							),
						),
					),
					array(
						'id'      => 'pin_image_button',
						'type'    => 'select',
						'label'   => __( 'Button Shape', 'wp-pin-master' ),
						'default' => $defaults['pin_image_button'],
						'show_if' => array( 'pin_image', 'in', array( 'default', 'icon' ) ),
						'choices' => array(
							'square'         => array( 'label' => __( 'Square', 'wp-pin-master' ) ),
							'rounded-square' => array( 'label' => __( 'Rounded Square', 'wp-pin-master' ) ),
							'round'          => array( 'label' => __( 'Round', 'wp-pin-master' ) ),
						),
					),
					array(
						'id'      => 'pin_button_width',
						'type'    => 'number',
						'label'   => __( 'Button Width (px)', 'wp-pin-master' ),
						'default' => $defaults['pin_button_width'],
						'min'     => 10,
						'max'     => 300,
					),
					array(
						'id'      => 'pin_button_height',
						'type'    => 'number',
						'label'   => __( 'Button Height (px)', 'wp-pin-master' ),
						'default' => $defaults['pin_button_height'],
						'min'     => 10,
						'max'     => 300,
					),
					array(
						'id'      => 'pin_font_size',
						'type'    => 'number',
						'label'   => __( 'Icon Size (px)', 'wp-pin-master' ),
						'default' => $defaults['pin_font_size'],
						'min'     => 6,
						'max'     => 120,
						'show_if' => array( 'pin_image', 'in', array( 'default', 'icon' ) ),
					),
					array(
						'id'      => 'pin_font_color',
						'type'    => 'color',
						'label'   => __( 'Icon Color', 'wp-pin-master' ),
						'default' => $defaults['pin_font_color'],
						'show_if' => array( 'pin_image', 'in', array( 'default', 'icon' ) ),
					),
					array(
						'id'      => 'pin_bg_color',
						'type'    => 'color',
						'label'   => __( 'Background Color', 'wp-pin-master' ),
						'default' => $defaults['pin_bg_color'],
						'show_if' => array( 'pin_image', 'in', array( 'default', 'icon' ) ),
					),
					array(
						'id'      => 'pin_font_color_hover',
						'type'    => 'color',
						'label'   => __( 'Icon Color (hover)', 'wp-pin-master' ),
						'default' => $defaults['pin_font_color_hover'],
						'show_if' => array( 'pin_image', 'in', array( 'default', 'icon' ) ),
					),
					array(
						'id'      => 'pin_bg_color_hover',
						'type'    => 'color',
						'label'   => __( 'Background Color (hover)', 'wp-pin-master' ),
						'default' => $defaults['pin_bg_color_hover'],
						'show_if' => array( 'pin_image', 'in', array( 'default', 'icon' ) ),
					),
					array(
						'id'      => 'pin_space',
						'type'    => 'box',
						'label'   => __( 'Button Margin (px)', 'wp-pin-master' ),
						'default' => $defaults['pin_space'],
					),
				),
			),
			array(
				'id'     => 'advanced',
				'title'  => __( 'Advanced', 'wp-pin-master' ),
				'fields' => array(
					array(
						'id'      => 'where_show',
						'type'    => 'multiselect',
						'label'   => __( 'Active On', 'wp-pin-master' ),
						'default' => $defaults['where_show'],
						'choices' => array(
							'front'    => array( 'label' => __( 'Front page', 'wp-pin-master' ) ),
							'home'     => array( 'label' => __( 'Blog homepage', 'wp-pin-master' ) ),
							'single'   => array( 'label' => __( 'Single posts', 'wp-pin-master' ) ),
							'page'     => array( 'label' => __( 'Pages', 'wp-pin-master' ) ),
							'archive'  => array( 'label' => __( 'Archives', 'wp-pin-master' ) ),
							'search'   => array( 'label' => __( 'Search results', 'wp-pin-master' ) ),
							'category' => array( 'label' => __( 'Category pages', 'wp-pin-master' ) ),
						),
					),
					array(
						'id'      => 'where_show_cpt',
						'type'    => 'multiselect',
						'label'   => __( 'Custom Post Types', 'wp-pin-master' ),
						'default' => array(),
						'pro'     => true,
						'choices' => $cpt_choices,
					),
					array(
						'id'      => 'min_image_width',
						'type'    => 'number',
						'label'   => __( 'Minimum Image Width (px)', 'wp-pin-master' ),
						'help'    => __( 'Images narrower than this never get a pin button.', 'wp-pin-master' ),
						'default' => $defaults['min_image_width'],
						'min'     => 0,
						'max'     => 4000,
					),
					array(
						'id'      => 'min_image_height',
						'type'    => 'number',
						'label'   => __( 'Minimum Image Height (px)', 'wp-pin-master' ),
						'default' => $defaults['min_image_height'],
						'min'     => 0,
						'max'     => 4000,
					),
					array(
						'id'      => 'min_image_width_pixel',
						'type'    => 'number',
						'label'   => __( 'Minimum Image Width on Mobile (px)', 'wp-pin-master' ),
						'default' => $defaults['min_image_width_pixel'],
						'min'     => 0,
						'max'     => 4000,
					),
					array(
						'id'      => 'min_image_height_pixel',
						'type'    => 'number',
						'label'   => __( 'Minimum Image Height on Mobile (px)', 'wp-pin-master' ),
						'default' => $defaults['min_image_height_pixel'],
						'min'     => 0,
						'max'     => 4000,
					),
				),
			),
			array(
				'id'     => 'pro',
				'title'  => __( 'Pro', 'wp-pin-master' ),
				'upsell' => true,
				'fields' => array(),
			),
		);

		/**
		 * Filter the settings schema. Addons unlock pro-flagged fields and
		 * choices, remove the upsell tab, and append their own tabs here.
		 *
		 * @param array $tabs Settings tabs.
		 */
		return apply_filters( 'pin_master_settings_schema', $tabs );
	}

	/**
	 * Flat field list keyed by field id.
	 *
	 * @return array
	 */
	public static function fields() {
		$fields = array();

		foreach ( self::get() as $tab ) {
			foreach ( $tab['fields'] as $field ) {
				$fields[ $field['id'] ] = $field;
			}
		}

		return $fields;
	}

	/**
	 * Sanitize a raw settings array against the schema.
	 *
	 * Unknown keys are dropped. Pro-locked fields/choices are rejected
	 * unless the schema filter unlocked them. Invalid values fall back to
	 * the previously saved value (or the default).
	 *
	 * @param array $input Raw input.
	 * @return array Clean settings.
	 */
	public static function sanitize( $input ) {
		$fields = self::fields();
		$saved  = Options::get();
		$clean  = array();

		foreach ( $fields as $id => $field ) {
			$current = $saved[ $id ] ?? ( $field['default'] ?? null );

			if ( ! array_key_exists( $id, (array) $input ) ) {
				$clean[ $id ] = $current;
				continue;
			}

			if ( ! empty( $field['pro'] ) ) {
				$clean[ $id ] = $current;
				continue;
			}

			$value = $input[ $id ];

			switch ( $field['type'] ) {
				case 'select':
					$allowed = array();
					foreach ( $field['choices'] as $choice_value => $choice ) {
						if ( empty( $choice['pro'] ) ) {
							$allowed[] = (string) $choice_value;
						}
					}
					$clean[ $id ] = in_array( (string) $value, $allowed, true ) ? (string) $value : $current;
					break;

				case 'multiselect':
					$allowed = array();
					foreach ( $field['choices'] as $choice_value => $choice ) {
						if ( empty( $choice['pro'] ) ) {
							$allowed[] = (string) $choice_value;
						}
					}
					$value        = array_map( 'strval', (array) $value );
					$clean[ $id ] = array_values( array_intersect( $value, $allowed ) );
					break;

				case 'number':
					$number = is_numeric( $value ) ? (int) $value : (int) $current;
					if ( isset( $field['min'] ) ) {
						$number = max( (int) $field['min'], $number );
					}
					if ( isset( $field['max'] ) ) {
						$number = min( (int) $field['max'], $number );
					}
					$clean[ $id ] = $number;
					break;

				case 'color':
					$color        = sanitize_hex_color( (string) $value );
					$clean[ $id ] = $color ? $color : $current;
					break;

				case 'box':
					$box          = wp_parse_args(
						(array) $value,
						array(
							'top'    => 0,
							'right'  => 0,
							'bottom' => 0,
							'left'   => 0,
						)
					);
					$clean[ $id ] = array(
						'top'    => absint( $box['top'] ),
						'right'  => absint( $box['right'] ),
						'bottom' => absint( $box['bottom'] ),
						'left'   => absint( $box['left'] ),
					);
					break;

				case 'toggle':
					$clean[ $id ] = (bool) $value;
					break;

				case 'text':
				default:
					$clean[ $id ] = sanitize_text_field( (string) $value );
					break;
			}
		}

		/**
		 * Filter the sanitized settings before they are saved. Addons
		 * handling custom field types sanitize their own keys here.
		 *
		 * @param array $clean Sanitized settings.
		 * @param array $input Raw input.
		 */
		return apply_filters( 'pin_master_sanitize_settings', $clean, (array) $input );
	}
}
