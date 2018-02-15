<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Override template file of woocommerce.
 * Variable product add to cart.
 *
 * @version  2.0.3
 * @package  clever-swatches/templates/woocommerce
 * @category Templates
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

global $product;

$available_variations = $product->get_available_variations();

$general_settings = get_option('zoo-cw-settings',true);
$is_gallery_enabled = isset($general_settings['product_gallery']) ? intval($general_settings['product_gallery']) : 1;

$product_swatch_data_array = get_post_meta($product->get_id(), 'zoo_cw_product_swatch_data', true);
$zoo_form_class='cw-form-data';
if ($product_swatch_data_array == '') {
    $is_gallery_enabled=0;
    $zoo_form_class='no-cw-data';
}
$zoo_clever_swatch_product_page = new Zoo_Clever_Swatch_Product_Page();
$product_swatch_data_array = $zoo_clever_swatch_product_page->prepare_single_page_data($product, $attributes, $product_swatch_data_array);

$default_active = $zoo_clever_swatch_product_page->get_default_active_option($product);
do_action('woocommerce_before_add_to_cart_form');
?>
<form class="variations_form cart <?php echo esc_attr($zoo_form_class)?>" method="post" enctype='multipart/form-data'
      data-product_id="<?php echo absint($product->get_id()); ?>"
      data-attributes_count="<?php echo(count($attributes)); ?>"
      data-product_variations="<?php echo htmlspecialchars(wp_json_encode($available_variations)) ?>"
      data-gallery_enabled="<?php echo strval($is_gallery_enabled) ?>"
    <?php if (count($default_active)) : ?>
        data-selected_options="<?php echo htmlspecialchars(wp_json_encode($default_active)) ?>"
    <?php endif; ?>
>
    <?php do_action('woocommerce_before_variations_form'); ?>

    <?php if (empty($available_variations) && false !== $available_variations) : ?>
        <p class="stock out-of-stock"><?php _e('This product is currently out of stock and unavailable.', 'woocommerce'); ?></p>
    <?php else : ?>
        <ul class="variations zoo-cw-variations">
            <?php
            $zoo_cw_attr_class = '';
            $zoo_cw_tr_class = '';

            foreach ($attributes as $attribute_name => $options) :
                $selected = isset($_REQUEST['attribute_' . sanitize_title($attribute_name)])
                    ? wc_clean(stripslashes(urldecode($_REQUEST['attribute_' . sanitize_title($attribute_name)])))
                    : $product->get_variation_default_attribute($attribute_name);

                $attribute_slug = sanitize_title($attribute_name);
                /**
                 * Prepare data for Render html of swatch attributes
                 * */

                if ($product_swatch_data_array != '') {
                    $zoo_cw_attr_class = $product_swatch_data_array[$attribute_slug]['class_attribute'];
                    $zoo_cw_tr_class = 'zoo-cw-attr-row';
                }
                $zoo_cw_attribute = $zoo_cw_name_attr_selected = $zoo_cw_attribute_options = '';
                if ($product_swatch_data_array != '') {
                    $zoo_cw_attribute = $product_swatch_data_array[$attribute_slug];
                    $zoo_cw_attribute_options = $zoo_cw_attribute['options_data'];
                    if ($selected != '') {
                        $zoo_cw_name_attr_selected = $zoo_cw_attribute_options[$selected]['name'];
                    }
                }
                /**
                 * End Prepare data for Render html of swatch attributes
                 * */
                $zoo_cw_attr_display_type = isset($zoo_cw_attribute['display_type']) ? $zoo_cw_attribute['display_type'] : 'default';
                ?>
                <li class="<?php echo esc_attr($zoo_cw_tr_class) ?>">
                    <div class="label">
                        <label>
                            <?php echo wc_attribute_label($attribute_name); ?>:
                        </label>
                        <span class="zoo-cw-name">
                        <?php
                        if ($zoo_cw_name_attr_selected != '') {
                            echo esc_html($zoo_cw_name_attr_selected);
                        }
                        ?>
                            </span>
                    </div>
                    <div class="value zoo-cw-group-attribute <?php echo esc_attr($zoo_cw_attr_class); ?>"
                         data-group-attribute="<?php echo(esc_attr("attribute_" . $attribute_slug)); ?>"
                         data-attribute-display-type="<?php echo(esc_attr($zoo_cw_attr_display_type)); ?>">
                        <?php

                        wc_dropdown_variation_attribute_options(array('options' => $options, 'attribute' => $attribute_slug, 'product' => $product, 'selected' => $selected));
                        if ($product_swatch_data_array != '') {
                            /**
                             * Render html of swatch attributes
                             * */
                            if ($zoo_cw_attr_display_type != 'default'): ?>
                                <?php foreach ($zoo_cw_attribute_options as $zoo_cw_attribute_option):
                                    $zoo_cw_attr_name = isset($zoo_cw_attribute_option['name']) ? $zoo_cw_attribute_option['name'] : '';
                                    $zoo_cw_attr_val = isset($zoo_cw_attribute_option['value']) ? $zoo_cw_attribute_option['value'] : '';
                                    ?>
                                    <div class="zoo-cw-attribute-option <?php echo esc_attr($zoo_cw_attribute_option['option_class']);
                                    if ($selected == $zoo_cw_attr_val) echo esc_attr(' zoo-cw-active '); ?>"
                                         data-attribute-option="<?php echo esc_attr($zoo_cw_attr_val); ?>"
                                         data-attribute-name=" <?php echo esc_attr($zoo_cw_attr_name); ?>">
                                        <div class="zoo-cw-attr-item <?php echo($zoo_cw_attribute['class_options']); ?>"
                                            <?php if (isset($zoo_cw_attribute['custom_style'])) echo($zoo_cw_attribute['custom_style']); ?> >
                                            <?php if ($zoo_cw_attr_display_type == 'color'): ?>
                                                <span style="background-color: <?php echo esc_attr($zoo_cw_attribute_option['color']); ?>;"
                                                      data-tooltip="<?php echo esc_attr($zoo_cw_attr_name); ?>"
                                                      class="zoo-cw-label-color">
                                            </span>
                                            <?php elseif ($zoo_cw_attr_display_type == 'text'): ?>
                                                <span class="zoo-cw-label-text">
                                                <?php echo esc_html($zoo_cw_attr_name); ?>
                                            </span>
                                            <?php elseif ($zoo_cw_attr_display_type == 'image'): ?>
                                                <img src="<?php echo esc_url($zoo_cw_attribute_option['image']); ?>"
                                                     data-tooltip="<?php echo esc_attr($zoo_cw_attribute_option['name']); ?>"
                                                     alt="<?php echo esc_attr($zoo_cw_attribute_option['name']); ?>">
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($zoo_cw_attribute['product_swatch_display_name_yn'] == 1 && $zoo_cw_attr_display_type == 'text'): ?>
                                            <span class="zoo-cw-attr-label"><?php echo esc_html($zoo_cw_attr_name); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else:
                                wc_dropdown_variation_attribute_options(array('options' => $options, 'class' => 'zoo-cw-attribute-select', 'attribute' => $attribute_slug, 'product' => $product, 'selected' => $selected));
                                ?>
                            <?php endif;
                            ?>
                            <?php
                        }
                        if (isset($default_active[esc_attr("attribute_" . $attribute_slug)]))
                            $default_value_option = $default_active[esc_attr("attribute_" . $attribute_slug)];
                        else $default_value_option = '';
                        ?>
                        <input name="<?php echo(esc_attr("attribute_" . $attribute_slug)); ?>"
                               value="<?php echo $selected ?>" type="hidden">
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php do_action('woocommerce_before_add_to_cart_button'); ?>

        <div class="single_variation_wrap">
            <?php
            /**
             * woocommerce_before_single_variation Hook.
             */
            do_action('woocommerce_before_single_variation');

            /**
             * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
             * @since 2.4.0
             * @hooked woocommerce_single_variation - 10 Empty div for variation data.
             * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
             */
            do_action('woocommerce_single_variation');

            /**
             * woocommerce_after_single_variation Hook.
             */
            do_action('woocommerce_after_single_variation');
            ?>
        </div>

        <?php do_action('woocommerce_after_add_to_cart_button'); ?>
    <?php endif; ?>

    <?php do_action('woocommerce_after_variations_form'); ?>
</form>

<?php
do_action('woocommerce_after_add_to_cart_form');
?>
