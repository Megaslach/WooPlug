(function( $ ) {
	'use strict';

	$(function() {


		// Make minus and plus button fonctionnal
		$('.woocommerce').on('click', 'input.qty_button', function (e) {
			e.preventDefault();
			var incrementValue = $(this).hasClass('plus') ? 1 : -1;
			// Get the field name
			var fieldId = $(this).attr('data-field');
			var currentInput = $('input[id=' + fieldId + ']');
			// Get its current value
			var currentVal = parseInt(currentInput.val());
			var maxVal = parseInt(currentInput.attr('max'));
			var minVal = parseInt(currentInput.attr('min'));
			// If is not undefined
			if (isNaN(currentVal)){
				currentInput.val(0);
			} else if ((currentVal + incrementValue) > minVal && !maxVal) {
				// with no max limit
				currentInput.val(currentVal + incrementValue);
			} else if ((currentVal + incrementValue) > maxVal){
				// Set max value in case current value is over
				currentInput.val(maxVal);
			} else if ((currentVal + incrementValue) < minVal) {
				// Set min value in case current value is under
				currentInput.val(minVal);
			} else if (
				(currentVal + incrementValue) >= minVal &&
				(currentVal + incrementValue) <= maxVal) {
				// between allowed limits
				currentInput.val(currentVal + incrementValue);
			}
			currentInput.trigger('change');
		});

		// Ajax update cart on quantity change
		var timeout;
		$('.woocommerce').on('change', 'input.qty', function(){

			if ( timeout !== undefined ) {
				clearTimeout( timeout );
			}

			timeout = setTimeout(function() {
				$("[name='update_cart']").trigger("click");
			}, 1000 );

		});

		// Rebind apply coupon input to form submit event
		$('.woocommerce').on('click', '#apply_code', function (e) {
			e.preventDefault();

			$(this).attr('clicked', true);
			let form = $('#' + $(this).attr('form'));
			form.trigger('submit');
		});

		// Add flags in select for choosen country
		change_select_flag_image($('#calc_shipping_country'));

		$(document).on('change', '#calc_shipping_country', function (e) {
			change_select_flag_image(this);
		});

		$( document.body ).on( 'updated_cart_totals', function(){
			change_select_flag_image($('#calc_shipping_country'));
		});

		// Make checkout a two steps
		display_correct_checkout_step();

		$(document.body).on('updated_checkout', function () {
			display_correct_checkout_step();
		});

		$(document).on('click', 'div.checkout_steps > a', function(e) {
			e.preventDefault();
			var next_step = $(this).attr('data-step');
			$(`[data-display]:not([data-display="${next_step}"])`).slideToggle(500);
			$(`[data-display="${next_step}"]`).slideToggle(500);
			window.location.hash = `#${next_step}`;
		});

	});

	function change_select_flag_image(element) {
		var flag = $('option:selected', element).attr('data-image');
		$('.calc_shipping_country_flag').attr('src', flag);
	}

	function display_correct_checkout_step() {
		var current_step = $(location).attr('hash').substr(1);
		if (current_step && $(`[data-display="${current_step}"]`).length) {
			$(`[data-display]:not([data-display="${current_step}"])`).hide();
			$(`[data-display="${current_step}"]`).show();

		}

	}

	// Use same logic than woocommerce scripts
	var is_blocked = function( $node ) {
		return $node.is( '.processing' ) || $node.parents( '.processing' ).length;
	};

	var block = function( $node ) {
		if ( ! is_blocked( $node ) ) {
			$node.addClass( 'processing' ).block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			} );
		}
	};

	var unblock = function( $node ) {
		$node.removeClass( 'processing' ).unblock();
	};

})( jQuery );
