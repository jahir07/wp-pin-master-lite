<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: spacing
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'WPPinMasterLite_Option_spacing' ) ) {
	class WPPinMasterLite_Option_spacing extends WPPinMasterLite_Options {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function output() {
			$args = wp_parse_args(
				$this->field, array(
					'top_icon'           => '<i class="fa fa-long-arrow-up"></i>',
					'right_icon'         => '<i class="fa fa-long-arrow-right"></i>',
					'bottom_icon'        => '<i class="fa fa-long-arrow-down"></i>',
					'left_icon'          => '<i class="fa fa-long-arrow-left"></i>',
					'all_text'           => '<i class="fa fa-arrows"></i>',
					'top_placeholder'    => esc_html__( 'top', 'pin-master' ),
					'right_placeholder'  => esc_html__( 'right', 'pin-master' ),
					'bottom_placeholder' => esc_html__( 'bottom', 'pin-master' ),
					'left_placeholder'   => esc_html__( 'left', 'pin-master' ),
					'all_placeholder'    => esc_html__( 'all', 'pin-master' ),
					'top'                => true,
					'left'               => true,
					'bottom'             => true,
					'right'              => true,
					'unit'               => true,
					'all'                => false,
					'units'              => array( 'px', '%', 'em' ),
				)
			);

			$default_values = array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
				'all'    => '',
				'unit'   => 'px',
			);

			$value = wp_parse_args( $this->value, $default_values );

			echo $this->element_before();

			if ( ! empty( $args['all'] ) ) {
				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . $args['all_placeholder'] . '"' : '';

				echo '<div class="pm-input">';
				echo ( ! empty( $args['all_text'] ) ) ? '<span class="pm-label pm-label-icon">' . $args['all_text'] . '</span>' : '';
				echo '<input type="text" name="' . $this->element_name( '[all]' ) . '" value="' . $value['all'] . '"' . $placeholder . ' class="pm-number" />';
				echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="pm-label pm-label-unit">' . $args['units'][0] . '</span>' : '';
				echo '</div>';
			} else {
				$properties = array();

				foreach ( array( 'top', 'right', 'bottom', 'left' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( $properties === array( 'right', 'left' ) ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {
					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . $args[ $property . '_placeholder' ] . '"' : '';

					echo '<div class="pm-input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="pm-label pm-label-icon">' . $args[ $property . '_icon' ] . '</span>' : '';
					echo '<input type="text" name="' . $this->element_name( '[' . $property . ']' ) . '" value="' . $value[ $property ] . '"' . $placeholder . ' class="pm-number" />';
					echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="pm-label pm-label-unit">' . $args['units'][0] . '</span>' : '';
					echo '</div>';
				}
			}

			if ( ! empty( $args['unit'] ) && count( $args['units'] ) > 1 ) {
				echo '<select name="' . $this->element_name( '[unit]' ) . '">';
				foreach ( $args['units'] as $unit ) {
					$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
					echo '<option value="' . $unit . '"' . $selected . '>' . $unit . '</option>';
				}
				echo '</select>';
			}

			echo $this->element_after();
		}
	}
}
