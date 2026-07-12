/**
 * Settings bag: copies the localized options and adds runtime detection.
 */
const NUMERIC_KEYS = [
	'min_image_width',
	'min_image_height',
	'min_image_width_pixel',
	'min_image_height_pixel',
	'button_margin_top',
	'button_margin_right',
	'button_margin_bottom',
	'button_margin_left',
	'custom_image_width',
	'custom_image_height',
];

export default class Settings {
	constructor( options ) {
		Object.keys( options ).forEach( ( key ) => {
			this[ key ] = options[ key ];
		} );

		// Guard against filters localizing numbers as strings — the
		// position math would silently concatenate instead of adding.
		NUMERIC_KEYS.forEach( ( key ) => {
			if ( typeof this[ key ] === 'string' && this[ key ] !== '' ) {
				this[ key ] = Number( this[ key ] );
			}
		} );

		this.isTouchDevice =
			'ontouchstart' in window ||
			Object.prototype.hasOwnProperty.call( navigator, 'maxTouchPoints' );
	}
}
