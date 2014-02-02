<?
/*
Plugin Name: WooCommerce & Gravity Forms Customize Quantity (list add-on)
Description: Remove the ability to change Quantity from the cart page and hook custom Gravity Form list items to the quantity
Version: 0.2
Author: Timothy Wood @codearachnid
Author URI: http://www.codearachnid.com
GitHub Theme URI: codearachnid/woocommerce-gravityforms-list-quantity
GitHub Branch:    master
License: GPL V.3


    Copyright 2014  Imagine Simplicity

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 3, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action( 'wp_head', 'woogf_list_quantity_enqueue_script');
function woogf_list_quantity_enqueue_script(){
	$plugin_dir_url = plugin_dir_url(__FILE__);
	wp_enqueue_script( 'woogf_list_quantity', $plugin_dir_url . 'script.js', array('jquery'), '0.1', true );
}

add_action( 'woocommerce_product_options_general_product_data', 'woogf_list_quantity_add_prevent_quantity_fields' );
function woogf_list_quantity_add_prevent_quantity_fields() {

	global $woocommerce, $post;

	echo '<div class="options_group">';

	woocommerce_wp_checkbox(array( 
		'id'            => 'woogf_list_quantity_prevent_quantity_change', 
		'wrapper_class' => 'show_if_simple', 
		'label'         => __('Lock Quantity', 'woogf_list_quantity' ), 
		'description'   => __( 'Prevent quantity changing on the cart.', 'woogf_list_quantity' ) 
		));

	echo '</div>';

}

add_action( 'woocommerce_process_product_meta', 'woogf_list_quantity_save_prevent_quantity_fields' );
function woogf_list_quantity_save_prevent_quantity_fields($post_id){
	update_post_meta( $post_id, 'woogf_list_quantity_prevent_quantity_change', isset( $_POST['woogf_list_quantity_prevent_quantity_change'] ) ? 'yes' : 'no' );
}


add_filter( 'woocommerce_cart_item_quantity', 'woogf_list_quantity_cart_item_quantity', 20, 2 );
function woogf_list_quantity_cart_item_quantity( $html, $cart_item_key ){
	global $woocommerce;
	$cart_contents = $woocommerce->cart->get_cart();
	$product = $cart_contents[ $cart_item_key ];
	if( get_post_meta( $product['product_id'], 'woogf_list_quantity_prevent_quantity_change', true ) == 'yes' ){
		return $product['quantity']; //sprintf('<div class="quantity">%s</div>', $product['quantity'] );
	} else {
		return $html;
	}
}
