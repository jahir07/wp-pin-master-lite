<?php
/**
 * Pinterest Pin / Board / Profile embed widget.
 *
 * @package Pin_Master
 */

namespace Pin_Master\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Embeds a Pinterest Pin, Board, or Profile.
 */
class Board_Widget extends \WP_Widget {

	/**
	 * Register the widget.
	 */
	public function __construct() {
		parent::__construct(
			'wppml_pinterest_builder',
			__( 'Pin Master: Pinterest Builder', 'wp-pin-master-lite' ),
			array(
				'classname'   => 'wppml_pinterest_builder',
				'description' => esc_html__( 'Embed a Pinterest Pin, Board, or Profile.', 'wp-pin-master-lite' ),
			)
		);
	}

	/**
	 * Frontend output.
	 *
	 * @param array $args     Widget area arguments.
	 * @param array $instance Saved values.
	 */
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

		wp_enqueue_script( 'pin-master-pinit' );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-provided widget markup.

		if ( '' !== $instance['title'] ) {
			echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-provided widget markup.
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
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-provided widget markup.
	}

	/**
	 * Sanitize submitted values.
	 *
	 * @param array $new_instance New values.
	 * @param array $old_instance Previous values.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']              = sanitize_text_field( $new_instance['title'] ?? '' );
		$instance['pinterest_user_url'] = esc_url_raw( $new_instance['pinterest_user_url'] ?? '' );
		$instance['pinterest_name']     = sanitize_text_field( $new_instance['pinterest_name'] ?? '' );

		$builder                    = $new_instance['builder_select'] ?? 'embedBoard';
		$instance['builder_select'] = in_array( $builder, array( 'embedPin', 'embedBoard', 'embedUser' ), true ) ? $builder : 'embedBoard';

		foreach ( array( 'pinterest_borard_width', 'pinterest_scale_width', 'pinterest_scale_height' ) as $key ) {
			$instance[ $key ] = absint( $new_instance[ $key ] ?? 0 );
		}

		return $instance;
	}

	/**
	 * Admin form.
	 *
	 * @param array $instance Saved values.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'                  => __( 'Pinterest Board', 'wp-pin-master-lite' ),
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'wp-pin-master-lite' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'builder_select' ) ); ?>"><?php esc_html_e( 'Widget Builder', 'wp-pin-master-lite' ); ?>:</label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'builder_select' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'builder_select' ) ); ?>">
				<?php
				$choices = array(
					'embedPin'   => __( 'Pin', 'wp-pin-master-lite' ),
					'embedBoard' => __( 'Board', 'wp-pin-master-lite' ),
					'embedUser'  => __( 'Profile', 'wp-pin-master-lite' ),
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'pinterest_user_url' ) ); ?>"><?php esc_html_e( 'Pinterest URL', 'wp-pin-master-lite' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest_user_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pinterest_user_url' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['pinterest_user_url'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'pinterest_name' ) ); ?>"><?php esc_html_e( 'Link Text', 'wp-pin-master-lite' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pinterest_name' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['pinterest_name'] ); ?>">
		</p>
		<?php
		$numbers = array(
			'pinterest_borard_width' => __( 'Pinterest Board Width', 'wp-pin-master-lite' ),
			'pinterest_scale_width'  => __( 'Pinterest Scale Width', 'wp-pin-master-lite' ),
			'pinterest_scale_height' => __( 'Pinterest Scale Height', 'wp-pin-master-lite' ),
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
