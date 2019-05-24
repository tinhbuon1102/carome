<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WDP_Statistics_Db_Helper {
	public static function get_rules_count_rows( $params ) {
		global $wpdb;

		$params = array_merge( array(
			'from'  => '',
			'to'    => '',
			'limit' => 5,
		), $params );

		if ( empty( $params['from'] ) || empty( $params['to'] ) ) {
			return false;
		}

		$table_items = $wpdb->prefix . 'wdp_rules';
		$table_stats = $wpdb->prefix . 'wdp_orders';

		$query_total = $wpdb->prepare(
			"SELECT rules.id AS rule_id, COUNT(rules_stats.id) AS value
			FROM {$table_items} AS rules LEFT JOIN {$table_stats} AS rules_stats
			ON rules.id = rules_stats.rule_id
			WHERE DATE(rules_stats.date) BETWEEN %s AND %s
			GROUP BY rules.id
			HAVING value>0
			ORDER BY value DESC
			LIMIT %d",
			array( $params['from'], $params['to'], (int) $params['limit'] )
		);

		$top = $wpdb->get_col( $query_total );
		if ( empty( $top ) ) {
			return false;
		}

		$placeholders = array_fill( 0, count( $top ), '%d' );
		$placeholders = implode( ', ', $placeholders );

		$query = $wpdb->prepare(
			"SELECT DATE(rules_stats.date) as date_rep, rules.id AS rule_id, CONCAT('#', rules.id, ' ', rules.title) AS title, COUNT(rules_stats.id) AS value
			FROM {$table_items} AS rules LEFT JOIN {$table_stats} AS rules_stats
			ON rules.id = rules_stats.rule_id
			WHERE DATE(rules_stats.date) BETWEEN %s AND %s AND rules.id IN ({$placeholders})
			GROUP BY date_rep, rule_id, title
			HAVING value>0
			ORDER BY value DESC",
			array_merge( array( $params['from'], $params['to'] ), $top )
		);
		$rows = $wpdb->get_results( $query );

		return $rows;
	}

	public static function get_rules_rows_summary( $params ) {
		global $wpdb;

		$params = array_merge( array(
			'from'                  => '',
			'to'                    => '',
			'limit'                 => 5,
			'include_amount'        => true,
			'include_extra'         => false,
			'include_shipping'      => false,
			'include_gifted_amount' => false,
			'include_gifted_qty'    => false,
		), $params );

		if ( empty( $params['from'] ) || empty( $params['to'] ) ) {
			return false;
		}

		$table_items = $wpdb->prefix . 'wdp_rules';
		$table_stats = $wpdb->prefix . 'wdp_orders';

		$summary_components = array();
		if ( $params['include_gifted_qty'] ) {
			$summary_components[] = 'rules_stats.gifted_qty';
		} else {
			if ( $params['include_amount'] ) {
				$summary_components[] = 'rules_stats.amount';
			}
			if ( $params['include_extra'] ) {
				$summary_components[] = 'rules_stats.extra';
			}
			if ( $params['include_shipping'] ) {
				$summary_components[] = 'rules_stats.shipping';
			}
			if ( $params['include_gifted_amount'] ) {
				$summary_components[] = 'rules_stats.gifted_amount';
			}
		}
		if ( empty( $summary_components ) ) {
			return false;
		}
		$summary_field = implode( '+', $summary_components );

		$query_total = $wpdb->prepare(
			"SELECT rules.id AS rule_id, SUM({$summary_field}) AS value
			FROM {$table_items} AS rules LEFT JOIN {$table_stats} AS rules_stats
			ON rules.id = rules_stats.rule_id
			WHERE DATE(rules_stats.date) BETWEEN %s AND %s
			GROUP BY rules.id
			HAVING value>0
			ORDER BY value DESC
			LIMIT %d",
			array( $params['from'], $params['to'], (int) $params['limit'] )
		);

		$top = $wpdb->get_col( $query_total );
		if ( empty( $top ) ) {
			return false;
		}

		$placeholders = array_fill( 0, count( $top ), '%d' );
		$placeholders = implode( ', ', $placeholders );

		$query = $wpdb->prepare(
			"SELECT DATE(rules_stats.date) as date_rep, rules.id AS rule_id, CONCAT('#', rules.id, ' ', rules.title) AS title, SUM({$summary_field}) AS value
			FROM {$table_items} AS rules LEFT JOIN {$table_stats} AS rules_stats
			ON rules.id = rules_stats.rule_id
			WHERE DATE(rules_stats.date) BETWEEN %s AND %s AND rules.id IN ({$placeholders})
			GROUP BY date_rep, rule_id, title
			HAVING value>0
			ORDER BY value DESC",
			array_merge( array( $params['from'], $params['to'] ), $top )
		);

		$rows = $wpdb->get_results( $query );

		return $rows;
	}

	public static function get_products_rows_summary( $params ) {
		global $wpdb;

		$params = array_merge( array(
			'from'                  => '',
			'to'                    => '',
			'limit'                 => 5,
			'include_amount'        => true,
			'include_qty'           => false,
			'include_gifted_amount' => false,
			'include_gifted_qty'    => false,
		), $params );

		if ( empty( $params['from'] ) || empty( $params['to'] ) ) {
			return false;
		}

		$table_items = $wpdb->posts;
		$table_stats = $wpdb->prefix . 'wdp_order_items';

		$summary_components = array();
		if ( $params['include_amount'] || $params['include_gifted_amount'] ) {
			if ( $params['include_amount'] ) {
				$summary_components[] = 'stats.amount';
			}
			if ( $params['include_gifted_amount'] ) {
				$summary_components[] = 'stats.gifted_amount';
			}
		} elseif ( $params['include_qty'] || $params['include_gifted_qty'] ) {
			if ( $params['include_qty'] ) {
				$summary_components[] = 'stats.qty';
			}
			if ( $params['include_gifted_qty'] ) {
				$summary_components[] = 'stats.gifted_qty';
			}
		}
		if ( empty( $summary_components ) ) {
			return false;
		}
		$summary_field = implode( '+', $summary_components );

		$query_total = $wpdb->prepare(
			"SELECT products.id AS product_id, SUM({$summary_field}) AS value
			FROM {$table_items} AS products LEFT JOIN {$table_stats} AS stats
			ON products.id = stats.product_id
			WHERE DATE(stats.date) BETWEEN %s AND %s
			GROUP BY product_id
			HAVING value>0
			ORDER BY value DESC
			LIMIT %d",
			array( $params['from'], $params['to'], (int) $params['limit'] )
		);

		$top = $wpdb->get_col( $query_total );
		if ( empty( $top ) ) {
			return false;
		}

		$placeholders = array_fill( 0, count( $top ), '%d' );
		$placeholders = implode( ', ', $placeholders );

		$query = $wpdb->prepare(
			"SELECT DATE(stats.date) as date_rep, products.id AS product_id, CONCAT('#', products.id, ' ', products.post_title) AS title, SUM({$summary_field}) AS value
			FROM {$table_items} AS products LEFT JOIN {$table_stats} AS stats
			ON products.id = stats.product_id
			WHERE DATE(stats.date) BETWEEN %s AND %s AND products.id IN ({$placeholders})
			GROUP BY date_rep, product_id, title
			HAVING value>0
			ORDER BY value DESC",
			array_merge( array( $params['from'], $params['to'] ), $top )
		);
		$rows = $wpdb->get_results( $query );

		return $rows;
	}
}