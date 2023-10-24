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
			}, 100);

		});



		// Rebind apply coupon input to form submit event
		$('.woocommerce').on('click', '#apply_code', function (e) {
			e.preventDefault();

			$(this).attr('clicked', true);
			let form = $('#' + $(this).attr('form'));
			form.trigger('submit');
		});




		// Update gift option on cart
		$(document).on('click', 'a.submit_message', function(e) {
			e.preventDefault();
			var data_id = $(this).attr('data-id');
			var product_key = $(this).closest('div[data-product-key]').attr('data-product-key');
			var product_id = $(this).closest('div[data-product-key]').attr('data-product-id');
			var product_meta_id = $('textarea[data-id="'+ data_id + '"').attr('data-meta-id');
			var gift_message = $('textarea[data-id="'+ data_id + '"').val();
			var data = {
				'action': 'menstate_update_gift_message',
				'product_id': product_id,
				'product_key': product_key,
				'product_meta_id': product_meta_id,
				'value': gift_message
			}

			block( $('#woocommerce-cart-form') );
			$.post(ajaxurl, data, function(response){
				// Submit form
				unblock( $('#woocommerce-cart-form') );
				$("[name='update_cart']").prop("disabled", false);
				$("[name='update_cart']").trigger("click");
			});
		});

		// delete gift option on delete link clicked
		$(document).on('click', 'a.delete_gift', function (e) {
			e.preventDefault();
			var group_id = $(this).attr('data-group-id');
			var product_key = $(this).closest('div[data-product-key]').attr('data-product-key');
			var data = {
				'action': 'menstate_delete_gift_option',
				'product_key': product_key,
				'group_id': group_id
			};
			block( $('#woocommerce-cart-form') );
			$.post(ajaxurl, data, function(response){
				// Submit form
				unblock( $('#woocommerce-cart-form') );
				$("[name='update_cart']").prop("disabled", false);
				$("[name='update_cart']").trigger("click");
			});
		});

		// activate gift option on radio button checked
		$(document).on('change', 'input.custom_radio.gift_option', function (e) {
			if ($(this).is(':checked')) {
				var product_key = $(this).closest('div[data-product-key]').attr('data-product-key');
				var product_metas = $.parseJSON($(this).attr('data-meta'));
				var data = {
					'action': 'menstate_add_gift_option',
					'product_key': product_key,
					...product_metas
				};
				block( $('#woocommerce-cart-form') );
				$.post(ajaxurl, data, function(response){
					// Submit form
					unblock( $('#woocommerce-cart-form') );
					$("[name='update_cart']").prop("disabled", false);
					$("[name='update_cart']").trigger("click");
				});
			}
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
