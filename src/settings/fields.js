/**
 * Field type registry for the settings app.
 *
 * Every renderer receives:
 * - field    The schema field definition.
 * - value    Current value.
 * - onChange ( nextValue ) => void
 * - disabled Whether the field is pro-locked.
 *
 * Addons register extra types via the `pinMaster.settings.fieldTypes`
 * filter (see docs/hooks.md).
 */
import { applyFilters } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import {
	SelectControl,
	CheckboxControl,
	TextControl,
	ToggleControl,
	ColorPicker,
	Dropdown,
	Button,
	ColorIndicator,
} from '@wordpress/components';

const choiceEntries = ( field ) => Object.entries( field.choices || {} );

const SelectField = ( { field, value, onChange, disabled } ) => (
	<SelectControl
		__nextHasNoMarginBottom
		label={ field.label }
		help={ field.help }
		value={ value }
		disabled={ disabled }
		onChange={ onChange }
		options={ choiceEntries( field ).map( ( [ key, choice ] ) => ( {
			value: key,
			label: choice.pro && ! window.pinMasterSettings?.isPro
				? `${ choice.label } — ${ __( 'Pro', 'wp-pin-master' ) }`
				: choice.label,
			disabled: !! choice.pro && ! window.pinMasterSettings?.isPro,
		} ) ) }
	/>
);

const MultiselectField = ( { field, value, onChange, disabled } ) => {
	const selected = Array.isArray( value ) ? value : [];

	const toggle = ( key, isChecked ) => {
		const next = isChecked
			? [ ...selected, key ]
			: selected.filter( ( item ) => item !== key );
		onChange( next );
	};

	return (
		<fieldset className="pin-master-field-multiselect">
			<legend className="pin-master-field-label">{ field.label }</legend>
			{ field.help && <p className="pin-master-field-help">{ field.help }</p> }
			{ choiceEntries( field ).map( ( [ key, choice ] ) => {
				const locked = !! choice.pro && ! window.pinMasterSettings?.isPro;
				return (
					<CheckboxControl
						__nextHasNoMarginBottom
						key={ key }
						label={
							locked
								? `${ choice.label } — ${ __( 'Pro', 'wp-pin-master' ) }`
								: choice.label
						}
						checked={ selected.includes( key ) }
						disabled={ disabled || locked }
						onChange={ ( isChecked ) => toggle( key, isChecked ) }
					/>
				);
			} ) }
		</fieldset>
	);
};

const NumberField = ( { field, value, onChange, disabled } ) => (
	<TextControl
		__nextHasNoMarginBottom
		type="number"
		label={ field.label }
		help={ field.help }
		min={ field.min }
		max={ field.max }
		value={ value ?? '' }
		disabled={ disabled }
		onChange={ ( next ) => onChange( next === '' ? '' : Number( next ) ) }
	/>
);

const ColorField = ( { field, value, onChange, disabled } ) => (
	<div className="pin-master-field-color">
		<span className="pin-master-field-label">{ field.label }</span>
		<Dropdown
			popoverProps={ { placement: 'left-start' } }
			renderToggle={ ( { isOpen, onToggle } ) => (
				<Button
					variant="tertiary"
					onClick={ onToggle }
					aria-expanded={ isOpen }
					disabled={ disabled }
					className="pin-master-color-toggle"
				>
					<ColorIndicator colorValue={ value } />
					<code>{ value || '—' }</code>
				</Button>
			) }
			renderContent={ () => (
				<ColorPicker
					color={ value }
					enableAlpha={ false }
					onChange={ ( next ) => onChange( next ) }
				/>
			) }
		/>
	</div>
);

const BoxField = ( { field, value, onChange, disabled } ) => {
	const box = { top: 0, right: 0, bottom: 0, left: 0, ...( value || {} ) };
	const sides = [
		[ 'top', __( 'Top', 'wp-pin-master' ) ],
		[ 'right', __( 'Right', 'wp-pin-master' ) ],
		[ 'bottom', __( 'Bottom', 'wp-pin-master' ) ],
		[ 'left', __( 'Left', 'wp-pin-master' ) ],
	];

	return (
		<fieldset className="pin-master-field-box">
			<legend className="pin-master-field-label">{ field.label }</legend>
			<div className="pin-master-field-box-inputs">
				{ sides.map( ( [ side, label ] ) => (
					<TextControl
						__nextHasNoMarginBottom
						key={ side }
						type="number"
						label={ label }
						min={ 0 }
						value={ box[ side ] ?? 0 }
						disabled={ disabled }
						onChange={ ( next ) =>
							onChange( { ...box, [ side ]: next === '' ? 0 : Number( next ) } )
						}
					/>
				) ) }
			</div>
		</fieldset>
	);
};

const ToggleField = ( { field, value, onChange, disabled } ) => (
	<ToggleControl
		__nextHasNoMarginBottom
		label={ field.label }
		help={ field.help }
		checked={ !! value }
		disabled={ disabled }
		onChange={ onChange }
	/>
);

const TextField = ( { field, value, onChange, disabled } ) => (
	<TextControl
		__nextHasNoMarginBottom
		label={ field.label }
		help={ field.help }
		value={ value ?? '' }
		disabled={ disabled }
		onChange={ onChange }
	/>
);

/**
 * The filterable registry. Addons may add renderers for custom types:
 *
 * addFilter( 'pinMaster.settings.fieldTypes', 'my-plugin', ( types ) => ( {
 *     ...types,
 *     my_type: MyRenderer,
 * } ) );
 */
export const getFieldTypes = () =>
	applyFilters( 'pinMaster.settings.fieldTypes', {
		select: SelectField,
		multiselect: MultiselectField,
		number: NumberField,
		color: ColorField,
		box: BoxField,
		toggle: ToggleField,
		text: TextField,
	} );

/**
 * Evaluate a field's show_if condition against current values.
 *
 * @param {Array|undefined} showIf [ fieldId, operator, expected ].
 * @param {Object}          values Current settings values.
 * @return {boolean} Whether the field is visible.
 */
export const isVisible = ( showIf, values ) => {
	if ( ! Array.isArray( showIf ) || showIf.length < 3 ) {
		return true;
	}

	const [ fieldId, operator, expected ] = showIf;
	const actual = values[ fieldId ];

	switch ( operator ) {
		case '==':
			return actual === expected;
		case '!=':
			return actual !== expected;
		case 'in':
			return Array.isArray( expected ) && expected.includes( actual );
		default:
			return true;
	}
};
