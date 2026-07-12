/**
 * Minimal trailing-edge throttle (replaces lodash/throttle).
 */
export default function throttle( fn, wait ) {
	let last = 0;
	let timer = null;

	return function ( ...args ) {
		const now = Date.now();
		const remaining = wait - ( now - last );

		if ( remaining <= 0 ) {
			if ( timer ) {
				clearTimeout( timer );
				timer = null;
			}
			last = now;
			fn.apply( this, args );
		} else if ( ! timer ) {
			timer = setTimeout( () => {
				last = Date.now();
				timer = null;
				fn.apply( this, args );
			}, remaining );
		}
	};
}
