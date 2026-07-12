/**
 * Runtime CSS helpers: injects rules/media queries into a <style> tag and
 * scales button sizes per responsive breakpoint.
 */

export const Breakpoints = {
	xsm: 0,
	sm: 576,
	md: 768,
	lg: 992,
	xlg: 1200,
};

export const BreakpointsList = Object.values( Breakpoints );

const MAX_SIZES = {
	[ Breakpoints.xsm ]: { width: 50, height: 50 },
	[ Breakpoints.sm ]: { width: 60, height: 60 },
	[ Breakpoints.md ]: { width: 80, height: 80 },
	[ Breakpoints.lg ]: { width: 110, height: 110 },
	[ Breakpoints.xlg ]: { width: 1000, height: 1000 },
};

export const getCurrentBreakpoint = () => {
	const width = window.innerWidth;

	for ( let i = BreakpointsList.length - 1; i >= 0; i-- ) {
		if ( width >= BreakpointsList[ i ] ) {
			return BreakpointsList[ i ];
		}
	}

	return Breakpoints.xsm;
};

const ruleToString = ( selector, declarations ) => {
	const body =
		typeof declarations === 'string'
			? declarations
			: Object.keys( declarations )
					.map( ( prop ) =>
						`${ prop }:${ prop === 'content' ? `'${ declarations[ prop ] }'` : declarations[ prop ] }`
					)
					.join( ';' );

	return `${ selector } {${ body }}`;
};

const insertRule = ( rule ) => {
	const style = document.createElement( 'style' );
	const sheet = document.head.appendChild( style ).sheet;
	sheet.insertRule( rule, sheet.cssRules.length );
};

export const addCssRule = ( selector, declarations ) => {
	insertRule( ruleToString( selector, declarations ) );
};

export const addMediaQueryRule = ( selector, breakpoint, declarations ) => {
	const rule = ruleToString( selector, declarations );
	insertRule( breakpoint > 0 ? `@media (min-width: ${ breakpoint }px){ ${ rule } }` : rule );
};

const scaleFactor = ( size, max ) => {
	const byWidth = Math.min( 1, max.width / size.width );
	const byHeight = Math.min( 1, max.height / size.height );

	return Math.min( byWidth, byHeight );
};

export const calculateSizeForBreakpoint = ( size, breakpoint ) => {
	const factor = scaleFactor( size, MAX_SIZES[ breakpoint ] );

	return {
		height: size.height * factor,
		width: size.width * factor,
	};
};
