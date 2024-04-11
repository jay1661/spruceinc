<?php

/**
 * Main class of plugin for admin
 */
class Carafity_Woocommerce_Bought_Together {
    /**
     * Class constructor.
     */
    public function __construct() {

        add_filter('woocommerce_product_data_tabs', array($this, 'product_meta_tab'), 10, 1);
        add_action('woocommerce_product_data_panels', array($this, 'product_meta_panel'));
        add_action('woocommerce_process_product_meta', array($this, 'product_meta_fields_save'));
        add_action('wp_ajax_carafity_woocommerce_fbt_add_to_cart', array($this, 'ajax_fbt_add_to_cart'));
        add_action('wp_ajax_nopriv_carafity_woocommerce_fbt_add_to_cart', array($this, 'ajax_fbt_add_to_cart'));
        add_action('wp_ajax_nopriv_carafity_woocommerce_fbt_add_to_cart', array($this, 'ajax_fbt_add_to_cart'));
        add_action('woocommerce_after_single_product_summary', array($this, 'bought_together_html'), 5);
    }

    /**
     * Add Frequently bought together tab in edit product page
     *
     * @param $tabs
     *
     * @return mixed
     */
    public function product_meta_tab($tabs) {

        $tabs['carafity_product_together'] = array(
            'label'  => esc_html__('Bought Together', 'carafity'),
            'target' => 'carafity_woocommerce_product_bought_together',
            'class'  => array('show_if_simple'),
        );
        return $tabs;
    }


    /**
     * product_meta_fields_save function.
     *
     * @param mixed $post_id
     */
    public function product_meta_fields_save($post_id) {
        if (isset($_POST['carafity_products_bought_together'])) {
            update_post_meta($post_id, 'carafity_products_bought_together', $_POST['carafity_products_bought_together']);
        } else {
            update_post_meta($post_id, 'carafity_products_bought_together', 0);
        }
    }


    /**
     * Add Frequently bought together panel in edit product page
     */
    public function product_meta_panel() {
        global $post;
        ?>

        <div id="carafity_woocommerce_product_bought_together" class="panel woocommerce_options_panel">

            <div class="options_group">

                <p class="form-field">
                    <label for="carafity_products_bought_together"><?php esc_html_e('Select Products', 'carafity'); ?></label>
                    <select class="wc-product-search" multiple="multiple" style="width: 50%;"
                            id="carafity_products_bought_together" name="carafity_products_bought_together[]"
                            data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'carafity'); ?>"
                            data-action="woocommerce_json_search_products_and_variations"
                            data-exclude="<?php echo intval($post->ID); ?>">
                        <?php
                        $product_ids = maybe_unserialize(get_post_meta($post->ID, 'carafity_products_bought_together', true));

                        if ($product_ids && is_array($product_ids)) {
                            foreach ($product_ids as $product_id) {
                                $product = wc_get_product($product_id);
                                if (is_object($product)) {
                                    echo '<option value="' . esc_attr($product_id) . '"' . selected(true, true, false) . '>' . wp_kses_post($product->get_formatted_name()) . '</option>';
                                }
                            }
                        }
                        ?>
                    </select> <?php echo wc_help_tip(__('Select products for "Bought together" group.', 'carafity')); ?>
                </p>

            </div>

        </div>

        <?php
    }

    public function ajax_fbt_add_to_cart() {

        $product_ids = $_POST['product_ids'];
        $quantity    = 1;
        $product_ids = explode(',', $product_ids);
        if (is_array($product_ids)) {
            foreach ($product_ids as $product_id) {
                if ($product_id == 0) {
                    continue;
                }
                $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
                $product_status    = get_post_status($product_id);

                if ($passed_validation && false !== WC()->cart->add_to_cart($product_id, $quantity) && 'publish' === $product_status) {

                    do_action('woocommerce_ajax_added_to_cart', $product_id);

                    if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                        wc_add_to_cart_message(array($product_id => $quantity), true);
                    }


                } else {

                    // If there was an error adding to the cart, redirect to the product page to show any errors
                    $data = array(
                        'error'       => true,
                        'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id),
                    );

                    wp_send_json($data);
                }
            }
        }

    }

    public function bought_together_html() {
        global $product;

        if ('simple' === $product->get_type()) {
            $product_ids = maybe_unserialize(get_post_meta($product->get_id(), 'carafity_products_bought_together', true));
            if (empty($product_ids) || !is_array($product_ids)) {
                return;
            }

            $total_price = $product->get_price();

            if ($product_ids) : ?>
                <section class="carafity-frequently-bought clearfix"
                         data-currency_pos="<?php echo get_option('woocommerce_currency_pos'); ?>"
                         data-currency="<?php echo get_woocommerce_currency_symbol(); ?>"
                         data-thousand="<?php echo wc_get_price_thousand_separator(); ?>"
                         data-decimal="<?php echo wc_get_price_decimal_separator(); ?>"
                         data-price_decimals="<?php echo wc_get_price_decimals(); ?>"
                         data-current-id="<?php echo esc_attr($product->get_id()); ?>">
                    <div class="frequently-bought-product">
                        <h5 class="frequently-bought-title"><?php echo esc_html__('Frequently Bought Together', 'carafity'); ?></h5>
                        <div class="carafity-products-overflow">
                            <ul class="products">
                                <?php
                                foreach ($product_ids as $key => $product_id) {
                                    $item = wc_get_product($product_id);
                                    if (empty($item) || !$item->is_in_stock()) {
                                        continue;
                                    }
                                    $total_price += $item->get_price();
                                    $seperator   = $key == array_key_last($product_ids) ? '' : 'seperator'
                                    ?>
                                    <li class="product-style-frequently-bought product <?php echo esc_attr(' ' . $seperator); ?>">
                                        <div class="product-block">
                                            <div class="product-transition">
                                                <div class="product-image">
                                                    <?php echo sprintf('%s', $item->get_image('medium')); ?>
                                                </div>
                                                <?php
                                                $output = array();
                                                if ($item->is_on_sale()) {
                                                    $percentage = '';
                                                    if ($item->get_type() == 'variable') {
                                                        $available_variations = $item->get_variation_prices();
                                                        $max_percentage       = 0;
                                                        foreach ($available_variations['regular_price'] as $key => $regular_price) {
                                                            $sale_price = $available_variations['sale_price'][$key];
                                                            if ($sale_price < $regular_price) {
                                                                $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
                                                                if ($percentage > $max_percentage) {
                                                                    $max_percentage = $percentage;
                                                                }
                                                            }
                                                        }
                                                        $percentage = $max_percentage;
                                                    } elseif (($item->get_type() == 'simple' || $item->get_type() == 'external')) {
                                                        $percentage = round((($item->get_regular_price() - $item->get_sale_price()) / $item->get_regular_price()) * 100);
                                                    }
                                                    if ($percentage) {
                                                        $output[] = '<span class="onsale">' . $percentage . '%' . esc_html__(' OFF', 'carafity') . '</span>';
                                                    } else {
                                                        $output[] = '<span class="onsale">' . esc_html__('Sale!', 'carafity') . '</span>';
                                                    }
                                                }
                                                if ($output) {
                                                    echo implode('', $output);
                                                }
                                                ?>
                                                <a href="<?php echo esc_url($item->get_permalink()); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"></a>
                                            </div>
                                            <div class="product-caption">
                                                <h3 class="woocommerce-loop-product__title">
                                                    <a href="<?php echo esc_url($item->get_permalink()); ?>">
                                                        <?php echo wp_kses_post($item->get_name()); ?>
                                                    </a>
                                                </h3>
                                                <?php printf('<span class="price">%s</span>', $item->get_price_html()); ?>
                                                <?php
                                                echo '<label class="select-item" data-price="' . esc_attr($product->get_price()) . '"><input class="combo-checkbox" type="checkbox" value="' . esc_attr($item->get_id()) . '" name="' . $item->get_slug() . '" checked></label>';
                                                ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                            <div class="group-add-to-cart">
                                <div class="label"><?php esc_html_e('Total Price: ', 'carafity'); ?></div>
                                <div class="carafity-total-price"><?php echo wc_price($total_price); ?></div>
                                <input type="hidden" data-price="<?php echo esc_attr($total_price); ?>"
                                       id="carafity-data_price">
                                <button name="carafity-add-to-cart" value="<?php echo esc_attr($product->get_id()) . ',' . esc_attr(implode(',', $product_ids)); ?>"
                                        class="carafity_add_to_cart_button ajax_add_to_cart button"><?php esc_html_e('Add All To Cart', 'carafity'); ?></button>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif;

            wp_reset_postdata();
        }

    }

}

new Carafity_Woocommerce_Bought_Together;
