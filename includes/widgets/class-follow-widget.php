<?php
/**
 * Pinterest Follow button widget.
 *
 * @package Pin_Master
 */

namespace Pin_Master\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders a Pinterest Follow button.
 */
class Follow_Widget extends \WP_Widget {

	/**
	 * Register the widget.
	 */
	public function __construct() {
		parent::__construct(
			'wppml_pinterest_follow_button',
			__( 'Pin Master: Pinterest Follower', 'wp-pin-master-lite' ),
			array(
				'classname'   => 'wppml_pinterest_follow_button',
				'description' => esc_html__( 'Pinterest Follow button.', 'wp-pin-master-lite' ),
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
				'title'              => '',
				'pinterest_user_url' => '',
				'pinterest_name'     => 'Pinterest',
			)
		);

		wp_enqueue_script( 'pin-master-pinit' );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-provided widget markup.

		if ( '' !== $instance['title'] ) {
			echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-provided widget markup.
		}
		?>
		<div class="pm-pinterest-follow">
			<a data-pin-do="buttonFollow" href="<?php echo esc_url( $instance['pinterest_user_url'] ); ?>"><?php echo esc_html( $instance['pinterest_name'] ); ?></a>
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
		$instance                       = $old_instance;
		$instance['title']              = sanitize_text_field( $new_instance['title'] ?? '' );
		$instance['pinterest_user_url'] = esc_url_raw( $new_instance['pinterest_user_url'] ?? '' );
		$instance['pinterest_name']     = sanitize_text_field( $new_instance['pinterest_name'] ?? '' );

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
				'title'              => __( 'Pinterest Follow', 'wp-pin-master-lite' ),
				'pinterest_user_url' => '',
				'pinterest_name'     => 'Pinterest',
			)
		);

		$fields = array(
			'title'              => __( 'Title', 'wp-pin-master-lite' ),
			'pinterest_user_url' => __( 'Pinterest URL', 'wp-pin-master-lite' ),
			'pinterest_name'     => __( 'Pinterest Profile Name', 'wp-pin-master-lite' ),
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
