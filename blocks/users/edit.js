import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	ToggleControl,
	PanelBody,
	PanelRow,
	Spinner,
} from '@wordpress/components';
import { useSelect, dispatch } from '@wordpress/data';
import {isEmpty} from './utils';

export default function edit( {
	setAttributes,
	attributes: {
		hide,
		api: { url, request },
	},
} ) {
	const mapping = {},
		HeadersEl = [],
		ColsEl = [],
		TogglesEl = [];
	let columns = 0;

	dispatch( 'core' ).addEntities( [
		{
			baseURL: `/${ url }/${ request }/`,
			kind: url,
			name: request,
		},
	] );

	const {
		success,
		data: response,
		isLoading,
		// eslint-disable-next-line react-hooks/rules-of-hooks
	} = useSelect( ( select ) => {
		const args = [ url, request ];
		const response = select( 'core' ).getEntityRecord( ...args ); // eslint-disable-line no-shadow

		return {
			...response,
			isLoading: select( 'core/data' ).isResolving(
				'core',
				'getEntityRecord',
				args
			),
		};
	} );

	if ( false === success ) {
		return (
			<div
				{
					...useBlockProps() /* eslint-disable-line react-hooks/rules-of-hooks */
				}
			>
				<p>{ __( 'API Error', 'kishan-jasani' ) }</p>
			</div>
		);
	}

	if ( isLoading || isEmpty( response ) ) {
		return (
			<div
				{
					...useBlockProps() /* eslint-disable-line react-hooks/rules-of-hooks */
				}
			>
				<p>{ __( 'Loading (…)', 'kishan-jasani' ) }</p>
				<Spinner />
			</div>
		);
	}

	const { headers, rows } = response;

	headers.forEach( ( header, i ) => {
		const firstRow = rows[ Object.keys( rows )[ 0 ] ];
		mapping[ header ] = Object.keys( firstRow )[ i ];
	} );

	columns = Object.keys( headers ).length - hide.length;

	headers.forEach( ( header ) => {
		TogglesEl.push(
			<PanelRow>
				<ToggleControl
					label={ header }
					checked={ ! hide.includes( mapping[ header ] ) }
					onChange={ ( state ) => {
						const newHide = [ ...hide ];
						if ( ! state ) {
							newHide.push( mapping[ header ] );
						} else {
							const i = newHide.indexOf( mapping[ header ] );
							newHide.splice( i, 1 );
						}
						setAttributes( { hide: newHide } );
					} }
				/>
			</PanelRow>
		);

		if ( hide.includes( mapping[ header ] ) ) {
			return;
		}

		HeadersEl.push( <th>{ header }</th> );
	} );

	Object.values( rows ).forEach( ( row ) => {
		const ColumnEl = [];
		Object.keys( row ).forEach( ( key ) => {
			if ( ! hide.includes( key ) ) {
				ColumnEl.push(
					<td>
						<center>{ row[ key ] }</center>
					</td>
				);
			}
		} );
		ColsEl.push( <tr>{ ColumnEl }</tr> );
	} );

	return (
		<div
			{
				...useBlockProps() /* eslint-disable-line react-hooks/rules-of-hooks */
			}
		>
			<InspectorControls key="setting">
				<div id="kishan-jasani-block-controls">
					<PanelBody>{ TogglesEl }</PanelBody>
				</div>
			</InspectorControls>

			<div>
				{ 0 !== columns && (
					<table>
						<thead>
							<tr>{ HeadersEl }</tr>
						</thead>
						<tbody>{ ColsEl }</tbody>
					</table>
				) }
				{ 0 === columns && (
					<h5>{ __( 'No data selected', 'kishan-jasani' ) }</h5>
				) }
			</div>
		</div>
	);
}
