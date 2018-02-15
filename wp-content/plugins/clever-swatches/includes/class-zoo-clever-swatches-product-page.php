<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * class for managing gallery images per variation.
 *
 * @class    Zoo_Clever_Swatch_Admin_Variation_Gallery
 *
 * @version  2.0.0
 * @package  clever-swatches/includes
 * @category Class
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if (!class_exists('Zoo_Clever_Swatch_Product_Page')) {

    class Zoo_Clever_Swatch_Product_Page
    {

        public function __construct()
        {
            add_filter('wc_get_template', array($this, 'load_template'), 10, 5);
            add_filter('body_class', 'zoo_cw_body_class');
            if (!function_exists('zoo_cw_body_class')) {
                function zoo_cw_body_class($classes)
                {
                    $classes[] = 'zoo-cw-page';
                    return $classes;
                }
            }
        }

        public function load_template($located, $template_name, $args, $template_path, $default_path)
        {
            if ($template_name == 'single-product/add-to-cart/variable.php' || $template_name == 'single-product/product-image.php') {
                $general_settings = get_option('zoo-cw-settings',true);
                $is_gallery_enabled = isset($general_settings['product_gallery']) ? intval($general_settings['product_gallery']) : 1;

                if ($template_name == 'single-product/product-image.php') {
                    if (!$is_gallery_enabled) {
                        return $located;
                    }
                    $template_name = 'single-product/clever-swatches-image.php';
                    $located = str_replace('product-image.php', 'clever-swatches-image.php', $located);
                    if (!file_exists($located)) {
                        $plugin_path = ZOO_CW_TEMPLATES_PATH . 'woocommerce/';
                        $located = $plugin_path . $template_name;
                    }
                }

                if (!is_array($general_settings) || $general_settings['swatch'] == 0) {
                    return $located;
                }

                $old_located = $located;

                //check if themes was overwite file
                $is_from_theme = strpos($old_located,'/wp-content/themes/');
                if (!$is_from_theme) {
                    $plugin_path = ZOO_CW_TEMPLATES_PATH . 'woocommerce/';
                    $located = $plugin_path . $template_name;

                    //check if file is in plugin path
                    if (!file_exists($located)) {
                        $located = $old_located;
                    }
                }
                require_once(ZOO_CW_TEMPLATES_PATH . 'add-to-cart-button.php');

            }
            return $located;
        }

        public function get_default_active_option($product){
            $default_active = [];
            $default_attributes = $product->get_default_attributes();
            foreach ($default_attributes as $key => $value) {
                $default_active['attribute_'.$key] = $value;
            }
            return $default_active;
        }

        public function check_valid_options($product_variations, $check_options) {
            $is_valid = false;
            foreach ($product_variations as $key => $product_variation) {
                if ($product_variation['is_in_stock'] && $product_variation['is_purchasable']) {
                    $attribute_case = $product_variation['attributes'];
                    $count_check = count($check_options);

                    foreach ($check_options as $key => $option) {
                        if ($attribute_case[$key] == "" || $option == $attribute_case[$key]) {
                            $count_check --;
                        }
                    }

                    if ($count_check <= 0) {
                        $is_valid = true;
                        break;
                    }
                }
            };
            return $is_valid;
        }

        public function prepare_single_page_data($product, $attributes, $product_swatch_data_array)
        {
            $temp_attribute = [];

            foreach ($attributes as $attribute_name => $value) {
                $temp_attribute[sanitize_title($attribute_name)] = $value;
            }

            $attributes = $temp_attribute;

            $default_active = $this->get_default_active_option($product);

            $available_variations = $product->get_available_variations();

            $general_settings = get_option('zoo-cw-settings', true);
            if ($product_swatch_data_array != '') {
                foreach ($product_swatch_data_array as $attribute_slug => $data) {
                    if (isset($attributes[$attribute_slug])) {
                        $attribute_enabled_options = $attributes[$attribute_slug];
                        $terms = wc_get_product_terms($product->get_id(), $attribute_slug, array('fields' => 'all'));
                        $options_data = $data['options_data'];

                        if (taxonomy_exists($attribute_slug)) {
                            foreach ($terms as $term) {
                                if (in_array($term->slug, $attribute_enabled_options)) {
                                    $options_data[$term->slug]['name'] = $term->name;
                                    $options_data[$term->slug]['value'] = $term->slug;
                                    $options_data[$term->slug]['option_class'] = '';
                                } else {
                                    unset($options_data[$term->slug]);
                                }
                            }
                        } else {
                            foreach ($options_data as $key => $value) {
                                $options_data[$key]['name'] = $key;
                                $options_data[$key]['value'] = $key;
                                $options_data[$key]['option_class'] = '';
                            }
                        }

                        if (count($default_active)) {
                            foreach ($options_data as $key => $value) {
                                $default_active['attribute_'.$attribute_slug] = $key;
                                if (!$this->check_valid_options($available_variations, $default_active)) {
                                    $options_data[$key]['option_class'] = 'out-stock';
                                }
                            }
                        }

                        $product_swatch_data_array[$attribute_slug]['options_data'] = $options_data;

                        //render class
                        $class_attribute = '';
                        if ($data['display_type'] != 'default') {
                            $class_attribute .= ' zoo-cw-active zoo-cw-type-' . $data['display_type'];
                        }
                        $product_swatch_data_array[$attribute_slug]['class_attribute'] = $class_attribute;

                        $product_swatch_display_size = isset($data['product_swatch_display_size']) ? $data['product_swatch_display_size'] : 'default';
                        if ($product_swatch_display_size == 'default') {
                            $product_swatch_display_size = $general_settings['product_swatch_display_size'];
                        }

                        if ($product_swatch_display_size == 'custom') {
                            $custom_style = 'style="min-width: ' . $general_settings['product_swatch_display_size_width'] . 'px;height: ' . $general_settings['product_swatch_display_size_height'] . 'px;"';
                            $product_swatch_data_array[$attribute_slug]['custom_style'] = $custom_style;
                        }

                        $product_swatch_display_shape = isset($data['product_swatch_display_shape']) ? $data['product_swatch_display_shape'] : 'default';
                        if ($product_swatch_display_shape == 'default') {
                            $product_swatch_display_shape = $general_settings['product_swatch_display_shape'];
                            $product_swatch_data_array[$attribute_slug]['product_swatch_display_shape'] = $product_swatch_display_shape;
                        }
                        $product_swatch_display_name_yn = isset($data['product_swatch_display_name_yn']) ? $data['product_swatch_display_name_yn'] : 'default';
                        if ($product_swatch_display_name_yn === 'default') {
                            $product_swatch_display_name_yn = $general_settings['product_swatch_display_name'];
                            $product_swatch_data_array[$attribute_slug]['product_swatch_display_name_yn'] = $product_swatch_display_name_yn;
                        }

                        $class_options = 'zoo-cw-option-display-size-' . $product_swatch_display_size;
                        $class_options .= ' zoo-cw-option-display-shape-' . $product_swatch_display_shape;
                        $product_swatch_data_array[$attribute_slug]['class_options'] = $class_options;
                    }
                }
            }

            return $product_swatch_data_array;
        }
    }
}

$zoo_clever_swatch_product_page = new Zoo_Clever_Swatch_Product_Page();