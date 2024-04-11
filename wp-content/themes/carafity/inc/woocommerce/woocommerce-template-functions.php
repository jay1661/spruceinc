<?php

if (!function_exists('carafity_before_content')) {
    /**
     * Before Content
     * Wraps all WooCommerce content in wrappers which match the theme markup
     *
     * @return  void
     * @since   1.0.0
     */
    function carafity_before_content() {
        echo <<<HTML
<div id="primary" class="content-area">
    <main id="main" class="site-main">
HTML;

    }
}


if (!function_exists('carafity_after_content')) {
    /**
     * After Content
     * Closes the wrapping divs
     *
     * @return  void
     * @since   1.0.0
     */
    function carafity_after_content() {
        echo <<<HTML
	</main><!-- #main -->
</div><!-- #primary -->
HTML;

        do_action('carafity_sidebar');
    }
}

if (!function_exists('carafity_cart_link_fragment')) {
    /**
     * Cart Fragments
     * Ensure cart contents update when products are added to the cart via AJAX
     *
     * @param array $fragments Fragments to refresh via AJAX.
     *
     * @return array            Fragments to refresh via AJAX
     */
    function carafity_cart_link_fragment($fragments) {
        ob_start();
        carafity_cart_link();
        $fragments['a.cart-contents'] = ob_get_clean();

        ob_start();

        return $fragments;
    }
}

if (!function_exists('carafity_cart_link')) {
    /**
     * Cart Link
     * Displayed a link to the cart including the number of items present and the cart total
     *
     * @return void
     * @since  1.0.0
     */
    function carafity_cart_link() {
        $cart = WC()->cart;
        ?>
        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'carafity'); ?>">
            <?php if ($cart):?>
                <span class="count">(<?php echo wp_kses_data(sprintf(_n('%d', '%d', WC()->cart->get_cart_contents_count(), 'carafity'), WC()->cart->get_cart_contents_count())); ?>)</span>
                <?php echo WC()->cart->get_cart_subtotal(); ?>
            <?php endif; ?>
        </a>
        <?php
    }
}

class Carafity_Custom_Walker_Category extends Walker_Category {

    public function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters(
            'list_cats',
            esc_attr($category->name),
            $category
        );

        // Don't generate an element if the category name is empty.
        if (!$cat_name) {
            return;
        }

        $link = '<a class="pf-value" href="' . esc_url(get_term_link($category)) . '" data-val="' . esc_attr($category->slug) . '" data-title="' . esc_attr($category->name) . '" ';
        if ($args['use_desc_for_title'] && !empty($category->description)) {
            /**
             * Filters the category description for display.
             *
             * @param string $description Category description.
             * @param object $category Category object.
             *
             * @since 1.2.0
             *
             */
            $link .= 'title="' . esc_attr(strip_tags(apply_filters('category_description', $category->description, $category))) . '"';
        }

        $link .= '>';
        $link .= $cat_name . '</a>';

        if (!empty($args['feed_image']) || !empty($args['feed'])) {
            $link .= ' ';

            if (empty($args['feed_image'])) {
                $link .= '(';
            }

            $link .= '<a href="' . esc_url(get_term_feed_link($category->term_id, $category->taxonomy, $args['feed_type'])) . '"';

            if (empty($args['feed'])) {
                $alt = ' alt="' . sprintf(esc_html__('Feed for all posts filed under %s', 'carafity'), $cat_name) . '"';
            } else {
                $alt  = ' alt="' . $args['feed'] . '"';
                $name = $args['feed'];
                $link .= empty($args['title']) ? '' : $args['title'];
            }

            $link .= '>';

            if (empty($args['feed_image'])) {
                $link .= $name;
            } else {
                $link .= "<img src='" . $args['feed_image'] . "'$alt" . ' />';
            }
            $link .= '</a>';

            if (empty($args['feed_image'])) {
                $link .= ')';
            }
        }

        if (!empty($args['show_count'])) {
            $link .= ' (' . number_format_i18n($category->count) . ')';
        }
        if ('list' == $args['style']) {
            $output      .= "\t<li";
            $css_classes = array(
                'cat-item',
                'cat-item-' . $category->term_id,
            );

            if (!empty($args['current_category'])) {
                // 'current_category' can be an array, so we use `get_terms()`.
                $_current_terms = get_terms(
                    $category->taxonomy,
                    array(
                        'include'    => $args['current_category'],
                        'hide_empty' => false,
                    )
                );

                foreach ($_current_terms as $_current_term) {
                    if ($category->term_id == $_current_term->term_id) {
                        $css_classes[] = 'current-cat pf-active';
                    } elseif ($category->term_id == $_current_term->parent) {
                        $css_classes[] = 'current-cat-parent';
                    }
                    while ($_current_term->parent) {
                        if ($category->term_id == $_current_term->parent) {
                            $css_classes[] = 'current-cat-ancestor';
                            break;
                        }
                        $_current_term = get_term($_current_term->parent, $category->taxonomy);
                    }
                }
            }

            /**
             * Filters the list of CSS classes to include with each category in the list.
             *
             * @param array $css_classes An array of CSS classes to be applied to each list item.
             * @param object $category Category data object.
             * @param int $depth Depth of page, used for padding.
             * @param array $args An array of wp_list_categories() arguments.
             *
             * @since 4.2.0
             *
             * @see wp_list_categories()
             *
             */
            $css_classes = implode(' ', apply_filters('category_css_class', $css_classes, $category, $depth, $args));

            $output .= ' class="' . $css_classes . '"';
            $output .= ">$link\n";
        } elseif (isset($args['separator'])) {
            $output .= "\t$link" . $args['separator'] . "\n";
        } else {
            $output .= "\t$link<br />\n";
        }
    }
}

if (!function_exists('carafity_show_categories_dropdown')) {
    function carafity_show_categories_dropdown() {
        static $id = 0;
        $args  = array(
            'hide_empty' => 1,
            'parent'     => 0
        );
        $terms = get_terms('product_cat', $args);
        if (!empty($terms) && !is_wp_error($terms)) {
            ?>
            <div class="search-by-category input-dropdown">
                <div class="input-dropdown-inner carafity-scroll-content">
                    <!--                    <input type="hidden" name="product_cat" value="0">-->
                    <a href="#" data-val="0"><span><?php esc_html_e('All category', 'carafity'); ?></span></a>
                    <?php
                    $args_dropdown = array(
                        'id'               => 'product_cat' . $id++,
                        'show_count'       => 0,
                        'class'            => 'dropdown_product_cat_ajax',
                        'show_option_none' => esc_html__('All category', 'carafity'),
                    );
                    wc_product_dropdown_categories($args_dropdown);
                    ?>
                    <div class="list-wrapper carafity-scroll">
                        <ul class="carafity-scroll-content">
                            <li class="d-none">
                                <a href="#" data-val="0"><?php esc_html_e('All category', 'carafity'); ?></a></li>
                            <?php
                            if (!apply_filters('carafity_show_only_parent_categories_dropdown', false)) {
                                $args_list = array(
                                    'title_li'           => false,
                                    'taxonomy'           => 'product_cat',
                                    'use_desc_for_title' => false,
                                    'walker'             => new Carafity_Custom_Walker_Category(),
                                );
                                wp_list_categories($args_list);
                            } else {
                                foreach ($terms as $term) {
                                    ?>
                                    <li>
                                        <a href="#" data-val="<?php echo esc_attr($term->slug); ?>"><?php echo esc_attr($term->name); ?></a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if (!function_exists('carafity_product_search')) {
    /**
     * Display Product Search
     *
     * @return void
     * @uses  carafity_is_woocommerce_activated() check if WooCommerce is activated
     * @since  1.0.0
     */
    function carafity_product_search() {
        if (carafity_is_woocommerce_activated()) {
            static $index = 0;
            $index++;
            ?>
            <div class="site-search ajax-search">
                <div class="widget woocommerce widget_product_search">
                    <div class="ajax-search-result d-none"></div>
                    <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
                        <label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>"><?php esc_html_e('Search for:', 'carafity'); ?></label>
                        <input type="search" id="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>" class="search-field" placeholder="<?php echo esc_attr__('Search products&hellip;', 'carafity'); ?>" autocomplete="off" value="<?php echo get_search_query(); ?>" name="s"/>
                        <button type="submit" value="<?php echo esc_attr_x('Search', 'submit button', 'carafity'); ?>">
                            <i class="carafity-icon-search"></i><?php echo esc_html_x('Search', 'submit button', 'carafity'); ?>
                        </button>
                        <input type="hidden" name="post_type" value="product"/>
                        <?php carafity_show_categories_dropdown(); ?>
                        <?php wp_nonce_field('ajax-carafity-search-nonce', 'security-search'); ?>
                    </form>
                </div>
            </div>
            <?php
        }
    }
}

if (!function_exists('carafity_header_cart')) {
    /**
     * Display Header Cart
     *
     * @return void
     * @uses  carafity_is_woocommerce_activated() check if WooCommerce is activated
     * @since  1.0.0
     */
    function carafity_header_cart() {
        if (carafity_is_woocommerce_activated()) {
            if (!carafity_get_theme_option('show_header_cart', true)) {
                return;
            }
            ?>
            <div class="site-header-cart menu">
                <?php carafity_cart_link(); ?>
                <?php

                if (!apply_filters('woocommerce_widget_cart_is_hidden', is_cart() || is_checkout())) {

                    if (carafity_get_theme_option('header_cart_dropdown', 'side') == 'side') {
                        add_action('wp_footer', 'carafity_header_cart_side');
                    } else {
                        the_widget('WC_Widget_Cart', 'title=');
                    }
                }
                ?>
            </div>
            <?php
        }
    }
}

if (!function_exists('carafity_header_cart_side')) {
    function carafity_header_cart_side() {
        if (carafity_is_woocommerce_activated()) {
            ?>
            <div class="site-header-cart-side">
                <div class="cart-side-heading">
                    <span class="cart-side-title"><?php echo esc_html__('Shopping cart', 'carafity'); ?></span>
                    <a href="#" class="close-cart-side"><?php echo esc_html__('close', 'carafity') ?></a></div>
                <?php the_widget('WC_Widget_Cart', 'title='); ?>
            </div>
            <div class="cart-side-overlay"></div>
            <?php
        }
    }
}

if (!function_exists('carafity_product_columns_wrapper')) {
    /**
     * Product columns wrapper
     *
     * @return  void
     * @since   2.2.0
     */
    function carafity_product_columns_wrapper() {
        $columns = carafity_loop_columns();
        echo '<div class="columns-' . absint($columns) . '">';
    }
}

if (!function_exists('carafity_loop_columns')) {
    /**
     * Default loop columns on product archives
     *
     * @return integer products per row
     * @since  1.0.0
     */
    function carafity_loop_columns() {
        $columns = 3; // 3 products per row

        if (function_exists('wc_get_default_products_per_row')) {
            $columns = wc_get_default_products_per_row();
        }

        return apply_filters('carafity_loop_columns', $columns);
    }
}

if (!function_exists('carafity_product_columns_wrapper_close')) {
    /**
     * Product columns wrapper close
     *
     * @return  void
     * @since   2.2.0
     */
    function carafity_product_columns_wrapper_close() {
        echo '</div>';
    }
}

if (!function_exists('carafity_shop_messages')) {
    /**
     * ThemeBase shop messages
     *
     * @since   1.4.4
     * @uses    carafity_do_shortcode
     */
    function carafity_shop_messages() {
        if (!is_checkout()) {
            echo carafity_do_shortcode('woocommerce_messages');
        }
    }
}

if (!function_exists('carafity_woocommerce_pagination')) {
    /**
     * ThemeBase WooCommerce Pagination
     * WooCommerce disables the product pagination inside the woocommerce_product_subcategories() function
     * but since ThemeBase adds pagination before that function is excuted we need a separate function to
     * determine whether or not to display the pagination.
     *
     * @since 1.4.4
     */
    function carafity_woocommerce_pagination() {
        if (woocommerce_products_will_display()) {
            woocommerce_pagination();
        }
    }
}


if (!function_exists('carafity_single_product_pagination')) {
    /**
     * Single Product Pagination
     *
     * @since 2.3.0
     */
    function carafity_single_product_pagination() {

        // Show only products in the same category?
        $in_same_term   = apply_filters('carafity_single_product_pagination_same_category', true);
        $excluded_terms = apply_filters('carafity_single_product_pagination_excluded_terms', '');
        $taxonomy       = apply_filters('carafity_single_product_pagination_taxonomy', 'product_cat');

        $previous_product = carafity_get_previous_product($in_same_term, $excluded_terms, $taxonomy);
        $next_product     = carafity_get_next_product($in_same_term, $excluded_terms, $taxonomy);

        if ((!$previous_product && !$next_product) || !is_product()) {
            return;
        }

        ?>
        <div class="carafity-product-pagination-wrap">
            <nav class="carafity-product-pagination" aria-label="<?php esc_attr_e('More products', 'carafity'); ?>">
                <?php if ($previous_product) : ?>
                    <a href="<?php echo esc_url($previous_product->get_permalink()); ?>" rel="prev">
                        <span class="pagination-prev "><i class="carafity-icon-arrow-left"></i><?php echo esc_html__('Prev', 'carafity'); ?></span>
                        <div class="product-item">
                            <?php echo sprintf('%s', $previous_product->get_image()); ?>
                            <div class="carafity-product-pagination-content">
                                <h4 class="carafity-product-pagination__title"><?php echo sprintf('%s', $previous_product->get_name()); ?></h4>
                                <?php if ($price_html = $previous_product->get_price_html()) :
                                    printf('<span class="price">%s</span>', $price_html);
                                endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>

                <?php if ($next_product) : ?>
                    <a href="<?php echo esc_url($next_product->get_permalink()); ?>" rel="next">
                        <span class="pagination-next"><?php echo esc_html__('Next', 'carafity'); ?><i class="carafity-icon-arrow-right"></i></span>
                        <div class="product-item">
                            <?php echo sprintf('%s', $next_product->get_image()); ?>
                            <div class="carafity-product-pagination-content">
                                <h4 class="carafity-product-pagination__title"><?php echo sprintf('%s', $next_product->get_name()); ?></h4>
                                <?php if ($price_html = $next_product->get_price_html()) :
                                    printf('<span class="price">%s</span>', $price_html);
                                endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
            </nav><!-- .carafity-product-pagination -->
        </div>
        <?php

    }
}

if (!function_exists('carafity_sticky_single_add_to_cart')) {
    /**
     * Sticky Add to Cart
     *
     * @since 2.3.0
     */
    function carafity_sticky_single_add_to_cart() {
        global $product;

        if (!is_product()) {
            return;
        }

        $show = false;

        if ($product->is_purchasable() && $product->is_in_stock()) {
            $show = true;
        } else if ($product->is_type('external')) {
            $show = true;
        }

        if (!$show) {
            return;
        }

        $params = apply_filters(
            'carafity_sticky_add_to_cart_params', array(
                'trigger_class' => 'entry-summary',
            )
        );

        wp_localize_script('carafity-sticky-add-to-cart', 'carafity_sticky_add_to_cart_params', $params);
        ?>

        <section class="carafity-sticky-add-to-cart">
            <div class="col-fluid">
                <div class="carafity-sticky-add-to-cart__content">
                    <?php echo woocommerce_get_product_thumbnail(); ?>
                    <div class="carafity-sticky-add-to-cart__content-product-info">
						<span class="carafity-sticky-add-to-cart__content-title"><?php esc_attr_e('You\'re viewing:', 'carafity'); ?>
							<strong><?php the_title(); ?></strong></span>
                        <span class="carafity-sticky-add-to-cart__content-price"><?php echo sprintf('%s', $product->get_price_html()); ?></span>
                        <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                    </div>
                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="carafity-sticky-add-to-cart__content-button button alt">
                        <?php echo esc_attr($product->add_to_cart_text()); ?>
                    </a>
                </div>
            </div>
        </section><!-- .carafity-sticky-add-to-cart -->
        <?php
    }
}

if (!function_exists('carafity_woocommerce_product_loop_action')) {
    function carafity_woocommerce_product_loop_action() {
        ?>
        <div class="group-action">
            <div class="shop-action">
                <?php do_action('carafity_woocommerce_product_loop_action'); ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('carafity_single_product_summary_top')) {
    function carafity_single_product_summary_top() {
        ?>
        <div class="entry-summary-top">
            <div class="single-product-label">
                <?php
                woocommerce_show_product_sale_flash();
                carafity_woocommerce_get_product_label_stock();
                ?>
            </div>
            <?php carafity_single_product_pagination(); ?>
        </div>
        <?php
    }
}

if (!function_exists('carafity_single_product_after_title')) {
    function carafity_single_product_after_title() {
        global $product;
        ?>
        <div class="product_after_title">
            <?php
            carafity_woocommerce_single_brand();
            woocommerce_template_single_rating();
            if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) :
                $sku = $product->get_sku() ? $product->get_sku() : esc_html__('N/A', 'carafity');
                ?>
                <span class="sku_wrapper"><?php esc_html_e('SKU:', 'carafity'); ?> <span class="sku"><?php printf('%s', $sku); ?></span></span>
            <?php endif; ?>
        </div>
        <?php
    }
}

if (!function_exists('carafity_single_product_summary_content_start')) {
    function carafity_single_product_summary_content_start() {
        echo <<<HTML
<div class="entry-summary-content">
HTML;
    }
}

if (!function_exists('carafity_single_product_summary_content_end')) {
    function carafity_single_product_summary_content_end() {
        echo <<<HTML
</div>
HTML;
    }
}

if (!function_exists('carafity_button_grid_list_layout')) {
    function carafity_button_grid_list_layout() {
        ?>
        <div class="gridlist-toggle desktop-hide-down">
            <a href="<?php echo esc_url(add_query_arg('layout', 'list')); ?>" id="list" class="<?php echo isset($_GET['layout']) && $_GET['layout'] == 'list' ? 'active' : ''; ?>" title="<?php echo esc_html__('List View', 'carafity'); ?>"><i class="carafity-icon-list"></i></a>
            <a href="<?php echo esc_url(add_query_arg('layout', 'grid')); ?>" id="grid" class="<?php echo isset($_GET['layout']) && $_GET['layout'] == 'list' ? '' : 'active'; ?>" title="<?php echo esc_html__('Grid View', 'carafity'); ?>"><i class="carafity-icon-grid"></i></a>
        </div>
        <?php
    }
}

if (!function_exists('carafity_product_label')) {
    function carafity_product_label() {
        global $product;

        $output = array();

        if ($product->is_on_sale()) {

            $percentage = '';

            if ($product->get_type() == 'variable') {

                $available_variations = $product->get_variation_prices();
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
            } elseif (($product->get_type() == 'simple' || $product->get_type() == 'external')) {
                $percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
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
    }
}

add_filter('woocommerce_sale_flash', 'carafity_product_label', 10);


if (!function_exists('carafity_template_loop_product_thumbnail')) {
    function carafity_template_loop_product_thumbnail($size = 'woocommerce_thumbnail', $deprecated1 = 0, $deprecated2 = 0) {
        global $product;
        if (!$product) {
            return '';
        }
        $gallery    = $product->get_gallery_image_ids();
        $hover_skin = carafity_get_theme_option('woocommerce_product_hover', 'none');
        if ($hover_skin == 'none' || count($gallery) <= 0) {
            echo '<div class="product-image image-main">' . $product->get_image('woocommerce_thumbnail') . '</div>';

            return '';
        }
        $image_featured = '<div class="product-image image-main">' . $product->get_image('woocommerce_thumbnail') . '</div>';
        $image_featured .= '<div class="product-image second-image">' . wp_get_attachment_image($gallery[0], 'woocommerce_thumbnail') . '</div>';

        echo <<<HTML
<div class="product-img-wrap {$hover_skin}">
    <div class="inner">
        {$image_featured}
    </div>
</div>
HTML;
    }
}


if (!function_exists('carafity_woocommerce_single_product_image_thumbnail_html')) {
    function carafity_woocommerce_single_product_image_thumbnail_html($image, $attachment_id) {
        return wc_get_gallery_image_html($attachment_id, true);
    }
}

if (!function_exists('woocommerce_template_loop_product_title')) {

    /**
     * Show the product title in the product loop.
     */
    function woocommerce_template_loop_product_title() {
        echo '<h3 class="woocommerce-loop-product__title"><a href="' . esc_url_raw(get_the_permalink()) . '">' . get_the_title() . '</a></h3>';
    }
}

if (!function_exists('carafity_woocommerce_get_product_category')) {
    function carafity_woocommerce_get_product_category() {
        global $product;
        echo wc_get_product_category_list($product->get_id(), ', ', '<div class="posted-in">', '</div>');
    }
}

if (!function_exists('carafity_header_wishlist')) {
    function carafity_header_wishlist() {
        if (function_exists('yith_wcwl_count_all_products')) {
            if (!carafity_get_theme_option('show_header_wishlist', true)) {
                return;
            }
            ?>
            <div class="site-header-wishlist">
                <a class="header-wishlist" href="<?php echo esc_url(get_permalink(get_option('yith_wcwl_wishlist_page_id'))); ?>">
                    <i class="carafity-icon-heart-circle"></i>
                    <span class="count">(<?php echo esc_html(yith_wcwl_count_all_products()); ?>)</span>
                </a>
            </div>
            <?php
        } elseif (function_exists('woosw_init')) {
            if (!carafity_get_theme_option('show_header_wishlist', true)) {
                return;
            }
            $key   = WPCleverWoosw::get_key();
            $class = WPCleverWoosw::get_count($key) > 0 ? 'count' : 'count hide';
            ?>
            <div class="site-header-wishlist">
                <a class="header-wishlist" href="<?php echo esc_url(WPCleverWoosw::get_url($key, true)); ?>">
                    <i class="carafity-icon-heart-circle"></i>
                    <span class="<?php echo esc_attr($class); ?>"><?php echo esc_html(WPCleverWoosw::get_count($key)); ?></span>
                </a>
            </div>
            <?php
        }
    }
}

if (!function_exists('carafity_single_product_time_sale')) {
    function carafity_single_product_time_sale() {
        /**
         * @var $product WC_Product
         */
        global $product;

        if (!$product->is_on_sale()) {
            return;
        }

        $time_sale = '';
        $show      = '';
        $type      = 'simple';

        if ($product->is_type('variable')) {
            $time_sale            = [];
            $available_variations = $product->get_available_variations();
            foreach ($available_variations as $variation) {
                $variation_id = $variation['variation_id'];
                $time         = intval(get_post_meta($variation_id, '_sale_price_dates_to', true)) + (get_option('gmt_offset') * HOUR_IN_SECONDS);
                if ($time) {
                    $time_sale[$variation_id] = intval(get_post_meta($variation_id, '_sale_price_dates_to', true)) + (get_option('gmt_offset') * HOUR_IN_SECONDS);
                }
            }
            $show      = 'display:none;';
            $type = 'variable';
            $time_sale = json_encode($time_sale);
        } else {
            $time = intval(get_post_meta($product->get_id(), '_sale_price_dates_to', true)) + (get_option('gmt_offset') * HOUR_IN_SECONDS);
            if ($time) {
                $time_sale = $time;
            }
        }
        if (!empty($time_sale)) {
            wp_enqueue_script('carafity-countdown-single');

            ?>
            <div class="time-sale" style="<?php echo esc_attr($show); ?>">
                <div class="title"><?php echo esc_html__('Hurry up! Sale ends in:', 'carafity'); ?></div>
                <div class="carafity-countdown <?php echo esc_attr($type); ?>" data-countdown="true" data-date="<?php echo esc_attr($time_sale); ?>">
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-days"></span>
                        <span class="countdown-label"><?php echo esc_html__('days', 'carafity'); ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-hours"></span>
                        <span class="countdown-label"><?php echo esc_html__('hrs', 'carafity'); ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-minutes"></span>
                        <span class="countdown-label"><?php echo esc_html__('mins', 'carafity'); ?></span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-digits countdown-seconds"></span>
                        <span class="countdown-label"><?php echo esc_html__('secs', 'carafity'); ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
if (!function_exists('carafity_woocommerce_get_product_short_description')) {
    function carafity_woocommerce_get_product_short_description() {
        global $post;
        $short_description = wp_trim_words(apply_filters('woocommerce_short_description', $post->post_excerpt), 20);
        if ($short_description) {
            ?>
            <p class="short-description">
                <?php echo sprintf('%s', $short_description); ?>
            </p>
            <?php
        }
    }
}
if (!function_exists('carafity_woocommerce_group_action')) {
    function carafity_woocommerce_group_action() {
        ?>
        <div class="group-action">
            <div class="shop-action">
                <?php
                carafity_wishlist_button();
                carafity_quickview_button();
                carafity_compare_button();
                ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('carafity_single_product_extra')) {
    function carafity_single_product_extra() {
        global $product;
        $product_extra = carafity_get_theme_option('single_product_content_meta', '');
        $product_extra = get_post_meta($product->get_id(), '_extra_info', true) !== '' ? get_post_meta($product->get_id(), '_extra_info', true) : $product_extra;
        if ($product_extra !== '') {
            echo '<div class="carafity-single-product-extra">' . html_entity_decode($product_extra) . '</div>';
        }
    }
}

if (!function_exists('carafity_button_shop_canvas')) {
    function carafity_button_shop_canvas() {
        if (is_active_sidebar('sidebar-woocommerce-shop')) { ?>
            <a href="#" class="filter-toggle" aria-expanded="false">
                <i class="carafity-icon-filter"></i><span><?php esc_html_e('Filter', 'carafity'); ?></span></a>
            <?php
        }
    }
}

if (!function_exists('carafity_render_woocommerce_shop_canvas')) {
    function carafity_render_woocommerce_shop_canvas() {
        if (is_active_sidebar('sidebar-woocommerce-shop') && carafity_is_product_archive()) {
            ?>
            <div id="carafity-canvas-filter" class="carafity-canvas-filter">
                <div class="carafity-canvas-header">
                    <span class="filter-close"></span>
                </div>

                <div class="carafity-canvas-filter-wrap">
                    <?php if (carafity_get_theme_option('woocommerce_archive_layout') == 'canvas' || carafity_get_theme_option('woocommerce_archive_layout') == 'menu' || carafity_get_theme_option('woocommerce_archive_layout') == 'fullwidth') {
                        dynamic_sidebar('sidebar-woocommerce-shop');
                    }
                    ?>
                </div>
            </div>
            <div class="carafity-overlay-filter"></div>
            <?php
        }
    }
}

if (!function_exists('carafity_render_woocommerce_shop_dropdown')) {
    function carafity_render_woocommerce_shop_dropdown() {
        ?>
        <div id="carafity-dropdown-filter" class="carafity-dropdown-filter">
            <div class="carafity-dropdown-filter-wrap">
                <?php
                dynamic_sidebar('sidebar-woocommerce-shop');
                ?>
            </div>
        </div>
        <?php
    }
}


if (!function_exists('carafity_render_woocommerce_shop_menu')) {
    function carafity_render_woocommerce_shop_menu() {
        ?>
        <div id="carafity-menu-filter" class="carafity-menu-filter">
            <div class="carafity-menu-filter-wrap">
                <?php
                dynamic_sidebar('sidebar-woocommerce-shop');
                ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('woocommerce_checkout_order_review_start')) {

    function woocommerce_checkout_order_review_start() {
        echo '<div class="checkout-review-order-table-wrapper">';
    }
}

if (!function_exists('woocommerce_checkout_order_review_end')) {

    function woocommerce_checkout_order_review_end() {
        echo '</div>';
    }
}

if (!function_exists('carafity_woocommerce_get_product_label_stock')) {
    function carafity_woocommerce_get_product_label_stock() {
        /**
         * @var $product WC_Product
         */
        global $product;
        if (!$product->managing_stock()) {
            if (!$product->is_in_stock()) {
                echo '<span class="stock-label out-of-stock">' . esc_html__('Out Of Stock', 'carafity') . '</span>';
            } elseif ($product->is_on_backorder()) {
                echo '<span class="stock-label backorder">' . esc_html__('Available on backorder', 'carafity') . '</span>';
            } else {
                echo '<span class="stock-label">' . esc_html__('In stock', 'carafity') . '</span>';
            }
        } else {
            if (!$product->is_in_stock()) {
                echo '<span class="stock-label out-of-stock">' . esc_html__('Out Of Stock', 'carafity') . '</span>';
            } elseif ($product->is_on_backorder(1) && $product->backorders_require_notification()) {
                echo '<span class="stock-label backorder">' . esc_html__('Available on backorder', 'carafity') . '</span>';
            } else {
                echo '<span class="stock-label">' . esc_html__('In stock', 'carafity') . '</span>';
            }
        }
        if ($product->is_featured()) {
            echo '<span class="stock-label featured">' . esc_html__('Featured', 'carafity') . '</span>';
        }
    }
}

if (!function_exists('carafity_woocommerce_single_brand')) {
    function carafity_woocommerce_single_brand() {
        $id = get_the_ID();

        $terms = get_the_terms($id, 'product_brand');

        if (is_wp_error($terms)) {
            return $terms;
        }

        if (empty($terms)) {
            return false;
        }

        $links = array();

        foreach ($terms as $term) {
            $link = get_term_link($term, 'product_brand');
            if (is_wp_error($link)) {
                return $link;
            }
            $links[] = '<a href="' . esc_url($link) . '" rel="tag">' . $term->name . '</a>';
        }
        echo '<div class="product-brand">' . esc_html__('Brands: ', 'carafity') . join('', $links) . '</div>';
    }
}

if (!function_exists('carafity_single_product_video_360')) {
    function carafity_single_product_video_360() {
        global $product;
        echo '<div class="product-video-360">';
        $images = get_post_meta($product->get_id(), '_product_360_image_gallery', true);
        $video  = get_post_meta($product->get_id(), '_video_select', true);

        if ($video || $images) {
            wp_enqueue_style('magnific-popup');
            wp_enqueue_script('magnific-popup');
            wp_enqueue_script('spritespin');
            wp_enqueue_script('carafity-single-product-video');

        }
        if ($video && wc_is_valid_url($video)) {
            echo '<a class="product-video-360__btn btn-video" href="' . $video . '"><i class="carafity-icon-video"></i></a>';
        }

        if ($images) {
            $array      = explode(',', $images);
            $images_url = [];
            foreach ($array as $id) {
                $url          = wp_get_attachment_image_src($id, 'full');
                $images_url[] = $url[0];
            }

            echo '<a class="product-video-360__btn btn-360" href="#view-360"><i class="carafity-icon-360"></i></a>';
            ?>
            <div id="view-360" class="view-360 zoom-anim-dialog mfp-hide">
                <div id="rotateimages" class="opal-loading" data-images="<?php echo implode(',', $images_url); ?>"></div>
                <div class="view-360-group">
                    <span class='view-360-button view-360-prev'><i class="carafity-icon-drop-left"></i></span>
                    <i class="carafity-icon-360 view-360-svg"></i>
                    <span class='view-360-button view-360-next'><i class="carafity-icon-drop-right"></i></span>
                </div>
            </div>
            <?php
        }

        echo '</div>';
    }
}

if (!function_exists('carafity_output_product_data_accordion')) {
    function carafity_output_product_data_accordion() {
        $product_tabs = apply_filters('woocommerce_product_tabs', array());
        if (!empty($product_tabs)) : ?>
            <div id="carafity-accordion-container" class="woocommerce-tabs wc-tabs-wrapper product-accordions">
                <?php $_count = 0; ?>
                <?php foreach ($product_tabs as $key => $tab) : ?>
                    <div class="accordion-item">
                        <div class="accordion-head <?php echo esc_attr($key); ?>_tab js-btn-accordion" id="tab-title-<?php echo esc_attr($key); ?>">
                            <div class="accordion-title"><?php echo apply_filters('woocommerce_product_' . $key . '_tab_title', esc_html($tab['title']), $key); ?></div>
                        </div>
                        <div class="accordion-body js-card-body">
                            <?php call_user_func($tab['callback'], $key, $tab); ?>
                        </div>
                    </div>
                    <?php $_count++; ?>
                <?php endforeach; ?>
            </div>
        <?php endif;
    }
}

if (!function_exists('carafity_quickview_button')) {
    function carafity_quickview_button() {
        if (function_exists('woosq_init')) {
            echo do_shortcode('[woosq]');
        }
    }
}

if (!function_exists('carafity_compare_button')) {
    function carafity_compare_button() {
        if (function_exists('woosc_init')) {
            echo do_shortcode('[woosc]');
        }
    }
}

if (!function_exists('carafity_wishlist_button')) {
    function carafity_wishlist_button() {
        if (function_exists('woosw_init')) {
            echo do_shortcode('[woosw]');
        }
    }
}

function carafity_ajax_add_to_cart_add_fragments($fragments) {
    $all_notices  = WC()->session->get('wc_notices', array());
    $notice_types = apply_filters('woocommerce_notice_types', array('error', 'success', 'notice'));

    ob_start();
    foreach ($notice_types as $notice_type) {
        if (wc_notice_count($notice_type) > 0) {
            wc_get_template("notices/{$notice_type}.php", array(
                'notices' => array_filter($all_notices[$notice_type]),
            ));
        }
    }
    $fragments['notices_html'] = ob_get_clean();

    wc_clear_notices();

    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'carafity_ajax_add_to_cart_add_fragments');


if (!function_exists('carafity_ajax_search_result')) {
    function carafity_ajax_search_result() {
        ?>
        <div class="ajax-search-result d-none">
        </div>
        <?php
    }
}
add_action('pre_get_product_search_form', 'carafity_ajax_search_result');

if (!function_exists('carafity_ajax_live_search_template')) {
    function carafity_ajax_live_search_template() {
        echo <<<HTML
        <script type="text/html" id="tmpl-ajax-live-search-template">
        <div class="product-item-search">
            <# if(data.url){ #>
            <a class="product-link" href="{{{data.url}}}" title="{{{data.title}}}">
            <# } #>
                <# if(data.img){#>
                <img src="{{{data.img}}}" alt="{{{data.title}}}">
                 <# } #>
                <div class="product-content">
                <h3 class="product-title">{{{data.title}}}</h3>
                <# if(data.price){ #>
                {{{data.price}}}
                 <# } #>
                </div>
                <# if(data.url){ #>
            </a>
            <# } #>
        </div>
        </script>
HTML;
    }
}
add_action('wp_footer', 'carafity_ajax_live_search_template');

if (!function_exists('carafity_shop_page_link')) {
    function carafity_shop_page_link($keep_query = false, $taxonomy = '') {
        // Base Link decided by current page
        if (is_post_type_archive('product') || is_page(wc_get_page_id('shop')) || is_shop()) {
            $link = get_permalink(wc_get_page_id('shop'));
        } elseif (is_product_category()) {
            $link = get_term_link(get_query_var('product_cat'), 'product_cat');
        } elseif (is_product_tag()) {
            $link = get_term_link(get_query_var('product_tag'), 'product_tag');
        } else {
            $queried_object = get_queried_object();
            $link           = get_term_link($queried_object->slug, $queried_object->taxonomy);
        }

        if ($keep_query) {

            // Min/Max
            if (isset($_GET['min_price'])) {
                $link = add_query_arg('min_price', wc_clean($_GET['min_price']), $link);
            }

            if (isset($_GET['max_price'])) {
                $link = add_query_arg('max_price', wc_clean($_GET['max_price']), $link);
            }

            // Orderby
            if (isset($_GET['orderby'])) {
                $link = add_query_arg('orderby', wc_clean($_GET['orderby']), $link);
            }

            if (isset($_GET['woocommerce_catalog_columns'])) {
                $link = add_query_arg('woocommerce_catalog_columns', wc_clean($_GET['woocommerce_catalog_columns']), $link);
            }

            if (isset($_GET['woocommerce_archive_layout'])) {
                $link = add_query_arg('woocommerce_archive_layout', wc_clean($_GET['woocommerce_archive_layout']), $link);
            }

            if (isset($_GET['wocommerce_block_style'])) {
                $link = add_query_arg('wocommerce_block_style', wc_clean($_GET['wocommerce_block_style']), $link);
            }

            /**
             * Search Arg.
             * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
             */
            if (get_search_query()) {
                $link = add_query_arg('s', rawurlencode(wp_specialchars_decode(get_search_query())), $link);
            }

            // Post Type Arg
            if (isset($_GET['post_type'])) {
                $link = add_query_arg('post_type', wc_clean($_GET['post_type']), $link);
            }

            // Min Rating Arg
            if (isset($_GET['min_rating'])) {
                $link = add_query_arg('min_rating', wc_clean($_GET['min_rating']), $link);
            }

            // All current filters
            if ($_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes()) {
                foreach ($_chosen_attributes as $name => $data) {
                    if ($name === $taxonomy) {
                        continue;
                    }
                    $filter_name = sanitize_title(str_replace('pa_', '', $name));
                    if (!empty($data['terms'])) {
                        $link = add_query_arg('filter_' . $filter_name, implode(',', $data['terms']), $link);
                    }
                    if ('or' == $data['query_type']) {
                        $link = add_query_arg('query_type_' . $filter_name, 'or', $link);
                    }
                }
            }
        }

        if (is_string($link)) {
            return $link;
        } else {
            return '';
        }
    }
}

if (!function_exists('carafity_products_per_page_select')) {

    function carafity_products_per_page_select() {
        if ((wc_get_loop_prop('is_shortcode') || !wc_get_loop_prop('is_paginated') || !woocommerce_products_will_display())) return;

        $row          = wc_get_default_products_per_row();
        $max_col      = apply_filters('carafity_products_row_step_max', 6);
        $array_option = [];
        if ($max_col > 2) {
            for ($i = 2; $i <= $max_col; $i++) {
                $array_option[] = $row * $i;
            }
        } else {
            return;
        }

        $col = wc_get_default_product_rows_per_page();

        $products_per_page_options = apply_filters('carafity_products_per_page_options', $array_option);

        $current_variation = isset($_GET['per_page']) ? $_GET['per_page'] : $col * $row;
        ?>

        <div class="carafity-products-per-page">

            <label for="per_page" class="per-page-title"><?php esc_html_e('Show', 'carafity'); ?></label>
            <select name="per_page" id="per_page" class="per_page">
                <?php
                foreach ($products_per_page_options as $key => $value) :

                    ?>
                    <option value="<?php echo add_query_arg('per_page', $value, carafity_shop_page_link(true)); ?>" <?php echo esc_attr($current_variation == $value ? 'selected' : ''); ?>>
                        <?php echo esc_html($value); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
}

if (isset($_GET['per_page'])) {
    add_filter('loop_shop_per_page', 'carafity_loop_shop_per_page', 20);
}

function carafity_loop_shop_per_page($cols) {

    $cols = isset($_GET['per_page']) ? $_GET['per_page'] : $cols;

    return $cols;
}

if (!function_exists('carafity_active_filters')) {
    function carafity_active_filters() {
        global $wp;
        $url             = home_url(add_query_arg(array(), $wp->request));
        $link_remove_all = strtok($url, '?');
        echo '<div class="carafity-active-filters">';
        the_widget('WC_Widget_Layered_Nav_Filters');
        echo '<a class="clear-all" href="' . esc_url($link_remove_all) . '">' . esc_html__('Clear All', 'carafity') . '</a></div>';
    }
}
if (!function_exists('carafity_add_technical_specs_product_tab')) {

    function carafity_add_technical_specs_product_tab($tabs) {
        global $product;
        if ($product->get_meta('_technical_specs') !== '') {

            $tabs['additional_information'] = array(
                'title'    => esc_html__('Additional information', 'carafity'),
                'priority' => 20,
                'callback' => 'carafity_display_technical_specs_product_tab_content'

            );
        }

        return $tabs;
    }
}

add_filter('woocommerce_product_tabs', 'carafity_add_technical_specs_product_tab', 20, 1);

if (!function_exists('carafity_display_technical_specs_product_tab_content')) {
    function carafity_display_technical_specs_product_tab_content() {
        global $product;
        echo '<div class="wrapper-technical_specs">' . carafity_parse_text_editor($product->get_meta('_technical_specs')) . '</div>';
    }
}

if (!function_exists('carafity_single_product_no_tab')) {
    function carafity_single_product_no_tab() {
        $product_tabs = apply_filters('woocommerce_product_tabs', array());
        if (!empty($product_tabs)) :
            ?>
            <div class="carafity-product-no-tabs">
                <?php foreach ($product_tabs as $key => $product_tab) : ?>
                    <div id="tab-<?php echo esc_attr($key); ?>" class="section">
                        <?php printf('<h2>%s</h2>', $product_tab['title']) ?>
                        <div class="content">
                            <?php
                            if (isset($product_tab['callback'])) {
                                call_user_func($product_tab['callback'], $key, $product_tab);
                            }
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif;
    }
}