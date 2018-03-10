<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Product_Quantity_Report_List extends WP_List_Table {
	/** Class constructor */
	public function __construct() {
	
		parent::__construct( [
			'singular' => __( 'Product Quantity Report', 'elsey' ), //singular name of the listed records
			'plural'   => __( 'Product Quantity ', 'elsey' ), //plural name of the listed records
			'ajax'     => true,
			'screen' => 'product_quantity_report_list',
	
		] );
	}
	
	/**
	 * Check the current user's permissions.
	 *
	 * @since 3.1.0
	 *
	 * @return bool
	 */
	public function ajax_user_can() {
		return current_user_can( 'manage_sites' );
	}
	
	
	private function get_where($product_id = 0)
	{
		$where = '';
		$action = $this->current_action();
		switch ($action)
		{
			case 'today' :
				$where .= ' AND DATE(p.post_date) = CURDATE() ';
				break;
			case 'yesterday' :
				$where .= ' AND (p.post_date >= CURDATE() - INTERVAL 1 DAY) AND (p.post_date < CURDATE()) ';
				break;
				
			case 'current-week' :
				$where .= ' AND YEARWEEK(p.post_date, 1) = YEARWEEK(CURDATE(), 1) ';
				break;
				
			case 'last-week' :
				$where .= ' AND (p.post_date >= CURDATE() - INTERVAL DAYOFWEEK(curdate())+6 DAY ) AND (p.post_date < CURDATE() - INTERVAL DAYOFWEEK(curdate())-1 DAY) ';
				break;
				
			case 'current-month' :
				$where .= ' AND MONTH(p.post_date) = MONTH(CURRENT_DATE()) AND YEAR(p.post_date) = YEAR(CURRENT_DATE()) ';
				break;
				
			case 'last-month' :
				$where .= ' AND YEAR(p.post_date) = YEAR(CURDATE() - INTERVAL 1 MONTH) AND MONTH(p.post_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) ';
				break;
				
			case 'current-year' :
				$where .= ' AND YEAR(p.post_date) = YEAR(CURDATE())';
				break;
				
			case 'last-year' :
				$where .= ' AND YEAR(p.post_date) = YEAR(CURDATE() - INTERVAL 1 YEAR)';
				break;
				
			default :
				$where .= ' AND DATE(p.post_date) = CURDATE() ';
				break;
		}
		
		if ($product_id)
		{
			$where .= ' AND om2.meta_value = ' . $product_id;
		}
		
		return $where;
	}
	/**
	 * Retrieve customer’s data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public function get_products( $per_page = 10, $page_number = 1 ) {
	
		global $wpdb;

		$where = " WHERE post_type = 'shop_order' AND post_status IN ('wc-completed', 'wc-processing')
			AND ot.order_item_type = 'line_item'
			AND om1.meta_key = '_qty' AND om2.meta_key = '_product_id' " . $this->get_where();
		
		$sql = "
		SELECT om2.meta_value as product_id, ot.order_item_name as product_name, COUNT(om1.meta_value) as quantity
		FROM wp_posts p
			INNER JOIN {$wpdb->prefix}woocommerce_order_items ot ON p.ID = ot.order_id
			INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta om1 ON ot.order_item_id = om1.order_item_id
			INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta om2 ON ot.order_item_id = om2.order_item_id
		 ";
	
		$sql .= $where;
		$sql .= " GROUP BY om2.meta_value ";
		
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}
		else {
			$sql .= ' ORDER BY quantity DESC ';
		}
	
		$sql .= " LIMIT $per_page";
	
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
	
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
	
		return $result;
	}
	
	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public function record_count($statuses = array(), $product_id = 0) {
		global $wpdb;
	
		$statuses = empty($statuses) ? array('wc-completed', 'wc-processing') : $statuses;
		$sql = "
		SELECT COUNT(*) as quantity
		FROM wp_posts p
			INNER JOIN {$wpdb->prefix}woocommerce_order_items ot ON p.ID = ot.order_id
			INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta om1 ON ot.order_item_id = om1.order_item_id
			INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta om2 ON ot.order_item_id = om2.order_item_id
		WHERE
			post_type = 'shop_order' AND post_status IN ('". implode("','", $statuses) . "')
			AND ot.order_item_type = 'line_item'
			AND om1.meta_key = '_qty' AND om2.meta_key = '_product_id' ". $this->get_where($product_id) ."
			GROUP BY om2.meta_value";
	
		if (!$product_id)
		{
			$sql = 'SELECT COUNT(*) FROM ( ' . $sql . ') as t';
		}
		else {
			$sql = 'SELECT SUM(quantity) FROM ( ' . $sql . ') as t';
		}
		return $wpdb->get_var( $sql );
	}
	
	
	public function extra_tablenav($which){
		$this->table_header_list($which);
	}
	
	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No products avaliable.', 'elsey' );
	}
	
	
	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {
		$title = '<strong>' . $item['name'] . '</strong>';
		return $title;
	}
	
	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'product_name':
			case 'product_id':
			case 'quantity':
				return $item[ $column_name ];
				break;
		}
	}
	
	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'product_id'    => __( 'Product ID', 'elsey' ),
			'product_name' => __( 'Product Name', 'elsey' ),
			'quantity'    => __( 'Sold Quantity', 'elsey' )
		];
	
		return $columns;
	}
	
	function single_row_columns($item) {
		list ($columns, $hidden) = $this->get_column_info();
		foreach ( $columns as $column_name => $column_display_name )
		{
			$product_id = $item['product_id'];
			echo "<td>";
			if ( 'product_id' == $column_name || 'product_name' == $column_name)
			{
				
				echo '<a target="_blank" href="'. site_url() . '/wp-admin/post.php?post='. $product_id .'&action=edit'.'">'. $item[$column_name] .'</a>';
			}
			elseif ( 'quantity' == $column_name)
			{
				$completed = $this->record_count(array('wc-completed'), $product_id);
				$processing = $this->record_count(array('wc-processing'), $product_id);
				echo '<p>'. sprintf(__('Total: <strong>%s</strong>'), (int)$item[$column_name]) .'</p>';
				echo '<p>'. sprintf(__('Completed: <strong>%s</strong>'), (int)$completed) .'</p>';
				echo '<p>'. sprintf(__('Processing: <strong>%s</strong>'), (int)$processing) .'</p>';
			}
			else {
				echo $item[$column_name];
			}
			echo "</td>";
		}
	}
	
		
	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'product_id' => array( 'product_id', false ),
			'product_name' => array( 'product_name', false ),
			'quantity' => array( 'quantity', false ),
		);
	
		return $sortable_columns;
	}
	
	
	/**
	 * Returns an associative array containing the time action
	 *
	 * @return array
	 */
	public function get_time_actions() {
		$actions = [
			'today' => __('To Day', 'elsey'),
			'yesterday' => __('Yesterday', 'elsey'),
			'current-week' => __('Current Week', 'elsey'),
			'last-week' => __('Last Week', 'elsey'),
			'current-month' => __('Current Month', 'elsey'),
			'last-month' => __('Last Month', 'elsey'),
			'current-year' => __('Current Year', 'elsey'),
			'last-year' => __('Last Year', 'elsey'),
			'all' => __('All Time', 'elsey'),
		];
	
		return $actions;
	}
	
	public function table_header_list( $which = '' ) {
		echo '<style>
				.tablenav.bottom .product-reporting-dashboard {display: none;}
				</style>';
		echo '<form method="get" name="product-reporting-dashboard" class="product-reporting-dashboard">';
		echo '<select name="action' . $two . '" id="time-action-selector-' . esc_attr( $which ) . "\">\n";
		$current_action = $this->current_action();
		$actions = $this->get_time_actions();
		foreach ( $actions as $name => $title ) {
			$class = 'edit' === $name ? ' class="hide-if-no-js"' : '';
		
			echo "\t" . '<option '. ($current_action == $name ? 'selected' : '') .' value="' . $name . '"' . $class . '>' . $title . "</option>\n";
		}
		
		echo "</select>\n";
		
		submit_button( __( 'Search', 'elsey' ), 'action', '', false, array( 'id' => "doaction$two" ) );
		echo '</form>';
		echo "\n";
	}
	
	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
	
		$this->_column_headers = $this->get_column_info();
	
		$per_page     = 5;
		$current_page = $this->get_pagenum();
		$total_items  = $this->record_count();
	
		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages'   => ceil( $total_items / $per_page ),
		
		] );
	
	
		$this->items = $this->get_products( $per_page, $current_page );
	}
}