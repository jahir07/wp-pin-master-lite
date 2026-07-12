/**
 * Decides whether an image is eligible for a pin button: class allow /
 * deny lists plus responsive minimum-size constraints.
 */
export default class ImageFilter {
	constructor( settings ) {
		this.settings = settings;
		this.disabledClasses = this.createClassList( settings.disabled_classes );
		this.enabledClasses = this.createClassList( settings.enabled_classes );

		this.updateSizeConstraints();
		window.addEventListener( 'resize', () => this.updateSizeConstraints(), false );
	}

	createClassList( classes = '' ) {
		return String( classes )
			.split( ';' )
			.filter( Boolean );
	}

	imageEligible( $img ) {
		return (
			( this.enabledClasses.length === 0 ||
				this.enabledClasses.some( ( cls ) => $img.hasClass( cls ) ) ) &&
			! this.disabledClasses.some( ( cls ) => $img.hasClass( cls ) ) &&
			this.imageSizeIsOk( $img )
		);
	}

	imageSizeIsOk( $img ) {
		const width = $img[ 0 ].clientWidth;
		const height = $img[ 0 ].clientHeight;

		return width >= this.minWidth && height >= this.minHeight;
	}

	updateSizeConstraints() {
		const mobile = window.outerWidth < 768;

		this.minWidth = mobile
			? this.settings.min_image_width_pixel
			: this.settings.min_image_width;
		this.minHeight = mobile
			? this.settings.min_image_height_pixel
			: this.settings.min_image_height;
	}
}
