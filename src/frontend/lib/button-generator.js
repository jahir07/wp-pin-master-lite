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

		this.$element = this.$( '<a />', {
			target: '_blank',
			class: `pm-button ${ settings.pin_image }`,
		} );

		if ( settings.pin_image === 'default' ) {
			this.$element.html( `<span class="pm-icon-${ settings.pin_image_icon }"></span>` );
			this.$element.addClass( `pm-button-${ settings.pin_image_button }` );
		} else if ( settings.pin_image === 'icon' ) {
			this.$element.html(
				`<span class="${ settings.pin_image_icon }-pm-select-icon ${ settings.custom_icon || '' }"></span>`
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
