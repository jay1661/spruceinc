<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
$class = wc_get_loop_prop('product-carousel') == 'swiper-wrapper' ? 'swiper-slide product-style-1' : 'product-style-1';
?>
<li <?php wc_product_class($class, $product); ?>>
    <div class="product-block">
        <div class="product-transition">
            <?php woocommerce_show_product_loop_sale_flash(); ?>
            <?php carafity_template_loop_product_thumbnail(); ?>
            <?php woocommerce_template_loop_product_link_open(); ?>
            <?php woocommerce_template_loop_product_link_close(); ?>
            <div class="group-action">
                <div class="shop-action">
                    <?php
                    carafity_wishlist_button();
                    carafity_quickview_button();
                    carafity_compare_button(); ?>
                </div>
            </div>
        </div>
        <div class="product-caption">
            <?php woocommerce_template_loop_product_title(); ?>
            <div class="product-action">
                <?php woocommerce_template_loop_price(); ?>
                <?php woocommerce_template_loop_add_to_cart(); ?>
            </div>
        </div>
    </div>
</li>
