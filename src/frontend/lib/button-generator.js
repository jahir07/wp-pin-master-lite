/**
 * Creates the pin button element for an image and knows its size.
 */
import LinkGenerator from './link-generator';
import { getCurrentBreakpoint, calculateSizeForBreakpoint } from './css-helper';

export default class ButtonGenerator {
	constructor( $, settings ) {
		this.$ = $;
		this.settings = settings;
		this.linkGenerator = new LinkGenerator( settings );

		// 'icon' and 'custom' are Pro-only styles: their glyph class / image
		// URL are added to the localized settings by Pro's frontend filter.
		// If that data is missing — Pro is inactive, or (for 'custom') no
		// image has been chosen yet — fall back to the built-in style
		// rather than rendering an empty, unstyled button.
		let pinImage = settings.pin_image;
		if ( 'icon' === pinImage && ! settings.custom_icon ) {
			pinImage = 'default';
		} else if ( 'custom' === pinImage && ! settings.custom_image_url ) {
			pinImage = 'default';
		}

		this.$element = this.$( '<a />', {
			target: '_blank',
			class: `pm-button ${ pinImage }`,
		} );

		if ( 'default' === pinImage ) {
			// No class on the span: the glyph comes from the plain
			// `span::before` rule in style.scss. A `pm-icon-*` class here
			// would also match the (differently-centered) attribute-selector
			// rule reserved for Pro's icon picker, fighting it for layout.
			this.$element.html( '<span></span>' );
			this.$element.addClass( `pm-button-${ settings.pin_image_button }` );
		} else if ( 'icon' === pinImage ) {
			this.$element.html(
				`<span class="${ settings.pin_image_icon }-pm-select-icon ${ settings.custom_icon }"></span>`
			);
			this.$element.addClass( `pm-icon-${ settings.pin_image_button }` );
		} else {
			this.$element.html( '<span></span>' );
		}
	}

	createButton( $img ) {
		const href = this.linkGenerator.generate( $img );

		if ( ! href ) {
			return undefined;
		}

		return this.$element
			.clone( false )
			.attr( 'href', href )
			.click( ( event ) => {
				event.preventDefault();
				event.stopPropagation();

				if ( event.currentTarget.href.slice( -1 ) !== '#' ) {
					window.open(
						event.currentTarget.href,
						`mw${ event.timeStamp }`,
						'left=20,top=20,width=600,height=500,toolbar=1,resizable=0'
					);
				}
			} );
	}

	getSize() {
		const size = {
			height: this.settings.custom_image_height,
			width: this.settings.custom_image_width,
		};

		if ( this.settings.scale_pin_image ) {
			return calculateSizeForBreakpoint( size, getCurrentBreakpoint() );
		}

		return size;
	}
}
