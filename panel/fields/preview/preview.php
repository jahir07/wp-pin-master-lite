<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access pages directly.
/**
 *
 * Field: Notice
 *
 * @since 1.0.0
 * @version 1.0.0
 */
class WPPinMasterLite_Option_preview extends WPPinMasterLite_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {
		echo $this->element_before();

		$value = $this->element_value();

		echo '<div class="pm-preview pinit-button icon"><img src="' . $value . '" alt="preview" /></div>';
		echo '<div class="pm-preview pinit-button icon"><span id="item_preview"></span></div>';

		ob_start(); ?>

		<?php
		echo ob_get_clean();
		echo $this->element_after();
	}
}
