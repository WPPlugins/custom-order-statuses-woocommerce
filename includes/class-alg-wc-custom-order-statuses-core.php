<?php
/**
 * Custom Order Statuses for WooCommerce - Core Class
 *
 * @version 1.3.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Custom_Order_Statuses_Core' ) ) :

class Alg_WC_Custom_Order_Statuses_Core {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function __construct() {

		if ( 'yes' === get_option( 'alg_wc_custom_order_statuses_enabled', 'yes' ) ) {

			// Tool
			require_once( 'class-alg-wc-custom-order-statuses-tool.php' );

			// Custom Status: Filter, Register, Icons
			add_filter( 'wc_order_statuses',  array( $this, 'add_custom_statuses_to_filter' ), PHP_INT_MAX );
			add_action( 'init',               array( $this, 'register_custom_post_statuses' ) );
			add_action( 'admin_head',         array( $this, 'hook_statuses_icons_css' ), 11 );

			// Default Status
			if ( 'alg_disabled' != get_option( 'alg_orders_custom_statuses_default_status', 'alg_disabled' ) ) {
				add_filter( 'woocommerce_default_order_status', array( $this, 'set_default_order_status' ), PHP_INT_MAX );
			}

			// Reports
			if ( 'yes' === get_option( 'alg_orders_custom_statuses_add_to_reports', 'yes' ) ) {
				add_filter( 'woocommerce_reports_order_statuses', array( $this, 'add_custom_order_statuses_to_reports' ), PHP_INT_MAX );
			}

			// Bulk Actions
			if ( 'yes' === get_option( 'alg_orders_custom_statuses_add_to_bulk_actions', 'yes' ) ) {
				if ( version_compare( get_bloginfo( 'version' ), '4.7' ) >= 0 ) {
					add_filter( 'bulk_actions-edit-shop_order', array( $this, 'register_order_custom_status_bulk_actions' ), PHP_INT_MAX );
				} else {
					add_action( 'admin_footer', array( $this, 'bulk_admin_footer' ), 11 );
				}
			}

			// Admin Order List Actions
			if ( 'yes' === apply_filters( 'alg_orders_custom_statuses', 'no', 'value_order_list_actions' ) ) {
				add_filter( 'woocommerce_admin_order_actions', array( $this, 'add_custom_status_actions_buttons' ), PHP_INT_MAX, 2 );
				add_action( 'admin_head',                      array( $this, 'add_custom_status_actions_buttons_css' ) );
			}

		}
	}

	/**
	 * add_custom_status_actions_buttons.
	 *
	 * @version 1.3.0
	 * @since   1.2.0
	 */
	function add_custom_status_actions_buttons( $actions, $_order ) {
		$statuses = alg_get_custom_order_statuses();
		foreach ( $statuses as $slug => $label ) {
			$custom_order_status = substr( $slug, 3 );
			if ( ! $_order->has_status( array( $custom_order_status ) ) ) { // if order status is not $custom_order_status
				$_order_id = ( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ? $_order->id : $_order->get_id() );
				$actions[ $custom_order_status ] = array(
					'url'    => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=' . $custom_order_status . '&order_id=' . $_order_id ), 'woocommerce-mark-order-status' ),
					'name'   => $label,
					'action' => "view " . $custom_order_status, // setting "view" for proper button CSS
				);
			}
		}
		return $actions;
	}

	/**
	 * add_custom_status_actions_buttons_css.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function add_custom_status_actions_buttons_css() {
		$statuses = alg_get_custom_order_statuses();
		foreach ( $statuses as $slug => $label ) {
			$custom_order_status = substr( $slug, 3 );
			if ( '' != ( $icon_data = get_option( 'alg_orders_custom_status_icon_data_' . $custom_order_status, '' ) ) ) {
				$content = $icon_data['content'];
				$color   = $icon_data['color'];
			} else {
				$content = 'e011';
				$color   = '#999999';
			}
			$color_style = ( 'yes' === apply_filters( 'alg_orders_custom_statuses', 'no', 'value_order_list_actions_colored' ) ) ? ' color: ' . $color . ' !important;' : '';
			echo '<style>.view.' . $custom_order_status . '::after { font-family: WooCommerce !important;' . $color_style . ' content: "\\' . $content . '" !important; }</style>';
		}
	}

	/**
	 * add_custom_order_statuses_to_reports.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_custom_order_statuses_to_reports( $order_statuses ) {
		if ( is_array( $order_statuses ) && in_array( 'completed', $order_statuses ) ) {
			$custom_order_statuses = get_option( 'alg_orders_custom_statuses_array', array() );
			if ( ! empty( $custom_order_statuses ) && is_array( $custom_order_statuses ) ) {
				foreach ( $custom_order_statuses as $slug => $label ) {
					$order_statuses[] = substr( $slug, 3 );
				}
			}
		}
		return $order_statuses;
	}

	/**
	 * set_default_order_status.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function set_default_order_status() {
		return get_option( 'alg_orders_custom_statuses_default_status', 'alg_disabled' );
	}

	/**
	 * register_custom_post_statuses.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function register_custom_post_statuses() {
		$alg_orders_custom_statuses_array = alg_get_custom_order_statuses();
		foreach ( $alg_orders_custom_statuses_array as $slug => $label )
			register_post_status( $slug, array(
				'label'                     => $label,
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( $label . ' <span class="count">(%s)</span>', $label . ' <span class="count">(%s)</span>' ),
			) );
	}

	/**
	 * add_custom_statuses_to_filter.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function add_custom_statuses_to_filter( $order_statuses ) {
		$alg_orders_custom_statuses_array = alg_get_custom_order_statuses();
		$order_statuses = ( '' == $order_statuses ) ? array() : $order_statuses;
		return array_merge( $order_statuses, $alg_orders_custom_statuses_array );
	}

	/**
	 * hook_statuses_icons_css.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function hook_statuses_icons_css() {
		$output = '<style>';
		$statuses = alg_get_custom_order_statuses();
		foreach( $statuses as $status => $status_name ) {
			if ( '' != ( $icon_data = get_option( 'alg_orders_custom_status_icon_data_' . substr( $status, 3 ), '' ) ) ) {
				$content = $icon_data['content'];
				$color   = $icon_data['color'];
			} else {
				$content = 'e011';
				$color   = '#999999';
			}
			$output .= 'mark.' . substr( $status, 3 ) . '::after { content: "\\' . $content . '"; color: ' . $color . '; }';
			$output .= 'mark.' . substr( $status, 3 ) . ':after {font-family:WooCommerce;speak:none;font-weight:400;font-variant:normal;text-transform:none;line-height:1;-webkit-font-smoothing:antialiased;margin:0;text-indent:0;position:absolute;top:0;left:0;width:100%;height:100%;text-align:center}';
		}
		$output .= '</style>';
		echo $output;
	}

	/**
	 * register_order_custom_status_bulk_actions.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 * @see     https://make.wordpress.org/core/2016/10/04/custom-bulk-actions/
	 */
	function register_order_custom_status_bulk_actions( $bulk_actions ) {
		$custom_order_statuses = get_option( 'alg_orders_custom_statuses_array', array() );
		if ( ! empty( $custom_order_statuses ) && is_array( $custom_order_statuses ) ) {
			foreach ( $custom_order_statuses as $slug => $label ) {
				$bulk_actions[ 'mark_' . substr( $slug, 3 ) ] = __( 'Mark', 'custom-order-statuses-woocommerce' ) . ' ' . $label;
			}
		}
		return $bulk_actions;
	}

	/**
	 * Add extra bulk action options to mark orders as complete or processing
	 *
	 * Using Javascript until WordPress core fixes: http://core.trac.wordpress.org/ticket/16031
	 * Fixed in WordPress v4.7
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function bulk_admin_footer() {
		global $post_type;
		if ( 'shop_order' == $post_type ) {
			?><script type="text/javascript"><?php
			foreach( alg_get_order_statuses() as $key => $order_status ) {
				if ( in_array( $key, array( 'processing', 'on-hold', 'completed', ) ) ) continue;
				?>jQuery(function() {
					jQuery('<option>').val('mark_<?php echo $key; ?>').text('<?php echo __( 'Mark', 'custom-order-statuses-woocommerce' ) . ' ' . $order_status; ?>').appendTo('select[name="action"]');
					jQuery('<option>').val('mark_<?php echo $key; ?>').text('<?php echo __( 'Mark', 'custom-order-statuses-woocommerce' ) . ' ' . $order_status; ?>').appendTo('select[name="action2"]');
				});<?php
			}
			?></script><?php
		}
	}

}

endif;

return new Alg_WC_Custom_Order_Statuses_Core();
