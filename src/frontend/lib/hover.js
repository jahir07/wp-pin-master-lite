/**
 * Picks and starts the display strategy from the settings.
 */
import ShowOnHoverStrategy from './show-on-hover-strategy';
import ShowOnScrollStrategy from './show-on-scroll-strategy';

export default class Hover {
	constructor( $, settings, logger ) {
		this.$ = $;
		this.settings = settings;
		this.logger = logger;
	}

	init() {
		this.showStrategy = this.getStrategy();
		this.showStrategy.start();
	}

	getStrategy() {
		let Strategy;

		if ( this.logger.getFlag( 'show_button' ) === 'always' ) {
			Strategy = ShowOnScrollStrategy;
		} else if (
			this.settings.show_button === 'hover' ||
			( ! this.settings.isTouchDevice && this.settings.show_button === 'always_touch' )
		) {
			Strategy = ShowOnHoverStrategy;
		} else {
			Strategy = ShowOnScrollStrategy;
		}

		return new Strategy( this.$, this.settings, this.logger );
	}
}
