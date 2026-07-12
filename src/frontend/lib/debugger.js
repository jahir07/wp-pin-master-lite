/**
 * Tiny query-string driven debug logger.
 *
 * Append e.g. ?pin-master_print=1&pin-master_prevent_hide=1 to a page URL
 * to enable console logging / keep buttons visible while inspecting.
 */
export default class Debugger {
	constructor( pluginName ) {
		this.flags = {};
		this.pluginName = pluginName;

		const params = new URLSearchParams( document.location.search );
		params.forEach( ( value, key ) => {
			if ( key.indexOf( `${ pluginName }_` ) === 0 ) {
				this.setFlag( key.replace( `${ pluginName }_`, '' ), value );
			}
		} );
	}

	getFlag( flag ) {
		return this.flags[ flag ] !== undefined && this.flags[ flag ];
	}

	setFlag( flag, value = true ) {
		this.flags[ flag ] = value;
	}

	log( message ) {
		if ( this.getFlag( 'print' ) && window.console ) {
			window.console.log( `${ this.pluginName } debug: ${ message }` );
		}
	}

	logObject( obj ) {
		if ( this.getFlag( 'print' ) ) {
			this.log( JSON.stringify( obj, null, 4 ) );
		}
	}
}
