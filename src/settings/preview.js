/**
 * Live pin button preview for the Style tab.
 */
import { __ } from '@wordpress/i18n';

const POSITIONS = {
	'top-left': { top: '0', left: '0' },
	'top-right': { top: '0', right: '0' },
	'bottom-left': { bottom: '0', left: '0' },
	'bottom-right': { bottom: '0', right: '0' },
	middle: { top: '50%', left: '50%', transform: 'translate(-50%, -50%)' },
};

const RADII = {
	square: '0',
	'rounded-square': '8px',
	round: '50%',
};

export default function Preview( { values } ) {
	const {
		button_position: position = 'top-left',
		pin_image: pinImage = 'default',
		pin_image_button: shape = 'round',
		pin_button_width: width = 45,
		pin_button_height: height = 45,
		pin_font_size: fontSize = 20,
		pin_font_color: color = '#ffffff',
		pin_bg_color: background = '#e60023',
		pin_space: space = {},
	} = values;

	const assetsUrl = window.pinMasterSettings?.assetsUrl || '';
	const isImageButton = pinImage === 'old_default' || pinImage === 'custom';

	const buttonStyle = {
		position: 'absolute',
		...( POSITIONS[ position ] || POSITIONS[ 'top-left' ] ),
		margin: `${ space.top ?? 0 }px ${ space.right ?? 0 }px ${ space.bottom ?? 0 }px ${
			space.left ?? 0
		}px`,
		width: `${ width }px`,
		height: `${ height }px`,
		display: 'flex',
		alignItems: 'center',
		justifyContent: 'center',
		...( isImageButton
			? {
					backgroundImage: `url(${ assetsUrl }/images/pin-old.png)`,
					backgroundSize: 'contain',
					backgroundRepeat: 'no-repeat',
					backgroundPosition: 'center',
			  }
			: {
					background,
					color,
					borderRadius: RADII[ shape ] ?? '50%',
					fontSize: `${ fontSize }px`,
					fontWeight: 700,
					fontFamily: 'Georgia, serif',
			  } ),
	};

	return (
		<div className="pin-master-preview">
			<p className="pin-master-field-label">{ __( 'Live Preview', 'wp-pin-master' ) }</p>
			<div className="pin-master-preview-stage">
				{ ! isImageButton && <span style={ buttonStyle }>P</span> }
				{ isImageButton && <span style={ buttonStyle } /> }
			</div>
			<p className="pin-master-field-help">
				{ __( 'Approximation of the pin button over an image.', 'wp-pin-master' ) }
			</p>
		</div>
	);
}
