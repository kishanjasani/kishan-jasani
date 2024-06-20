import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import domReady from '@wordpress/dom-ready';
import $ from 'jquery'; // eslint-disable-line import/no-unresolved

import './scss/admin.scss';

const selectors = {
	btn: '#kj-refresh',
	table: '.kj-page table',
};

const API_PATH = 'kishan-jasani/v1/users';

/**
 * Retrieves data from REST API.
 *
 * @return {Object} API Data.
 */
const getAPIData = async () => {
	let resp = null;

	try {
		resp = await apiFetch( { path: API_PATH } );
	} catch ( e ) {
		resp = {
			success: false,
			error: e.message,
		};
	}

	return resp;
};

/**
 * Finds button element.
 *
 * @param {Object} element HTML element.
 * @return {Object} Button element.
 */
const findBtn = ( element ) => {
	element = $( element );
	return element.closest( '.kj-page__refresh' ).find( selectors.btn );
};

/**
 * Creates notice element for table refresh.
 *
 * @param {string}  message Message to show as notice.
 * @param {string}  classes Classes for notice element, except ".notice"
 * @param {boolean} remove  Whether to remove current notices.
 */
const createNotice = ( message, classes, remove = false ) => {
	$( selectors.table ).find( '.notice' ).remove();
	if ( remove ) {
		return;
	}

	const isDismissible = classes.includes( 'is-dismissible' );
	const noticeElem = $( '<div>', {
		class: 'notice ' + classes,
	} );
	const noticeData = $( '<p>', {} );

	noticeData.text( message );
	noticeElem.append( noticeData );

	if ( isDismissible ) {
		const button = $( '<button>', {
			type: 'button',
			class: 'notice-dismiss',
		} );
		button.on( 'click', ( e ) => {
			$( e.target ).closest( '.notice' ).remove();
		} );
		noticeElem.append( button );
	}

	$( selectors.table ).find( 'caption' ).append( noticeElem );

	/**
	 * Remove notice after 3 seconds.
	 */
	setTimeout( () => {
		noticeElem.remove();
	}, 3000 );
};

/**
 * Enables/Disables HTML element.
 *
 * @param {Object}  element HTML Element.
 * @param {boolean} disable Whether to disable the element.
 */
const disableElement = ( element, disable ) => {
	const btn = findBtn( element );
	if ( ! btn.length ) {
		return;
	}

	btn.prop( 'disabled', disable );

	const btnIcon = 'span.dashicons';
	const rotateIcon = 'dashicons-image-rotate';
	const ellipsisIcon = 'dashicons-ellipsis';

	if ( disable ) {
		btn.find( btnIcon ).removeClass( rotateIcon ).addClass( ellipsisIcon );
	} else {
		btn.find( btnIcon ).removeClass( ellipsisIcon ).addClass( rotateIcon );
	}
};

/**
 * Checks whether an element is disabled or not.
 *
 * @param {Object} element HTML Element.
 * @return {boolean} Is element disabled.
 */
const isDisabled = ( element ) => {
	const btn = findBtn( element );
	return btn.length && true === btn.prop( 'disabled' );
};

/**
 * Handles refresh button click event.
 *
 * @param {Object} e Event
 */
const handleClick = async ( e ) => {
	if ( isDisabled( e.target ) ) {
		return;
	}
	disableElement( e.target, true );
	createNotice( __( 'Fetching…', 'kishan-jasani' ), 'notice-info' );

	const table = $( selectors.table );
	if ( ! table.length ) {
		createNotice(
			__( 'Something went wrong! Data table not found!', 'kishan-jasani' ),
			'notice-error'
		);
		disableElement( e.target, false );
		return;
	}

	const { success, error, data } = await getAPIData();

	if ( ! success ) {
		if ( error ) {
			createNotice( error, 'notice-error' );
		} else {
			createNotice(
				__( 'Something went wrong! Data not found!', 'kishan-jasani' ),
				'notice-error'
			);
		}
		disableElement( e.target, false );
		return;
	}

	const { headers, rows } = data;
	const thead = table.find( 'thead > tr' );
	const tbody = table.find( 'tbody' );

	thead.html( '' );
	tbody.html( '' );

	headers.forEach( ( header ) => {
		const th = $( '<th>', {} );
		th.text( header );
		thead.append( th );
	} );

	Object.values( rows ).forEach( ( row ) => {
		const tr = $( '<tr>', {} );
		Object.values( row ).forEach( ( value ) => {
			const td = $( '<td>', {} );
			const center = $( '<center>', {} );

			center.text( value );
			td.append( center );
			tr.append( td );
		} );
		tbody.append( tr );
	} );

	createNotice(
		__( 'Cache Refreshed!', 'kishan-jasani' ),
		'notice-success is-dismissible'
	);
	disableElement( e.target, false );
};

/**
 * DOM Ready function.
 */
domReady( () => {
	$( selectors.btn ).on( 'click', handleClick );
} );
