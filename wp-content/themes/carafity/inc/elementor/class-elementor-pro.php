<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_action('elementor/theme/before_do_header', function () {
    wp_body_open();
    do_action('carafity_before_site'); ?>
    <div id="page" class="hfeed site">
    <?php
});

add_action('elementor/theme/after_do_header', function () {
    do_action('carafity_before_content');
    ?>
    <div id="content" class="site-content" tabindex="-1">
        <div class="col-full">
    <?php
    do_action('carafity_content_top');
});

add_action('elementor/theme/before_do_footer', function () {
    ?>
		</div><!-- .col-fluid -->
	</div><!-- #content -->

	<?php do_action( 'carafity_before_footer' );
});

add_action('elementor/theme/after_do_footer', function () {

    do_action( 'carafity_after_footer' );
    ?>

    </div><!-- #page -->
        <?php
});

if(class_exists('WooCommerce')) {
    if (defined('ELEMENTOR_PRO_VERSION') && version_compare(ELEMENTOR_PRO_VERSION, '3.12.0', '>=')) {
        if (get_option('elementor_use_mini_cart_template') == 'initial') {
            update_option('elementor_use_mini_cart_template', 'no');
        }
        add_filter('woocommerce_add_to_cart_fragments', 'elementor_pro_cart_count_fragments', 1, 9999);
        function elementor_pro_cart_count_fragments($fragments) {

            ob_start();
            woocommerce_mini_cart();
            $mini_cart                             = ob_get_clean();
            $fragments['div.widget_shopping_cart'] = '<div class="widget woocommerce widget_shopping_cart"><div class="widget_shopping_cart_content">' . $mini_cart . '</div></div>';

            return $fragments;
        }
    }
}