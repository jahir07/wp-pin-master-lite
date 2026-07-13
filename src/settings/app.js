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
} from '@wordpress/components';

import { getFieldTypes, isVisible } from './fields';
import Preview from './preview';

const ENDPOINT = '/pin-master/v1/settings';

const COMPARISON = [
	[ __( 'Pin button on hover', 'wp-pin-master-lite' ), true, true ],
	[ __( 'Button colors, size, position & margins', 'wp-pin-master-lite' ), true, true ],
	[ __( 'Classic & modern button styles', 'wp-pin-master-lite' ), true, true ],
	[ __( 'Pinterest Follow & Board widgets', 'wp-pin-master-lite' ), true, true ],
	[ __( 'Elementor No-Pin control', 'wp-pin-master-lite' ), true, true ],
	[ __( 'Always-visible & touch-device buttons', 'wp-pin-master-lite' ), false, true ],
	[ __( 'Sidebar images or every image on the site', 'wp-pin-master-lite' ), false, true ],
	[ __( 'Icon picker & custom button image', 'wp-pin-master-lite' ), false, true ],
	[ __( 'Custom post type targeting', 'wp-pin-master-lite' ), false, true ],
	[ __( 'Per-image Pinterest description, Repin ID & No Pin', 'wp-pin-master-lite' ), false, true ],
	[ __( 'AI pin descriptions (Claude / OpenAI / Gemini)', 'wp-pin-master-lite' ), false, true ],
	[ __( 'AI alt text from the image itself', 'wp-pin-master-lite' ), false, true ],
	[ __( 'AI hashtag suggestions', 'wp-pin-master-lite' ), false, true ],
	[ __( 'Bulk AI generation for the media library', 'wp-pin-master-lite' ), false, true ],
];

const CompareMark = ( { yes } ) =>
	yes ? (
		<span className="pm-yes" aria-label={ __( 'Included', 'wp-pin-master-lite' ) }>
			✓
		</span>
	) : (
		<span className="pm-no" aria-label={ __( 'Not included', 'wp-pin-master-lite' ) }>
			—
		</span>
	);

const UpsellTab = () => (
	<Card>
		<CardBody>
			<div className="pin-master-upsell-hero">
				<h2>{ __( 'Do more with WP Pin Master Pro', 'wp-pin-master-lite' ) }</h2>
				<p>
					{ __(
						'Everything in the free plugin, plus per-image Pinterest data and AI-generated content.',
						'wp-pin-master-lite'
					) }
				</p>
			</div>
			<table className="pin-master-compare">
				<thead>
					<tr>
						<th>{ __( 'Feature', 'wp-pin-master-lite' ) }</th>
						<th>{ __( 'Free', 'wp-pin-master-lite' ) }</th>
						<th className="pin-master-compare-pro">
							{ __( 'Pro', 'wp-pin-master-lite' ) }
						</th>
					</tr>
				</thead>
				<tbody>
					{ COMPARISON.map( ( [ label, free, pro ] ) => (
						<tr key={ label }>
							<td>{ label }</td>
							<td>
								<CompareMark yes={ free } />
							</td>
							<td className="pin-master-compare-pro">
								<CompareMark yes={ pro } />
							</td>
						</tr>
					) ) }
				</tbody>
			</table>
			<div className="pin-master-upsell-cta">
				<Button
					variant="primary"
					href={ window.pinMasterSettings?.upgradeUrl }
					target="_blank"
					rel="noreferrer noopener"
				>
					{ __( 'Upgrade to Pro', 'wp-pin-master-lite' ) }
				</Button>
				<p>{ __( 'Bring your own AI API key — no extra subscription.', 'wp-pin-master-lite' ) }</p>
			</div>
		</CardBody>
	</Card>
);

export default function App() {
	const config = window.pinMasterSettings || {};
	const schema = config.schema || [];
	const isPro = !! config.isPro;
	const initialTab = new URLSearchParams( window.location.search ).get( 'tab' ) || '';

	const [ values, setValues ] = useState( null );
	const [ saving, setSaving ] = useState( false );
	const [ error, setError ] = useState( null );
	const [ snackbar, setSnackbar ] = useState( null );
	const importInput = useRef( null );

	useEffect( () => {
		apiFetch( { path: ENDPOINT } )
			.then( ( response ) => setValues( response.settings || {} ) )
			.catch( ( err ) => setError( err.message || __( 'Failed to load settings.', 'wp-pin-master-lite' ) ) );
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
			setSnackbar( __( 'Settings saved.', 'wp-pin-master-lite' ) );
		} catch ( err ) {
			setError( err.message || __( 'Saving failed.', 'wp-pin-master-lite' ) );
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
			setError( err.message || __( 'Export failed.', 'wp-pin-master-lite' ) );
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
			setSnackbar( __( 'Settings imported.', 'wp-pin-master-lite' ) );
			window.setTimeout( () => setSnackbar( null ), 3000 );
		} catch ( err ) {
			setError( err.message || __( 'Import failed — is this a WP Pin Master export file?', 'wp-pin-master-lite' ) );
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
					<span className="pin-master-pro-badge">{ __( 'Pro', 'wp-pin-master-lite' ) }</span>
				) }
				<Renderer
					field={ field }
					value={ values[ field.id ] ?? field.default }
					values={ values }
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
				<div className="pin-master-brand">
					<span className="pin-master-brand-mark" aria-hidden="true">
						P
					</span>
					<h1>
						{ config.title || __( 'WP Pin Master', 'wp-pin-master-lite' ) }
						{ config.version && (
							<span className="pin-master-version">v{ config.version }</span>
						) }
					</h1>
				</div>
				<div className="pin-master-header-actions">
					<Button variant="tertiary" onClick={ exportSettings }>
						{ __( 'Export', 'wp-pin-master-lite' ) }
					</Button>
					<Button variant="tertiary" onClick={ () => importInput.current?.click() }>
						{ __( 'Import', 'wp-pin-master-lite' ) }
					</Button>
					<input
						ref={ importInput }
						type="file"
						accept="application/json"
						hidden
						onChange={ importSettings }
					/>
					<Button variant="primary" isBusy={ saving } onClick={ () => save() }>
						{ saving ? __( 'Saving…', 'wp-pin-master-lite' ) : __( 'Save Changes', 'wp-pin-master-lite' ) }
					</Button>
				</div>
			</header>

			{ error && (
				<Notice status="error" onRemove={ () => setError( null ) }>
					{ error }
				</Notice>
			) }

			<TabPanel
				className="pin-master-tabs"
				tabs={ tabs }
				initialTabName={
					tabs.some( ( t ) => t.name === initialTab ) ? initialTab : undefined
				}
			>
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
