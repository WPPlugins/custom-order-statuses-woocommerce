<?php
/**
 * Custom Order Statuses for WooCommerce - Functions
 *
 * @version 1.3.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'alg_get_order_statuses' ) ) {
	/**
	 * alg_get_order_statuses.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function alg_get_order_statuses() {
		$result = array();
		$statuses = function_exists( 'wc_get_order_statuses' ) ? wc_get_order_statuses() : array();
		foreach( $statuses as $status => $status_name ) {
			$result[ substr( $status, 3 ) ] = $status_name;
		}
		return $result;
	}
}

if ( ! function_exists( 'alg_get_custom_order_statuses' ) ) {
	/**
	 * alg_get_custom_order_statuses.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function alg_get_custom_order_statuses() {
		return ( '' == get_option( 'alg_orders_custom_statuses_array', array() ) ) ? array() : get_option( 'alg_orders_custom_statuses_array', array() );
	}
}

if ( ! function_exists( 'alg_get_table_html' ) ) {
	/**
	 * alg_get_table_html.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function alg_get_table_html( $data, $args = array() ) {
		$defaults = array(
			'table_class'        => '',
			'table_style'        => '',
			'row_styles'         => '',
			'table_heading_type' => 'horizontal',
			'columns_classes'    => array(),
			'columns_styles'     => array(),
		);
		$args = array_merge( $defaults, $args );
		extract( $args );
		$table_class = ( '' == $table_class ) ? '' : ' class="' . $table_class . '"';
		$table_style = ( '' == $table_style ) ? '' : ' style="' . $table_style . '"';
		$row_styles  = ( '' == $row_styles )  ? '' : ' style="' . $row_styles  . '"';
		$html = '';
		$html .= '<table' . $table_class . $table_style . '>';
		$html .= '<tbody>';
		foreach( $data as $row_number => $row ) {
			$html .= '<tr' . $row_styles . '>';
			foreach( $row as $column_number => $value ) {
				$th_or_td = ( ( 0 === $row_number && 'horizontal' === $table_heading_type ) || ( 0 === $column_number && 'vertical' === $table_heading_type ) ) ? 'th' : 'td';
				$column_class = ( ! empty( $columns_classes ) && isset( $columns_classes[ $column_number ] ) ) ? ' class="' . $columns_classes[ $column_number ] . '"' : '';
				$column_style = ( ! empty( $columns_styles ) && isset( $columns_styles[ $column_number ] ) ) ? ' style="' . $columns_styles[ $column_number ] . '"' : '';

				$html .= '<' . $th_or_td . $column_class . $column_style . '>';
				$html .= $value;
				$html .= '</' . $th_or_td . '>';
			}
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		return $html;
	}
}
