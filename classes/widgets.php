<?php
namespace Pin_Master\Classes;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'Pin_Master_Widget' ) ) {
	class Pin_Master_Widget extends \WP_Widget {

		function __construct() {

			$widget_ops     = array(
				'classname'   => 'wppml_pinterest_follow_button',
				'description' => esc_html__('Pinterest Follower', 'pin-master')
			);

			parent::__construct( 'wppml_pinterest_follow_button', 'Pin Master : Pinterest Follower', $widget_ops );

		}

		function widget( $args, $instance ) {

			extract( $args );

			echo $before_widget;

			?>
			<div class="pm-pinterest-follow">
				<a data-pin-do="buttonFollow" href="<?php echo esc_url($instance['pinterest_user_url']) ?>"><?php echo esc_html( $instance['pinterest_name'] ); ?></a>


			</div>
			<script async defer src="//assets.pinterest.com/js/pinit.js"></script>
			<?php echo $after_widget;

		}

		function update( $new_instance, $old_instance ) {

			$instance            = $old_instance;
			$instance['title']   = $new_instance['title'];
			$instance['builder_select'] = $new_instance['builder_select'];
			$instance['pinterest_user_url']    = $new_instance['pinterest_user_url'];
			$instance['pinterest_name'] = $new_instance['pinterest_name'];
			return $instance;

		}

		function form( $instance ) {

      //
      // Title Field Defaults
      // -------------------------------------------------
			$instance   = wp_parse_args( $instance, array(
				'title'   => 'Pinterest Follow',
				'pinterest_user_url'    => '',
				'pinterest_name' => 'Pinterest',
				'builder_select' => '',
			));

	      //
	      // Title Field
	      // -------------------------------------------------
			$text_value = esc_attr( $instance['title'] );
			$text_field = array(
				'id'    => $this->get_field_name('title'),
				'name'  => $this->get_field_name('title'),
				'type'  => 'text',
				'title' => 'Title',
			);

			echo wppml_add_element( $text_field, $text_value );


			
	      //
	      // profile url
	      // -------------------------------------------------
			$pinterest_user_URL_value = esc_attr( $instance['pinterest_user_url'] );
			$pinterest_user_URL_field = array(
				'id'    => $this->get_field_name('pinterest_user_url'),
				'name'  => $this->get_field_name('pinterest_user_url'),
				'type'  => 'Text',
				'title' => 'Pinterest URL',
			);

			echo wppml_add_element( $pinterest_user_URL_field, $pinterest_user_URL_value );

	      //
	      // Name
	      // -------------------------------------------------
			$pinterest_name_value = esc_attr( $instance['pinterest_name'] );
			$pinterest_name_field = array(
				'id'    => $this->get_field_name('pinterest_name'),
				'name'  => $this->get_field_name('pinterest_name'),
				'type'  => 'text',
				'title' => 'Pinterest Profile Name',
			);

			echo wppml_add_element( $pinterest_name_field, $pinterest_name_value );

		}
	}
}

if ( ! function_exists( 'wppml_pinterest_follow_button_init' ) ) {
	function wppml_pinterest_follow_button_init() {
		register_widget( __NAMESPACE__ . '\\Pin_Master_Widget' );
	}
	add_action( 'widgets_init', __NAMESPACE__ . '\\wppml_pinterest_follow_button_init', 2 );
}

/*
* 	board
* -----------------------------------------------------------------------------------------*/

if( ! class_exists( 'Pin_Master_Board_Widget' ) ) {
	class Pin_Master_Board_Widget extends \WP_Widget {

		function __construct() {

			$widget_ops     = array(
				'classname'   => 'wppml_pinterest_builder',
				'description' => esc_html__('Pinterest Builder', 'pin-master')
			);

			parent::__construct( 'wppml_pinterest_builder', 'Pin Master : Pinterest Builder', $widget_ops );

		}

		function widget( $args, $instance ) {

			extract( $args );

			echo $before_widget;

			?>
			<div class="pm-pinterest-follow">
				
				<a data-pin-do="<?php echo esc_attr($instance['builder_select']) ?>" data-pin-width="large" data-pin-terse="true" data-pin-board-width="<?php echo esc_attr($instance['pinterest_borard_width']) ?>" data-pin-scale-width="<?php echo esc_attr($instance['pinterest_scale_width']) ?>" data-pin-scale-height="<?php echo esc_attr($instance['pinterest_scale_height']) ?>" href="<?php echo esc_url($instance['pinterest_user_url']) ?>"><?php echo esc_html( $instance['pinterest_name'] ); ?></a>

			</div>
			<script async defer src="//assets.pinterest.com/js/pinit.js"></script>
			<?php echo $after_widget;

		}

		function update( $new_instance, $old_instance ) {

			$instance            = $old_instance;
			$instance['title']   = $new_instance['title'];
			$instance['builder_select'] = $new_instance['builder_select'];
			$instance['pinterest_user_url']    = $new_instance['pinterest_user_url'];
			$instance['pinterest_borard_width'] = $new_instance['pinterest_borard_width'];
			$instance['pinterest_scale_width'] = $new_instance['pinterest_scale_width'];
			$instance['pinterest_scale_height'] = $new_instance['pinterest_scale_height'];
			return $instance;

		}

		function form( $instance ) {

      //
      // Title Field Defaults
      // -------------------------------------------------
			$instance   = wp_parse_args( $instance, array(
				'title'   => 'Pinterest Board',
				'pinterest_user_url'    => '',
				'builder_select' => '',
				'pinterest_borard_width' => '',
				'pinterest_scale_width' => '',
				'pinterest_scale_height' => '',
			));

	      //
	      // Title Field
	      // -------------------------------------------------
			$text_value = esc_attr( $instance['title'] );
			$text_field = array(
				'id'    => $this->get_field_name('title'),
				'name'  => $this->get_field_name('title'),
				'type'  => 'text',
				'title' => 'Title',
			);

			echo wppml_add_element( $text_field, $text_value );


			 //
	      // select Field
	      // -------------------------------------------------
			$builder_select_value = esc_attr( $instance['builder_select'] );
			$builder_select_field = array(
				'id'    => $this->get_field_name('builder_select'),
				'name'  => $this->get_field_name('builder_select'),
				'type'  => 'select',
				'options' => array(
					'embedPin' => 'Pin',
					'embedBoard' => 'Board',
					'embedUser' => 'Profile',
				),
				'title' => 'Widget Builder',
			);

			echo wppml_add_element( $builder_select_field, $builder_select_value );

	      //
	      // profile url
	      // -------------------------------------------------
			$pinterest_user_URL_value = esc_attr( $instance['pinterest_user_url'] );
			$pinterest_user_URL_field = array(
				'id'    => $this->get_field_name('pinterest_user_url'),
				'name'  => $this->get_field_name('pinterest_user_url'),
				'type'  => 'Text',
				'title' => 'Pinterest URL',
			);

			echo wppml_add_element( $pinterest_user_URL_field, $pinterest_user_URL_value );

	      
			 //
	      // Board Width
	      // -------------------------------------------------
			$pinterest_borard_width_value = esc_attr( $instance['pinterest_borard_width'] );
			$pinterest_borard_width_field = array(
				'id'    => $this->get_field_name('pinterest_borard_width'),
				'name'  => $this->get_field_name('pinterest_borard_width'),
				'type'  => 'number',
				'default' => '400',
				'title' => 'Pinterest Board Width',
			);

			echo wppml_add_element( $pinterest_borard_width_field, $pinterest_borard_width_value );


			 //
	      // scale Width
	      // -------------------------------------------------
			$pinterest_scale_width_value = esc_attr( $instance['pinterest_scale_width'] );
			$pinterest_scale_width_field = array(
				'id'    => $this->get_field_name('pinterest_scale_width'),
				'name'  => $this->get_field_name('pinterest_scale_width'),
				'type'  => 'number',
				'default' => '400',
				'title' => 'Pinterest Scale Width',
			);

			echo wppml_add_element( $pinterest_scale_width_field, $pinterest_scale_width_value );

			 //
	      // Board Width
	      // -------------------------------------------------
			$pinterest_scale_height_value = esc_attr( $instance['pinterest_scale_height'] );
			$pinterest_scale_height_field = array(
				'id'    => $this->get_field_name('pinterest_scale_height'),
				'name'  => $this->get_field_name('pinterest_scale_height'),
				'type'  => 'number',
				'default' => '400',
				'title' => 'Pinterest Scale Height',
			);

			echo wppml_add_element( $pinterest_scale_height_field, $pinterest_scale_height_value );

		}
	}
}

if ( ! function_exists( 'wppml_pinterest_board_init' ) ) {
	function wppml_pinterest_board_init() {
		register_widget( __NAMESPACE__ . '\\Pin_Master_Board_Widget' );
	}
	add_action( 'widgets_init', __NAMESPACE__ . '\\wppml_pinterest_board_init', 2 );
}
