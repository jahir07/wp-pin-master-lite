/**
 * Keeps pin buttons visible on all eligible images currently in the
 * viewport, refreshing on scroll/resize ("always" / touch modes).
 */
import ShowStrategy from './show-strategy';
import throttle from './throttle';

const REFRESH_INTERVAL = 250;

export default class ShowOnScrollStrategy extends ShowStrategy {
	constructor( $, settings, logger ) {
		super( $, settings, logger );

		this.indexer = 1;
		this.buttonsDictionary = {};
		this.running = false;
	}

	start() {
		this.addContainers();

		const self = this;

		const imageForButton = ( button ) => {
			const index = self.$( button ).data( 'pm-indexer' );
			return self.$( `[data-pm-priority="${ index }"]:not(a.pm-button)` );
		};

		this.$( document ).on( 'mouseenter', `[${ this.indexerAttr }]`, function () {
			const $img = imageForButton( this );
			$img.addClass( 'pm-hover' );
			clearTimeout( $img.data( 'pm-timeoutId' ) );
		} );

		this.$( document ).on( 'mouseleave', `[${ this.indexerAttr }]`, function () {
			const $img = imageForButton( this );
			$img.data(
				'pm-timeoutId',
				setTimeout( () => $img.removeClass( 'pm-hover' ), 100 )
			);
		} );

		this.$( window ).on(
			'load scroll touchmove pm-refresh-scroll',
			throttle( () => this.scroll(), REFRESH_INTERVAL )
		);

		if ( this.settings.scroll_selector ) {
			this.$( this.settings.scroll_selector ).scroll(
				throttle( () => this.scroll(), REFRESH_INTERVAL )
			);
		}

		// Initial paint without waiting for a scroll event.
		this.scroll();
	}

	scroll() {
		if ( this.running ) {
			return;
		}

		this.running = true;
		this.refreshElements();
		this.running = false;
	}

	elementIsVisible( $el ) {
		const top = $el.offset().top;
		const bottom = top + $el.height();
		const viewportTop = this.$( window ).scrollTop();
		const viewportBottom = viewportTop + this.$( window ).height();

		return (
			( top >= viewportTop && top <= viewportBottom ) ||
			( bottom >= viewportTop && bottom <= viewportBottom ) ||
			( top <= viewportTop && bottom >= viewportTop )
		);
	}

	getButtonByIndexer( index ) {
		return this.$( `a.pm-button[${ this.indexerAttr }="${ index }"]` );
	}

	refreshElements() {
		const $images = this.$( this.settings.image_selector );

		for ( let i = 0; i < $images.length; i++ ) {
			this.processElement( this.$( $images[ i ] ) );
		}
	}

	processElement( $img ) {
		if ( ! this.imageFilter.imageEligible( $img ) ) {
			return;
		}

		const index = $img.attr( this.indexerAttr ) || String( this.indexer++ );
		const visible = this.elementIsVisible( $img );

		if ( visible ) {
			const offset = $img.offset();
			const bottomRight = {
				top: offset.top + $img[ 0 ].clientHeight,
				left: offset.left + $img[ 0 ].clientWidth,
			};
			const size = this.buttonGenerator.getSize();
			const position = this.positioner.calculatePosition( offset, bottomRight, size );

			if ( this.buttonsDictionary[ index ] === undefined ) {
				this.buttonsDictionary[ index ] = true;

				const $button = this.buttonGenerator.createButton( $img );
				if ( ! $button ) {
					return;
				}

				$img.attr( this.indexerAttr, index );
				$img.after( $button );
				$button
					.attr( this.indexerAttr, index )
					.css( 'visibility', 'hidden' )
					.show()
					.offset( position )
					.css( 'visibility', 'visible' );
			} else {
				const $button = this.getButtonByIndexer( index );
				const current = $button.offset();

				if ( current.top !== position.top || current.left !== position.left ) {
					$button.offset( position );
				}
			}
		} else if ( this.buttonsDictionary[ index ] !== undefined ) {
			delete this.buttonsDictionary[ index ];
			this.getButtonByIndexer( index ).remove();
		}
	}
}
