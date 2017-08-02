<?php
/**
 * Custom Order Statuses for WooCommerce - Section Settings
 *
 * @version 1.3.1
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Custom_Order_Statuses_Settings_Section' ) ) :

class Alg_WC_Custom_Order_Statuses_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		add_filter( 'woocommerce_get_sections_alg_wc_custom_order_statuses',              array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_alg_wc_custom_order_statuses_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
		add_action( 'init', array( $this, 'add_settings_hook' ) );
	}

	/**
	 * add_settings_hook.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_settings_hook() {
		add_filter( 'alg_custom_order_statuses_settings_' . $this->id, array( $this, 'add_settings' ) );
	}

	/**
	 * get_settings.
	 *
	 * @version 1.3.1
	 * @since   1.0.0
	 */
	function get_settings( $settings = array() ) {
		return apply_filters( 'alg_custom_order_statuses_settings_' . $this->id, $settings );
	}

	/**
	 * settings_section.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

}

endif;
