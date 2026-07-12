<?php
namespace Pin_Master\Classes;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds a No Pin / Yes Pin control to Elementor image widgets.
 */
class Elementor {

	public function __construct() {
		add_action( 'elementor/element/before_section_end', array( $this, 'extra_field' ), 10, 3 );
	}

	/**
	 * Register the control on Image and Image Box widgets.
	 *
	 * @param \Elementor\Controls_Stack $section    Elementor element.
	 * @param string                    $section_id Current section.
	 * @param array                     $args       Section args.
	 */
	public function extra_field( $section, $section_id, $args ) {
		if ( 'section_image' !== $section_id || ! in_array( $section->get_name(), array( 'image', 'image-box' ), true ) ) {
			return;
		}

		$section->add_control(
			'elementor_wp_pin_master',
			array(
				'label'        => __( 'WP Pin Master', 'wp-pin-master' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'nopin',
				'options'      => array(
					'nopin'  => __( 'No Pin', 'wp-pin-master' ),
					'pin-it' => __( 'Yes Pin', 'wp-pin-master' ),
				),
				'prefix_class' => '',
				'label_block'  => true,
			)
		);
	}
}
