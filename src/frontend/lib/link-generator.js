/**
 * Builds the Pinterest bookmarklet / repin URL for an image.
 */
import DescriptionHelper from './description-helper';
import { getWidestImageUrlFromSrcset } from './srcset-helper';

export const getExtension = ( url = '' ) => {
	const path = url.replace( /^https?:\/\/[^/?#]+(?:[/?#]|$)/i, '' );
	const parts = path.split( '.' );

	if ( parts.length === 1 ) {
		return '';
	}

	return parts[ parts.length - 1 ].replace( /\?.*/i, '' ).toLowerCase();
};

const isPageExtension = ( extension ) => [ '', 'html', 'php' ].indexOf( extension ) !== -1;

export default class LinkGenerator {
	constructor( settings ) {
		this.descriptionHelper = new DescriptionHelper( { siteTitle: settings.siteTitle } );
		this.priorities = settings.pin_text_data_collect || [];
		this.supportSrcset = settings.support_srcset;
	}

	getDescription( $img ) {
		return this.descriptionHelper.getDescription( $img, this.priorities );
	}

	getImage( $img, parentLink ) {
		let src = $img.attr( 'data-pm-src' ) || $img.prop( 'src' );

		if ( this.supportSrcset ) {
			src = getWidestImageUrlFromSrcset( $img ) || src;
		}

		src = src || this.getSrcFromNotImage( $img );

		if ( ! src ) {
			return undefined;
		}

		if ( ! parentLink ) {
			return src;
		}

		// If the image links to a media file of the same type, prefer the
		// linked (usually full-size) file.
		return parentLink.extension === getExtension( src ) ? parentLink.href : src;
	}

	getSrcFromNotImage( $el ) {
		const match = /url\(["'](.*?)["']\)/g.exec( $el.css( 'background-image' ) );

		return match ? match[ 1 ] : '';
	}

	getUrl( $img, parentLink ) {
		if ( parentLink && isPageExtension( parentLink.extension ) ) {
			return parentLink.href;
		}

		return $img.attr( 'data-pm-post-url' ) || window.location.href;
	}

	generate( $img ) {
		const repinId = $img.attr( 'data-pin-id' );
		if ( repinId ) {
			return `https://pinterest.com/pin/${ repinId }/repin/x/`;
		}

		const $closestLink = $img.closest( 'a[href]' );
		let parentLink;

		if ( $closestLink.length === 1 ) {
			parentLink = {
				extension: getExtension( $closestLink.prop( 'href' ) ),
				href: $closestLink.prop( 'href' ),
			};
		}

		const image = this.getImage( $img, parentLink );

		if ( image === undefined ) {
			return undefined;
		}

		const media = encodeURIComponent( image );
		const description = encodeURIComponent( this.getDescription( $img ) );
		const url = encodeURIComponent( this.getUrl( $img, parentLink ) );

		return `https://pinterest.com/pin/create/bookmarklet/?is_video=false&url=${ url }&media=${ media }&description=${ description }`;
	}
}
