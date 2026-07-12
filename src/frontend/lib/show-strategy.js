/**
 * Base class for button display strategies. Owns the shared services
 * (filter, generator, positioner) and the container class marker.
 */
import ImageFilter from './image-filter';
import ButtonGenerator from './button-generator';
import Positioner from './positioner';
import {
	DefaultButtonCSS,
	DefaultButtonMediaQuery,
	CustomButtonCSS,
	CustomButtonMediaQuery,
} from './css-generator';

export default class ShowStrategy {
	constructor( $, settings, logger ) {
		this.$ = $;
		this.settings = settings;
		this.logger = logger;
		this.imageFilter = new ImageFilter( settings );
		this.buttonGenerator = new ButtonGenerator( $, settings );
		this.positioner = new Positioner( settings.button_position, {
			top: settings.button_margin_top,
			right: settings.button_margin_right,
			bottom: settings.button_margin_bottom,
			left: settings.button_margin_left,
		} );
		this.indexerAttr = 'data-pm-priority';

		const size = {
			width: settings.custom_image_width,
			height: settings.custom_image_height,
		};

		switch ( settings.pin_image ) {
			case 'custom':
			case 'icon':
			case 'old_default': {
				const generate = settings.scale_pin_image
					? CustomButtonMediaQuery
					: CustomButtonCSS;
				generate( size );
				break;
			}
			case 'default': {
				const generate = settings.scale_pin_image
					? DefaultButtonMediaQuery
					: DefaultButtonCSS;
				generate( size, settings.pin_image_icon );
				break;
			}
		}
	}

	addContainers() {
		const $markers = this.$( '.pin-master' );
		let $containers = $markers.closest( 'div, article' );

		$containers = $containers.length ? $containers : $markers.parent();
		$containers.addClass( 'pm_container' );
	}
}
