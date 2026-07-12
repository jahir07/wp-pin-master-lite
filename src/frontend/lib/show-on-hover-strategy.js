/**
 * Shows the pin button while the pointer is over an eligible image.
 */
import ShowStrategy from './show-strategy';

const TIMEOUT_ATTR = 'data-pm-timeout';
const HIDE_DELAY = 100;

export default class ShowOnHoverStrategy extends ShowStrategy {
	start() {
		this.addContainers();

		const self = this;
		let indexer = 0;

		const buttonFor = ( index ) =>
			self.$( `a.pm-button[${ self.indexerAttr }="${ index }"]` );

		self.$( document ).on( 'mouseenter', this.settings.image_selector, function () {
			const $img = self.$( this );

			if ( ! self.imageFilter.imageEligible( $img ) ) {
				return;
			}

			let index = $img.attr( self.indexerAttr );
			if ( ! index ) {
				index = String( indexer++ );
				$img.attr( self.indexerAttr, index );
			}

			const $existing = buttonFor( index );

			if ( $existing.length !== 0 ) {
				clearTimeout( $existing.attr( TIMEOUT_ATTR ) );
				return;
			}

			const $button = self.buttonGenerator.createButton( $img );
			if ( ! $button ) {
				return;
			}

			$img.addClass( 'pm-hover' );

			const size = self.buttonGenerator.getSize();
			const offset = $img.offset();
			const bottomRight = {
				top: offset.top + $img[ 0 ].clientHeight,
				left: offset.left + $img[ 0 ].clientWidth,
			};
			const position = self.positioner.calculatePosition( offset, bottomRight, size );

			$img.after( $button );
			$button
				.attr( self.indexerAttr, index )
				.css( 'visibility', 'hidden' )
				.show()
				.offset( position )
				.css( 'visibility', 'visible' )
				.hover(
					() => clearTimeout( $button.attr( TIMEOUT_ATTR ) ),
					() =>
						$button.attr(
							TIMEOUT_ATTR,
							setTimeout( () => {
								$img.removeClass( 'pm-hover' );
								$button.remove();
							}, HIDE_DELAY )
						)
				);
		} );

		self.$( document ).on( 'mouseleave', this.settings.image_selector, function () {
			if ( self.logger.getFlag( 'prevent_hide' ) ) {
				return;
			}

			const $img = self.$( this );
			const index = $img.attr( self.indexerAttr );

			if ( ! index ) {
				return;
			}

			const $button = buttonFor( index );
			$button.attr(
				TIMEOUT_ATTR,
				setTimeout( () => {
					$img.removeClass( 'pm-hover' );
					$button.remove();
				}, HIDE_DELAY )
			);
		} );
	}
}
