<?php
namespace WP_Pin_Master_Lite\Classes;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Load class which responsible for frontend only
 *
 * @return void 
 */

Class Frontend {

	private $basic_options;

	function __construct() {

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
		add_action( 'wp_head', array( $this, 'print_header_styles' ) );

		$this->add_conditional_filters();
	}

	/**
	 * enqueue fontend scripts & styles
	 *
	 * @return void 
	 */
	public function enqueue_script() {
		// styles
		wp_enqueue_style('pin-master-style');

		// scripts
		$get_options = get_option('wppml_options');
		if( array_key_exists('where_show', $get_options ) ) {
			$where_show = $get_options['where_show'];
			if( isset( $where_show ) && in_array('front', $where_show) && is_front_page() && is_page() ){
				wp_enqueue_script('pin-master-vendor');
			} elseif( isset( $where_show ) && in_array('home', $where_show) && is_home() ){
				wp_enqueue_script('pin-master-vendor');
			} elseif( isset( $where_show ) && in_array('single', $where_show) && is_single() ){
				wp_enqueue_script('pin-master-vendor');
			} elseif( isset( $where_show ) && in_array('page', $where_show) && is_page() && !is_front_page() ){
				wp_enqueue_script('pin-master-vendor');
			} elseif( isset( $where_show ) && in_array('archive', $where_show) && is_archive() ){
				wp_enqueue_script('pin-master-vendor');
			} elseif( isset( $where_show ) && in_array('search', $where_show) && is_search() ){
				wp_enqueue_script('pin-master-vendor');
			} elseif( isset( $where_show ) && in_array('category', $where_show) && is_category() ){
				wp_enqueue_script('pin-master-vendor');
			} 
		}
		if( array_key_exists('where_show_cpt', $get_options )) { 
			$where_show_cpt = $get_options['where_show_cpt'];
			$args = array(
				'public'   => true,
				'_builtin' => false
			);
			$output = 'names'; 
			$operator = 'and'; 
			$post_types = get_post_types( $args, $output, $operator ); 
			foreach ( $post_types  as $post_type ) {
				if( isset( $where_show_cpt ) && in_array($post_type, $where_show_cpt) && ( "$post_type" == get_post_type() ) ){
					wp_enqueue_script('pin-master-vendor');
				}
			}
		}

		$parameters_array = array(
			'hover' => array_merge(
				array( 'siteTitle' => esc_attr( get_bloginfo( 'name', 'display' ) ) ),
				$this->default_options(),
				$this->get_plugins_settings()
			),
		);

		// var_dump($parameters_array);
		wp_localize_script( 'pin-master-vendor', 'wppml_options', $parameters_array );
	}

	/**
	 * Plugin settings data
	 *
	 * @return void 
	 */
	public function get_plugins_settings(){
		$options = get_option('wppml_options');
		return $options;
	}

	/**
	 * default data
	 *
	 * @param Object
	 * @since 1.0
	 * @return array 
	 */
	public function default_options(){
		$options= array( 
			'image_selector' => '.wppml_container img',
			'disabled_classes' => 'wp-smiley;nopin',
			'min_image_width' => 200,
			'min_image_height' => 150,
			'min_image_width_pixel' => 200,
			'min_image_height_pixel' => 120,
			'show_button' => 'always',
			'button_margin_bottom' => 20,
			'button_margin_top' => 20,
			'button_margin_left' => 20,
			'button_margin_right' => 20,
			'button_position' => 'top-left',
			'description_option' => array(
				'0' => 'img_alt',
				'1' => 'post_title',
				'2' => 'img_title',
				'3' => 'post_excerpt',
				'4' => 'site_title',
				'5' => 'img_description',
				'6' => 'img_caption',
			),
			'pin_image' => 'default',
			'pin_image_button' => 'square',
			'pin_image_icon' => 'circle',
			'pin_image_size' => 'normal',
			'custom_image_url' => '',
			'scale_pin_image' => '',
			'pin_linked_url' => 1,
			'custom_image_height' => 45,
			'custom_image_width' => 45,
		);
		return $options;
	}

	/**
	 * custom styles at head
	 *
	 * @since 1.0
	 */
	public function print_header_styles() {
		$options_val = get_option( 'wppml_options' );
		ob_start(); 
		if(isset($options_val)){
			$pin_space = $options_val['pin_space'];
			$top = ($pin_space['top']) ? $pin_space['top'] : '0';
			$right = ($pin_space['right']) ? $pin_space['right'] : '0';
			$bottom = ($pin_space['bottom']) ? $pin_space['bottom'] : '0';
			$left = ($pin_space['left']) ? $pin_space['left'] : '0';

			$pin_icon_size = $options_val['pin_icon_size'];
			$width = ($pin_icon_size['top']) ? $pin_icon_size['top'] : '0';
			$height = ($pin_icon_size['left']) ? $pin_icon_size['left'] : '0';
			?>
			<style>
				.pm-button.custom span {
					<?php echo $custom_button_span_css; ?>
				}
				<?php if ( $options_val['pin_image'] === 'default' ) { ?>
					.pm-button.icon span {
						text-align: center;
						margin-top: auto;
						margin-bottom: auto;
						align-items: center;
						justify-content: center;
						display: flex;
					}

					<?php if( !empty($options_val['pin_font_size']) ) { ?>
						a.pm-button.pm-button span:before {
							font-size: <?php echo esc_attr($options_val['pin_font_size']) ?>px;
						}
					<?php } ?>

					<?php if( !empty($options_val['pin_font_color']) ) { ?>
						a.pm-button.pm-button span:before{
							color: <?php echo esc_attr($options_val['pin_font_color']) ?>;
						}
					<?php } ?>

					<?php if( !empty($options_val['pin_bg_color']) ) { ?>
						a.pm-button.pm-button {
							background: <?php echo esc_attr($options_val['pin_bg_color']) ?>;
						}
					<?php } ?>

					<?php if( !empty($options_val['pin_font_color_hover']) ) { ?>
						a.pm-button.pm-button:hover span:before {
							color: <?php echo esc_attr($options_val['pin_font_color_hover']) ?>;
						}
					<?php } ?>

					<?php if( !empty($options_val['pin_bg_color_hover']) ) { ?>
						a.pm-button.pm-button:hover {
							background: <?php echo esc_attr($options_val['pin_bg_color_hover']) ?>;
						}
					<?php } ?>

					<?php if( !empty($pin_space) ) { ?>
						a.pm-button.pm-button.pm-button {
							margin: <?php echo esc_attr($top) ?><?php echo $pin_space['unit'] ?> <?php echo esc_attr($right) ?><?php echo $pin_space['unit'] ?> <?php echo esc_attr($bottom) ?><?php echo $pin_space['unit'] ?> <?php echo esc_attr($left) ?><?php echo $pin_space['unit'] ?>;
						}
					<?php } ?>

					<?php if( !empty($pin_icon_size) ) { ?>
						a.pm-button.pm-button.pm-button {
							width: <?php echo esc_attr($width) ?><?php echo $pin_icon_size['unit'] ?>;
							height: <?php echo esc_attr($height) ?><?php echo $pin_icon_size['unit'] ?>;
							align-items: center;
							display: flex !important;
							justify-content: center;
						}
					<?php } ?>

				<?php } ?>
			</style>
			<?php
		}
		echo ob_get_clean();
	}

	/**
	 * content filter
	 *
	 * @param Object
	 * @since 1.0
	 * @return mix 
	 */
	private function add_conditional_filters() {
		add_filter( 'the_content', [ $this, 'the_content'], 10 );
	}

	// This Function's concept was from jQuery Pin It Button For Images plugin
	function the_content( $content ) {
		
		global $post;

		$default = $this->default_options();
		if( isset($default['description_option']) ) { 
			$default_desc = $default['description_option'];
		};
		$basic_options = get_option('wppml_options');

		if( isset($basic_options['description_option']) ) { 
			$basic_desc = $basic_options['description_option'];
		};

		$img_desc = isset($basic_desc) ? $basic_desc : $default_desc;

		if(!empty($basic_options) )
			$get_description = in_array( 'img_description', $img_desc );
		$get_caption     = in_array( 'img_caption', $img_desc );

		$imgPattern  = '/<img[^>]*>/i';
		$attrPattern = '/ ([-\w]+)[ ]*=[ ]*([\"\'])(.*?)\2/i';

		preg_match_all( $imgPattern, $content, $images, PREG_SET_ORDER );

		foreach ( $images as $img ) {

			preg_match_all( $attrPattern, $img[0], $attributes, PREG_SET_ORDER );

			$new_img = '<img';
			$src     = '';
			$id      = '';

			foreach ( $attributes as $att ) {
				$full  = $att[0];
				$name  = $att[1];
				$value = $att[3];

				$new_img .= $full;

				if ( 'class' == $name ) {
					$id = $this->get_post_id_from_image_classes( $value );
				}

				if ( 'src' == $name ) {
					$src = $value;
				}
			}

			$att = $get_description || $get_caption ? $this->get_attachment( $id, $src ): null;
			if ( $att != null ) {
				$new_img .= $get_description ? sprintf( ' data-pm-description="%s"', esc_attr( $att->post_content ) ): '';
				$new_img .= $get_caption ? sprintf( ' data-pm-caption="%s"', esc_attr( $att->post_excerpt ) ): '';
			}

			$new_img .= sprintf( ' data-pm-post-excerpt="%s"', esc_attr( wp_kses( $post->post_excerpt, array() ) ) );
			$new_img .= sprintf( ' data-pm-post-url="%s"', esc_attr( get_permalink() ) );
			$new_img .= sprintf( ' data-pm-post-title="%s"', esc_attr( get_the_title() ) );
			$new_img .= sprintf( ' data-pm-src="%s"', esc_attr( $src ) );
			$new_img .= ' >';
			$content = str_replace( $img[0], $new_img, $content );
		}
		$jscript = '';
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			ob_start();
			?>
			<script type="text/javascript">
				(function () {
					if (!jQuery) return;
					jQuery(document).ready(function () {
						var $inputs = jQuery('.pin-master');
						var $closest = $inputs.closest('div, article');
						$closest = $closest.length ? $closest : $inputs.parent();
						$closest.addClass('wppml_container');
					});
				})();
			</script>
			<?php
			$jscript = ob_get_clean();
		}

		return '<input class="pin-master" type="hidden">' . $content . $jscript;
	}

	//function gets the id of the image by searching for class with wp-image- prefix, otherwise returns empty string
	function get_post_id_from_image_classes( $class_attribute ) {
		$classes = preg_split( '/\s+/', $class_attribute, - 1, PREG_SPLIT_NO_EMPTY );
		$prefix  = 'wp-image-';

		foreach ( $classes as $class ) {
			if ( $prefix === substr( $class, 0, strlen( $prefix ) ) ) {
				return str_replace( $prefix, '', $class );
			}
		}

		return '';
	}

	/**
	 * @param $id
	 * @param $src
	 *
	 * @return array|null|WP_Post
	 */
	function get_attachment( $id, $src ) {
		$result = is_numeric( $id ) ? get_post( $id ) : null;
		if ( $result )
			return $result;

		$id = $this->get_attachment_id_by_url( $src );
		return  $id !== 0 ? get_post( $id ) : null;
	}

	/**
	 * Function copied from https://wpscholar.com/blog/get-attachment-id-from-wp-image-url/
	 * Return an ID of an attachment by searching the database with the file URL.
	 *
	 * @return {int} $attachment
	 */
	function get_attachment_id_by_url( $url ) {
		$attachment_id = 0;
		$dir           = wp_upload_dir();
		if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) {
			$file       = basename( $url );
			$query_args = array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'fields'      => 'ids',
				'meta_query'  => array(
					array(
						'value'   => $file,
						'compare' => 'LIKE',
						'key'     => '_wp_attachment_metadata',
					),
				)
			);
			$query      = new \WP_Query( $query_args );
			if ( $query->have_posts() ) {
				foreach ( $query->posts as $post_id ) {
					$meta                = wp_get_attachment_metadata( $post_id );
					$original_file       = basename( $meta['file'] );
					$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
					if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
						$attachment_id = $post_id;
						break;
					}
				}
			}
		}

		return $attachment_id;
	}

}