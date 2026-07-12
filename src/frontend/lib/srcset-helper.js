/**
 * Picks the widest candidate URL out of an img srcset.
 */
const WIDTH_DESCRIPTOR = /(.+)\s+(\d{1,4})w\s*/i;
const DENSITY_DESCRIPTOR = /(.*)\s+(\d{1,3}(\.\d{0,2})?)x\s*/i;

export const getWidestImageUrlFromSrcset = ( $img ) => {
	const srcset = $img.prop ? $img.prop( 'srcset' ) : $img.attr( 'srcset' );

	if ( ! srcset || srcset.length === 0 ) {
		return undefined;
	}

	try {
		const candidates = srcset.split( ',' );

		// Only width-descriptor srcsets are comparable; density descriptors
		// all reference the same layout size.
		if ( ! candidates.some( ( c ) => WIDTH_DESCRIPTOR.test( c ) ) ) {
			if ( candidates.some( ( c ) => DENSITY_DESCRIPTOR.test( c ) ) ) {
				return undefined;
			}
			return undefined;
		}

		const parsed = candidates
			.map( ( candidate ) => WIDTH_DESCRIPTOR.exec( candidate ) )
			.filter( Boolean )
			.map( ( match ) => ( {
				url: match[ 1 ],
				width: Number( match[ 2 ] ),
			} ) );

		if ( parsed.length === 0 ) {
			return undefined;
		}

		return parsed.reduce( ( widest, candidate ) =>
			widest.width > candidate.width ? widest : candidate
		).url;
	} catch ( error ) {
		if ( window.console && window.console.error ) {
			window.console.error( error );
		}
		return undefined;
	}
};
