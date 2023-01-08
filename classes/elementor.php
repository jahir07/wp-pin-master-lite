<?php
namespace WP_Pin_Master_Lite\Classes;

use Elementor\Controls_Manager;


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * summary
 */
class WP_WP_Pin_Master_Lite_Elements {

	public function __construct() {
		add_action( 'elementor/element/before_section_end', [ $this, 'extra_field' ], 10, 3 );
	}

	public function extra_field( $section, $section_id, $args ) {
		if ( ( $section->get_name() == 'image-box' && $section_id == 'section_image' ) || ( $section->get_name() == 'image' && $section_id == 'section_image' ) ) {
			// we are at the end of the "section_image" area of the "image-box"
			$section->add_control(
				'elementor_wp_pin_master',
				[
					'label'        => 'WP Pin Master',
					'type'         => Controls_Manager::SELECT,
					'default'      => 'nopin',
					'options'      => array(
						'nopin' => 'No Pin',
						'pin-it' => 'Yes Pin',
					),
					'prefix_class' => '',
					'label_block'  => true,
				]
			);
		}
	}
}

new WP_WP_Pin_Master_Lite_Elements();
