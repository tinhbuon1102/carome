<?php
/*
Plugin Name: Open Swatch - Woocommerce Color Swatch
Plugin URI: http://openswatch.com/
Description: Swatch color for woocommerce.
Author: anhvnit@gmail.com
Author URI: http://openswatch.com/
Version: 3.5
Text Domain: open-swatch
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
define('OPENSWATCH_PATH',plugin_dir_path(__FILE__));
define('OPENSWATCH_URI',plugins_url('openswatch'));

require_once OPENSWATCH_PATH.'modules/options/options-framework.php';
require_once OPENSWATCH_PATH . '/modules/class-tgm-plugin-activation.php';
require_once OPENSWATCH_PATH.'/includes/class-color-swatch.php';
if(!class_exists('WC_Openswatch_Widget'))
{
    include_once( OPENSWATCH_PATH.'/includes/abstract-wc-widget.php' );
}

require_once OPENSWATCH_PATH.'/includes/class-wc-widget-layered-nav.php';
require_once OPENSWATCH_PATH.'/includes/class-wc-widget-price-filter.php';

if(!function_exists('openswatch_theme_header_script'))
{
    function openswatch_theme_header_script() {
        echo '
        <script type="text/javascript" >
            var openwatch_ajax_url = "'.admin_url('admin-ajax.php').'";
            var openwatch_swatch_attr = "'.esc_attr( sanitize_title(openwatch_get_option('openwatch_attribute_image_swatch'))).'";
        </script>';

    }
}


//frontend
if(!function_exists('openswatch_name_scripts'))
{
    function openswatch_name_scripts()
    {
        global $post;
        $colorswatch = new ColorSwatch();
        $swatch_attr = openwatch_get_option('openwatch_attribute_image_swatch');

        $is_custom_attr = false;

        if($tsw = get_post_meta($post->ID,'_openswatch_attribute_gallery',true))
        {
            $swatch_attr = esc_attr( sanitize_title(get_post_meta($post->ID,'_openswatch_attribute_gallery',true)));


        }
        if(strpos($swatch_attr,'pa_') === false)
        {
            $is_custom_attr = true;
        }
        wp_register_script('openswatch', OPENSWATCH_URI . '/assets/js/openswatch.js', array('jquery','wc-single-product'));
        wp_localize_script('openswatch', 'openwatch_ajax_url', admin_url('admin-ajax.php'));
        wp_localize_script('openswatch', 'openwatch_swatch_attr', esc_attr(sanitize_title($swatch_attr)));
        wp_localize_script('openswatch', 'openwatch_pre_select', esc_attr(sanitize_title(openwatch_get_option('openwatch_attribute_pre_select'))));
        if (is_product())
        {

            global $product;
            $product_id = $post->ID;
            wp_localize_script('openswatch', 'openwatch_product_id', esc_attr($product_id));
            $_pf = new WC_Product_Factory();
            $product = $_pf->get_product($product_id);
            $vars = array();
            $attributes = $product->get_attributes();
            if($is_custom_attr)
            {
                if(isset($attributes[$swatch_attr]))
                {
                    $swatchAttribute = $attributes[$swatch_attr];
                    $term_data = $swatchAttribute->get_data();
                    $terms = isset($term_data['options']) ? $term_data['options'] : array();
                    foreach($terms as $term)
                    {
                        $slug = strtolower(esc_attr(sanitize_title($term)));
                        $tmp = get_post_meta( $product_id, '_product_image_swatch_gallery', true );

                        $option = $slug;
                        $s_img = array();

                        if(isset($tmp[$option]) || $option == 'null') {
                            if ($option == 'null') {
                                $attachment_ids = array(get_post_thumbnail_id($product_id));
                                $attachment_ids = array_merge($attachment_ids, $product->get_gallery_attachment_ids());
                            } else {
                                $attachment_ids = explode(',', $tmp[$option]);
                            }

                            $s_img = $colorswatch->openswatch_get_product_gallery_html($attachment_ids);
                        }
                        $vars[$slug] = $s_img;
                    }
                }
            }else{
                $terms = wc_get_product_terms( $product_id, $swatch_attr, array( 'fields' => 'all' ) );
                foreach($terms as $t)
                {


                    $slug = $t->slug;

                    $tmp = get_post_meta( $product_id, '_product_image_swatch_gallery', true );

                    $option = $slug;
                    $s_img = array();

                    if(isset($tmp[$option]) || $option == 'null') {
                        if ($option == 'null') {
                            $attachment_ids = array(get_post_thumbnail_id($product_id));
                            $attachment_ids = array_merge($attachment_ids, $product->get_gallery_attachment_ids());
                        } else {
                            $attachment_ids = explode(',', $tmp[$option]);
                        }

                        $s_img = $colorswatch->openswatch_get_product_gallery_html($attachment_ids);
                    }
                    $vars[$slug] = $s_img;
                }
            }





            wp_localize_script('openswatch', 'openwatch_galleries', $vars);
        }
        if(file_exists(get_stylesheet_directory().'/openswatch/assets/js/openswatch.js'))
        {

            wp_register_script( 'openswatch_custom', get_stylesheet_directory_uri().'/openswatch/assets/js/openswatch.js',array('jquery','openswatch') );
        }else{

            wp_register_script( 'openswatch_custom', OPENSWATCH_URI.'/assets/js/openswatch_custom.js',array('jquery','openswatch') );
        }

        wp_register_style('openswatch', OPENSWATCH_URI.'/assets/css/openswatch.css');

        wp_register_script( 'tooltipster', OPENSWATCH_URI.'/assets/js/jquery.tooltipster.min.js',array('jquery') );
        wp_register_style('tooltipster', OPENSWATCH_URI.'/assets/css/tooltipster.css');

        wp_enqueue_script('openswatch');
        wp_enqueue_script('openswatch_custom');
        wp_enqueue_style('openswatch');

        if(openwatch_get_option('openwatch_attribute_tooltips'))
        {
            wp_enqueue_script('tooltipster');
            wp_enqueue_style('tooltipster');
        }

    }
}


add_action( 'wp_enqueue_scripts', 'openswatch_name_scripts' );

add_filter('wc_get_template','openswatch_get_template',10,5);
if(!function_exists('openswatch_woocommerce_get_item_data'))
{
    function openswatch_woocommerce_get_item_data( $item_data, $cart_item)
    {
        $new_data = array();
        $product_id = $cart_item['product_id'];
        $_pf = new WC_Product_Factory();
        $_product = $_pf->get_product($product_id);

        $colorswatch = new ColorSwatch();
        if($colorswatch->enableOnProduct($_product))
        {
            foreach($item_data as $val)
            {
                $tmp_data = $val;

                $attribute = esc_attr( 'pa_'.strtolower($val['key']) ) ;

                $terms = wc_get_product_terms( $product_id, $attribute, array( 'fields' => 'all' ) );
                $image = false;
                foreach($terms as $t)
                {
                    if($t->name == $val['value'])
                    {
                        $term_id = $t->term_id;
                        $image = $colorswatch->getSwatchImage($term_id,$product_id);
                    }
                }
                $tmp_data['image'] = $image;
                $new_data[] = $tmp_data;
            }
            return $new_data;
        }else{
            return $item_data;
        }

    }
}
add_filter( 'woocommerce_get_item_data','openswatch_woocommerce_get_item_data',10,2 );
function openswatch_get_template($located, $template_name, $args, $template_path, $default_path)
{
   if($template_name == 'cart/cart-item-data.php')
    {

        if (file_exists(get_stylesheet_directory() . '/openswatch/' . $template_name)) {
            return get_stylesheet_directory().'/openswatch/'. $template_name;
        } else {

            return OPENSWATCH_PATH . 'templates/' . $template_name;
        }

    }

    return $located;
}

// Require woocommerce plugin
add_action( 'tgmpa_register', 'openswatch_register_required_plugins' );
function openswatch_register_required_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        array(
            'name'        => 'Woocommerce',
            'slug'        => 'woocommerce',
            'required'    => true,
        )
    );

    $config = array(
        'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'plugins.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.

    );
    tgmpa( $plugins, $config );
}


if(! function_exists('openswatch_price_ranges'))
{
    function openswatch_price_ranges()
    {
        $ranges = openwatch_get_option('openwatch_price_range');
        $result = false;
        if($ranges != '')
        {
            $result = array();
            $lines = explode(PHP_EOL,$ranges);
            foreach($lines as $line)
            {
                $tmp = explode('|',trim($line));
                if(count($tmp) == 2)
                {
                    $key = esc_attr(trim($tmp[0]));
                    $value = trim($tmp[1]);
                    $tmp = explode(',',$key);
                    $min = isset($tmp[0]) ? $tmp[0] : 0;
                    $max = isset($tmp[1]) ? $tmp[1] : 10000000;
                    $key = implode(',',array($min,$max));
                    $value = array(
                        'label' => $value,
                        'min' => $min,
                        'max' => $max
                    );
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }

}

function openswatch_register_widgets() {
    register_widget( 'OpenSwatch_Widget_Layered_Nav' );
    register_widget( 'OpenSwatch_Widget_Price_Filter' );
}
add_action( 'widgets_init', 'openswatch_register_widgets' );




if(!function_exists('openswatch_price_filter'))
{
    function openswatch_price_filter($post_in)
    {
        global $woocommerce;
        $woo_version = $woocommerce->version;
        if ( (isset( $_GET['max_price'] ) || isset( $_GET['min_price'] ) ) && ( $_GET['min_price'] !='' || $_GET['max_price'] !='') ) {

            if($woo_version >= 2.6)
            {
                global $wpdb, $wp_the_query;
                $filtered = wp_list_pluck( $wp_the_query->posts, 'ID' );
            }else{
                $filtered = WC()->query->price_filter();
            }


            if(is_array($post_in) && !empty($post_in))
            {
                $tmp = array_intersect($filtered,$post_in);
                if(empty($tmp))
                {
                    return array(0);
                }
                return array_intersect($filtered,$post_in);
            }

            return $filtered;
        }

        return $post_in;
    }
}
if(!function_exists('openswatch_price_filter_init'))
{
    function openswatch_price_filter_init()
    {
        if ( (isset( $_GET['max_price'] ) || isset( $_GET['min_price'] ) ) && ( $_GET['min_price'] !='' || $_GET['max_price'] !='') ) {
            //add_filter( 'loop_shop_post_in', 'openswatch_price_filter',1000,1 );
        }

    }
}
add_action('init','openswatch_price_filter_init');

if(!function_exists('openswatch_is_layered_nav_active'))
{
    function openswatch_is_layered_nav_active($active)
    {
        if(!$active)
        {
            if(is_active_widget( false, false, 'openswatch_layered_nav', true ))
            {
                $active = true;
            }
        }
        return $active;
    }
}

add_filter('woocommerce_is_layered_nav_active','openswatch_is_layered_nav_active',10,1);

if(!function_exists('openswatch_wc_ajax_variation_threshold'))
{
    function openswatch_wc_ajax_variation_threshold( $qty, $product ) {
        return 1000;
    }
}

add_filter( 'woocommerce_ajax_variation_threshold', 'openswatch_wc_ajax_variation_threshold', 100, 2 );
if(!function_exists('openswatch_get_thumbnail_id_metadata')) {
    function openswatch_get_thumbnail_id_metadata($tmp, $object_id, $meta_key, $single){
        global $in_openswatch;

        if($meta_key == '_thumbnail_id' && $in_openswatch)
        {
            global $openswatch_attachement_ids;
            if($openswatch_attachement_ids && !empty($openswatch_attachement_ids))
            {
                return $openswatch_attachement_ids[0];
            }
        }
        if($meta_key == '_product_image_gallery'&& $in_openswatch)
        {

            global $openswatch_attachement_ids;
            $tmp_attr = $openswatch_attachement_ids;
            $first_thumb = $tmp_attr[0];
            unset($tmp_attr[0]);
            $openswatch_attachement_ids = array_filter($tmp_attr);
            return apply_filters( 'openswatch_get_product_image_gallery', $openswatch_attachement_ids,$first_thumb );
        }

    }
}
add_filter('get_post_metadata','openswatch_get_thumbnail_id_metadata',10,4);

if(!function_exists('openswatch_woocommerce_product_get_gallery_image_ids'))
{
    function openswatch_woocommerce_product_get_gallery_image_ids($value, $obj)
    {
        global $in_openswatch;
        global $openswatch_attachement_ids;
        if($in_openswatch && $openswatch_attachement_ids)
        {
            $tmp_attr = $openswatch_attachement_ids;
            $thumb_id = $tmp_attr[0];
            unset($tmp_attr[0]);
            $openswatch_attachement_ids = array_values($tmp_attr);
            $openswatch_attachement_ids = array_filter($openswatch_attachement_ids, function($value) { return $value !== ''; });
            $openswatch_attachement_ids = apply_filters( 'openswatch_get_gallery_image_ids', $openswatch_attachement_ids,$thumb_id );

            return $openswatch_attachement_ids;
        }
        return $value;
    }
}
add_filter('woocommerce_product_get_gallery_image_ids','openswatch_woocommerce_product_get_gallery_image_ids',10,2);

if(!function_exists('openswatch_woocommerce_dropdown_variation_attribute_options_html'))
{
    function openswatch_woocommerce_dropdown_variation_attribute_options_html($html,$args)
    {
        global $sitepress;
        if($sitepress)
        {
            $default_lang = $sitepress->get_default_language();
        }

        $colorswatch = new ColorSwatch();
        $product               = $args['product'];
        $attribute             = $args['attribute'];
        $options               = $args['options'];
        $swatch_attrs = openwatch_get_option('openwatch_attribute_swatch');
        $attribute_code = esc_attr( sanitize_title($args['attribute']));
        $isSwatchGalleryAttribute = false;


        $swatch_style = false;
        $swatch_attributes = get_post_meta($product->get_id(),'_openswatch_attribute_style',true);
        $swatch_attribute_gallery = get_post_meta($product->get_id(),'_openswatch_attribute_gallery',true);

        if(is_array($swatch_attributes) && in_array($attribute_code,$swatch_attributes))
        {
            $swatch_style = true;
        }
        if(!is_array($swatch_attributes) && $attribute_code == $swatch_attributes )
        {
            $swatch_style = true;
        }
        if($swatch_attribute_gallery && $swatch_attribute_gallery == $attribute_code)
        {
           // $isSwatchGalleryAttribute = true;
        }

        if($colorswatch->enableOnProduct($product) && ($swatch_style || $isSwatchGalleryAttribute))
        {

            $html  = '<div style="display: none;">'.$html.'</div>';
            $html .= '<div class="value op-swatch-value" >';
            $html .= ' <ul  id="'.  esc_attr( sanitize_title( $attribute ) ).'" class="swatch">';

            if ( ! empty( $options ) ) {
                if ( $product && taxonomy_exists( $attribute ) ) {
                    // Get terms if this is a taxonomy - ordered. We need the names too.
                    $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

                    foreach ( $terms as $term ) {
                        $style = '';
                        $slug = esc_attr( sanitize_title( $term->name ) );
                        $image = ColorSwatch::getSwatchImage($attribute_code,$slug,$product->get_id());
                        $color = ColorSwatch::getSwatchColor($attribute_code,$slug,$product->get_id());

                        if($sitepress)
                        {
                            $get_origtermid = icl_object_id( $term->term_id, $term->taxonomy, false, $default_lang);
                            //$term = get_term($get_origtermid);
                            $image = ColorSwatch::getSwatchImage($attribute_code,$slug,$product->get_id());
                        }
                        if ( $image ) {

                        }
                        if ( in_array( $term->slug, $options ) ) {
                            $selected =  sanitize_title( $args['selected']) == $term->slug ? 'selected' : '';
                            if($image)
                            {
                                $selected .= ' has-image';
                                $style .= " background-image: url('".$image."'); text-indent: -999em;'";
                            }
                            if($color)
                            {
                                $selected .= ' has-color';
                                $style .= " background-color:".$color."; text-indent: -999em;'";
                            }
                            $html .= '<li data-toggle="tooltip" title="'.$term->name.'" data-vslug="'.  esc_attr( sanitize_title( $term->name ) ).'" option-value="' . esc_attr( $term->slug ) . '" class="swatch-item ' . $selected . '"><span style="'.$style.'" >' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</span></li>';
                        }
                    }
                } else {

                    foreach ( $options as $option ) {
                        $style = '';
                        $slug = esc_attr( sanitize_title( $option) );
                        $color = ColorSwatch::getSwatchColor($attribute_code,$slug,$product->get_id());

                        $image = ColorSwatch::getSwatchImage($attribute_code,$slug,$product->get_id());

                        // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                        $selected = sanitize_title( $option ) == $args['selected'] ? 'selected' : '';
                        if($image)
                        {
                            $selected .= ' has-image';
                            $style .= " background-image: url('".$image."'); text-indent: -999em;'";
                        }
                        if($color)
                        {
                            $selected .= ' has-color';
                            $style .= " background-color:".$color."; text-indent: -999em;'";
                        }
                        $html .= '<li data-toggle="tooltip" title="'.$option.'" data-vslug="'.  esc_attr( sanitize_title( $option ) ).'" option-value="' . esc_attr( $option ) . '" class="swatch-item ' . $selected . '"><span style="'.$style.'" >' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</span></li>';
                    }
                }
            }

            $html .= '</ul>';
            $html .= '</div>';

        }

        return $html;
    }
}

add_filter('woocommerce_dropdown_variation_attribute_options_html','openswatch_woocommerce_dropdown_variation_attribute_options_html',10,2);

if(!function_exists('openswatch_woocommerce_after_add_to_cart_form'))
{
    function openswatch_woocommerce_after_add_to_cart_form(){
        global $product;
        $colorswatch = new ColorSwatch();
        if($colorswatch->enableOnProduct($product))
        {
            $template_name = 'swatch.php';
            if (file_exists(get_stylesheet_directory() . '/openswatch/' . $template_name)) {
                $located =  get_stylesheet_directory().'/openswatch/'. $template_name;
            } else {

                $located =  OPENSWATCH_PATH . 'templates/' . $template_name;
            }
            load_template( $located, false );
        }
       
    }
}
add_action('woocommerce_after_add_to_cart_form','openswatch_woocommerce_after_add_to_cart_form');

if(!function_exists('openswatch_woocommerce_cart_item_thumbnail'))
{
    function openswatch_woocommerce_cart_item_thumbnail($img,$item,$item_key){
        $s_att = openwatch_get_option('openwatch_attribute_image_swatch');
        $colorswatch = new ColorSwatch();
        $cart_varition = 'attribute_'.$s_att;

        if(isset($item['variation']) && isset($item['variation'][$cart_varition]))
        {
            $variation = $item['variation'][$cart_varition];

            $img = $colorswatch->getSwatchProductThumb($item['product_id'],$variation);

        }
        return $img;
    }
}
add_filter('woocommerce_cart_item_thumbnail','openswatch_woocommerce_cart_item_thumbnail',10,3);

//start new update

if(!function_exists('openswatch_woocommerce_after_product_attribute_settings'))
{
    function openswatch_woocommerce_after_product_attribute_settings( $attribute, $i ){
        global $post;

        $swatch = esc_attr( sanitize_title(openwatch_get_option('openwatch_attribute_image_swatch')));

        $taxonomy =  sanitize_title($attribute->get_name());
        if($post->ID)
        {
            $swatch = esc_attr( sanitize_title(get_post_meta($post->ID,'_openswatch_attribute_gallery',true)));
        }

        $_openswatch_attribute_style = get_post_meta( $post->ID, '_openswatch_attribute_style',true );
        $is_style = false;
        
        if($_openswatch_attribute_style && is_array($_openswatch_attribute_style) && in_array($taxonomy,$_openswatch_attribute_style))
        {
            $is_style = true;
        }
        ?>

        <tr>
            <td>
                <div class="enable_variation show_if_variable">
                    <label><input type="radio" class="checkbox" <?php checked( $swatch, $taxonomy ); ?> name="_openswatch_attribute_gallery" value="<?php echo $i; ?>" /> <?php _e( 'Used for swatch gallery', 'woocommerce' ); ?></label>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="enable_variation show_if_variable">
                    <label><input type="checkbox" class="checkbox" <?php checked( $is_style ); ?> name="_openswatch_attribute_style[<?php echo $i; ?>]" value="1" /> <?php _e( 'Use swatch style', 'woocommerce' ); ?></label>
                </div>
            </td>
        </tr>

        <?php
    }
}

add_action("woocommerce_after_product_attribute_settings","openswatch_woocommerce_after_product_attribute_settings",10,2);



//start continue custom
function op_woocommerce_process_product_meta_variable($post_id)
{

}
add_action('woocommerce_process_product_meta_variable','op_woocommerce_process_product_meta_variable',10,1);


