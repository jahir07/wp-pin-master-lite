/**
 * Calculates the pin button position relative to an image, respecting
 * the configured corner/center and margins.
 */
const topAligned = ( offset, _bottomRight, _size, margins ) => offset.top + margins.top;
const leftAligned = ( offset, _bottomRight, _size, margins ) => offset.left + margins.left;
const bottomAligned = ( _offset, bottomRight, size, margins ) =>
	bottomRight.top - size.height - margins.bottom;
const rightAligned = ( _offset, bottomRight, size, margins ) =>
	bottomRight.left - margins.right - size.width;
const centeredTop = ( offset, bottomRight, size ) =>
	offset.top + ( ( bottomRight.top - offset.top ) / 2 - size.height / 2 );
const centeredLeft = ( offset, bottomRight, size ) =>
	offset.left + ( ( bottomRight.left - offset.left ) / 2 - size.width / 2 );

const CALCULATORS = {
	'top-left': [ topAligned, leftAligned ],
	'top-right': [ topAligned, rightAligned ],
	'bottom-left': [ bottomAligned, leftAligned ],
	'bottom-right': [ bottomAligned, rightAligned ],
	middle: [ centeredTop, centeredLeft ],
};

export default class Positioner {
	constructor( position, margins ) {
		const [ topF, leftF ] = CALCULATORS[ position ] || CALCULATORS.middle;

		this.topF = topF;
		this.leftF = leftF;
		this.margins = margins;
	}

	calculatePosition( offset, bottomRight, size ) {
		return {
			top: this.topF( offset, bottomRight, size, this.margins ),
			left: this.leftF( offset, bottomRight, size, this.margins ),
		};
	}
}
