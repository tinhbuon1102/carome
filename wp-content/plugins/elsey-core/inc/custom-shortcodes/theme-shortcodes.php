<?php
/*
 * All Custom Shortcode for [theme_name] theme.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

if( ! function_exists( 'elsey_vt_shortcodes' ) ) {

  function elsey_vt_shortcodes( $options ) {

    $options       = array();

    /* Shortcodes */
    $options[]     = array(
      'title'      => esc_html__('Shortcodes', 'elsey-core'),
      'shortcodes' => array(

        // Spacer Start
        array(
          'name'           => 'vc_empty_space',
          'title'          => esc_html__('Spacer', 'elsey-core'),
          'fields'         => array(

            array(
              'id'         => 'height',
              'type'       => 'text',
              'title'      => esc_html__('Height', 'elsey-core'),
              'attributes' => array(
                'placeholder' => '20px',
              ),
            ),

          ),
        ),
        // Spacer End

        // Contact Info Start
        array(
          'name'           => 'vt_contact_info',
          'title'          => esc_html__('Contact Info', 'elsey-core'),
          'fields'         => array(

            array(
              'id'         => 'custom_class',
              'type'       => 'text',
              'title'      => esc_html__('Custom Class', 'elsey-core'),
            ),
            array(
              'id'         => 'info_contact_icon',
              'type'       => 'icon',
              'title'      => esc_html__('Info Icon', 'elsey-core')
            ),
            array(
              'id'         => 'info_title_text',
              'type'       => 'text',
              'title'      => esc_html__('Title Text', 'elsey-core')
            ),
            array(
              'id'         => 'info_address_text_one',
              'type'       => 'text',
              'title'      => esc_html__('Address Line One', 'elsey-core'),
            ),
            array(
              'id'         => 'info_address_text_two',
              'type'       => 'text',
              'title'      => esc_html__('Address Line Two', 'elsey-core'),
            ),
            array(
              'id'         => 'info_phone_number',
              'type'       => 'text',
              'title'      => esc_html__('Phone Number', 'elsey-core'),
            ),
            array(
              'id'         => 'info_email_address',
              'type'       => 'text',
              'title'      => esc_html__('Email Address', 'elsey-core'),
            ),
            array(
              'id'         => 'info_website_text',
              'type'       => 'text',
              'title'      => esc_html__('Website Button Text', 'elsey-core')
            ),
            array(
              'id'         => 'info_website_link',
              'type'       => 'text',
              'title'      => esc_html__('Website Link', 'elsey-core'),
            ),
            array(
              'id'         => 'info_target_tab',
              'type'       => 'switcher',
              'default'    => 'true',
              'title'      => esc_html__('Open Website On New Tab?', 'elsey-core'),
              'on_text'    => esc_html__('Yes', 'elsey-core'),
              'off_text'   => esc_html__('No', 'elsey-core'),
            ),
            array(
              'type'       => 'notice',
              'class'      => 'info',
              'content'    => esc_html__('Colors', 'elsey-core')
            ),
            array(
              'id'         => 'info_contact_icon_color',
              'type'       => 'color_picker',
              'title'      => esc_html__('Info Icon Color', 'elsey-core'),
              'wrap_class' => 'column_half',
            ),
            array(
              'id'         => 'info_title_text_color',
              'type'       => 'color_picker',
              'title'      => esc_html__('Title Text Color', 'elsey-core'),
              'wrap_class' => 'column_half',
            ),
            array(
              'id'         => 'info_address_text_color',
              'type'       => 'color_picker',
              'title'      => esc_html__('Address Text Color', 'elsey-core'),
              'wrap_class' => 'column_half',
            ),
            array(
              'id'         => 'info_website_text_color',
              'type'       => 'color_picker',
              'title'      => esc_html__('Website Text Color', 'elsey-core'),
              'wrap_class' => 'column_half',
            ),

          ),

        ),
        // Contact Info End

        // Simple Image List
        array(
          'name'          => 'vt_image_lists',
          'title'         => __('Simple Image List', 'elsey-core'),
          'view'          => 'clone',
          'clone_id'      => 'vt_image_list',
          'clone_title'   => __('Add New', 'elsey-core'),
          'fields'        => array(

            array(
              'id'        => 'custom_class',
              'type'      => 'text',
              'title'     => __('Custom Class', 'elsey-core'),
            ),

          ),
          'clone_fields'  => array(
            array(
              'id'        => 'get_image',
              'type'      => 'upload',
              'title'     => __('Image', 'elsey-core')
            ),
            array(
              'id'        => 'link',
              'type'      => 'text',
              'attributes' => array(
                'placeholder'     => 'http://',
              ),
              'title'     => __('Link', 'elsey-core')
            ),
            array(
              'id'    => 'open_tab',
              'type'  => 'switcher',
              'std'   => false,
              'title' => __('Open link to new tab?', 'elsey-core')
            ),
          ),
        ),
        // Simple Image List End

        // Social Icons Start
        array(
          'name'           => 'vt_socials',
          'title'          => __('Social Icons', 'elsey-core'),
          'view'           => 'clone',
          'clone_id'       => 'vt_social',
          'clone_title'    => __('Add New', 'elsey-core'),
          'fields'         => array(

            array(
              'id'         => 'custom_class',
              'type'       => 'text',
              'title'      => __('Custom Class', 'elsey-core'),
            ),
            // Icon Size
            array(
              'id'         => 'icon_size',
              'type'       => 'text',
              'title'      => __('Icon Size', 'elsey-core'),
              'wrap_class' => 'column_full',
            ),
            array(
              'id'         => 'icon_color',
              'type'       => 'color_picker',
              'title'      => __('Icon Color', 'elsey-core'),
              'wrap_class' => 'column_half',
            ),
            array(
              'id'         => 'icon_hover_color',
              'type'       => 'color_picker',
              'title'      => __('Icon Hover Color', 'elsey-core'),
              'wrap_class' => 'column_half',
            ),         
            
          ),

          'clone_fields'   => array(
            array(
              'id'         => 'social_link',
              'type'       => 'text',
              'attributes' => array(
                'placeholder' => 'http://',
              ),
              'title'      => __('Link', 'elsey-core')
            ),
            array(
              'id'         => 'social_icon',
              'type'       => 'icon',
              'title'      => __('Social Icon', 'elsey-core')
            ),
            array(
              'id'         => 'target_tab',
              'type'       => 'switcher',
              'title'      => __('Open New Tab?', 'elsey-core'),
              'on_text'    => __('Yes', 'elsey-core'),
              'off_text'   => __('No', 'elsey-core'),
            ),

          ),

        ),
        // Social Icons End
        

        // FAQ Nagivation Shortcodes 
        array(
          'name'          => 'vt_faq_navigation',
          'title'         => __('FAQ Nagivation', 'elsey-core'),
          'fields'        => array(

            array(
              'id'        => 'custom_class',
              'type'      => 'text',
              'title'     => __('Custom Class', 'elsey-core'),
            ),
              array(
              'id'         => 'sidenav_title',
              'type'       => 'text',
              'title'      => __('Sidebar Title', 'elsey-core'),
            ),

            array(
              'id'        => 'faq_limit',
              'type'      => 'text',
              'title'     => __('Limit (Enter the number of items to show)', 'elsey-core'),
            ),
            array(
              'type' => 'select',
              'title' => __( 'Order', 'elsey-core' ),
              'options' => array(
               '' => __( 'Select faq Order', 'elsey-core' ) ,
                'ASC' => __('Asending', 'elsey-core') ,
               'DESC' => __('Desending', 'elsey-core') ,
              ),
              'id' => 'faq_order',
            ),
            array(
              'type' => 'select',
              'title' => __( 'Order By', 'elsey-core' ),
              'options' => array(
                'none' => __('None', 'elsey-core') ,
                'ID' => __('ID', 'elsey-core') ,
                'author' => __('Author', 'elsey-core') ,
                'title' => __('Title', 'elsey-core') ,
               'date'  => __('Date', 'elsey-core') ,
              ),
              'id' => 'faq_orderby',

            ),
            array(
              "type"        => 'text',
              "title"     => __('Show only certain faq? Enter faq ids (comma separated) you want to display.', 'elsey-core'),
              "id"  => "faq_show",
              "value"       => "",
            ),

          ),

        ),

        // Quick Links

        array(
          'name'           => 'vt_quicklink',
          'title'          => __('Quick Links', 'elsey-core'),
          'fields'         => array(

            array(
              'id'         => 'custom_class',
              'type'       => 'text',
              'title'      => __('Custom Class', 'elsey-core'),
            ),
            // Text Size
          array(
              'id'         => 'link_text',
              'type'       => 'text',
              'title'      => __('Title Text', 'elsey-core'),
            ),
             array(
              'id'         => 'text_link',
              'type'       => 'text',
              'title'      => esc_html__('Text Link', 'elsey-core'),
            ),
            array(
              'id'         => 'link_target_tab',
              'type'       => 'switcher',
              'default'    => 'true',
              'title'      => esc_html__('Open Website On New Tab?', 'elsey-core'),
              'on_text'    => esc_html__('Yes', 'elsey-core'),
              'off_text'   => esc_html__('No', 'elsey-core'),
            ),
            array(
              'id'        => 'text_align',
              'type'      => 'select',
              'options'   => array(
                'text-left' => __('Align Left', 'elsey-core'),
                'text-center' => __('Align Center', 'elsey-core'),
                'text-right' => __('Align Right', 'elsey-core'),
              ),
              'title'     => __('Text Align Style', 'elsey-core'),
            ),
            array(
              'id'         => 'text_size',
              'type'       => 'text',
              'title'      => __('Text Size', 'elsey-core'),
              'wrap_class' => 'column_full',
            ),
            array(
              'id'         => 'text_color',
              'type'       => 'color_picker',
              'title'      => __('Text Color', 'elsey-core'),
              'wrap_class' => 'column_half',
            ),
            array(
              'id'         => 'text_hover_color',
              'type'       => 'color_picker',
              'title'      => __('Text Hover Color', 'elsey-core'),
              'wrap_class' => 'column_half',
            ),  
              array(
              'id'         => 'border_color',
              'type'       => 'color_picker',
              'title'      => __('Border Color', 'elsey-core'),
              'wrap_class' => 'column_half',
            ), 
            array(
              'id'         => 'border_hover_color',
              'type'       => 'color_picker',
              'title'      => __('Border Hover Color', 'elsey-core'),
              'wrap_class' => 'column_half',
            ),             
            
          ),


        ),



      ),
    );

    return $options;

  }
  add_filter( 'cs_shortcode_options', 'elsey_vt_shortcodes' );
}