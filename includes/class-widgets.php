<?php
namespace Pin_Master\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pinterest Follow button widget.
 */
class Follow_Widget extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			'wppml_pinterest_follow_button',
			__( 'Pin Master: Pinterest Follower', 'wp-pin-master' ),
			array(
				'classname'   => 'wppml_pinterest_follow_button',
				'description' => esc_html__( 'Pinterest Follow button.', 'wp-pin-master' ),
			)
		);
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'              => '',
				'pinterest_user_url' => '',
				'pinterest_name'     => 'Pinterest',
			)
		);

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput -- Theme-provided widget markup.

		if ( '' !== $instance['title'] ) {
			echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput
		}
		?>
		<div class="pm-pinterest-follow">
			<a data-pin-do="buttonFollow" href="<?php echo esc_url( $instance['pinterest_user_url'] ); ?>"><?php echo esc_html( $instance['pinterest_name'] ); ?></a>
		</div>
		<script async defer src="https://assets.pinterest.com/js/pinit.js"></script>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput
	}

	public function update( $new_instance, $old_instance ) {
		$instance                       = $old_instance;
		$instance['title']              = sanitize_text_field( $new_instance['title'] ?? '' );
		$instance['pinterest_user_url'] = esc_url_raw( $new_instance['pinterest_user_url'] ?? '' );
		$instance['pinterest_name']     = sanitize_text_field( $new_instance['pinterest_name'] ?? '' );

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'              => __( 'Pinterest Follow', 'wp-pin-master' ),
				'pinterest_user_url' => '',
				'pinterest_name'     => 'Pinterest',
			)
		);

		$fields = array(
			'title'              => __( 'Title', 'wp-pin-master' ),
			'pinterest_user_url' => __( 'Pinterest URL', 'wp-pin-master' ),
			'pinterest_name'     => __( 'Pinterest Profile Name', 'wp-pin-master' ),
		);

		foreach ( $fields as $key => $label ) {
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $label ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="text" value="<?php echo esc_attr( $instance[ $key ] ); ?>">
			</p>
			<?php
		}
	}
}

/**
 * Pinterest Pin / Board / Profile embed widget.
 */
class Board_Widget extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			'wppml_pinterest_builder',
			__( 'Pin Master: Pinterest Builder', 'wp-pin-master' ),
			array(
				'classname'   => 'wppml_pinterest_builder',
				'description' => esc_html__( 'Embed a Pinterest Pin, Board, or Profile.', 'wp-pin-master' ),
			)
		);
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'                  => '',
				'builder_select'         => 'embedBoard',
				'pinterest_user_url'     => '',
				'pinterest_name'         => '',
				'pinterest_borard_width' => 400,
				'pinterest_scale_width'  => 80,
				'pinterest_scale_height' => 320,
			)
		);

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput -- Theme-provided widget markup.

		if ( '' !== $instance['title'] ) {
			echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput
		}
		?>
		<div class="pm-pinterest-follow">
			<a data-pin-do="<?php echo esc_attr( $instance['builder_select'] ); ?>"
				data-pin-width="large"
				data-pin-terse="true"
				data-pin-board-width="<?php echo esc_attr( $instance['pinterest_borard_width'] ); ?>"
				data-pin-scale-width="<?php echo esc_attr( $instance['pinterest_scale_width'] ); ?>"
				data-pin-scale-height="<?php echo esc_attr( $instance['pinterest_scale_height'] ); ?>"
				href="<?php echo esc_url( $instance['pinterest_user_url'] ); ?>"><?php echo esc_html( $instance['pinterest_name'] ); ?></a>
		</div>
		<script async defer src="https://assets.pinterest.com/js/pinit.js"></script>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']              = sanitize_text_field( $new_instance['title'] ?? '' );
		$instance['pinterest_user_url'] = esc_url_raw( $new_instance['pinterest_user_url'] ?? '' );
		$instance['pinterest_name']     = sanitize_text_field( $new_instance['pinterest_name'] ?? '' );

		$builder              = $new_instance['builder_select'] ?? 'embedBoard';
		$instance['builder_select'] = in_array( $builder, array( 'embedPin', 'embedBoard', 'embedUser' ), true ) ? $builder : 'embedBoard';

		foreach ( array( 'pinterest_borard_width', 'pinterest_scale_width', 'pinterest_scale_height' ) as $key ) {
			$instance[ $key ] = absint( $new_instance[ $key ] ?? 0 );
		}

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'                  => __( 'Pinterest Board', 'wp-pin-master' ),
				'builder_select'         => 'embedBoard',
				'pinterest_user_url'     => '',
				'pinterest_name'         => '',
				'pinterest_borard_width' => 400,
				'pinterest_scale_width'  => 80,
				'pinterest_scale_height' => 320,
			)
		);
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'wp-pin-master' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'builder_select' ) ); ?>"><?php esc_html_e( 'Widget Builder', 'wp-pin-master' ); ?>:</label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'builder_select' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'builder_select' ) ); ?>">
				<?php
				$choices = array(
					'embedPin'   => __( 'Pin', 'wp-pin-master' ),
					'embedBoard' => __( 'Board', 'wp-pin-master' ),
					'embedUser'  => __( 'Profile', 'wp-pin-master' ),
				);
				foreach ( $choices as $value => $label ) {
					printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $value ),
						selected( $instance['builder_select'], $value, false ),
						esc_html( $label )
					);
				}
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'pinterest_user_url' ) ); ?>"><?php esc_html_e( 'Pinterest URL', 'wp-pin-master' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest_user_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pinterest_user_url' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['pinterest_user_url'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'pinterest_name' ) ); ?>"><?php esc_html_e( 'Link Text', 'wp-pin-master' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pinterest_name' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['pinterest_name'] ); ?>">
		</p>
		<?php
		$numbers = array(
			'pinterest_borard_width' => __( 'Pinterest Board Width', 'wp-pin-master' ),
			'pinterest_scale_width'  => __( 'Pinterest Scale Width', 'wp-pin-master' ),
			'pinterest_scale_height' => __( 'Pinterest Scale Height', 'wp-pin-master' ),
		);
		foreach ( $numbers as $key => $label ) {
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $label ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="number" value="<?php echo esc_attr( $instance[ $key ] ); ?>">
			</p>
			<?php
		}
	}
}

add_action(
	'widgets_init',
	function () {
		register_widget( __NAMESPACE__ . '\\Follow_Widget' );
		register_widget( __NAMESPACE__ . '\\Board_Widget' );
	}
);
