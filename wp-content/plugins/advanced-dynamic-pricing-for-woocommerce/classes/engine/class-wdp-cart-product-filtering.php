<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Product_Filtering {

	protected $type;
	protected $values;
	protected $method;

	protected $cached_parents = array();

	public function prepare( $operation_type, $operation_values, $operation_method ) {
		$this->type   = $operation_type;
		$this->values = $operation_values;
		$this->method = ! empty( $operation_method ) ? $operation_method : 'in_list';
	}

	public function set_type( $operation_type ) {
		$this->type = $operation_type;
	}

	public function is_type($type) {
		return $type === $this->type;
	}

	public function set_operation_values( $operation_values ) {
		$this->values = $operation_values;
	}

	public function set_method( $operation_method ) {
		$this->method = $operation_method;
	}

	/**
	 * @param $product WC_Product
	 *
	 * @return false|WC_Product|null
	 */
	protected function get_main_product( $product ) {
		if ( ! $product->get_parent_id() ) {
			return $product;
		}

		return WDP_Object_Cache::get_instance()->get_wc_product( $product->get_parent_id() );
	}

	/**
	 * @param WC_Product $product
	 * @param array          $cart_item
	 *
	 * @return boolean
	 */
	public function check_product_suitability( $product, $cart_item = array() ) {
		if ( $this->method === 'any' ) {
			return true;
		}

		if ( ! count( $this->values ) ) {
			return false;
		}
		$func = array( $this, "compare_product_with_{$this->type}" );

		if ( is_callable($func) ) {
			return call_user_func( $func, $product, $cart_item );
		} elseif ( in_array( $this->type, array_keys( WDP_Helpers::get_custom_product_taxonomies() ) ) ) {
			return $this->compare_product_with_custom_taxonomy($product, $cart_item);
		}

		return false;
	}

	protected function compare_product_with_products( $product, $cart_item ) {
		$result = false;
		$product_parent = $this->get_main_product( $product );

		if ( 'in_list' === $this->method ) {
			$result = ( in_array( $product->get_id(), $this->values ) OR in_array( $product_parent->get_id(), $this->values ) );
		} elseif ( 'not_in_list' === $this->method ) {
			$result = ! ( in_array( $product->get_id(), $this->values ) OR in_array( $product_parent->get_id(), $this->values ) );
		} elseif ( 'any' === $this->method ) {
			$result = true;
		}

		return $result;
	}

	protected function compare_product_with_product_categories( $product, $cart_item ) {
		$product = $this->get_main_product( $product );
		$categories = $product->get_category_ids();

		$values = array();
		foreach ( $this->values as $value ) {
			$values[] = $value;
			$child    = get_term_children( $value, 'product_cat' );

			if ( $child && ! is_wp_error( $child ) ) {
				$values = array_merge( $values, $child );
			}
		}

		$is_product_in_category = count( array_intersect( $categories, $values ) ) > 0;

		if ( 'in_list' === $this->method ) {
			return $is_product_in_category;
		} elseif ( 'not_in_list' === $this->method ) {
			return ! $is_product_in_category;
		}

		return false;
	}

	protected function compare_product_with_product_category_slug( $product, $cart_item) {
		$product = $this->get_main_product( $product );
		$category_slugs = array_map(function($category_id) {
			$term = get_term( $category_id, 'product_cat' );
			return $term ? $term->slug : '';
		}, $product->get_category_ids());

		$is_product_in_category = count( array_intersect( $category_slugs, $this->values ) ) > 0;

		if ( 'in_list' === $this->method ) {
			return $is_product_in_category;
		} elseif ( 'not_in_list' === $this->method ) {
			return ! $is_product_in_category;
		}

		return false;
	}

	protected function compare_product_with_product_tags( $product, $cart_item) {
		$product = $this->get_main_product( $product );
		$tag_ids = $product->get_tag_ids();

		$is_product_has_tag = count( array_intersect( $tag_ids, $this->values ) ) > 0;

		if ( 'in_list' === $this->method ) {
			return $is_product_has_tag;
		} elseif ( 'not_in_list' === $this->method ) {
			return ! $is_product_has_tag;
		}

		return false;
	}

	/**
	 * @param $product WC_Product
	 * @param $cart_item array
	 *
	 * @return bool
	 */
	protected function compare_product_with_product_attributes( $product, $cart_item) {
//		$product = $this->get_cached_wc_product( $product ); // use variation attributes?
		$attrs = $product->get_attributes();

		$calculated_term_obj = array();
		$term_attr_ids       = array(
			'empty' => array(),
		);

		$attr_ids    = array();
		$attr_custom = array();

		if ( $product->is_type( 'variation' ) ) {
			if ( count( array_filter( $attrs ) ) < count( $attrs ) ) {
				if ( isset( $cart_item['variation'] ) ) {
					$attrs = array();
					foreach ( $cart_item['variation'] as $attribute_name => $value ) {
						$attrs[ str_replace( 'attribute_', '', $attribute_name ) ] = $value;
					}
				}
			}

			$product_variable = $this->get_main_product( $product );
			$attrs_variable   = $product_variable->get_attributes();

			foreach ( $attrs as $attribute_name => $value ) {
				$init_attribute_name = $attribute_name;
				$attribute_name      = $this->attribute_taxonomy_slug( $attribute_name );
				if ( $value ) {
					$term_obj = get_term_by( 'slug', $value, $init_attribute_name );
					if ( ! is_wp_error( $term_obj ) && $term_obj && $term_obj->name ) {
						$attr_ids[ $attribute_name ] = (array)($term_obj->term_id);
					} else {
						$attr_custom[ $attribute_name ] = (array) ( $value );
					}
				} else {
					// replace undefined variation attribute by the list of all option of this attribute
					if ( isset( $attrs_variable[ $attribute_name ] ) ) {
						$attribute_object = $attrs_variable[ $attribute_name ];
					} elseif ( isset( $attrs_variable[ 'pa_' . $attribute_name ] ) ) {
						$attribute_object = $attrs_variable[ 'pa_' . $attribute_name ];
					} else {
						continue;
					}

					/** @var WC_Product_Attribute $attribute_object */
					if ( $attribute_object->is_taxonomy() ) {
						$attr_ids[ $attribute_name ] = (array) ( $attribute_object->get_options() );
					} else {
						if ( strtolower( $attribute_name ) == strtolower( $attribute_object->get_name() ) ) {
							$attr_custom[ $attribute_name ] = $attribute_object->get_options();
						}
					}
				}
			}
		} else {
			foreach ( $attrs as $attr ) {
				/** @var WC_Product_Attribute $attr */
				if ( $attr->is_taxonomy() ) {
					$attr_ids[ strtolower( $attr->get_name() ) ] = (array) ( $attr->get_options() );
				} else {
					if ( strtolower( $attr->get_name() ) == strtolower( $attr->get_name() ) ) {
						$attr_custom[ strtolower( $attr->get_name() ) ] = $attr->get_options();
					}
				}
			}
		}

		$operation_values_tax          = array();
		$operation_values_custom_attrs = array();
		foreach ( $this->values as $attr_id ) {
			$term_obj = false;

			foreach ( $term_attr_ids as $hash => $tmp_attr_ids ) {
				if ( in_array( $attr_id, $tmp_attr_ids ) ) {
					$term_obj = isset( $calculated_term_obj[ $hash ] ) ? $calculated_term_obj[ $hash ] : false;
					break;
				}
			}

			if ( empty( $term_obj ) ) {
				$term_obj = get_term( $attr_id );
				if ( ! $term_obj ) {
					$term_attr_ids['empty'][] = $attr_id;
					continue;
				}

				if ( is_wp_error( $term_obj ) ) {
					continue;
				}

				$hash                         = md5( json_encode( $term_obj ) );
				$calculated_term_obj[ $hash ] = $term_obj;
				if ( ! isset( $term_attr_ids[ $hash ] ) ) {
					$term_attr_ids[ $hash ] = array();
				}
				$term_attr_ids[ $hash ][] = $attr_id;
			}

			$attribute_name = $this->attribute_taxonomy_slug( $term_obj->taxonomy );
			if ( ! isset( $operation_values_tax[ $attribute_name ] ) ) {
				$operation_values_tax[ $attribute_name ] = array();
			}
			$operation_values_tax[ $attribute_name ][]          = $attr_id;
			$operation_values_custom_attrs[ $attribute_name ][] = $term_obj->name;
		}

		$is_product_has_attrs_id     = true;
		foreach ( $operation_values_tax as $attribute_name => $tmp_attr_ids ) {
			if (
				(! isset( $attr_ids[ $attribute_name ] )
			     || ! count( array_intersect( $tmp_attr_ids, $attr_ids[ $attribute_name ] ) )
				)
			     &&
				(! isset( $attr_ids[ wc_attribute_taxonomy_name( $attribute_name ) ] )
			     || ! count( array_intersect( $tmp_attr_ids, $attr_ids[ wc_attribute_taxonomy_name( $attribute_name ) ] ) )
				)
			) {
				$is_product_has_attrs_id = false;
				break;
			}
		}

		$is_product_has_attrs_custom = true;
		foreach ( $operation_values_custom_attrs as $attribute_name => $tmp_attr_names ) {
			if ( ! isset( $attr_custom[ $attribute_name ] ) || ! count( array_intersect( $tmp_attr_names, $attr_custom[ $attribute_name ] ) ) ) {
				$is_product_has_attrs_custom = false;
				break;
			}
		}

		if ( 'in_list' === $this->method ) {
			return $is_product_has_attrs_id || $is_product_has_attrs_custom;
		} elseif ( 'not_in_list' === $this->method ) {
			return ! ( $is_product_has_attrs_id || $is_product_has_attrs_custom );
		}

		return false;
	}

	private function attribute_taxonomy_slug( $attribute_name ) {
		$attribute_name = wc_sanitize_taxonomy_name( $attribute_name );
		$attribute_slug = 0 === strpos( $attribute_name, 'pa_' ) ? substr( $attribute_name, 3 ) : $attribute_name;

		return $attribute_slug;
	}

	protected function compare_product_with_product_sku( $product, $cart_item ) {
		$result = false;
//		$product = $this->get_cached_wc_product( $product ); // use variation sku!
		$product_sku = $product->get_sku();

		if ( 'in_list' === $this->method ) {
			$result = ( in_array( $product_sku, $this->values ) );
		} elseif ( 'not_in_list' === $this->method ) {
			$result = ! ( in_array( $product_sku, $this->values ) );
		} elseif ( 'any' === $this->method ) {
			$result = true;
		}

		return $result;
	}

	protected function compare_product_with_product_custom_fields( $product, $cart_item ) {
		$check_children_custom_fields = apply_filters( 'wdp_compare_product_with_product_custom_fields_check_children', false );
		$meta                         = array();

		if ( $check_children_custom_fields ) {
			$meta = $this->get_product_meta( $product );
		}

		$product = $this->get_main_product( $product );
		$meta = array_merge_recursive( $this->get_product_meta( $product ), $meta );

		$custom_fields = array();
		foreach ( $meta as $key => $values ) {
			foreach ( $values as $value ) {
				if ( ! is_array( $value ) ) {
					$custom_fields[] = "$key=$value";
				}
			}
		}
		$is_product_has_fields = count( array_intersect( $custom_fields, $this->values ) ) > 0;

		if ( 'in_list' === $this->method ) {
			return $is_product_has_fields;
		} elseif ( 'not_in_list' === $this->method ) {
			return ! $is_product_has_fields;
		}
	}

	protected function compare_product_with_custom_taxonomy( $product, $cart_item ) {
		$product = $this->get_main_product( $product );
		$taxonomy = $this->type;

		$term_ids = wp_get_post_terms( $product->get_id(), $taxonomy, array( "fields" => "ids" ) );
		$is_product_has_term = count( array_intersect( $term_ids, $this->values ) ) > 0;

		if ( 'in_list' === $this->method ) {
			return $is_product_has_term;
		} elseif ( 'not_in_list' === $this->method ) {
			return ! $is_product_has_term;
		}

		return false;
	}

	/**
	 * @param WC_Product $product
	 *
	 * @return array
	 */
	private function get_product_meta( $product ) {
		$meta = array();

		foreach ( $product->get_meta_data() as $meta_datum ) {
			/**
			 * @var WC_Meta_Data $meta_datum
			 */
			$data                 = $meta_datum->get_data();

			if ( ! isset($meta[ $data['key'] ]) ) {
				$meta[ $data['key'] ] = array();
			}
			$meta[ $data['key'] ][] = $data['value'];
		}

		return $meta;
	}

}