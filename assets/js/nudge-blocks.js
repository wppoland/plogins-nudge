/**
 * Shiplume, progress bar for the Cart and Checkout blocks (no build, no jQuery).
 *
 * The blocks never reload the page: they talk to the Store API and keep the
 * cart in the wc/store/cart data store. Every cart response carries the
 * server-rendered bar for each placement under extensions.shiplume, so this
 * script never computes an amount. It watches the store and, when the markup
 * for a placement changes, copies the new message, width and state into the
 * bar already on the page. Updating in place (instead of replacing the node)
 * keeps the role="status" region, so screen readers announce the new message.
 */
( function () {
	'use strict';

	var data = window.wp && window.wp.data;
	if ( ! data ) {
		return;
	}

	var last = {};

	function apply( slot, html ) {
		var current = slot.querySelector( '[data-nudge]' );
		var tpl = document.createElement( 'template' );
		tpl.innerHTML = html;
		var next = tpl.content.querySelector( '[data-nudge]' );

		// Shown, hidden, or a different structure (tier markers): swap whole.
		if ( ! current || ! next || current.querySelectorAll( '*' ).length !== next.querySelectorAll( '*' ).length ) {
			slot.innerHTML = html;
			return;
		}

		current.className = next.className;
		current.querySelector( '[data-nudge-message]' ).innerHTML = next.querySelector( '[data-nudge-message]' ).innerHTML;

		var track = current.querySelector( '[role="progressbar"]' );
		track.setAttribute( 'aria-valuenow', next.querySelector( '[role="progressbar"]' ).getAttribute( 'aria-valuenow' ) );

		// The CSS transition on the fill animates the width change.
		current.querySelector( '[data-nudge-fill]' ).style.width = next.querySelector( '[data-nudge-fill]' ).style.width;
	}

	function update() {
		var store = data.select( 'wc/store/cart' );
		if ( ! store || ! store.getCartData ) {
			return;
		}

		var ext = ( store.getCartData().extensions || {} ).shiplume;
		if ( ! ext ) {
			return;
		}

		document.querySelectorAll( '[data-nudge-slot]' ).forEach( function ( slot ) {
			var placement = slot.getAttribute( 'data-nudge-slot' );
			var html = typeof ext[ placement ] === 'string' ? ext[ placement ] : null;

			if ( html === null || html === last[ placement ] ) {
				return;
			}

			// The first payload matches what the server printed; only record it.
			if ( placement in last ) {
				apply( slot, html );
			}
			last[ placement ] = html;
		} );
	}

	data.subscribe( update );
	update();
} )();
