(function ($) {
    'use strict';
    $(document).ready(function () {

        function cw_findMatchVariation(product_variations, check_options, check_purchasable) {
            var match_variation = false;
            $.each(product_variations, function (key, value) {
                var product_variation = value;
                if (check_purchasable) check_purchasable = (product_variation.is_in_stock && product_variation.is_purchasable);
                else check_purchasable = true;
                if (check_purchasable) { //not in_stock or don't have price will be out
                    var attribute_case = product_variation.attributes;
                    var count_check = Object.keys(check_options).length;

                    $.each(check_options, function (key, value) {
                        if ((attribute_case[key] == "") || (value == attribute_case[key])) {
                            count_check--;
                        }
                    });
                    if (count_check <= 0) {
                        match_variation = value;
                        // break jquery each loop
                        return false;
                    }
                }
            });
            return match_variation;
        }

        function cw_runFilter(product_variations, selected_options, attribute_rows, disable_class, enable_class) {
            attribute_rows.each(function () {
                var current_attribute_row = $(this);
                var attribute_name = current_attribute_row.data('group-attribute');
                var check_options = {};
                check_options = $.extend({}, selected_options);
                var attribute_display_type = $(this).data('attribute-display-type');
                if (attribute_display_type == 'default') {
                    if (disable_class == 'out-stock') { //only run filter on click event
                        //this for select type option
                        $(this).find('select.zoo-cw-attribute-select option').each(function () {
                            var option_value = $(this).val();
                            if (option_value != '') {
                                var option_text = $(this).text();
                                var new_text = option_text.replace(' * (Not suitable)', '');
                                check_options[attribute_name] = option_value;
                                if (cw_findMatchVariation(product_variations, check_options, true)) {
                                    $(this).text(new_text);
                                } else {
                                    $(this).text(new_text + ' * (Not suitable)');
                                }
                            }
                        });
                    }
                } else {
                    //this for other type option
                    var options = $(this).find('.zoo-cw-attribute-option');
                    options.each(function () {
                        var option = $(this);
                        var option_value = $(this).data('attribute-option');
                        check_options[attribute_name] = option_value;
                        if (cw_findMatchVariation(product_variations, check_options, true)) {
                            option.addClass(enable_class).removeClass(disable_class);
                        } else {
                            option.addClass(disable_class).removeClass(enable_class);
                        }
                    });
                }
            });
        }

        function cw_processSelectedOption(product_variations, selected_options, form_add_to_cart) {
            var old_variation_id = form_add_to_cart.find('input[name=variation_id]').val();
            var gallery_enabled = form_add_to_cart.data('gallery_enabled');
            var add_to_cart_button = form_add_to_cart.find('.single_add_to_cart_button');
            var variation_id = 0;

            var match_variation = cw_findMatchVariation(product_variations, selected_options, false);
            if (match_variation) {
                variation_id = match_variation.variation_id;
            }
            //trigger default function of woocommerce
            if (gallery_enabled) {
                if (variation_id != old_variation_id) {
                    form_add_to_cart.find('input[name=variation_id]').val(variation_id);
                    if (variation_id != 0) {
                        if (match_variation.is_in_stock && match_variation.is_purchasable) {
                            $(document).trigger('cleverswatch_button_add_cart', {
                                "selector": add_to_cart_button
                            });
                        } else {
                            $(document).trigger('cleverswatch_button_out_stock', {
                                "selector": add_to_cart_button
                            });
                        }
                        var template = wp.template('variation-template');
                        var template_html = template({
                            variation: match_variation
                        });
                        form_add_to_cart.find('.woocommerce-variation.single_variation').html(template_html).show();

                        var product_id = form_add_to_cart.data('product_id');
                        $('.product_meta .sku').html(match_variation.sku);
                        cw_updateGallery(form_add_to_cart, product_id, selected_options);
                    } else {
                        add_to_cart_button.html(wp.template('add-to-cart-button-out-stock')).addClass('disabled');
                        var template_html = wp.template('unavailable-variation-template');
                        form_add_to_cart.find('.woocommerce-variation.single_variation').html(template_html).show();
                    }
                }
            } else {
                $('.variations_form').trigger('found_variation', [match_variation]);
            }
        }

        var wrap_class = !!zoo_cw_params.product_image_custom_class ? zoo_cw_params.product_image_custom_class : 'div.woocommerce-product-gallery,div.images';

        function cw_updateGallery(form_add_to_cart, product_id, selected_options) {
            $(document).trigger('cleverswatch_before_update_gallery', {
                "product_id": product_id,
                "selected_options": selected_options
            });
            var ajax_url = zoo_cw_params.ajax_url;
            $('#product-' + product_id).find(wrap_class).parent().addClass('zoo-cw-gallery-loading');
            $.ajax({
                url: ajax_url,
                cache: false,
                type: "POST",
                data: {
                    'action': 'clever_swatch_action',
                    'product_id': product_id,
                    'selected_options': selected_options
                }, success: function (response) {
                    if (!!response) {
                        $(document).trigger('cleverswatch_update_gallery', {
                            "content": response.html_content,
                            "product_id": product_id,
                            "form_add_to_cart": form_add_to_cart,
                            "variation_id": response.variation_id,
                            "variation_data": response.variation_data,
                        });
                    }
                }, error: function (jqXHR, textStatus) {
                    console.log(jqXHR);
                }
            });
        }

        //binding update gallery
        if (!$('.variations_form.no-cw-data')[0]) {
            $(document).bind('cleverswatch_update_gallery', function (event, response) {
                var imagesDiv = $('#product-' + response.product_id).find(wrap_class);
                if (jQuery(imagesDiv).length) {
                    $('.zoo-cw-gallery-loading').removeClass('zoo-cw-gallery-loading');
                    imagesDiv.replaceWith(response.content);
                    if (!!zoo_cw_params.slider_support) {
                        $('#product-' + response.product_id).find(wrap_class).wc_product_gallery();
                    }
                }
            });

            //click function for image/text/color types option
            $(document).on('click', '.variations_form .zoo-cw-attribute-option', function () {
                var form_add_to_cart = $(this).parents('.variations_form');
                var attribute_rows = form_add_to_cart.find('.zoo-cw-group-attribute');
                var current_attribute_row = $(this).parents('.zoo-cw-group-attribute');
                var attribute_name = current_attribute_row.data('group-attribute');
                var product_variations = form_add_to_cart.data('product_variations');
                var selected_option_name = $(this).data('attribute-name');
                var option_value = $(this).data('attribute-option');
                var selected_options = form_add_to_cart.data('selected_options');
                if (selected_options === undefined) {
                    selected_options = {};
                }
                if ($(this).hasClass('zoo-cw-active')) {
                    //visual
                    $(this).removeClass('zoo-cw-active');
                    current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text('');
                    //process
                    current_attribute_row.find('input[name=' + attribute_name + ']').val('');
                    delete selected_options[attribute_name];
                    form_add_to_cart.data('selected_options', selected_options);
                    cw_runFilter(product_variations, selected_options, attribute_rows, 'out-stock', "");
                    //disable add to cart button
                    form_add_to_cart.find('input[name=variation_id]').val('');
                    form_add_to_cart.find('button.single_add_to_cart_button').addClass('disabled');
                    form_add_to_cart.find('.woocommerce-variation.single_variation').hide();
                } else {
                    var options = current_attribute_row.find('.zoo-cw-attribute-option');
                    //visual
                    options.removeClass('zoo-cw-active');
                    $(this).addClass('zoo-cw-active');
                    current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text(selected_option_name);
                    //process
                    current_attribute_row.find('input[name=' + attribute_name + ']').val(option_value);
                    selected_options[attribute_name] = option_value;
                    form_add_to_cart.data('selected_options', selected_options);
                    cw_runFilter(product_variations, selected_options, attribute_rows, 'out-stock', "");
                    //apply to cart
                    var attributes_count = form_add_to_cart.data('attributes_count');
                    if (Object.keys(selected_options).length == attributes_count) {
                        cw_processSelectedOption(product_variations, selected_options, form_add_to_cart);
                    } else {
                        form_add_to_cart.find('button.single_add_to_cart_button').addClass('disabled');
                        form_add_to_cart.find('.woocommerce-variation.single_variation').hide();
                    }
                }
            });

            //hover function for image/text/color types option
            $(document).on({
                mouseenter: function () {
                    var form_add_to_cart = $(this).parents('.variations_form');
                    var attribute_rows = form_add_to_cart.find('.zoo-cw-group-attribute');
                    var current_attribute_row = $(this).parents('.zoo-cw-group-attribute');
                    var attribute_name = current_attribute_row.data('group-attribute');
                    var product_variations = form_add_to_cart.data('product_variations');
                    var option_value = $(this).data('attribute-option');
                    var selected_options = form_add_to_cart.data('selected_options');
                    if (selected_options === undefined) {
                        selected_options = {};
                    }
                    //visual
                    var new_option_name = $(this).data('attribute-name');
                    current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text(new_option_name);
                    //process
                    var check_options = $.extend({}, selected_options);
                    check_options[attribute_name] = option_value;
                    cw_runFilter(product_variations, check_options, attribute_rows, 'temp-out-stock', 'temp-in-stock');

                    //add to cart button effect
                    var add_to_cart_button = form_add_to_cart.find('.single_add_to_cart_button');
                    var attributes_count = form_add_to_cart.data('attributes_count');
                    if (Object.keys(check_options).length == attributes_count) {
                        var match_variation = cw_findMatchVariation(product_variations, check_options, false);
                        if (match_variation) {
                            if (match_variation.is_in_stock && match_variation.is_purchasable) {
                                $(document).trigger('cleverswatch_button_add_cart', {
                                    "selector": add_to_cart_button
                                });
                            } else {
                                $(document).trigger('cleverswatch_button_out_stock', {
                                    "selector": add_to_cart_button
                                });
                            }

                            var template = wp.template('variation-template');
                            var template_html = template({
                                variation: match_variation
                            });
                            form_add_to_cart.find('.woocommerce-variation.single_variation').html(template_html);
                            if ($('.single_variation_wrap .single_variation').css('display') == 'none') {
                                form_add_to_cart.find('.woocommerce-variation.single_variation').slideDown()
                            }
                        } else {
                            $(document).trigger('cleverswatch_button_out_stock', {
                                "selector": add_to_cart_button
                            });
                            var template = wp.template('unavailable-variation-template');
                            form_add_to_cart.find('.woocommerce-variation.single_variation').html(template);
                            if ($('.single_variation_wrap .single_variation').css('display') == 'none') {
                                form_add_to_cart.find('.woocommerce-variation.single_variation').slideDown()
                            }
                        }

                    } else {
                        $(document).trigger('cleverswatch_button_select_option', {
                            "selector": add_to_cart_button
                        });
                        form_add_to_cart.find('.woocommerce-variation.single_variation').slideUp();
                    }
                },
                mouseleave: function () {
                    var form_add_to_cart = $(this).parents('.variations_form');
                    var attribute_rows = form_add_to_cart.find('.zoo-cw-group-attribute');
                    var current_attribute_row = $(this).parents('.zoo-cw-group-attribute');
                    //visual
                    attribute_rows.find('.zoo-cw-attribute-option').removeClass('temp-out-stock').removeClass('temp-in-stock');
                    var selected_option_name = current_attribute_row.find('.zoo-cw-attribute-option.zoo-cw-active').data('attribute-name');
                    if (selected_option_name === undefined) {
                        selected_option_name = '';
                    }
                    current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text(selected_option_name);

                    //add to cart button effect
                    var add_to_cart_button = form_add_to_cart.find('.single_add_to_cart_button');
                    var product_variations = form_add_to_cart.data('product_variations');
                    var selected_options = form_add_to_cart.data('selected_options');
                    if (selected_options === undefined) {
                        selected_options = {};
                    }
                    var attributes_count = form_add_to_cart.data('attributes_count');
                    if (Object.keys(selected_options).length == attributes_count) {
                        var match_variation = cw_findMatchVariation(product_variations, selected_options, false);
                        if (match_variation) {
                            if (match_variation.is_in_stock && match_variation.is_purchasable) {
                                $(document).trigger('cleverswatch_button_add_cart', {
                                    "selector": add_to_cart_button
                                });
                            } else {
                                $(document).trigger('cleverswatch_button_out_stock', {
                                    "selector": add_to_cart_button
                                });
                            }
                            var template = wp.template('variation-template');
                            var template_html = template({
                                variation: match_variation
                            });
                            form_add_to_cart.find('.woocommerce-variation.single_variation').html(template_html);
                            if ($('.single_variation_wrap .single_variation').css('display') == 'none') {
                                form_add_to_cart.find('.woocommerce-variation.single_variation').slideDown()
                            }
                        } else {
                            $(document).trigger('cleverswatch_button_out_stock', {
                                "selector": add_to_cart_button
                            });
                            var template = wp.template('unavailable-variation-template');
                            form_add_to_cart.find('.woocommerce-variation.single_variation').html(template);
                            if ($('.single_variation_wrap .single_variation').css('display') == 'none') {
                                form_add_to_cart.find('.woocommerce-variation.single_variation').slideDown()
                            }
                        }
                    } else {
                        $(document).trigger('cleverswatch_button_select_option', {
                            "selector": add_to_cart_button
                        });
                    }
                }
            }, '.variations_form .zoo-cw-attribute-option');
            $(document).on({
                mouseleave: function () {
                    if (!$(this).find('.zoo-cw-active')[0]) {
                        $(this).parents('.variations_form').find('.woocommerce-variation.single_variation').slideUp();
                    }
                }
            }, '.variations_form .zoo-cw-group-attribute');
            //pass the element as an argument to .on
            //change function for select type option
            $(document).on('change', '.variations_form .zoo-cw-group-attribute select.zoo-cw-attribute-select', function () {
                var form_add_to_cart = $(this).parents('.variations_form');
                var attribute_rows = form_add_to_cart.find('.zoo-cw-group-attribute');
                var current_attribute_row = $(this).parents('.zoo-cw-group-attribute');
                var attribute_name = current_attribute_row.data('group-attribute');
                var product_variations = form_add_to_cart.data('product_variations');
                var selected_options = form_add_to_cart.data('selected_options');
                if (selected_options === undefined) {
                    selected_options = {};
                }
                var selected_option_name = '';
                var option_value = $(this).val();
                if (option_value == "") {
                    selected_option_name = "";
                } else {
                    selected_option_name = $(this).find('option:selected').text();
                }
                //visual
                current_attribute_row.parents('.zoo-cw-attr-row').find('.zoo-cw-name').text(selected_option_name);
                //process
                current_attribute_row.find('input[name=' + attribute_name + ']').val(option_value);
                selected_options[attribute_name] = option_value;
                form_add_to_cart.data('selected_options', selected_options);
                cw_runFilter(product_variations, selected_options, attribute_rows, 'out-stock', "");
                //apply to cart
                var attributes_count = form_add_to_cart.data('attributes_count');
                if (Object.keys(selected_options).length == attributes_count) {
                    cw_processSelectedOption(product_variations, selected_options, form_add_to_cart);
                } else {
                    form_add_to_cart.find('button.single_add_to_cart_button').addClass('disabled');
                }

                if (option_value == '') {
                    form_add_to_cart.find('button.single_add_to_cart_button').addClass('disabled');
                }
            });
        }
        //Bind for button if button is select option
        $(document).bind('cleverswatch_button_select_option', function (event, response) {
            var add_to_cart_button = response.selector;
            var icon = add_to_cart_button.find('i').clone();
            add_to_cart_button.html(wp.template('add-to-cart-button-select-option')).addClass('disabled');
            add_to_cart_button.prepend(icon);
        });
        //Bind for button if button is Add to Cart
        $(document).bind('cleverswatch_button_add_cart', function (event, response) {
            var add_to_cart_button = response.selector;
            var icon = add_to_cart_button.find('i').clone();
            add_to_cart_button.html(wp.template('add-to-cart-button')).removeClass('disabled');
            add_to_cart_button.prepend(icon);
        });
        //Bind for button if button is Out of stock
        $(document).bind('cleverswatch_button_out_stock', function (event, response) {
            var add_to_cart_button = response.selector;
            var icon = add_to_cart_button.find('i').clone();
            add_to_cart_button.html(wp.template('add-to-cart-button-out-stock')).addClass('disabled');
            add_to_cart_button.prepend(icon);
        });
        //Add Tooltip
        $('.zoo-cw-attr-item>span, .zoo-cw-attr-item>img').tooltip({
            tooltipClass: "zoo-cw-tooltip",
            items: "[data-tooltip]",
            content: function () {
                return $(this).data('tooltip');
            }
        });
    });
})(jQuery);
