<?php
/*
 * Product Attribute Filter Widget
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

  class elsey_product_attribute_filter extends WP_Widget {

    /**
     * Specifies the widget name, description, class name and instatiates it
     */
    public function __construct() {
      parent::__construct(
        'els-product-attribute-filter',
        ELSEY_NAME_P . esc_html__( ' : Product Attribute Filter', 'elsey-core' ),
        array(
          'classname'   => 'els-product-attribute-filter els-filter-column',
          'description' => ELSEY_NAME_P . esc_html__( ' widget that works for product attribute filtering.', 'elsey-core' )
        )
      );
    }

    /**
     * Generates the back-end layout for the widget
     */
    public function form( $instance ) {

      // Default Values
      $instance = wp_parse_args( $instance, array(
        'title'      => esc_html__( 'Filter By', 'elsey-core' ),
        'attribute'  => '',
        'query_type' => 'and',
        'count'			 => 'false',
      ));

      $attribute_array      = array();
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $tax ) {
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
						$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
					}
				}
			}

      // Title
      $title_value = esc_attr( $instance['title'] );
      $title_field = array(
        'id'         => $this->get_field_name('title'),
        'name'       => $this->get_field_name('title'),
        'type'       => 'text',
        'title'      => esc_html__( 'Filter by', 'elsey-core' ),
        'wrap_class' => 'els-cs-widget-fields',
      );
      echo cs_add_element( $title_field, $title_value );

      // Attribute
      $attribute_value = esc_attr( $instance['attribute'] );
      $attribute_field = array(
        'id'            => $this->get_field_name('attribute'),
        'name'          => $this->get_field_name('attribute'),
        'type'          => 'select',
        'options'       => $attribute_array,
        'title'         => esc_html__( 'Attribute ', 'elsey-core' ),
      );
      echo cs_add_element( $attribute_field, $attribute_value );

      // Query Type
      $query_type_value = esc_attr( $instance['query_type'] );
      $query_type_field = array(
        'id'            => $this->get_field_name('query_type'),
        'name'          => $this->get_field_name('query_type'),
        'type'          => 'select',
        'options'       => array(
          'and'         => esc_html__( 'AND', 'elsey-core' ),
          'or'          => esc_html__( 'OR', 'elsey-core' ),
        ),
        'title'         => esc_html__( 'Query type ', 'elsey-core' ),
      );
      echo cs_add_element( $query_type_field, $query_type_value );

      // Count
      $count_value = $instance['count'];
      $count_field = array(
        'id'         => $this->get_field_name('count'),
        'name'       => $this->get_field_name('count'),
        'type'       => 'checkbox',
        'title'      => esc_html__( 'Show Count', 'elsey-core' ),
        'default'    => false,
        'wrap_class' => 'els-cs-widget-fields',
      );
      echo cs_add_element( $count_field, $count_value );

    }

    /**
     * Processes the widget's values
     */
    public function update( $new_instance, $old_instance ) {
      $instance = $old_instance;

      $instance['title']      = strip_tags( stripslashes( $new_instance['title'] ) );
      $instance['attribute']  = strip_tags( stripslashes( $new_instance['attribute'] ) );
      $instance['query_type'] = strip_tags( stripslashes( $new_instance['query_type'] ) );
      $instance['count']      = $new_instance['count'];

      return $instance;
    }

    /**
     * Output the contents of the widget
     */
    public function widget( $args, $instance ) {
     
      // Extract the arguments
      extract( $args );

      $title       = apply_filters( 'widget_title', $instance['title'] );
      $query_type  = $instance['query_type'];
      $count       = $instance['count'];
      $taxonomy    = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : '';

      $output      = '';
      $e_uniqid    = uniqid();
      $unique_name = 'els-filterAttr-'. $e_uniqid;

      if ( ! taxonomy_exists( $taxonomy ) ) {
				return;
			}

			$get_terms_args = array( 'hide_empty' => '1' );

			$orderby = wc_attribute_orderby( $taxonomy );

			switch ( $orderby ) {
				case 'name' :
					$get_terms_args['orderby']    = 'name';
					$get_terms_args['menu_order'] = false;
				break;
				case 'id' :
					$get_terms_args['orderby']    = 'id';
					$get_terms_args['order']      = 'ASC';
					$get_terms_args['menu_order'] = false;
				break;
				case 'menu_order' :
					$get_terms_args['menu_order'] = 'ASC';
				break;
			}

			$terms = get_terms( $taxonomy, $get_terms_args );

			if ( 0 === sizeof( $terms ) ) {
				return;
			}

			switch ( $orderby ) {
				case 'name_num' :
					usort( $terms, '_wc_get_product_terms_name_num_usort_callback' );
				break;
				case 'parent' :
					usort( $terms, '_wc_get_product_terms_parent_usort_callback' );
				break;
			}

			echo $before_widget;

			$output.= $before_title;
			if ( $title ) { $output.= $title; }
      $output.= $after_title;

      $output.= '<div class="'.esc_attr($unique_name).' els-filter-content">';
      $output.= '<ul id="els-attribute-filter" class="els-attribute-filter">';

      $term_counts = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
      $filter_name = sanitize_title( str_replace( 'pa_', '', $taxonomy ) );

      foreach ($terms as $term) {
      	// Skip the term for the current archive
				if ( $this->get_current_term_id() === $term->term_id ) {
					continue;
				}
				$product_count = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

				$output.= '<li>';
        $output.= ($product_count > 0) ? '<a href="javascript:void(0);" data-attribute="'.esc_attr($term->slug).'" data-attrname="'.esc_attr($filter_name).'">' : '<span>';
        $output.= esc_attr($term->name);
        $output.=	($count) ? '<span class="count"><sup class="count"> [' . $product_count . ']</sup></span>' : '';
        $output.= ($product_count > 0) ? '</a> ' : '</span>';
        $output.= '</li>';
      }

      $output .= '</ul>';
      $output .= '</div>';

      echo $output . $after_widget;

    }

    /**
		 * Return the currently viewed term ID.
		 * @return int
		 */
		protected function get_current_term_id() {
			return absint( is_tax() ? get_queried_object()->term_id : 0 );
		}

		/**
		 * Return the currently viewed term slug.
		 * @return int
		 */
		protected function get_current_term_slug() {
			return absint( is_tax() ? get_queried_object()->slug : 0 );
		}

		/**
		* Count products within certain terms, taking the main WP query into consideration.
		*
		* @param  array  $term_ids
		* @param  string $taxonomy
		* @param  string $query_type
		* @return array
		*/
		protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
			global $wpdb;

			$tax_query  = WC_Query::get_main_tax_query();
			$meta_query = WC_Query::get_main_meta_query();

			if ( 'or' === $query_type ) {
				foreach ( $tax_query as $key => $query ) {
					if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
						unset( $tax_query[ $key ] );
					}
				}
			}

			$meta_query      = new WP_Meta_Query( $meta_query );
			$tax_query       = new WP_Tax_Query( $tax_query );
			$meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );

			// Generate query
			$query           = array();
			$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
			$query['from']   = "FROM {$wpdb->posts}";
			$query['join']   = "
				INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
				INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
				INNER JOIN {$wpdb->terms} AS terms USING( term_id )
				" . $tax_query_sql['join'] . $meta_query_sql['join'];

			$query['where']   = "
				WHERE {$wpdb->posts}.post_type IN ( 'product' )
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . "
				AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
			";

			if ( $search = WC_Query::get_main_search_query_sql() ) {
				$query['where'] .= ' AND ' . $search;
			}

			$query['group_by'] = "GROUP BY terms.term_id";
			$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
			$query             = implode( ' ', $query );
			$results           = $wpdb->get_results( $query );

			return wp_list_pluck( $results, 'term_count', 'term_count_id' );
		}

  }

  // Register the widget using an annonymous function
  add_action( 'widgets_init', create_function( '', 'register_widget( "elsey_product_attribute_filter" );' ) );

}
