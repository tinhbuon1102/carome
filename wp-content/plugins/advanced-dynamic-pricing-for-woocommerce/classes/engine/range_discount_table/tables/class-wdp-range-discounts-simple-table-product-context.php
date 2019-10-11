<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Range_Discounts_Simple_Table_Product_Context extends WDP_Range_Discounts_Table_Product_Context_Abstract {
	public function get_table_header() {
		$table_header = array();

		foreach ( $this->ranges as $index => $line ) {
			if ( $line['from'] == $line['to'] ) {
				$value = $line['from'];
			} else {
				if ( empty( $line['to'] ) ) {
					$value = $line['from'] . ' +';
				} else {
					$value = $line['from'] . ' - ' . $line['to'];
				}
			}

			$table_header[ $index ] = apply_filters( 'wdp_format_bulk_record', $value, $line );
		}

		return $table_header;
	}

	/**
	 * @param WDP_Price_Display $price_display
	 * @param array             $table_header
	 *
	 * @return array
	 */
	public function get_table_rows( $price_display, $table_header ) {
		$row = array();
		foreach ( array_keys( $table_header ) as $index ) {
			$line = $this->ranges[ $index ];

			$product = WDP_Object_Cache::get_instance()->get_wc_product( $this->object_id );
			if ( $product->is_type( 'variable' ) ) {
				$row[ $index ] = "-";
				continue;
			}

			$wdp_product = $price_display->process_product( $product, (float) $line['from'] );
			$value       = ! is_null( $wdp_product ) ? wc_price( $wdp_product->get_new_price() ) : "-";

			$row[ $index ] = $value;
		}

		return array( $row );
	}
}