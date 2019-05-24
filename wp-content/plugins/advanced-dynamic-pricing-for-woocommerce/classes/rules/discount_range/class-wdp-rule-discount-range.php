<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class WDP_Rule_Discount_Range {
	private $from;
	private $to;
	private $value;

	/**
	 * Configure the way how calculate discount price
	 * 'total' - apply discount to TOTAL price of items in range
	 * 'every' - apply discount to item price INDIVIDUALLY
	 *
	 * @var string
	 */
	private $apply_type = 'total';

	public function __construct( $from, $to, $value ) {
		$this->from  = $from && $from >= 1 ? (int) $from : 1;
		$this->to    = $to ? (int) $to : PHP_INT_MAX;
		$this->value = (float) $value;
	}

	public function get_value() {
		return $this->value;
	}

	public function get_from() {
		return $this->from;
	}

	public function get_to() {
		return $this->to;
	}

	public function get_qty() {
		return $this->to - $this->from + 1;
	}

	public function is_in( $comparison_value ) {
		return $comparison_value >= $this->from && ( $comparison_value <= $this->to || $this->to == '' );
	}

	public function is_more( $comparison_value ) {
		return $this->to !== '' && $this->to < $comparison_value;
	}

	public function is_less( $comparison_value ) {
		return $this->from > $comparison_value;
	}

	public function apply_price_individually() {
		$this->apply_type = 'individually';
	}

	public function is_apply_to_total_price() {
		return 'total' === $this->apply_type;
	}
}