/**
 * WP Pin Master — frontend entry.
 *
 * Reads the localized `pinMasterOptions` payload and starts the pin
 * button display strategy.
 */
import jQuery from 'jquery';

import Settings from './lib/settings';
import Debugger from './lib/debugger';
import Hover from './lib/hover';
import './style.scss';

jQuery( function ( $ ) {
	const options = window.pinMasterOptions;

	if ( ! options ) {
		return;
	}

	// Elementor "No Pin" support: propagate the nopin class to images.
	$( '.elementor-element.nopin' ).each( function () {
		$( this ).find( 'img' ).addClass( 'nopin' );
	} );

	const settings = new Settings(
		$.extend(
			{
				pageUrl: document.URL,
				pageTitle: document.title,
				pageDescription: $( 'meta[name="description"]' ).attr( 'content' ) || '',
			},
			options
		)
	);

	const logger = new Debugger( 'pin-master' );
	window.pmDebugger = logger;

	new Hover( $, settings, logger ).init();
} );
