<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class WDP_Rule_Discount_Range_Calculation_Item {
	private $hash;
	private $qty;
	private $price;
	private $processed = false;

	public function __construct( $hash, $singular_price, $qty = 1 ) {
		$this->hash  = $hash;
		$this->price = (float) $singular_price;
		$this->qty   = (int) $qty;
	}

	public function get_hash() {
		return md5( $this->hash . '_' . (string) $this->price );
	}

	public function get_qty() {
		return $this->qty;
	}

	public function get_price() {
		return $this->price;
	}

	public function get_price_for_qty( $qty ) {
		return $this->price * (int) $qty;
	}

	public function get_total_price() {
		return $this->price * $this->qty;
	}

	public function get_as_flat() {
		return array(
			'hash'  => $this->hash,
			'price' => $this->price,
			'qty'   => $this->qty,
		);
	}

	public function set_singular_price( $price ) {
		$this->price = (float) $price;
	}

	public function mark_as_processed() {
		$this->processed = true;
	}

	public function is_processed() {
		return $this->processed;
	}

}