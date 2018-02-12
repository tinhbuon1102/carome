<?php
/*
 * Mega Menu - Default WordPress Menu Functions Edit
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 *
 * VictorThemes Changes Mentioned by "Custom Changes"
 * WordPress default commend lines are removed in following code lines.
 * Following code copied form : wp-admin/includes/class-walker-nav-menu-edit.php
 * WordPress version 4.4.0 while copying below code.
 */

if( ! class_exists( 'Walker_Nav_Menu_Edit_Custom' ) ){

    class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu {

        /**
        * Starts the list before the elements are added.
        *
        * @see Walker_Nav_Menu::start_lvl()
        *
        * @since 3.0.0
        *
        * @param string $output Passed by reference.
        * @param int    $depth  Depth of menu item. Used for padding.
        * @param array  $args   Not used.
        */

        public function start_lvl( &$output, $depth = 0, $args = array() ) {}

        /**
        * Ends the list of after the elements are added.
        *
        * @see Walker_Nav_Menu::end_lvl()
        *
        * @since 3.0.0
        *
        * @param string $output Passed by reference.
        * @param int    $depth  Depth of menu item. Used for padding.
        * @param array  $args   Not used.
        */

        public function end_lvl( &$output, $depth = 0, $args = array() ) {}

        /**
        * Start the element output.
        *
        * @see Walker_Nav_Menu::start_el()
        * @since 3.0.0
        *
        * @global int $_wp_nav_menu_max_depth
        *
        * @param string $output Passed by reference. Used to append additional content.
        * @param object $item   Menu item data object.
        * @param int    $depth  Depth of menu item. Used for padding.
        * @param array  $args   Not used.
        * @param int    $id     Not used.
        */

        public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
            global $_wp_nav_menu_max_depth;
            $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

    		ob_start();
    		$item_id = esc_attr( $item->ID );
    		$removed_args = array(
    			'action',
    			'customlink-tab',
    			'edit-menu-item',
    			'menu-item',
    			'page-tab',
    			'_wpnonce',
    		);

            $original_title = '';
            if ( 'taxonomy' == $item->type ) {
            	$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
            	if ( is_wp_error( $original_title ) )
            		$original_title = false;
            } elseif ( 'post_type' == $item->type ) {
            	$original_object = get_post( $item->object_id );
            	$original_title = get_the_title( $original_object->ID );
            } elseif ( 'post_type_archive' == $item->type ) {
            	$original_object = get_post_type_object( $item->object );
            	if ( $original_object ) {
            		$original_title = $original_object->labels->archives;
            	}
            }

    		$classes = array(
    			'menu-item menu-item-depth-' . $depth,
    			'menu-item-' . esc_attr( $item->object ),
    			'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
    		);

            $title = $item->title;

            if ( ! empty( $item->_invalid ) ) {
            	$classes[] = 'menu-item-invalid';
            	/* translators: %s: title of menu item which is invalid */
            	$title = sprintf( esc_html__( '%s (Invalid)', 'elsey' ), $item->title );
            } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
            	$classes[] = 'pending';
            	/* translators: %s: title of menu item in draft status */
            	$title = sprintf( esc_html__('%s (Pending)', 'elsey'), $item->title );
            }

            $title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

            $submenu_text = '';
            if ( 0 == $depth )
            	$submenu_text = 'display: none;';
            ?>

    		<li id="menu-item-<?php echo esc_attr($item_id); ?>" class="<?php echo implode(' ', $classes ); ?>">
    			<div class="menu-item-bar">
    				<div class="menu-item-handle">
    					<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" style="<?php echo esc_attr($submenu_text); ?>"><?php esc_html_e( 'sub item', 'elsey' ); ?></span></span>
    					<span class="item-controls">
    						<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
    						<span class="item-order hide-if-js">
    							<a href="<?php
    								echo wp_nonce_url(
    									add_query_arg(
    										array(
    											'action' => 'move-up-menu-item',
    											'menu-item' => $item_id,
    										),
    										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
    									),
    									'move-menu_item'
    								);
    							?>" class="item-move-up" aria-label="<?php esc_html_e( 'Move up', 'elsey' ); ?>">&#8593;</a>
    							|
    							<a href="<?php
    								echo wp_nonce_url(
    									add_query_arg(
    										array(
    											'action' => 'move-down-menu-item',
    											'menu-item' => $item_id,
    										),
    										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
    									),
    									'move-menu_item'
    								);
    							?>" class="item-move-down" aria-label="<?php esc_html_e( 'Move down', 'elsey' ); ?>">&#8595;</a>
    						</span>
    						<a class="item-edit" id="edit-<?php echo esc_attr($item_id); ?>" href="<?php
    							echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
    						?>" aria-label="<?php esc_html_e( 'Edit menu item', 'elsey' ); ?>"><?php esc_html_e( 'Edit', 'elsey' ); ?></a>
    					</span>
    				</div>
    			</div>

    			<div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo esc_attr($item_id); ?>">
    				<?php if ( 'custom' == $item->type ) : ?>
    					<p class="field-url description description-wide">
    						<label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
    							<?php esc_html_e( 'URL', 'elsey' ); ?><br />
    							<input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
    						</label>
    					</p>
    				<?php endif; ?>
    				<p class="description description-wide">
    					<label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
    						<?php esc_html_e( 'Navigation Label', 'elsey' ); ?><br />
    						<input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
    					</label>
    				</p>
    				<p class="field-title-attribute field-attr-title description description-wide">
    					<label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
    						<?php esc_html_e( 'Title Attribute', 'elsey' ); ?><br />
    						<input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
    					</label>
    				</p>
    				<p class="field-link-target description">
    					<label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
    						<input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
    						<?php esc_html_e( 'Open link in a new tab', 'elsey' ); ?>
    					</label>
    				</p>
    				<p class="field-css-classes description description-thin">
    					<label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
    						<?php esc_html_e( 'CSS Classes (optional)', 'elsey' ); ?><br />
    						<input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
    					</label>
    				</p>
    				<p class="field-xfn description description-thin">
    					<label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
    						<?php esc_html_e( 'Link Relationship (XFN)', 'elsey' ); ?><br />
    						<input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
    					</label>
    				</p>
    				<p class="field-description description description-wide">
    					<label for="edit-menu-item-description-<?php echo esc_attr($item_id); ?>">
    						<?php esc_html_e( 'Description', 'elsey' ); ?><br />
    						<textarea id="edit-menu-item-description-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr($item_id); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
    						<span class="description"><?php esc_html_e('The description will be displayed in the menu if the current theme supports it.', 'elsey'); ?></span>
    					</label>
    				</p>

    				<!-- Custom Changes - New fields action -->
    				<?php do_action('elsey_custom_menu_fields', $item_id, $item); ?>

    				<p class="field-move hide-if-no-js description description-wide">
    					<label>
    						<span><?php esc_html_e( 'Move', 'elsey' ); ?></span>
    						<a href="#" class="menus-move menus-move-up" data-dir="up"><?php esc_html_e( 'Up one', 'elsey' ); ?></a>
    						<a href="#" class="menus-move menus-move-down" data-dir="down"><?php esc_html_e( 'Down one', 'elsey' ); ?></a>
    						<a href="#" class="menus-move menus-move-left" data-dir="left"></a>
    						<a href="#" class="menus-move menus-move-right" data-dir="right"></a>
    						<a href="#" class="menus-move menus-move-top" data-dir="top"><?php esc_html_e( 'To the top', 'elsey' ); ?></a>
    					</label>
    				</p>

    				<div class="menu-item-actions description-wide submitbox">
    					<?php if ( 'custom' != $item->type && $original_title !== false ) : ?>
    						<p class="link-to-original">
    							<?php printf( esc_html__('Original: %s', 'elsey'), '<a href="' . esc_url( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
    						</p>
    					<?php endif; ?>
    					<a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
    					echo wp_nonce_url(
    						add_query_arg(
    							array(
    								'action' => 'delete-menu-item',
    								'menu-item' => $item_id,
    							),
    							admin_url( 'nav-menus.php' )
    						),
    						'delete-menu_item_' . $item_id
    					); ?>"><?php esc_html_e( 'Remove', 'elsey' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo esc_attr($item_id); ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
    						?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php esc_html_e('Cancel', 'elsey'); ?></a>
    				</div>

    				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item_id); ?>" />
    				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
    				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
    				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
    				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
    				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
    			</div><!-- .menu-item-settings-->

                <ul class="menu-item-transport"></ul>
                
            <?php
            $output .= ob_get_clean();
        }
    } // Walker_Nav_Menu_Edit
}

/**
 * VictorThemes Changes are added our own custom fields $item_output.
 * WordPress default commend lines are removed in following code lines.
 * Following code copied form : wp-includes/nav-menu-template.php
 * WordPress version 4.4.0 while copying below code.
 */

if( !class_exists('Walker_Nav_Menu_Custom') ){

    class Walker_Nav_Menu_Custom extends Walker_Nav_Menu {

        public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

        public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

        public function start_lvl( &$output, $depth = 0, $args = array() ) {
            $indent  = str_repeat("\t", $depth);
            if ($depth === 0) {
                $output .= "\n$indent<ul class=\"sub-menu row row-eq-height\">\n";
            } else {
                $output .= "\n$indent<ul class=\"sub-menu\">\n";
            }
        }

        public function end_lvl( &$output, $depth = 0, $args = array() ) {
            $indent  = str_repeat("\t", $depth);
            $output .= "$indent</ul>\n";
        }

        public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
            $indent  = ( $depth ) ? str_repeat( "\t", $depth ) : '';

            $classes = empty( $item->classes ) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;
            $image_cnt = '';
            $image_url = '';
            $hide_title = false;

            if ( $depth == 0 ) {
                if ( $item->mega_menu ) {
    		        $classes[] = 'els-megamenu';
    		    } else {
    		        $classes[] = 'els-dropdown-menu';
    		    }
    		} elseif ( $depth == 1 ) {
                if ( $item->mega_menu_columns ) {
    		        $classes[] = $item->mega_menu_columns;
    		    }
                if ( $item->hide_title ) {
    		        $classes[] = 'els-megamenu-hide-title';
    		        $hide_title = true;
    		    } else {
    		        $classes[] = 'els-megamenu-show-title';
    		        $hide_title = false;
    		    }
                if ( ! empty( $item->image_select ) ) {
                    $attachment = wp_get_attachment_image_src( $item->image_select , 'full' );
                    $image_src  = $attachment[0];
                    $image_url .= '<img src="'.$image_src.'" alt="" class="" />';
                    $classes[] = 'els-megamenu-show-bgimg';
                }
                if ( ! empty( $item->text_one ) || ! empty( $item->text_two ) ) {
                    $image_cnt .= '<span class="els-menubg-text">';
                    if ( ! empty( $item->text_one ) ) {
                        $image_cnt .= '<span class="els-menubg-text-one">'.esc_attr($item->text_one).'</span>';
                    }
                    if ( ! empty( $item->text_two ) ) {
                        $image_cnt .= '<span class="els-menubg-text-two">'.esc_attr($item->text_two).'</span>';
                    }
                    $image_cnt .= '</span>';
                }
    		}

            $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

    	    $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
    	    $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

    	    $output .= $indent . '<li' . $id . $class_names .'>';

	        $atts = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
			$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
			$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

	        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
	        $attributes = '';

			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
                    if ( $value == '#') {
                        if ( $depth != 0 ) {
                          $attributes .= ' onclick="return false;" class="els-title-menu"';
				        } else {
				          $attributes .= ' onclick="return false;"';
				        }
                    }
				}
			}

    	    /** This filter is documented in wp-includes/post-template.php */
    	    $title = apply_filters( 'the_title', $item->title, $item->ID );

    	    $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

    	    $item_output = $args->before;
    	    $item_output .= '<a '. $attributes .'>';
    	    $item_output .= ( $hide_title ) ? '' : $args->link_before . $title . $args->link_after;
            $item_output .= ( ! empty( $item->icon_select ) ) ? '<i class="'. $item->icon_select .'"></i>' : '';
            $item_output .= ( ! empty($image_url) ) ? $image_url : '';
            $item_output .= ( ! empty($image_cnt) ) ? $image_cnt : '';
    	    $item_output .= '</a>';
    	    $item_output .= $args->after;

	        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }

        public function end_el( &$output, $item, $depth = 0, $args = array() ) {
            $output .= "</li>\n";
        }
	} // Walker_Nav_Menu
}