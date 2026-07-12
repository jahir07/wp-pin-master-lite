/**
 * Live pin button preview for the Style tab.
 */
import { useEffect, useState } from '@wordpress/element';
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
		custom_icon: customIcon = '',
		custom_pin_image: customImageId = 0,
	} = values;

	const assetsUrl = window.pinMasterSettings?.assetsUrl || '';
	const [ customImageUrl, setCustomImageUrl ] = useState( '' );

	// Resolve the Pro custom button image for the preview.
	useEffect( () => {
		const id = Number( customImageId ) || 0;

		if ( ! id || pinImage !== 'custom' || ! window.wp?.media ) {
			setCustomImageUrl( '' );
			return;
		}

		const attachment = window.wp.media.attachment( id );
		attachment.fetch().then( () => {
			setCustomImageUrl( attachment.get( 'url' ) || '' );
		} );
	}, [ customImageId, pinImage ] );

	const isImageButton = pinImage === 'old_default' || pinImage === 'custom';
	const imageUrl =
		pinImage === 'custom' && customImageUrl
			? customImageUrl
			: `${ assetsUrl }/images/pin-old.png`;

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
					backgroundImage: `url(${ imageUrl })`,
					backgroundSize: 'contain',
					backgroundRepeat: 'no-repeat',
					backgroundPosition: 'center',
			  }
			: {
					background,
					color,
					borderRadius: RADII[ shape ] ?? '50%',
					fontSize: `${ fontSize }px`,
			  } ),
	};

	// Icon mode renders the selected PM-Font glyph (its stylesheet is
	// loaded on this screen by the Pro addon alongside the icon picker);
	// the default button approximates the frontend glyph with a "P".
	let inner = null;
	if ( pinImage === 'icon' && customIcon ) {
		inner = <span className={ customIcon } style={ { lineHeight: 1 } } />;
	} else if ( ! isImageButton ) {
		inner = (
			<span style={ { fontFamily: 'Georgia, serif', fontWeight: 700 } }>P</span>
		);
	}

	return (
		<div className="pin-master-preview">
			<p className="pin-master-field-label">{ __( 'Live Preview', 'wp-pin-master-lite' ) }</p>
			<div className="pin-master-preview-stage">
				<span style={ buttonStyle }>{ inner }</span>
			</div>
			<p className="pin-master-field-help">
				{ __( 'Approximation of the pin button over an image.', 'wp-pin-master-lite' ) }
			</p>
		</div>
	);
}
