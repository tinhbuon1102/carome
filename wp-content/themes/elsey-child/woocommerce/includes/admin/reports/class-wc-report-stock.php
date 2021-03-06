<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * WC_Report_Stock.
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce/Admin/Reports
 * @version     2.1.0
 */
class WC_Report_Stock extends WP_List_Table {

	public function get_orderby()
	{
		$orderby = '';
		$action = $this->current_action();
		switch ($action)
		{
			case 'publish-date-desc' :
				$orderby .= ' posts.post_date DESC ';
				break;
			case 'publish-date-asc' :
				$orderby .= ' posts.post_date ASC ';
				break;
	
			case 'stock-desc' :
				$orderby .= ' CAST(postmeta.meta_value AS SIGNED) DESC ';
				break;
			case 'stock-asc' :
				$orderby .= ' CAST(postmeta.meta_value AS SIGNED) ASC ';
				break;
	
			case 'title-desc' :
				$orderby .= ' posts.post_title DESC ';
				break;
	
			case 'title-asc' :
				$orderby .= ' posts.post_title ASC ';
				break;
			default :
				$orderby .= ' posts.post_date DESC ';
				break;
		}
	
		return $orderby;
	}
	
	/**
	 * Max items.
	 *
	 * @var int
	 */
	protected $max_items;

	/**
	 * Constructor.
	 */
	public function __construct() {

		parent::__construct( array(
			'singular'  => 'stock',
			'plural'    => 'stock',
			'ajax'      => false,
		) );
	}

	/**
	 * No items found text.
	 */
	public function no_items() {
		_e( 'No products found.', 'woocommerce' );
	}

	
	/**
	 * Returns an associative array containing the time action
	 *
	 * @return array
	 */
	public function get_order_actions() {
		$actions = [
			'publish-date-desc' => __('Publish Date Descending', 'elsey'),
			'publish-date-asc' => __('Publish Date Ascending', 'elsey'),
			
			'stock-desc' => __('Unit In Stock Descending', 'elsey'),
			'stock-asc' => __('Unit In Stock Asceding', 'elsey'),
			
			'title-desc' => __('Title Descending', 'elsey'),
			'title-asc' => __('Title Asceding', 'elsey'),
		];
	
		return $actions;
	}
	
	public function table_header_list( $which = '' ) {
		echo '<style>
				.tablenav.bottom .product-reporting-dashboard {display: none;}
				</style>';
		echo '<form method="get" name="product-reporting-stock" id="product-reporting-stock" action="'. site_url(esc_url( add_query_arg() )) .'">';
		echo '<select name="action" id="sort-action-selector-' . esc_attr( $which ) . "\">\n";
		$current_action = $this->current_action();
		$actions = $this->get_order_actions();
		foreach ( $actions as $name => $title ) {
			$class = 'edit' === $name ? ' class="hide-if-no-js"' : '';
	
			echo "\t" . '<option '. ($current_action == $name ? 'selected' : '') .' value="' . site_url(esc_url( add_query_arg('action', $name) )) . '"' . $class . '>' . $title . "</option>\n";
		}
		echo '<script>
				jQuery(function($){
					$(document).on("change", "#sort-action-selector-top", function(){
						var url = $(this).val();
						location.href = url;
					});
				})
		</script>';
		echo "</select>\n";
	
		
		echo '</form>';
		echo "\n";
	}
	
	public function extra_tablenav($which){
		$this->table_header_list($which);
	}
	
	/**
	 * Output the report.
	 */
	public function output_report() {

		$this->prepare_items();
		echo '<div id="poststuff" class="woocommerce-reports-wide">';
		$this->display();
		echo '</div>';
	}

	/**
	 * Get column value.
	 *
	 * @param mixed $item
	 * @param string $column_name
	 */
	public function column_default( $item, $column_name ) {
		global $product;

		if ( ! $product || $product->get_id() !== $item->id ) {
			$product = wc_get_product( $item->id );
		}

		if ( ! $product ) {
			return;
		}

		switch ( $column_name ) {

			case 'product' :
				if ( $sku = $product->get_sku() ) {
// 					echo esc_html( $sku ) . ' - ';
				}

				echo esc_html( $product->get_name() );

				// Get variation data.
				if ( $product->is_type( 'variation' ) ) {
					echo '<div class="description">' . wp_kses_post( wc_get_formatted_variation( $product, true ) ) . '</div>';
				}
			break;

			case 'parent' :
				if ( $item->parent ) {
					echo esc_html( get_the_title( $item->parent ) );
				} else {
					echo '-';
				}
			break;

			case 'stock_status' :
				if ( $product->is_in_stock() ) {
					$stock_html = '<mark class="instock">' . __( 'In stock', 'woocommerce' ) . '</mark>';
				} else {
					$stock_html = '<mark class="outofstock">' . __( 'Out of stock', 'woocommerce' ) . '</mark>';
				}
				echo apply_filters( 'woocommerce_admin_stock_html', $stock_html, $product );
			break;

			case 'stock_level' :
				echo esc_html( $product->get_stock_quantity() );
			break;

			case 'wc_actions' :
				?><p>
					<?php
						$actions = array();
						$action_id = $product->is_type( 'variation' ) ? $item->parent : $item->id;

						$actions['edit'] = array(
							'url'       => admin_url( 'post.php?post=' . $action_id . '&action=edit' ),
							'name'      => __( 'Edit', 'woocommerce' ),
							'action'    => "edit",
						);

						if ( $product->is_visible() ) {
							$actions['view'] = array(
								'url'       => get_permalink( $action_id ),
								'name'      => __( 'View', 'woocommerce' ),
								'action'    => "view",
							);
						}

						$actions = apply_filters( 'woocommerce_admin_stock_report_product_actions', $actions, $product );

						foreach ( $actions as $action ) {
							printf(
								'<a class="button tips %1$s" href="%2$s" data-tip="%3$s">%4$s</a>',
								esc_attr( $action['action'] ),
								esc_url( $action['url'] ),
								sprintf( esc_attr__( '%s product', 'woocommerce' ), $action['name'] ),
								esc_html( $action['name'] )
							);
						}
					?>
				</p><?php
			break;
		}
	}

	/**
	 * Get columns.
	 *
	 * @return array
	 */
	public function get_columns() {

		$columns = array(
			'product'      => __( 'Product', 'woocommerce' ),
			'parent'       => __( 'Parent', 'woocommerce' ),
			'stock_level'  => __( 'Units in stock', 'woocommerce' ),
			'stock_status' => __( 'Stock status', 'woocommerce' ),
			'wc_actions'   => __( 'Actions', 'woocommerce' ),
		);

		return $columns;
	}

	/**
	 * Prepare customer list items.
	 */
	public function prepare_items() {

		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
		$current_page          = absint( $this->get_pagenum() );
		$per_page              = apply_filters( 'woocommerce_admin_stock_report_products_per_page', 20 );

		$this->get_items( $current_page, $per_page );

		/**
		 * Pagination.
		 */
		$this->set_pagination_args( array(
			'total_items' => $this->max_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $this->max_items / $per_page ),
		) );
	}
}
