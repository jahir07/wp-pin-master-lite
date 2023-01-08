<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

// Settings
$settings = array(
	'menu_title'      => 'WP Pin Master',
	'menu_icon'        => WPPML_ASSETS . '/images/pinicon.png',
	'menu_type'       => 'menu',
	'menu_slug'       => 'pm-panel',
	'ajax_save'       => false,
	'show_reset_all'  => false,
	'framework_title' => 'WP Pin Master Lite<small>v ' . WPPML_VERSION . '</small>',
);


$options = array();


/**
 * General Settings.
 */
$options[] = array(
	'name'        => 'pinterest',
	'title'       => 'General',
	'icon'        => 'fa fa-pinterest',

	// begin: fields
	'fields'      => array(

		array(
			'id'      => 'show_button',
			'type'    => 'select',
			'title'    => __( 'Pin It Button - Showing Option', 'pin-master' ),
			'desc'    => __( 'How pinterest button display this option responsible.', 'pin-master' ),
			'options' => array(
				'hover' => 'On hover',
				'touch_for_pro'  => [ 'When Touch - Pro Only', 'disabled' ],
				'always_for_pro'  => [ 'Always - Pro Only', 'disabled' ],
			),
			'default' => 'hover',
		),

		array(
			'id'      => 'image_selector',
			'type'    => 'select',
			'title'    => __( 'Pin It Button - Where Show', 'pin-master' ),
			'desc'    => __( 'How pinterest button display this option responsible.', 'pin-master' ),
			'options' => array(
				'.wppml_container img' => 'Only Article Area',
				'article_sidebar_for_pro' => [ 'Article Area + Sidebar - Pro Only', 'disabled' ],
				'all_image' => [ 'All Image - Pro Only', 'disabled' ],
			),
			'default' => '.m_container img',
		),


		array(
			'id'     => 'pin_text_data_collect',
			'title'   => __( 'Pinterest Text Data Collected From?', 'pin-master' ),
			'options'        => array(
				'post_title'      => __( 'Post title', 'pin-master' ),
				'site_title'      => __( 'Site title (From Settings->General)', 'pin-master' ),
				'post_excerpt'    => __( 'Post description (excerpt)', 'pin-master' ),
				'img_title'       => __( 'Image title attribute', 'pin-master' ),
				'img_description' => __( 'Image description (read hints)', 'pin-master' ),
				'img_caption'     => __( 'Image caption (read hints)', 'pin-master' ),
				'img_alt'         => __( 'Image alt attribute (read hints)', 'pin-master' ),
				'data_pin_description' => [ 'Pinterest Data Description - Pro Only', 'disabled' ],
			),
			'default'  => [ 'post_title' ],
			'desc'    => __( 'This is define from where the Pinterest message should be taken. Priority according to the chronological options except default post title. If one option\'s pinterest text blank, it will catch next one. <br> <br><b>Please note that "Image description" and "Image caption" work properly only for images that were added to your Media Library.</b>', 'pin-master' ),
			'type'    => 'select',
			'class'         => 'chosen',
			'attributes'    => array(
				'placeholder' => 'Select a color',
				'multiple'    => 'multiple',
			),
		),



	),
);

/*=====  End of pinterest  ======*/


/*================================
=            Style            =
================================*/

$options[] = array(
	'name'        => 'styling',
	'title'       => 'Style',
	'icon'        => 'fa fa-paint-brush',

	// begin: fields
	'fields'      => array(

		array(
			'id'     => 'button_position',
			'title'   => __( 'Pinterest Button Position', 'pin-master' ),
			'options' => array(
				'top-left' => __( 'Top Left', 'pin-master' ),
				'top-right' => __( 'Top Right', 'pin-master' ),
				'bottom-left'     => __( 'Bottom Left', 'pin-master' ),
				'bottom-right'      => __( 'Bottom Right', 'pin-master' ),
				'middle'      => __( 'Center', 'pin-master' ),
			),
			'default' => 'top-left',
			'type'    => 'select',
		),

		array(
			'id'     => 'pin_image',
			'type'    => 'select',
			'title'   => __( 'Pin Icon?', 'pin-master' ),
			'options' => array(
				'old_default' => __( 'Old Style', 'pin-master' ),
				'default'     => __( 'Default', 'pin-master' ),
				'icon'      => [ 'Icon - Pro Only', 'disabled' ],
				'custom'      => [ 'Image - Pro Only', 'disabled' ],
			),
		),

		array(
			'id'      => 'old_style',
			'type'    => 'preview',
			'default'   => WPPML_ASSETS . '/images/pin-old.png',
			'title'    => __( 'Preview', 'pin-master' ),
			'dependency' => array( 'pin_image', '==', 'old_default' ),
		),


		array(
			'id'      => 'default_style',
			'type'    => 'preview',
			'default'   => WPPML_ASSETS . '/images/default.png',
			'title'    => __( 'Preview', 'pin-master' ),
			'dependency' => array( 'pin_image', '==', 'default' ),
		),


		array(
			'id'     => 'pin_image_button',
			'title'   => __( 'Pinterest Button Style', 'pin-master' ),
			'options' => array(
				'square'            => __( 'Square', 'pin-master' ),
				'rounded-square'    => __( 'Rounded Square', 'pin-master' ),
				'round'             => __( 'Round', 'pin-master' ),
			),
			'dependency' => array( 'pin_image', 'any', 'icon,default' ),
			'type'    => 'select',
			'default' => 'round',
		),

		array(
			'id'      => 'pin_icon_size',
			'type'    => 'spacing',
			'dependency' => array( 'pin_image', 'any', 'icon,default' ),
			'title'   => __( 'Pinterest Button Size', 'pin-master' ),
			'left_icon' => '<i class="fa fa-arrows-v"></i>',
			'top_icon' => '<i class="fa fa-arrows-h"></i>',
			'left_placeholder' => 'Height',
			'top_placeholder' => 'Width',
			'right'  => false,
			'bottom' => false,
		),

		array(
			'id'      => 'pin_font_size',
			'type'    => 'number',
			'dependency' => array( 'pin_image', 'any', 'icon,default' ),
			'title'   => __( 'Font Size', 'pin-master' ),
		),

		array(
			'id'      => 'pin_font_color',
			'dependency' => array( 'pin_image', 'any', 'icon,default' ),
			'type'    => 'color_picker',
			'title'   => 'Font Color',
		),

		array(
			'id'      => 'pin_bg_color',
			'type'    => 'color_picker',
			'dependency' => array( 'pin_image', 'any', 'icon,default' ),
			'title'   => __( 'Background Color', 'pin-master' ),
		),

		array(
			'id'      => 'pin_font_color_hover',
			'dependency' => array( 'pin_image', 'any', 'icon,default' ),
			'type'    => 'color_picker',
			'title'   => 'Hover Font Color',
		),

		array(
			'id'      => 'pin_bg_color_hover',
			'type'    => 'color_picker',
			'dependency' => array( 'pin_image', 'any', 'icon,default' ),
			'title'   => __( 'Hover Background Color', 'pin-master' ),
		),

		array(
			'id'      => 'pin_space',
			'type'    => 'spacing',
			'title'   => __( 'Margin', 'pin-master' ),
		),

	),
);


/**
 * Advance settings.
 */
$options[] = array(
	'name'        => 'advance_settings',
	'title'       => 'Advance',
	'icon'        => 'fa fa-flask',
	'fields'      => array(

		array(
			'id'     => 'where_show',
			'type'    => 'select',
			'title'   => __( 'Pinterest Active On?', 'pin-master' ),
			'options' => array(
				'front'     => esc_html__( 'Front Page', 'pin-master' ),
				'home'      => esc_html__( 'Homepage', 'pin-master' ),
				'single'    => esc_html__( 'Inner Post', 'pin-master' ),
				'page'      => esc_html__( 'Inner Page', 'pin-master' ),
				'archive'   => esc_html__( 'Archive Page', 'pin-master' ),
				'search'    => esc_html__( 'Search Page', 'pin-master' ),
				'category'  => esc_html__( 'Category Page', 'pin-master' ),
			),
			'default' => [ 'front', 'home', 'single', 'page', 'archive', 'search', 'category' ],
			'class'         => 'chosen',
			'attributes'    => array(
				'placeholder' => 'Select a color',
				'multiple'    => 'multiple',
			),
		),

		array(
			'id'     => 'where_show_cpt',
			'type'    => 'select',
			'title'   => __( 'Custop Post Type : Pro Only?', 'pin-master' ),
			'default_option' => 'Pro Only',
			'class'         => 'chosen',
			'attributes'    => array(
				'placeholder' => 'Select Post Type',
				'disabled'    => 'disabled',
			),
		),

		array(
			'type'    => 'subheading',
			'content' => 'When Pinterest Button Show - Minimum image resolution',
		),
		array(
			'id'      => 'min_image_width_pixel',
			'type'    => 'number',
			'default' => 300,
			'title'   => 'Screen Resolution',
		),

		array(
			'id'      => 'min_image_width',
			'type'    => 'number',
			'default' => 200,
			'title'   => 'Image Width',
			'desc'    => 'In Low resolution image, Pin Button Not display. Default 120px',
		),


		array(
			'id'      => 'min_image_height',
			'default' => 200,
			'type'    => 'number',
			'title'   => 'Image Height - In Low resolution image, Pin Button Not display ',
			'desc'    => 'In Low resolution image, Pin Button Not display. Default 120px',
		),

	// end: a field
	),
);


$options[] = array(
	'name'        => 'pro_settings',
	'title'       => 'Pro Version',
	'icon'        => 'fa fa-flask',
	'fields'      => array(
		array(
			'type'    => 'subheading',
			'content' => 'Exciting Feature Coming soon. To Purchase Pro - <a target="_blank" href="https://www.codenod.com/wp-pin-master-pro/">Click Here</a>',
		),

	),
);


WPPinMasterLite::instance( $settings, $options );
