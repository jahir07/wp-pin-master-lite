/**
 * Settings bag: copies the localized options and adds runtime detection.
 */
export default class Settings {
	constructor( options ) {
		Object.keys( options ).forEach( ( key ) => {
			this[ key ] = options[ key ];
		} );

		this.isTouchDevice =
			'ontouchstart' in window ||
			Object.prototype.hasOwnProperty.call( navigator, 'maxTouchPoints' );
	}
}
