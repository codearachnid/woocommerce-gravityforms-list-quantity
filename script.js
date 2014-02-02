/**
 * attach listener to the Gravity Forms list icons to increment/decrement product quantity
 * @param  object event
 * @return null
 */
jQuery(document).on( 'click', '.gfield_list_icons img', function( event ){
	var element = jQuery(".quantity input[type='number'].qty");
	var quantity = element.val(); // retrieve the current quantity from the product

	// increment quantity
	if( jQuery( this ).hasClass('add_list_item') )
		quantity++;

	// decrement quantity
	if( jQuery( this ).hasClass('delete_list_item') )
		quantity--;

	// set the new quantity to the form (if hidden we have to set attribute)
	if( element.is(":visible")){
		element.val( quantity );
	} else {
		element.attr('value', quantity);
	}
});

jQuery(document).ready(function(){
	// remove increment buttons
	jQuery(".quantity.buttons_added").removeClass('buttons_added').find("input[type='button']").remove();
	jQuery(".quantity input[type='number'].qty").hide();
	//jQuery(".quantity input[type='number'].qty").attr( 'disabled', true );//attr('type','hidden');
});