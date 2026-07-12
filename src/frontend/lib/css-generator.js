/**
 * Generates size CSS for the pin button, optionally responsive.
 */
import {
	addMediaQueryRule,
	BreakpointsList,
	calculateSizeForBreakpoint,
} from './css-helper';

const ICON_FONT_RATIO = 36 / 54;
const CIRCLE_ICON_WIDTH_RATIO = 55 / 64;

const fontSizeFor = ( height ) => height * ICON_FONT_RATIO;

const iconOffsetFor = ( fontSize, icon ) => {
	if ( icon === 'circle' ) {
		return {
			'margin-top': `${ -0.5 * fontSize }px`,
			'margin-left': `${ -0.5 * fontSize * CIRCLE_ICON_WIDTH_RATIO }px`,
		};
	}

	return undefined;
};

export const DefaultButtonCSS = ( size, icon ) => {
	const fontSize = fontSizeFor( size.height );

	addMediaQueryRule( 'a.pm-button span', 0, {
		'min-height': `${ size.height }px`,
		'min-width': `${ size.width }px`,
		'font-size': `${ fontSize }px`,
	} );

	const offset = iconOffsetFor( fontSize, icon );
	if ( offset ) {
		addMediaQueryRule( 'a.pm-button span:before', 0, offset );
	}
};

export const CustomButtonCSS = ( size ) => {
	addMediaQueryRule( 'a.pm-button', 0, {
		'min-height': `${ size.height }px`,
		'min-width': `${ size.width }px`,
	} );

	addMediaQueryRule( 'a.pm-button span', 0, {
		'background-size': `${ size.width }px ${ size.height }px`,
		'min-height': `${ size.height }px`,
		'min-width': `${ size.width }px`,
	} );
};

export const DefaultButtonMediaQuery = ( size, icon ) => {
	BreakpointsList.forEach( ( breakpoint ) => {
		const scaled = calculateSizeForBreakpoint( size, breakpoint );
		const fontSize = fontSizeFor( scaled.height );

		addMediaQueryRule( 'a.pm-button', breakpoint, {
			height: `${ scaled.height }px`,
			width: `${ scaled.width }px`,
		} );
		addMediaQueryRule( 'a.pm-button span', breakpoint, {
			'font-size': `${ fontSize }px`,
		} );

		const offset = iconOffsetFor( fontSize, icon );
		if ( offset ) {
			addMediaQueryRule( 'a.pm-button span:before', breakpoint, offset );
		}
	} );
};

export const CustomButtonMediaQuery = ( size ) => {
	BreakpointsList.forEach( ( breakpoint ) => {
		const scaled = calculateSizeForBreakpoint( size, breakpoint );

		addMediaQueryRule( 'a.pm-button', breakpoint, {
			height: `${ scaled.height }px`,
			width: `${ scaled.width }px`,
		} );
		addMediaQueryRule( 'a.pm-button span', breakpoint, {
			'background-size': `${ scaled.width }px ${ scaled.height }px`,
			height: `${ scaled.height }px`,
			width: `${ scaled.width }px`,
		} );
	} );
};
