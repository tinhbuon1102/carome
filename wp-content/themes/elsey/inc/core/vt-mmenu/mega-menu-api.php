<?php
/*
 * Mega Menu API
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

require_once( ELSEY_FRAMEWORK . '/core/vt-mmenu/custom-menu-walker.php' );

class elsey_framework_custom_menu {

  public $elsey_custom_fields = array( 'mega_menu', 'icon_select', 'mega_menu_columns', 'hide_title', 'image_select', 'text_one', 'text_two' );
  public $walker = null;

  public function __construct() {
		add_action( 'elsey_custom_menu_fields', array( $this, 'elsey_custom_menu_fields_add_function' ), 10, 2 );
		add_action( 'wp_update_nav_menu_item', array( $this, 'elsey_framework_update_fields'), 10, 3 );

		add_filter( 'wp_setup_nav_menu_item', array( $this, 'elsey_framework_add_fields' ) );
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'elsey_framework_edit_walker'), 10, 2 );
		add_filter( 'wp_nav_menu_args', array( $this, 'wp_nav_menu_args' ), 99 );
  }

  /**
  * Add custom fields
  */
  public function elsey_custom_menu_fields_add_function( $item_id, $item ) {
?>
		<div class="els-menu-mega">
			<span class="els-cf-sep"></span>
			<p class="els-cf-icon_select els-cf-field description description-thin">
				<?php
				  $hidden = ( empty( $item->icon_select ) ) ? ' hidden' : '';
				  $icon_select_class = ( !empty( $item->icon_select ) ) ? $item->icon_select : '';
				?>
			  <span class="els-cf-title">Menu Icon </span><br/>
			  <label for="edit-menu-item-icon_select-<?php echo esc_attr($item_id); ?>" class="cs-icon-select cs-field-icon">
		  		<span class="cs-icon-preview <?php echo esc_attr($hidden); ?>"><i class="<?php echo esc_attr($icon_select_class); ?>"></i></span>
		  		<a href="#" class="button button-primary cs-icon-add">Add Icon</a>
		  		<a href="#" class="button cs-warning-primary cs-icon-remove <?php echo esc_attr($hidden); ?>">Remove Icon</a>
		  		<input type="text" id="edit-menu-item-icon_select-<?php echo esc_attr($item_id); ?>" class="widefat cs-icon-value edit-menu-item-icon_select" name="menu-item-icon_select[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->icon_select ); ?>" />
			  </label>
			</p>
			<p class="els-cf-mega_menu els-cf-field description description-thin cs-field-switcher">
        <span class="els-cf-title">Enable Mega Menu?</span><br/>
        <label for="edit-menu-item-mega_menu-<?php echo esc_attr($item_id); ?>">
          <input type="checkbox" id="edit-menu-item-mega_menu-<?php echo esc_attr($item_id); ?>"<?php checked( $item->mega_menu, 1 ); ?> name="menu-item-mega_menu[<?php echo esc_attr($item_id); ?>]" value="1" />
          <em data-on="on" data-off="off"></em><span></span>
        </label>
			</p>
			<span class="els-cf-sep"></span>
		</div>

	  <!-- Columns -->
	  <div class="els-menu-columns">
      <span class="els-cf-sep"></span>
      <p class="els-cf-mega_menu_columns els-cf-field description description-thin">
        <span class="els-cf-title">Mega Menu Columns</span>
        <label for="edit-menu-item-mega_menu_columns-<?php echo esc_attr($item_id); ?>">
  			<select id="edit-menu-item-mega_menu_columns-<?php echo esc_attr($item_id); ?>" name="menu-item-mega_menu_columns[<?php echo esc_attr($item_id); ?>]">
          <option value="">Select Columns</option>
          <?php
            $mega_menu_columns = array(
            'col-lg-1 col-md-1 col-sm-1'    => '1 Column',
            'col-lg-2 col-md-2 col-sm-2'    => '2 Column',
            'col-lg-3 col-md-3 col-sm-3'    => '3 Column',
            'col-lg-4 col-md-4 col-sm-4'    => '4 Column',
            'col-lg-5 col-md-5 col-sm-5'    => '5 Column',
            'col-lg-6 col-md-6 col-sm-6'    => '6 Column (half)',
            'col-lg-7 col-md-7 col-sm-7'    => '7 Column',
            'col-lg-8 col-md-8 col-sm-8'    => '8 Column',
            'col-lg-9 col-md-9 col-sm-9'    => '9 Column',
            'col-lg-10 col-md-10 col-sm-10' => '10 Column',
            'col-lg-11 col-md-11 col-sm-11' => '11 Column',
            'col-lg-12 col-md-12 col-sm-12' => '12 Column (full-width)'
            );
            foreach ($mega_menu_columns as $key => $value) {
                echo '<option value="'. esc_attr($key) .'"'. selected($key, $item->mega_menu_columns) .'>'. esc_attr($value) .'</option>';
            } ?>
  			</select>
        </label>
      </p>
		  <p class="els-cf-hide_title els-cf-field description description-thin cs-field-switcher">
        <span class="els-cf-title">Hide Title?</span><br/>
        <label for="edit-menu-item-hide_title-<?php echo esc_attr($item_id); ?>">
            <input type="checkbox" id="edit-menu-item-hide_title-<?php echo esc_attr($item_id); ?>"<?php checked( $item->hide_title, 1 ); ?> name="menu-item-hide_title[<?php echo esc_attr($item_id); ?>]" value="1" />
            <em data-on="on" data-off="off"></em><span></span>
        </label>
      </p>
      <p class="els-cf-image_select els-cf-field description description-thin els-cs-image" style="float: inherit;">
        <?php
        $preview = '';
        $ahidden = '';
        $value   = $item->image_select;
        $hidden  = ( empty( $value ) ) ? ' hidden' : '';
        if( ! empty( $value ) ) {
          $attachment = wp_get_attachment_image_src( $value, 'thumbnail' );
          $preview    = $attachment[0];
          $ahidden    = ' hidden';
        } ?>
        <span class="els-cf-title"><?php echo esc_html__('Menu Image ', 'elsey'); ?></span><br/>
        <label for="edit-menu-item-image_select-<?php echo esc_attr($item_id); ?>" class="cs-field-image">
          <span class="cs-image-preview <?php echo esc_attr($hidden); ?>">
            <span class="cs-preview"><i class="fa fa-times cs-remove"></i><img src="<?php echo esc_url($preview); ?>" alt="preview" /></span>
          </span>
          <a href="javascript:void(0);" class="button button-primary cs-add <?php echo esc_attr($ahidden); ?>"><?php echo esc_html__('Add Image', 'elsey'); ?></a>
          <input type="text" id="edit-menu-item-image_select-<?php echo esc_attr($item_id); ?>" class="widefat cs-image-value edit-menu-item-image_select hidden" name="menu-item-image_select[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item->image_select); ?>" />
        </label>
      </p>
      <p class="els-cf-text_one els-cf-field description description-thin">
        <span class="els-cf-title"><?php echo esc_html__('Details Text One ', 'elsey'); ?></span><br/>
        <label for="edit-menu-item-text_one-<?php echo esc_attr($item_id); ?>" class="cs-field-text">
          <input type="text" id="edit-menu-item-text_one-<?php echo esc_attr($item_id); ?>" name="menu-item-text_one[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item->text_one); ?>">
        </label>
      </p>
      <p class="els-cf-text_two els-cf-field description description-thin">
        <span class="els-cf-title"><?php echo esc_html__('Details Text Two ', 'elsey'); ?></span><br/>
        <label for="edit-menu-item-text_two-<?php echo esc_attr($item_id); ?>" class="cs-field-text">
          <input type="text" id="edit-menu-item-text_two-<?php echo esc_attr($item_id); ?>" name="menu-item-text_two[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item->text_two); ?>">
        </label>
      </p>
      <span class="els-cf-sep"></span>
	  </div>
  <?php
  }

	/**
	 * Add custom fields to $menu_item nav object
	*/
	public function elsey_framework_add_fields( $menu_item ) {
    foreach ( $this->elsey_custom_fields as $key ) {
      $menu_item->$key = get_post_meta( $menu_item->ID, '_menu_item_'. $key, true );
    }
    return $menu_item;
	}

	/**
	 * Save menu custom fields
	*/
	public function elsey_framework_update_fields( $menu_id, $menu_item_db_id, $args ) {
    foreach ( $this->elsey_custom_fields as $key ) {
      $value = ( isset( $_REQUEST['menu-item-'.$key][$menu_item_db_id] ) ) ? $_REQUEST['menu-item-'.$key][$menu_item_db_id] : '';
      update_post_meta( $menu_item_db_id, '_menu_item_'. $key, $value );
    }
	}

	/**
	 * Setting these cutomization into core function of WordPress : wp_nav_menu()
	*/
	public function wp_nav_menu_args( $args ) {
    $walker = new Walker_Nav_Menu_Custom();
    $args['container'] = false;
    $args['menu_class'] = 'main-navigation';
    $args['walker'] = $walker;

    return $args;
	}

	/**
	 * Define new Walker edit
	*/
	public function elsey_framework_edit_walker($walker,$menu_id) {
    return 'Walker_Nav_Menu_Edit_Custom';
	}

}

// instantiate plugin's class
$GLOBALS['elsey_custom_menu'] = new elsey_framework_custom_menu();