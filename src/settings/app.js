/**
 * WP Pin Master settings app.
 */
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import {
	TabPanel,
	Button,
	Spinner,
	Notice,
	Snackbar,
	Card,
	CardBody,
	ExternalLink,
} from '@wordpress/components';

import { getFieldTypes, isVisible } from './fields';
import Preview from './preview';

const ENDPOINT = '/pin-master/v1/settings';

const UpsellTab = () => (
	<Card>
		<CardBody>
			<h2>{ __( 'WP Pin Master Pro', 'wp-pin-master' ) }</h2>
			<ul className="pin-master-upsell-list">
				<li>{ __( 'Show pin buttons always or on touch devices', 'wp-pin-master' ) }</li>
				<li>{ __( 'Pin buttons on sidebar images or every image', 'wp-pin-master' ) }</li>
				<li>{ __( 'Custom button icons and images', 'wp-pin-master' ) }</li>
				<li>{ __( 'Per-image Pinterest descriptions', 'wp-pin-master' ) }</li>
				<li>{ __( 'AI-generated pin descriptions, alt text, and hashtags', 'wp-pin-master' ) }</li>
				<li>{ __( 'Bulk AI generation for your whole media library', 'wp-pin-master' ) }</li>
			</ul>
			<ExternalLink href={ window.pinMasterSettings?.upgradeUrl }>
				{ __( 'Upgrade to Pro', 'wp-pin-master' ) }
			</ExternalLink>
		</CardBody>
	</Card>
);

export default function App() {
	const config = window.pinMasterSettings || {};
	const schema = config.schema || [];
	const isPro = !! config.isPro;

	const [ values, setValues ] = useState( null );
	const [ saving, setSaving ] = useState( false );
	const [ error, setError ] = useState( null );
	const [ snackbar, setSnackbar ] = useState( null );
	const importInput = useRef( null );

	useEffect( () => {
		apiFetch( { path: ENDPOINT } )
			.then( ( response ) => setValues( response.settings || {} ) )
			.catch( ( err ) => setError( err.message || __( 'Failed to load settings.', 'wp-pin-master' ) ) );
	}, [] );

	const save = async ( nextValues = values ) => {
		setSaving( true );
		setError( null );

		try {
			const response = await apiFetch( {
				path: ENDPOINT,
				method: 'POST',
				data: { settings: nextValues },
			} );
			setValues( response.settings || nextValues );
			setSnackbar( __( 'Settings saved.', 'wp-pin-master' ) );
		} catch ( err ) {
			setError( err.message || __( 'Saving failed.', 'wp-pin-master' ) );
		} finally {
			setSaving( false );
			window.setTimeout( () => setSnackbar( null ), 3000 );
		}
	};

	const exportSettings = async () => {
		try {
			const data = await apiFetch( { path: `${ ENDPOINT }/export` } );
			const blob = new Blob( [ JSON.stringify( data, null, 2 ) ], {
				type: 'application/json',
			} );
			const url = URL.createObjectURL( blob );
			const link = document.createElement( 'a' );
			link.href = url;
			link.download = 'wp-pin-master-settings.json';
			link.click();
			URL.revokeObjectURL( url );
		} catch ( err ) {
			setError( err.message || __( 'Export failed.', 'wp-pin-master' ) );
		}
	};

	const importSettings = async ( event ) => {
		const file = event.target.files?.[ 0 ];
		if ( ! file ) {
			return;
		}

		try {
			const parsed = JSON.parse( await file.text() );
			const settings = parsed.settings || parsed;
			const response = await apiFetch( {
				path: `${ ENDPOINT }/import`,
				method: 'POST',
				data: { settings },
			} );
			setValues( response.settings );
			setSnackbar( __( 'Settings imported.', 'wp-pin-master' ) );
			window.setTimeout( () => setSnackbar( null ), 3000 );
		} catch ( err ) {
			setError( err.message || __( 'Import failed — is this a WP Pin Master export file?', 'wp-pin-master' ) );
		} finally {
			event.target.value = '';
		}
	};

	if ( error && ! values ) {
		return <Notice status="error" isDismissible={ false }>{ error }</Notice>;
	}

	if ( ! values ) {
		return (
			<div className="pin-master-loading">
				<Spinner />
			</div>
		);
	}

	const fieldTypes = getFieldTypes();

	const renderField = ( field ) => {
		if ( ! isVisible( field.show_if, values ) ) {
			return null;
		}

		const Renderer = fieldTypes[ field.type ];
		if ( ! Renderer ) {
			return null;
		}

		const locked = !! field.pro && ! isPro;

		return (
			<div className="pin-master-field" key={ field.id }>
				{ locked && (
					<span className="pin-master-pro-badge">{ __( 'Pro', 'wp-pin-master' ) }</span>
				) }
				<Renderer
					field={ field }
					value={ values[ field.id ] ?? field.default }
					disabled={ locked }
					onChange={ ( next ) =>
						setValues( ( prev ) => ( { ...prev, [ field.id ]: next } ) )
					}
				/>
			</div>
		);
	};

	const tabs = schema
		.filter( ( tab ) => ! ( tab.upsell && isPro ) )
		.map( ( tab ) => ( {
			name: tab.id,
			title: tab.title,
			tab,
		} ) );

	return (
		<div className="pin-master-app">
			<header className="pin-master-header">
				<h1>{ config.title || __( 'WP Pin Master', 'wp-pin-master' ) }</h1>
				<div className="pin-master-header-actions">
					<Button variant="tertiary" onClick={ exportSettings }>
						{ __( 'Export', 'wp-pin-master' ) }
					</Button>
					<Button variant="tertiary" onClick={ () => importInput.current?.click() }>
						{ __( 'Import', 'wp-pin-master' ) }
					</Button>
					<input
						ref={ importInput }
						type="file"
						accept="application/json"
						hidden
						onChange={ importSettings }
					/>
					<Button variant="primary" isBusy={ saving } onClick={ () => save() }>
						{ saving ? __( 'Saving…', 'wp-pin-master' ) : __( 'Save Changes', 'wp-pin-master' ) }
					</Button>
				</div>
			</header>

			{ error && (
				<Notice status="error" onRemove={ () => setError( null ) }>
					{ error }
				</Notice>
			) }

			<TabPanel className="pin-master-tabs" tabs={ tabs }>
				{ ( { tab } ) =>
					tab.upsell ? (
						<UpsellTab />
					) : (
						<div className="pin-master-tab-body">
							<Card>
								<CardBody>
									<div className="pin-master-fields">
										{ tab.fields.map( renderField ) }
									</div>
								</CardBody>
							</Card>
							{ tab.id === 'style' && <Preview values={ values } /> }
						</div>
					)
				}
			</TabPanel>

			{ snackbar && <Snackbar className="pin-master-snackbar">{ snackbar }</Snackbar> }
		</div>
	);
}
