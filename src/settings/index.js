/**
 * WP Pin Master — settings app entry.
 */
import { createRoot } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';

import App from './app';
import './style.scss';

domReady( () => {
	const node = document.getElementById( 'pin-master-settings' );

	if ( node ) {
		createRoot( node ).render( <App /> );
	}
} );
