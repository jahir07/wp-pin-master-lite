<?php
namespace Pin_Master\Classes;

use Pin_Master\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend behavior: enqueues the pin script where configured, injects
 * per-image data attributes into post content, and prints option-driven
 * inline styles.
 */
class Frontend {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_head', array( $this, 'print_header_styles' ) );
		add_filter( 'the_content', array( $this, 'the_content' ), 10 );
	}

	/**
	 * Whether the pin button should load in the current context.
	 *
	 * @param array $options Merged options.
	 * @return bool
	 */
	private function should_enqueue( $options ) {
		$where  = (array) $options['where_show'];
		$should = false;

		if ( in_array( 'front', $where, true ) && is_front_page() && is_page() ) {
			$should = true;
		} elseif ( in_array( 'home', $where, true ) && is_home() ) {
			$should = true;
		} elseif ( in_array( 'single', $where, true ) && is_single() ) {
			$should = true;
		} elseif ( in_array( 'page', $where, true ) && is_page() && ! is_front_page() ) {
			$should = true;
		} elseif ( in_array( 'archive', $where, true ) && is_archive() ) {
			$should = true;
		} elseif ( in_array( 'search', $where, true ) && is_search() ) {
			$should = true;
		} elseif ( in_array( 'category', $where, true ) && is_category() ) {
			$should = true;
		}

		if ( ! $should && ! empty( $options['where_show_cpt'] ) ) {
			$cpts = get_post_types(
				array(
					'public'   => true,
					'_builtin' => false,
				),
				'names'
			);

			$current = get_post_type();
			if ( $current && isset( $cpts[ $current ] ) && in_array( $current, (array) $options['where_show_cpt'], true ) ) {
				$should = true;
			}
		}

		/**
		 * Final say on whether the pin button script loads on this request.
		 *
		 * @param bool  $should  Result of the where_show / CPT logic.
		 * @param array $options Merged options.
		 */
		return apply_filters( 'pin_master_should_enqueue', $should, $options );
	}

	/**
	 * Enqueue the frontend bundle with its localized settings.
	 */
	public function enqueue_assets() {
		$options = Options::get();

		if ( ! $this->should_enqueue( $options ) ) {
			return;
		}

		wp_enqueue_style( 'pin-master-style' );
		wp_enqueue_script( 'pin-master-frontend' );

		wp_localize_script( 'pin-master-frontend', 'pinMasterOptions', $this->frontend_settings( $options ) );
	}

	/**
	 * The settings payload consumed by the frontend script.
	 *
	 * @param array $options Merged options.
	 * @return array
	 */
	public function frontend_settings( $options ) {
		$space = wp_parse_args(
			(array) $options['pin_space'],
			array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			)
		);

		$params = array(
			'siteTitle'              => get_bloginfo( 'name', 'display' ),
			'image_selector'         => Options::image_selector( $options['image_selector'] ),
			'disabled_classes'       => 'wp-smiley;nopin',
			'enabled_classes'        => '',
			'min_image_width'        => (int) $options['min_image_width'],
			'min_image_height'       => (int) $options['min_image_height'],
			'min_image_width_pixel'  => (int) $options['min_image_width_pixel'],
			'min_image_height_pixel' => (int) $options['min_image_height_pixel'],
			'show_button'            => $options['show_button'],
			'button_position'        => $options['button_position'],
			'button_margin_top'      => (int) $space['top'],
			'button_margin_right'    => (int) $space['right'],
			'button_margin_bottom'   => (int) $space['bottom'],
			'button_margin_left'     => (int) $space['left'],
			'pin_image'              => $options['pin_image'],
			'pin_image_button'       => $options['pin_image_button'],
			'pin_image_icon'         => 'circle',
			'custom_image_width'     => (int) $options['pin_button_width'],
			'custom_image_height'    => (int) $options['pin_button_height'],
			'scale_pin_image'        => false,
			'support_srcset'         => true,
			'pin_text_data_collect'  => array_values( (array) $options['pin_text_data_collect'] ),
		);

		/**
		 * Filter the settings payload passed to the frontend script.
		 *
		 * @param array $params  Localized payload.
		 * @param array $options Merged options.
		 */
		return apply_filters( 'pin_master_frontend_settings', $params, $options );
	}

	/**
	 * Print option-driven styles in wp_head.
	 */
	public function print_header_styles() {
		$options = Options::get();

		if ( ! $this->should_enqueue( $options ) ) {
			return;
		}

		$css = $this->build_inline_css( $options );

		/**
		 * Filter the inline CSS printed in wp_head. Addons append rules
		 * (e.g. the Pro custom button image) here.
		 *
		 * @param string $css     Generated CSS.
		 * @param array  $options Merged options.
		 */
		$css = apply_filters( 'pin_master_inline_css', $css, $options );

		if ( '' === trim( $css ) ) {
			return;
		}

		printf( "<style id=\"pin-master-inline-css\">%s</style>\n", wp_strip_all_tags( $css ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- CSS built from sanitized scalars, tags stripped.
	}

	/**
	 * Build the option-driven CSS rules.
	 *
	 * @param array $options Merged options.
	 * @return string
	 */
	private function build_inline_css( $options ) {
		$css = '';

		if ( ! in_array( $options['pin_image'], array( 'default', 'icon' ), true ) ) {
			return $css;
		}

		$css .= '.pm-button.icon span,.pm-button.default span{text-align:center;margin-top:auto;margin-bottom:auto;align-items:center;justify-content:center;display:flex;}';

		$font_size = absint( $options['pin_font_size'] );
		if ( $font_size ) {
			$css .= sprintf( 'a.pm-button.pm-button span:before{font-size:%dpx;}', $font_size );
		}

		$color = sanitize_hex_color( $options['pin_font_color'] );
		$bg    = sanitize_hex_color( $options['pin_bg_color'] );
		if ( $color || $bg ) {
			$css .= sprintf(
				'a.pm-button.pm-button{%s%s}',
				$color ? "color:{$color};" : '',
				$bg ? "background:{$bg};" : ''
			);
		}

		$color_hover = sanitize_hex_color( $options['pin_font_color_hover'] );
		$bg_hover    = sanitize_hex_color( $options['pin_bg_color_hover'] );
		if ( $color_hover || $bg_hover ) {
			$css .= sprintf(
				'a.pm-button.pm-button:hover{%s%s}',
				$color_hover ? "color:{$color_hover};" : '',
				$bg_hover ? "background:{$bg_hover};" : ''
			);
		}

		$space = wp_parse_args(
			(array) $options['pin_space'],
			array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			)
		);

		$css .= sprintf(
			'a.pm-button.pm-button.pm-button{margin:%dpx %dpx %dpx %dpx;width:%dpx;height:%dpx;align-items:center;display:flex !important;justify-content:center;}',
			absint( $space['top'] ),
			absint( $space['right'] ),
			absint( $space['bottom'] ),
			absint( $space['left'] ),
			absint( $options['pin_button_width'] ),
			absint( $options['pin_button_height'] )
		);

		return $css;
	}

	/**
	 * Inject pin data attributes into content images.
	 *
	 * Concept originally from the jQuery Pin It Button For Images plugin.
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public function the_content( $content ) {
		global $post;

		if ( ! $post instanceof \WP_Post ) {
			return $content;
		}

		$options  = Options::get();
		$collect  = array_values( (array) $options['pin_text_data_collect'] );
		$get_desc = in_array( 'img_description', $collect, true );
		$get_capt = in_array( 'img_caption', $collect, true );

		preg_match_all( '/<img[^>]*>/i', $content, $images, PREG_SET_ORDER );

		foreach ( $images as $img ) {
			preg_match_all( '/ ([-\w]+)[ ]*=[ ]*([\"\'])(.*?)\2/i', $img[0], $attributes, PREG_SET_ORDER );

			$existing = '';
			$src      = '';
			$id       = '';

			foreach ( $attributes as $att ) {
				$existing .= $att[0];

				if ( 'class' === $att[1] ) {
					$id = $this->get_post_id_from_image_classes( $att[3] );
				}

				if ( 'src' === $att[1] ) {
					$src = $att[3];
				}
			}

			$attachment = ( $get_desc || $get_capt || '' !== $id ) ? $this->get_attachment( $id, $src ) : null;

			$attrs = array(
				'data-pm-post-excerpt' => wp_kses( $post->post_excerpt, array() ),
				'data-pm-post-url'     => get_permalink(),
				'data-pm-post-title'   => get_the_title(),
				'data-pm-src'          => $src,
			);

			if ( $attachment ) {
				if ( $get_desc ) {
					$attrs['data-pm-description'] = $attachment->post_content;
				}
				if ( $get_capt ) {
					$attrs['data-pm-caption'] = $attachment->post_excerpt;
				}
			}

			/**
			 * Filter the data attributes appended to a content image.
			 *
			 * @param array $attrs   Attribute name => raw value (escaped on output).
			 * @param array $context {
			 *     @type \WP_Post|null $attachment    Matched attachment, if any.
			 *     @type int           $attachment_id Attachment ID (0 if unknown).
			 *     @type string        $src           Image src attribute.
			 *     @type \WP_Post      $post          Current post.
			 *     @type string        $img_html      Original <img> HTML.
			 * }
			 */
			$attrs = apply_filters(
				'pin_master_image_attributes',
				$attrs,
				array(
					'attachment'    => $attachment,
					'attachment_id' => $attachment ? (int) $attachment->ID : 0,
					'src'           => $src,
					'post'          => $post,
					'img_html'      => $img[0],
				)
			);

			$new_img = '<img' . $existing;
			foreach ( $attrs as $name => $value ) {
				if ( '' === (string) $value ) {
					continue;
				}
				$new_img .= sprintf( ' %s="%s"', sanitize_key( $name ), esc_attr( $value ) );
			}
			$new_img .= ' >';

			$content = str_replace( $img[0], $new_img, $content );
		}

		return '<input class="pin-master" type="hidden">' . $content;
	}

	/**
	 * Extract the attachment ID from a wp-image-{id} class.
	 *
	 * @param string $class_attribute Class attribute value.
	 * @return string
	 */
	private function get_post_id_from_image_classes( $class_attribute ) {
		if ( preg_match( '/(?:^|\s)wp-image-(\d+)(?:\s|$)/', $class_attribute, $m ) ) {
			return $m[1];
		}

		return '';
	}

	/**
	 * Resolve an attachment post from an ID or image URL.
	 *
	 * @param string $id  Attachment ID candidate.
	 * @param string $src Image URL.
	 * @return \WP_Post|null
	 */
	private function get_attachment( $id, $src ) {
		if ( is_numeric( $id ) ) {
			$post = get_post( (int) $id );
			if ( $post ) {
				return $post;
			}
		}

		$found = attachment_url_to_postid( $src );

		if ( ! $found ) {
			// Size-suffixed URLs (image-300x200.jpg) don't match the original file.
			$original = preg_replace( '/-\d+x\d+(?=\.[a-z]{3,4}$)/i', '', $src );
			if ( $original !== $src ) {
				$found = attachment_url_to_postid( $original );
			}
		}

		return $found ? get_post( $found ) : null;
	}
}
