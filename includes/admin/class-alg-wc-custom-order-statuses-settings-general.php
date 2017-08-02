<?php
/**
 * Custom Order Statuses for WooCommerce - General Section Settings
 *
 * @version 1.3.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Custom_Order_Statuses_Settings_General' ) ) :

class Alg_WC_Custom_Order_Statuses_Settings_General extends Alg_WC_Custom_Order_Statuses_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'custom-order-statuses-woocommerce' );
		add_action( 'admin_head', array( $this, 'add_admin_styles' ) );
		parent::__construct();
	}

	/**
	 * add_admin_styles.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function add_admin_styles() {
		echo '<style>#alg-button-custom-status-tool { background: #00c28e; border-color: #009967; color: #fff; box-shadow: 0 1px 0 #009967; text-shadow: 0 -1px 1px #009967,1px 0 1px #009967,0 1px 1px #009967,-1px 0 1px #009967; }</style>';
	}

	/**
	 * add_settings.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function add_settings( $settings ) {
		$settings = array_merge(
			array(
				array(
					'title'     => __( 'Custom Order Statuses Options', 'custom-order-statuses-woocommerce' ),
					'type'      => 'title',
					'id'        => 'alg_wc_custom_order_statuses_options',
				),
				array(
					'title'     => __( 'WooCommerce Custom Order Statuses', 'custom-order-statuses-woocommerce' ),
					'desc'      => '<strong>' . __( 'Enable', 'custom-order-statuses-woocommerce' ) . '</strong>',
					'desc_tip'  => __( 'Custom Order Statuses for WooCommerce.', 'custom-order-statuses-woocommerce' ),
					'id'        => 'alg_wc_custom_order_statuses_enabled',
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_custom_order_statuses_options',
				),
				array(
					'title'    => __( 'Custom Statuses', 'custom-order-statuses-woocommerce' ),
					'type'     => 'title',
					'desc'     => sprintf( __( '<a %s href="%s">Custom Order Statuses Tool</a>', 'custom-order-statuses-woocommerce' ),
						'class="button-primary" id="alg-button-custom-status-tool"', admin_url( 'admin.php?page=alg-custom-order-statuses-tool' ) ),
					'id'       => 'alg_orders_custom_statuses_options',
				),
				array(
					'title'    => __( 'Add Custom Statuses to Admin Order Bulk Actions', 'custom-order-statuses-woocommerce' ),
					'desc'     => __( 'Add', 'custom-order-statuses-woocommerce' ),
					'id'       => 'alg_orders_custom_statuses_add_to_bulk_actions',
					'default'  => 'yes',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Add Custom Statuses to Admin Reports', 'custom-order-statuses-woocommerce' ),
					'desc'     => __( 'Add', 'custom-order-statuses-woocommerce' ),
					'id'       => 'alg_orders_custom_statuses_add_to_reports',
					'default'  => 'yes',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Default Order Status', 'custom-order-statuses-woocommerce' ),
					'desc_tip' => __( 'You can change the default order status here. However payment gateways can change this status immediately on order creation. E.g. BACS gateway will change status to On-hold.', 'custom-order-statuses-woocommerce' ) . ' ' .
						__( 'Plugin must be enabled to add custom statuses to the list.', 'custom-order-statuses-woocommerce' ),
					'id'       => 'alg_orders_custom_statuses_default_status',
					'default'  => 'alg_disabled',
					'type'     => 'select',
					'options'  => array_merge( array( 'alg_disabled' => __( 'No changes', 'custom-order-statuses-woocommerce' ) ), alg_get_order_statuses() ),
				),
				array(
					'title'    => __( 'Fallback Delete Order Status', 'custom-order-statuses-woocommerce' ),
					'desc_tip' => __( 'When you delete some custom status with "Custom Order Statuses Tool", all orders with that status will be updated to this fallback status. Please note that all fallback status triggers (email etc.) will be activated.', 'custom-order-statuses-woocommerce' ),
					'id'       => 'alg_orders_custom_statuses_fallback_delete_status',
					'default'  => 'on-hold',
					'type'     => 'select',
					'options'  => array_merge( alg_get_order_statuses(), array( 'alg_none' => __( 'No fallback', 'custom-order-statuses-woocommerce' ) ) ),
				),
				array(
					'title'    => __( 'Add Custom Statuses to Admin Order List Action Buttons', 'custom-order-statuses-woocommerce' ),
					'desc'     => __( 'Add', 'custom-order-statuses-woocommerce' ),
					'id'       => 'alg_orders_custom_statuses_add_to_order_list_actions',
					'default'  => 'no',
					'type'     => 'checkbox',
					'desc_tip' => apply_filters( 'alg_orders_custom_statuses', sprintf( __( 'Get <a href="%s" target="_blank">Custom Order Status for WooCommerce Pro</a> to enable this option.', 'custom-order-statuses-woocommerce' ), 'https://wpcodefactory.com/item/custom-order-status-woocommerce/' ), 'settings' ),
					'custom_attributes' => apply_filters( 'alg_orders_custom_statuses', array( 'disabled' => 'disabled' ), 'settings' ),
				),
				array(
					'desc'     => __( 'Enable Colors', 'custom-order-statuses-woocommerce' ),
					'id'       => 'alg_orders_custom_statuses_add_to_order_list_actions_colored',
					'default'  => 'no',
					'type'     => 'checkbox',
					'desc_tip' => apply_filters( 'alg_orders_custom_statuses', sprintf( __( 'Get <a href="%s" target="_blank">Custom Order Status for WooCommerce Pro</a> to enable this option.', 'custom-order-statuses-woocommerce' ), 'https://wpcodefactory.com/item/custom-order-status-woocommerce/' ), 'settings' ),
					'custom_attributes' => apply_filters( 'alg_orders_custom_statuses', array( 'disabled' => 'disabled' ), 'settings' ),
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_orders_custom_statuses_options',
				),
			),
			$settings
		);
		return $settings;
	}

}

endif;

return new Alg_WC_Custom_Order_Statuses_Settings_General();
