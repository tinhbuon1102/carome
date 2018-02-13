<?php
/**
 * Created by PhpStorm.
 * User: Vu Anh
 * Date: 7/1/2015
 * Time: 9:58 PM
 */

class ColorSwatch
{
    public function init()
    {

        add_action('admin_enqueue_scripts', array($this, 'upload_scripts'));
        
        $attrs = openwatch_get_option('openwatch_attribute_swatch');

        if(is_array($attrs))
        {
            foreach($attrs as $key => $val)
            {
                if($val)
                {
                    add_action( $key.'_add_form_fields', array( $this, 'add_attribute_fields' ) );
                    add_action( $key.'_edit_form_fields', array( $this, 'edit_attribute_fields' ), 10 );
                    add_action( 'created_term', array( $this, 'save_attribute_fields' ), 10, 3 );
                    add_action( 'edit_term', array( $this, 'save_attribute_fields' ), 10, 3 );
                }
            }
        }
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );

        add_action( 'woocommerce_process_product_meta', array( $this, 'save_image_swatch' ), 20, 2 );


        add_action('wp_ajax_openwatch_swatch_images', array($this,'swatch_images'));
        add_action("wp_ajax_nopriv_openwatch_swatch_images", array($this,'swatch_images'));

        

        add_action( 'woocommerce_product_write_panel_tabs', array( $this, 'create_admin_tab' ) );
        add_action( 'woocommerce_product_data_panels', array( $this, 'create_admin_tab_content' ) );

        add_action( 'woocommerce_process_product_meta', array($this,'save_product_attribute_swatch'),1 );


        //filter
        add_action('woocommerce_product_query',array($this,'openswatch_woocommerce_product_query'),55);

        //list
        if(openwatch_get_option('openwatch_attribute_product_list'))
        {

            add_action('woocommerce_before_shop_loop_item_title',array($this,'woocommerce_after_shop_loop_item'),11);
        }
        //add product setting
        add_action('wp_ajax_woocommerce_save_attributes_openswatch', array($this,'woocommerce_save_attributes_openswatch'));
        add_action('wp_ajax_woocommerce_save_attributes',array($this,'op_wp_ajax_woocommerce_save_attributes'),10);
        add_action('wp_ajax_openswatch_load_swatch_attributes',array($this,'openswatch_load_swatch_attributes'),10);

    }

    public function woocommerce_after_shop_loop_item()
    {
        global $post;
        $_pf = new WC_Product_Factory();
        $product = $_pf->get_product($post->ID);
        $attributes = $product->get_attributes();

        $swatch = false;
        if($tsw = get_post_meta($post->ID,'_openswatch_attribute_gallery',true))
        {
            $swatch = esc_attr( sanitize_title(get_post_meta($post->ID,'_openswatch_attribute_gallery',true)));

        }


        if($product->get_type() == 'variable' && $swatch )
        {

            $tmp = get_post_meta( $post->ID, '_allow_openswatch', true );
            $enable_all = openwatch_get_option('openwatch_enable_all_products');
            if($enable_all > 0)
            {
                $tmp = 1;
            }

            if(isset($attributes[$swatch]) && $tmp != 0) {
                $attribute = $attributes[$swatch];
                $html = '</a>';
                $tmp = get_post_meta( $product->get_id(), '_product_image_swatch_gallery', true );
                $attribute = $attribute->get_data();




                if ( $attribute['is_taxonomy'] ) {

                    $swatch_data = wc_get_product_terms( $product->get_id(), $attribute['name'], array( 'fields' => 'names' ) );

                } else {
                    $swatch_data = array_map( 'trim', explode( '|', $attribute['value'] ) );
                }

                if(!empty($swatch_data))
                {

                    $values = array();

                    foreach($swatch_data as $val)
                    {
                        $slug =  esc_attr(sanitize_title($val));
                        $values[$slug]['color'] = ColorSwatch::getSwatchColor($swatch,$slug,$product->get_id());
                        $values[$slug]['image'] = ColorSwatch::getSwatchImage($swatch,$slug,$product->get_id());
                        $values[$slug]['title'] = $val;
                        $thumb = isset($tmp[$slug]) ? $tmp[$slug] : array();
                        $thumbs = explode(',',$thumb);
                        $thumb_image = '';
                        if(!empty($thumbs))
                        {
                            $thumb_image =wp_get_attachment_thumb_url( $thumbs[0] );
                        }
                        $values[$slug]['thumb'] = apply_filters('op_product_list_thum',$thumb_image,$thumbs);
                    }
                }


                if (!empty($values)) {
                    $html .= '<div class="item-colors product-list-color-swatch"><ul>';



                    foreach ($values as $key => $value) {

                        $image = $value['thumb'];

                        if($value['color'] || $value['image'])
                        {

                            $sw_image = $value['image'];
                            $sw_color = $value['color'];

                            $style = '';
                            if ( $sw_image ) {
                                $style = "background: url('".$sw_image."');background-size: cover; text-indent: -999em;'";

                            }
                            if ( $sw_color ) {
                                $style = "background-color: ".$sw_color."; text-indent: -999em;'";
                            }

                            $html .= '<li class="catalog-swatch-item"><a href="javascript:void(0);" data-thumb="'.$image.'" class="catalog-swatch" swatch="'.$key.'" style="'.$style.'">'.$value['title'].'</a></li>';
                        }else{

                            $html .= '<li><a href="javascript:void(0);" data-thumb="'.$image.'"  class="no-img">'.$value['title'].'</a></li>';
                        }

                    }
                    $html .= '</ul></div>';
                }

                echo balanceTags($html);
            }
        }



    }

    public function add_setting_boxes()
    {
        global $post;
        $tmp = get_post_meta( $post->ID, '_allow_openswatch', true );
        if($tmp != 0)
        {
            $tmp = 1;
        }

        $_pf = new WC_Product_Factory();
        $product = $_pf->get_product($post->ID);
        if($product->get_type() == 'variable')
        {
        ?>
            <div class="openswatch-product-setting" style="text-align: center;">
                <?php _e('Enable Openswatch for this product','openswatch');?>
                <select name="allow_openswatch">
                    <option <?php echo selected(0,$tmp)?> value="0"><?php _e('No','openswatch'); ?></option>
                    <option <?php echo selected(1,$tmp)?> value="1"><?php _e('Yes','openswatch'); ?></option>
                </select>
            </div>
        <?php
        }else{
            return;
        }
    }

    public function woocommerce_template_loop_product_thumbnail()
    {
        global $post;
        $_pf = new WC_Product_Factory();
        $product = $_pf->get_product($post->ID);
        $attributes = $product->get_attributes();
        $swatch = esc_attr( sanitize_title(openwatch_get_option('openwatch_attribute_image_swatch')));

        if(isset($attributes[$swatch]))
        {

            $tmp = get_post_meta( $product->get_id(), '_product_image_swatch_gallery', true );

            if(!$tmp && $product->get_type() == 'variable')
            {
                $variations = $product->get_available_variations();
                $tmp = array();

                foreach($variations as $variation)
                {
                    $id = $variation['variation_id'];
                    if(isset($variation['attributes']['attribute_'.$swatch]) )
                    {
                        if($variation['image_src'] != '')
                        {
                            $option = $variation['attributes']['attribute_'.$swatch];
                            $vari = new WC_Product_Variation($id);
                            $tmp[$option] = $vari->get_image_id();
                        }
                    }
                }
            }
            if($tmp)
            {
                foreach($tmp as $option => $value)
                {

                    $attachment_ids = array_filter(explode(',',$value));
                    $html = '';



                    if(!empty($attachment_ids))
                    {
                        $attr = array('style'=>"display:none;",'swatch' =>$option);
                        $post_thumbnail_id = (int)$attachment_ids[0];
                        $size = apply_filters( 'post_thumbnail_size', 'shop_catalog' );
                        if ( $post_thumbnail_id ) {

                            do_action( 'begin_fetch_post_thumbnail_html', $post->ID, $post_thumbnail_id, $size );
                            if ( in_the_loop() )
                                update_post_thumbnail_cache();
                            $html = wp_get_attachment_image( $post_thumbnail_id, $size, false, $attr );
                            do_action( 'end_fetch_post_thumbnail_html', $post->ID, $post_thumbnail_id, $size );
                        }
                        echo apply_filters( 'post_thumbnail_html', $html, $post->ID, $post_thumbnail_id, $size, $attr );
                    }

                }
            }
        }
    }

    public static function getSwatchImage($attribute_code,$slug,$product_id = 0)
    {
        $_vna_product_swatch = get_post_meta( $product_id, '_vna_product_swatch', true  );
        $image = false;
        if(isset($_vna_product_swatch[$attribute_code]) && isset($_vna_product_swatch[$attribute_code][$slug]))
        {
            $attachment_id = absint( $_vna_product_swatch[$attribute_code][$slug]);

            if((int)$attachment_id > 0)
            {
                $image = wp_get_attachment_thumb_url( $attachment_id );
            }
            return $image;
        }
        return false;
    }

    public static function getSwatchColor($attribute_code,$slug,$product_id = 0)
    {
        $_vna_product_swatch = get_post_meta( $product_id, '_vna_product_swatch_color', true  );

        if(isset($_vna_product_swatch[$attribute_code]) && isset($_vna_product_swatch[$attribute_code][$slug]))
        {

            return $_vna_product_swatch[$attribute_code][$slug];
        }
        return false;
    }

    public function save_product_attribute_swatch($post_id){
        $swatch = array();
        $swatch_color = array();
        if(isset($_POST['_op_change_product_swatch']))
        {
            if(isset($_POST['product_swatch']))
            {
                $swatch = $_POST['product_swatch'];
            }

            update_post_meta( $post_id, '_vna_product_swatch', $swatch  );
            if(isset($_POST['product_swatch_color']))
            {
                $swatch_color = $_POST['product_swatch_color'];
            }
            update_post_meta( $post_id, '_vna_product_swatch_color', $swatch_color  );
        }

    }

    public function create_admin_tab()
    {
        global $post;
        $tmp = get_post_meta( $post->ID, '_allow_openswatch', true );
        $enable_all = openwatch_get_option('openwatch_enable_all_products');
        if($enable_all > 0)
        {
            $tmp = 1;
        }
        if($tmp != 0 || true) {
            ?>
            <li class="openswatch_tab show_if_variable">
                <a href="#openswatch_tab_data_ctabs" id="openswatch_tab_option_swatch">
                    <?php _e('Open Swatch', 'openswatch'); ?>
                </a>
            </li>

        <?php
        }
    }

    public function create_admin_tab_content()
    {

        ?>
        <div id="openswatch_tab_data_ctabs" class="panel woocommerce_options_panel" style="padding-left: 10px;"></div>
        <?php
    }


    public function add_meta_boxes()
    {
        global $post;
        $_pf = new WC_Product_Factory();
        $product = $_pf->get_product($post->ID);
        if($post->post_type == 'product' && $product->get_type() == 'variable')
        {
            $enable = openwatch_get_option('openwatch_enable_all_products');
            if($enable != 1)
            {
                add_meta_box( 'look-product-images-swatch-setting',__('Allow Colorswatch','openwatch'), array($this,'add_setting_boxes'), 'product', 'side', 'high' );
            }
            //$attr = openwatch_get_option('openwatch_attribute_image_swatch');
            $attr = esc_attr( sanitize_title(get_post_meta($post->ID,'_openswatch_attribute_gallery',true)));
            if(!$attr)
            {
                $attr = openwatch_get_option('openwatch_attribute_image_swatch');
            }


            $attributes = $product->get_attributes();
            $tmp = get_post_meta( $post->ID, '_allow_openswatch', true );
            $enable_all = openwatch_get_option('openwatch_enable_all_products');
            if($enable_all > 0)
            {
                $tmp = 1;
            }
            $check = false;
            if($attr && isset($attributes[$attr]) && $attributes[$attr]['is_variation'] == 1)
            {
                $check = true;
            }
            if(!$check)
            {
                $attr = str_replace('pa_','',$attr);

                if($attr && isset($attributes[$attr]) )
                {
                    $attr_data = $attributes[$attr]->get_data();
                    if($attr_data['is_variation'])
                    {
                        $check = true;
                    }
                }
            }
            if($check && $tmp !== 0 )
            {
                $attribute = $attributes[$attr];
                if ( $attribute['is_taxonomy'] ) {

                    $values = wc_get_product_terms( $product->get_id(), $attribute['name'], array( 'fields' => 'names' ) );
                } else {
                    $values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
                }

                foreach($values as $val)
                {
                    $key = esc_attr($val);
                    add_meta_box( 'look-product-images-swatch-'.$key, __( 'Product Swatch Gallery', 'openwatch' ).'- '.$val, array($this,'swatchMetaBox'), 'product', 'side', 'low',$key );
                }

            }

        }

    }

    public function swatch_images()
    {
        $productId = esc_attr($_POST['product_id']);
        $option = esc_attr($_POST['option']);
        $_pf = new WC_Product_Factory();
        $product = $_pf->get_product($productId);
        $attributes = $product->get_attributes();
        $swatch = esc_attr( sanitize_title(openwatch_get_option('openwatch_attribute_image_swatch')));
        $images = '';
        $thumb = '';
        $attachment_ids = array();
        if(isset($attributes[$swatch]) || $option == 'null' )
        {
            $attribute = $attributes[$swatch];

            $tmp = get_post_meta( $productId, '_product_image_swatch_gallery', true );
            if(isset($tmp[$option]) || $option == 'null')
            {
                if($option == 'null')
                {
                    $attachment_ids = array(get_post_thumbnail_id( $productId )) ;
                    $attachment_ids = array_merge($attachment_ids,$product->get_gallery_attachment_ids());
                }else{
                    $attachment_ids = explode(',',$tmp[$option]);
                }

                $loop 		= 0;
                $columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
                foreach ( $attachment_ids as $key => $attachment_id ) {
                    if($key == 0 && (int)$attachment_id > 0)
                    {
                        $image_title 	= esc_attr( get_the_title( $attachment_id ) );
                        $image_caption 	= get_post( $attachment_id )->post_excerpt;
                        $image_link  	= wp_get_attachment_url( $attachment_id );
                        $image       	= wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
                            'title'	=> $image_title,
                            'alt'	=> $image_title
                        ) );

                        $attachment_count = count( $attachment_ids);

                        if ( $attachment_count > 1 ) {
                            $gallery = '[product-gallery]';
                        } else {
                            $gallery = '';
                        }

                        $images .=  apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_caption, $image ), $productId );
                    }else{
                        $classes = array( 'zoom' );

                        if ( $loop == 0 || $loop % $columns == 0 )
                            $classes[] = 'first';

                        if ( ( $loop + 1 ) % $columns == 0 )
                            $classes[] = 'last';

                        $image_link = wp_get_attachment_url( $attachment_id );

                        if ( ! $image_link )
                            continue;

                        $image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
                        $image_class = esc_attr( implode( ' ', $classes ) );
                        $image_title = esc_attr( get_the_title( $attachment_id ) );

                        $thumb .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[product-gallery]">%s</a>', $image_link, $image_class, $image_title, $image ), $attachment_id, $productId, $image_class );
                        $loop++;
                    }

                }

            }
        }
        if($thumb !='')
        {
            $images .= '<div class="thumbnails thumnails-'.$option.' columns-'.$columns.'">'.$thumb.'</div>';
        }
        $images = apply_filters('openswatch_image_swatch_html',$images,$productId,$attachment_ids);
        echo balanceTags($images);exit;

    }

    public function swatchMetaBox($post,$box)
    {

        $attr = esc_attr(sanitize_title($box['args']));
        ?>

        <div id="product_images_swatch_container" class="op_sw_gallery">
            <ul class=" product_swatch_images product_images_<?php echo esc_attr($attr); ?>">
                <?php
                if ( metadata_exists( 'post', $post->ID, '_product_image_swatch_gallery' ) ) {
                    $tmp = get_post_meta( $post->ID, '_product_image_swatch_gallery', true );
                    if(isset($tmp[$attr]))
                    {
                        $product_image_swatch_gallery = $tmp[$attr];
                    }else{
                        $product_image_swatch_gallery = '';
                    }
                } else {
                    $attachment_ids = array();
                    $product_image_swatch_gallery = '';
                }

                $attachments = array_filter( explode( ',', $product_image_swatch_gallery ) );

                if ( ! empty( $attachments ) ) {
                    foreach ( $attachments as $attachment_id ) {
                        echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
								' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '
								<ul class="actions">
									<li><a href="#" class="delete tips" data-tip="' . esc_attr__( 'Delete image', 'openwatch' ) . '">' . __( 'Delete', 'look' ) . '</a></li>
								</ul>
							</li>';
                    }
                }
                ?>
            </ul>

            <input type="hidden" id="product_image_gallery_<?php echo esc_attr($attr); ?>" class="input_product_image_swatch_gallery" name="product_image_swatch_gallery[<?php echo esc_attr($attr); ?>]" value="<?php echo esc_attr( $product_image_swatch_gallery ); ?>" />

        </div>
        <p class="add_product_swatch_images  hide-if-no-js">
            <a href="#" data-choose="<?php esc_attr_e( 'Add Images to Product Gallery', 'openwatch' ); ?>" data-update="<?php esc_attr_e( 'Add to gallery', 'look' ); ?>" data-delete="<?php esc_attr_e( 'Delete image', 'look' ); ?>" data-text="<?php esc_attr_e( 'Delete', 'look' ); ?>"><?php _e( 'Add product gallery images', 'look' ); ?></a>
        </p>

    <?php
    }

    public function save_image_swatch($post_id,$post)
    {
        $attachment_ids = isset( $_POST['product_image_swatch_gallery'] ) ? $_POST['product_image_swatch_gallery'] : array();
        update_post_meta( $post_id, '_product_image_swatch_gallery', $attachment_ids );
        $openswatch_setting = 0;
        if(isset($_POST['allow_openswatch']))
        {
            $openswatch_setting = (int)$_POST['allow_openswatch'];
        }

        update_post_meta( $post_id, '_allow_openswatch', $openswatch_setting );

        $attr_setting = '';
        if(isset($_POST['_openswatch_attribute_gallery']))
        {
            $attr_setting = esc_attr($_POST['_openswatch_attribute_gallery']);
        }
        
        //update_post_meta( $post_id, '_openswatch_attribute_gallery', $attr_setting );
    }

    public function upload_scripts()
    {
        wp_register_style('manage-openswatch', OPENSWATCH_URI . '/assets/css/manage-openswatch.css');
        wp_register_script('manage-openswatch', OPENSWATCH_URI . '/assets/js/manage-openswatch.js');
        wp_enqueue_script('media-upload');
        wp_enqueue_script( 'wp-color-picker');
        wp_enqueue_script('manage-openswatch',array('jquery','wp-color-picker'));
        wp_enqueue_style('manage-openswatch');
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_media();
    }

    public function add_attribute_fields() {
        ?>
        <div class="form-field">
            <label><?php _e( 'Thumbnail', 'openwatch' ); ?></label>
            <div id="product_cat_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
            <div style="line-height: 60px;">
                <input type="hidden" name="is_attribute" value="1">
                <input type="hidden" id="product_attribute_thumbnail_id" name="product_attribute_thumbnail_id" />
                <button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'openwatch' ); ?></button>
                <button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'openwatch' ); ?></button>
            </div>
            <script type="text/javascript">
                (function($) {
                    "use strict";
                    // Only show the "remove image" button when needed

                    if ( ! $( '#product_attribute_thumbnail_id' ).val() ) {
                        $( '.remove_image_button' ).hide();
                    }
                    // Uploading files
                    var file_frame;
                    $(document).ready(function(){
                        $( document ).on( 'click', '.upload_image_button', function( event ) {

                            event.preventDefault();

                            // If the media frame already exists, reopen it.
                            if ( file_frame ) {
                                file_frame.open();
                                return;
                            }

                            // Create the media frame.
                            file_frame = wp.media.frames.downloadable_file = wp.media({
                                title: '<?php _e( "Choose an image", "openwatch" ); ?>',
                                button: {
                                    text: '<?php _e( "Use image", "openwatch" ); ?>'
                                },
                                multiple: false
                            });

                            // When an image is selected, run a callback.
                            file_frame.on( 'select', function() {
                                var attachment = file_frame.state().get( 'selection' ).first().toJSON();
                                $( '#product_attribute_thumbnail_id' ).val( attachment.id );
                                $( '#product_cat_thumbnail img' ).attr( 'src', attachment.url );
                                $( '.remove_image_button' ).show();
                            });

                            // Finally, open the modal.
                            file_frame.open();
                        });

                        $( document ).on( 'click', '.remove_image_button', function() {
                            $( '#product_cat_thumbnail img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                            $( '#product_attribute_thumbnail_id' ).val( '' );
                            $( '.remove_image_button' ).hide();
                            return false;
                        });
                    })
                } )( jQuery );

            </script>
            <div class="clear"></div>
        </div>
    <?php
    }

    public function edit_attribute_fields( $term ) {
        $thumbnail_id = absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );
        if ( $thumbnail_id ) {
            $image = wp_get_attachment_thumb_url( $thumbnail_id );
        } else {
            $image = wc_placeholder_img_src();
        }
        ?>

        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e( 'Thumbnail', 'openwatch' ); ?></label></th>
            <td>
                <div id="product_cat_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
                <div style="line-height: 60px;">
                    <input type="hidden" name="is_attribute" value="1">
                    <input type="hidden" id="product_attribute_thumbnail_id" name="product_attribute_thumbnail_id" value="<?php echo esc_attr($thumbnail_id); ?>" />
                    <button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'openwatch' ); ?></button>
                    <button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'openwatch' ); ?></button>
                </div>
                <script type="text/javascript">
                    (function($) {
                        "use strict";
                        // Only show the "remove image" button when needed
                        if ( '0' === $( '#product_attribute_thumbnail_id' ).val() ) {
                            $( '.remove_image_button' ).hide();
                        }

                        // Uploading files
                        var file_frame;
                        $(document).ready(function(){
                            $( document ).on( 'click', '.upload_image_button', function( event ) {

                                event.preventDefault();

                                // If the media frame already exists, reopen it.
                                if ( file_frame ) {
                                    file_frame.open();
                                    return;
                                }

                                // Create the media frame.
                                file_frame = wp.media.frames.downloadable_file = wp.media({
                                    title: '<?php _e( "Choose an image", "openwatch" ); ?>',
                                    button: {
                                        text: '<?php _e( "Use image", "openwatch" ); ?>'
                                    },
                                    multiple: false
                                });

                                // When an image is selected, run a callback.
                                file_frame.on( 'select', function() {
                                    var attachment = file_frame.state().get( 'selection' ).first().toJSON();

                                    $( '#product_attribute_thumbnail_id' ).val( attachment.id );

                                    $( '#product_cat_thumbnail img' ).attr( 'src', attachment.url );
                                    $( '.remove_image_button' ).show();
                                });

                                // Finally, open the modal.
                                file_frame.open();
                            });

                            $( document ).on( 'click', '.remove_image_button', function() {
                                $( '#product_cat_thumbnail img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                                $( '#product_attribute_thumbnail_id' ).val( '' );
                                $( '.remove_image_button' ).hide();
                                return false;
                            });
                        })
                    } )( jQuery );

                </script>
                <div class="clear"></div>
            </td>
        </tr>
    <?php
    }


    public function save_attribute_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
        if ( isset( $_POST['product_attribute_thumbnail_id'] ) && isset($_POST['is_attribute']) && $_POST['is_attribute'] == 1 ) {
            update_woocommerce_term_meta( $term_id, 'thumbnail_id', absint( $_POST['product_attribute_thumbnail_id'] ) );
        }
    }

    public function openswatch_woocommerce_product_query($q)
    {
        global $wpdb;

        $post_in = array();
        $check = false;


        if(!empty($post_in))
        {
            $q->set( 'post__in',$post_in );
        }else{
            if($check)
            {
                $sql = "SELECT ID FROM ".$wpdb->posts."  WHERE post_type = 'product' AND post_status = 'publish' ";
                $rows = $wpdb->get_results( $sql,ARRAY_A );
                foreach($rows as $row)
                {
                    $post_in[] = $row['ID'];
                }
                $q->set( 'post__in',$post_in );
            }
        }

    }

    public function openswatch_get_product_gallery_html($attachement_ids)
    {

        global $product;
        global $post;
        global $in_openswatch;
        global $openswatch_attachement_ids;
        $in_openswatch = 1;
        $openswatch_attachement_ids = $attachement_ids;
        ob_start();
        woocommerce_show_product_images();
        $tmp = ob_get_clean();
        $in_openswatch = false;
        $openswatch_attachement_ids = array();
        return $tmp;
    }
    public function enableOnProduct($product)
    {
        if($product->get_type() == 'variable')
        {
            $in_swatch_product_mode = false;
            if(!$swatch_attrs = openwatch_get_option('openwatch_attribute_swatch'))
            {
                $swatch_attrs = array();
            }

            $swatch = esc_attr( sanitize_title(get_post_meta($product->get_id(),'_openswatch_attribute_gallery',true)));

            if($swatch)
            {
                $in_swatch_product_mode = true;
            }


            if(empty($swatch_attrs) && !$in_swatch_product_mode)
            {
                return false;
            }

            $attributes = $product->get_attributes();
            $swt = array();
            foreach($attributes as $key => $a)
            {
                $data = $a->get_data();
                if(isset($swatch_attrs[$key]) && $swatch_attrs[$key] == 1 && $data['is_variation'] == 1)
                {

                    $swt[] = $key;
                }

            }

            if(!empty($swt) || $in_swatch_product_mode)
            {

                $allProduct = openwatch_get_option('openwatch_enable_all_products');
                if($allProduct == 1)
                {
                    return true;
                }else{
                    $tmp = get_post_meta( $product->get_id(), '_allow_openswatch', true );
                    if($tmp != 0) {
                        return true;
                    }
                }
            }

        }
        return false;
    }
    public function getSwatchProductThumb($product_id,$term_slug = false)
    {
        $_pf = new WC_Product_Factory();
        $_product = $_pf->get_product($product_id);

        $thumb = $_product->get_image();
        if($term_slug)
        {
            $tmp = get_post_meta( $product_id, '_product_image_swatch_gallery', true );

            if(isset($tmp[$term_slug]) && !empty($tmp[$term_slug]))
            {
                $tmp_attachments = explode(',',$tmp[$term_slug]);

                if(!empty($tmp_attachments))
                {
                    $attachment_id = $tmp_attachments[0];
                    $image = wp_get_attachment_image($attachment_id);
                    if($image)
                    {
                        $thumb = $image;
                    }
                }


            }
        }
        return $thumb;
    }
    public function op_wp_ajax_woocommerce_save_attributes()
    {
        if ( ! current_user_can( 'edit_products' ) ) {
            wp_die( -1 );
        }

        parse_str( $_POST['data'], $data );


        if(isset($data['attribute_names']) && isset($data['attribute_variation']))
        {
            $product_id   = absint( $_POST['post_id'] );
            $_openswatch_attribute_style = array();

            foreach($data['attribute_names'] as $key => $val){
                if($val && $data['attribute_variation'][$key] == 1 && isset($data['_openswatch_attribute_gallery']) && $data['_openswatch_attribute_gallery'] == $key)
                {

                    update_post_meta( $product_id, '_openswatch_attribute_gallery', esc_attr(sanitize_title($val)) );
                }
                if($val && $data['attribute_variation'][$key] == 1 && isset($data['_openswatch_attribute_style'][$key]) && $data['_openswatch_attribute_style'][$key] == 1)
                {
                    $_openswatch_attribute_style[] = esc_attr(sanitize_title($val));
                }
            }
            update_post_meta( $product_id, '_openswatch_attribute_style',$_openswatch_attribute_style );
        }

    }
    public function woocommerce_save_attributes_openswatch()
    {
        if ( ! current_user_can( 'edit_products' ) ) {
            wp_die( -1 );
        }

        parse_str( $_POST['data'], $data );

        if(isset($data['attribute_names']) && isset($data['attribute_variation']) && isset($data['_openswatch_attribute_gallery'])) {
            $product_id = absint($_POST['post_id']);
            $key = $data['_openswatch_attribute_gallery'];
            $values = $data['attribute_values'][$key];
            if(!is_array($values))
            {
                $values = explode('|',$values);
            }else{
                $values = wc_get_product_terms( $product_id, $data['attribute_names'][$key], array( 'fields' => 'names' ) );
            }
            $values = array_filter($values);
            $post = get_post($product_id);
            ?>
            <?php foreach($values as $val): $val = trim($val); ?>
                <?php if($val): $attr = esc_attr(sanitize_title($val)) ; ?>
                <div id="look-product-images-swatch-<?php echo $val;?>" class="postbox ">
                    <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Product Swatch Gallery - <?php echo $val; ?></span><span
                            class="toggle-indicator" aria-hidden="true"></span></button>
                    <h2 class="hndle ui-sortable-handle"><span>Product Swatch Gallery- <?php echo $val; ?></span></h2>
                    <div class="inside">

                        <?php $this->swatchMetaBox($post,array('args' => $val)); ?>

                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php
        }
        exit;
    }
    public function openswatch_load_swatch_attributes(){
        if ( ! current_user_can( 'edit_products' ) ) {
            wp_die( -1 );
        }

        parse_str( $_POST['data'], $data );

        $_openswatch_attribute_style =  array();
        if(isset($data['_openswatch_attribute_style']))
        {
            $attrs = $data['_openswatch_attribute_style'];
            foreach($attrs as $k => $v)
            {
                if($v == 1)
                {
                    $_openswatch_attribute_style[] = $k;
                }
            }
        }

        if(isset($data['_openswatch_attribute_gallery']))
        {
            $_openswatch_attribute_style[] = $data['_openswatch_attribute_gallery'];
        }
        $product_id = absint($_POST['post_id']);
        $_openswatch_attribute_style = array_unique($_openswatch_attribute_style);
        $product_swatch = get_post_meta($product_id,'_vna_product_swatch',true);
        $_vna_product_swatch_color = get_post_meta($product_id,'_vna_product_swatch_color',true);
        echo '<input type="hidden" name="_op_change_product_swatch" value="1">';
        foreach($_openswatch_attribute_style as  $val)
        {
            if(isset($data['attribute_values'][$val]) )
            {
                $values = $data['attribute_values'][$val];
                $taxonomy = esc_attr(sanitize_title(trim($data['attribute_names'][$val]))) ;
                ?>
                <div class="options_group swatch_group">
                    <h3><strong class="attribute_name"><?php echo $data['attribute_names'][$val] ?></strong></h3>
                    <?php if(is_array($values)): ?>
                    <?php foreach ( $values as $term ):  ?>
                        <?php

                            $term = get_term($term);
                            $term_slug = esc_attr(sanitize_title(trim($term->name))) ;
                            $thumb_id = false;
                            if(isset($product_swatch[$taxonomy]) && isset($product_swatch[$taxonomy][$term_slug]))
                            {
                                $thumb_id = $product_swatch[$taxonomy][$term_slug];
                            }
                            $image = wc_placeholder_img_src();
                            if($thumb_id)
                            {
                                $image = wp_get_attachment_thumb_url( $thumb_id );
                            }
                            $color = '';
                            if(isset($_vna_product_swatch_color[$taxonomy][$term_slug]))
                            {
                                $color = $_vna_product_swatch_color[$taxonomy][$term_slug];
                            }
                        ?>
                            <span class="form-field">
                                <div  class="swatch-attr">
                                    <label><strong><?php echo sanitize_text_field($term->name) ; ?></strong></label>

                                    <img lass="op_option_img" data-thumb="<?php echo wc_placeholder_img_src(); ?>" src="<?php echo esc_url( $image ); ?>" width="60px" height="60px">
                                    <input type="hidden" name="is_attribute" value="1">
                                    <div class="btn-container">
                                        <input type="hidden" id="product_attribute_thumbnail_id" value="<?php echo $thumb_id ? $thumb_id : ''; ?>" name="product_swatch[<?php echo esc_attr($taxonomy);?>][<?php echo $term_slug; ?>]" />
                                        <button type="button" class="vupload_image_button button"><?php _e( 'Add image', 'openwatch' ); ?></button>
                                        <button style="<?php if($image == wc_placeholder_img_src() ):?>display: none;<?php endif; ?>" type="button" class="remove_image_button button"><?php _e( 'Remove image', 'openwatch' ); ?></button>
                                        <input type="text" class="op-color" value="<?php echo $color; ?>" name="product_swatch_color[<?php echo esc_attr($taxonomy);?>][<?php echo $term_slug; ?>]">
                                    </div>
                                </div>
                            </span>

                    <?php endforeach; ?>
                    <?php else: ?>
                        <?php $values = explode('|',$values); $values = array_filter($values); foreach ( $values as $term ):  ?>
                            <?php

                            $image = wc_placeholder_img_src();

                            $term_slug = esc_attr(sanitize_title(trim($term))) ;

                            $thumb_id = false;
                            if(isset($product_swatch[$taxonomy]) && isset($product_swatch[$taxonomy][$term_slug]))
                            {
                                $thumb_id = $product_swatch[$taxonomy][$term_slug];
                            }

                            if($thumb_id)
                            {
                                $image = wp_get_attachment_thumb_url( $thumb_id );
                            }
                            $color = '';
                            if(isset($_vna_product_swatch_color[$taxonomy][$term_slug]))
                            {
                                $color = $_vna_product_swatch_color[$taxonomy][$term_slug];
                            }
                            ?>

                            <span class="form-field">
                            <div  class="swatch-attr">
                                <label><strong><?php echo $term; ?></strong></label>

                                <img class="op_option_img" data-thumb="<?php echo wc_placeholder_img_src(); ?>" src="<?php echo esc_url( $image ); ?>" width="60px" height="60px">
                                <input type="hidden" name="is_attribute" value="1">
                                <input type="hidden" id="product_attribute_thumbnail_id" value="<?php echo $thumb_id ? $thumb_id : ''; ?>" name="product_swatch[<?php echo esc_attr($taxonomy);?>][<?php echo $term_slug; ?>]" />
                                <div class="btn-container">
                                    <button type="button" class="vupload_image_button button"><?php _e( 'Add image', 'openwatch' ); ?></button>
                                    <button style="<?php if($image == wc_placeholder_img_src() ):?>display: none;<?php endif; ?>" type="button" class="remove_image_button button"><?php _e( 'Remove image', 'openwatch' ); ?></button>
                                    <input type="text" class="op-color" value="<?php echo $color; ?>" name="product_swatch_color[<?php echo esc_attr($taxonomy);?>][<?php echo $term_slug; ?>]">
                                </div>
                            </div>
                        </span>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php
            }
        }

        exit;
    }
}

$tmp = new ColorSwatch();
$tmp->init();