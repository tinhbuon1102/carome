<?php
	
	/** 
	* Functions for the Settings / Saved Tabs List Page
	*/
	class YIKES_Custom_Product_Tabs_Pro_Saved_Tabs_List {

		/**
		* Construct it >:D
		*/
		public function __construct() {

			// Saved tabs page - customize page title
			add_filter( 'yikes-woo-settings-menu-title', array( $this, 'change_settings_page_title' ), 10 );

			// Settings page - Add a <th> to the saved tabs list for taxonomy & global
			add_action( 'yikes-woo-saved-tabs-table-header', array( $this, 'add_taxonomy_th_to_saved_tabs_list' ), 10 );
			add_action( 'yikes-woo-saved-tabs-table-header', array( $this, 'add_global_th_to_saved_tabs_list' ), 10 );

			// Settings page - Add taxonomy & global data to the saved tabs list
			add_action( 'yikes-woo-saved-tabs-table-column', array( $this, 'add_taxonomy_data_to_saved_tabs_list' ), 10, 1 );
			add_action( 'yikes-woo-saved-tabs-table-column', array( $this, 'add_global_data_to_saved_tabs_list' ), 20, 1 );

			// Saved tabs list & single pages - show a warning if the user has too many products
			add_action( 'yikes-woo-display-too-many-products-warning', array( 'YIKES_Custom_Product_Tabs_Pro_Admin', 'display_too_many_products_warning' ), 10 );

			// Saved tabs list & single pages - add a class to the table
			add_action( 'yikes-woo-saved-tabs-table-classes', array( 'YIKES_Custom_Product_Tabs_Pro_Admin', 'add_class_to_saved_tabs_table' ), 10 );
		}

		/**
		* Display a different title for the Settings/Saved Tabs List page
		*/
		public function change_settings_page_title() {
			return __( 'Custom Product Tabs Pro', 'custom-product-tabs-pro' );
		}

		/**
		* Display a new `<th>` in the saved tabs table's `<thead>` and `<tfoot>`
		*/
		public function add_taxonomy_th_to_saved_tabs_list() {
			?>
				<th class="manage-column column-taxonomy" scope="col">
					<?php _e( 'Taxonomy', 'custom-product-tabs-pro' ); ?>
				</th>
			<?php
		}

		/**
		* Show a tab's taxonomies in the saved tabs table
		*
		* @param array | $tab | Array of tab data
		*/
		public function add_taxonomy_data_to_saved_tabs_list( $tab ) { 
			?>
				<td class="column-taxonomy">
					<?php 
						if ( isset( $tab['taxonomies'] ) && ! empty( $tab['taxonomies'] ) ) {

							foreach( $tab['taxonomies'] as $taxonomy_slug => $terms ) {

								if ( is_array( $terms ) && ! empty( $terms ) ) {

									$taxonomy       = get_taxonomy( $taxonomy_slug );
									$taxonomy_label = is_object( $taxonomy ) && isset( $taxonomy->label ) ? $taxonomy->label : '';
									$ii = 0;
									?>
										<div class="saved-tabs-list-taxonomy">
											<span class="saved-tabs-list-taxonomy-label"><?php echo $taxonomy_label ?>: </span>
											<?php foreach( $terms as $term_slug ) {
												$term_object = get_term_by( 'slug', $term_slug, $taxonomy_slug );

												if ( ! empty( $term_object ) ) {

													$product_term_url = add_query_arg( array( $taxonomy_slug => $term_object->slug, 'post_type' => 'product' ), admin_url( 'edit.php' ) );
													?> <a href="<?php echo $product_term_url; ?>" class="saved-tabs-list-taxonomy-terms"><?php echo $term_object->name ?></a><?php

													$ii++;
													if ( $ii !== count( $terms ) ) { echo ','; }
												}
											}
											?>
										</div>
									<?php
								}
							}
						}
					?>		
				</td>
			<?php
		}

		/**
		* Display a new `<th>` in the saved tabs table's `<thead>` and `<tfoot>`
		*/
		public function add_global_th_to_saved_tabs_list() {
			?>
				<th class="manage-column column-global" scope="col">
					<?php _e( 'Global', 'custom-product-tabs-pro' ); ?>
				</th>
			<?php
		}

		/**
		* Show a tab's global status in the saved tabs table
		*
		* @param array | $tab | Array of tab data
		*/
		public function add_global_data_to_saved_tabs_list( $tab ) {
			?>
			<td class="column-global">
				<?php if ( isset( $tab['global_tab'] ) && $tab['global_tab'] === true ) : ?>
					<span class="dashicons dashicons-yes"></span>
				<?php endif; ?>
			</td>
			<?php
		}

	}

	new YIKES_Custom_Product_Tabs_Pro_Saved_Tabs_List();