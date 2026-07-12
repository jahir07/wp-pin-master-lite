/**
 * Resolves the pin description for an image from the configured,
 * priority-ordered list of sources.
 */
export default class DescriptionHelper {
	constructor( { siteTitle = '' } = {} ) {
		this.sources = {
			img_title: ( $img ) => $img.attr( 'title' ) || $img.attr( 'data-pm-title' ),
			img_alt: ( $img ) => $img.attr( 'alt' ) || $img.attr( 'data-pm-alt' ),
			post_title: ( $img ) => $img.attr( 'data-pm-post-title' ),
			post_excerpt: ( $img ) => $img.attr( 'data-pm-post-excerpt' ),
			img_description: ( $img ) => $img.attr( 'data-pm-description' ),
			img_caption: ( $img ) => $img.attr( 'data-pm-caption' ),
			site_title: () => siteTitle,
			data_pin_description: ( $img ) => $img.attr( 'data-pin-description' ),
		};
	}

	getDescription( $img, priorities = [] ) {
		let description = '';

		for ( let i = 0; i < priorities.length && ! description; i++ ) {
			const source = this.sources[ priorities[ i ] ];
			description = source ? source( $img ) : '';
		}

		return description || '';
	}
}
