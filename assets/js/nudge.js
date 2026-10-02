/**
 * Nudge, free-shipping progress bar (storefront, dependency-free).
 *
 * WooCommerce re-renders the cart/checkout totals (and therefore our bar) on its
 * own when the cart changes, firing `updated_cart_totals` / `updated_checkout`
 * on document.body. The fresh server markup already carries the correct width
 * and message, so the bar is always accurate without JS. This script's only job
 * is polish: after each update it nudges the fill from its previous width to the
 * new one so the change animates smoothly instead of snapping.
 *
 * No jQuery dependency of our own: it uses the jQuery WooCommerce already
 * loaded, because jQuery-triggered events do not reach native listeners. Honours
 * prefers-reduced-motion by skipping the re-animation entirely.
 */
( function () {
	'use strict';

	var prefersReducedMotion =
		window.matchMedia &&
		window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/**
	 * Re-trigger the width transition on every bar currently in the DOM. The
	 * server has already set the target width inline; we momentarily reset to 0
	 * and then restore on the next frame so the CSS transition runs.
	 */
	function animateBars() {
		if ( prefersReducedMotion ) {
			return;
		}

		var bars = document.querySelectorAll( '[data-nudge]' );

		bars.forEach( function ( bar ) {
			if ( bar.getAttribute( 'data-nudge-animated' ) === '1' ) {
				return;
			}
			bar.setAttribute( 'data-nudge-animated', '1' );

			var fill = bar.querySelector( '[data-nudge-fill]' );
			if ( ! fill ) {
				return;
			}

			var target = fill.style.width || '0%';
			fill.style.width = '0%';

			// Force reflow so the browser registers the 0% start, then animate.
			// eslint-disable-next-line no-unused-expressions
			fill.offsetWidth;

			window.requestAnimationFrame( function () {
				fill.style.width = target;
			} );
		} );
	}

	function onUpdate() {
		// Markup is replaced by WC on update, so previously-marked bars are gone;
		// the fresh ones animate in from 0.
		animateBars();
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', animateBars );
	} else {
		animateBars();
	}

	// WooCommerce fires these through jQuery's trigger(), which never reaches a
	// native addEventListener for custom events, so bind through jQuery when
	// it is there (WooCommerce's cart and checkout scripts load it).
	var events = [ 'updated_cart_totals', 'updated_checkout', 'updated_shipping_method' ];
	if ( window.jQuery ) {
		window.jQuery( document.body ).on( events.join( ' ' ), onUpdate );
	} else {
		events.forEach( function ( name ) {
			document.body.addEventListener( name, onUpdate );
		} );
	}
} )();
