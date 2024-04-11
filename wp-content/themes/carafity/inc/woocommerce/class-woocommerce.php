<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Carafity_WooCommerce')) :

    /**
     * The Carafity WooCommerce Integration class
     */
    class Carafity_WooCommerce {

        public $list_shortcodes;

        /**
         * Setup class.
         *
         * @since 1.0
         */
        public function __construct() {
            $this->list_shortcodes = array(
                'recent_products',
                'sale_products',
                'best_selling_products',
                'top_rated_products',
                'featured_products',
                'related_products',
                'product_category',
                'products',
            );
            $this->init_shortcodes();

            add_action('after_setup_theme', array($this, 'setup'));
            add_filter('body_class', array($this, 'woocommerce_body_class'));
            add_action('widgets_init', array($this, 'widgets_init'));
            add_filter('carafity_theme_sidebar', array($this, 'set_sidebar'), 20);
            add_action('wp_enqueue_scripts', array($this, 'woocommerce_scripts'), 20);
            add_filter('woocommerce_enqueue_styles', '__return_empty_array');
            add_filter('woocommerce_output_related_products_args', array($this, 'related_products_args'));
            add_filter('woocommerce_upsell_display_args', array($this, 'upsell_products_args'));

            if (defined('WC_VERSION') && version_compare(WC_VERSION, '3.3', '<')) {
                add_filter('loop_shop_per_page', array($this, 'products_per_page'));
            }

            // Remove Shop Title
            add_filter('woocommerce_show_page_title', '__return_false');

            add_filter('carafity_register_nav_menus', [$this, 'add_location_menu']);
            add_filter('wp_nav_menu_items', [$this, 'add_extra_item_to_nav_menu'], 10, 2);

            add_filter('woocommerce_single_product_image_gallery_classes', function ($wrapper_classes) {
                $product_image_single_style = carafity_get_theme_option('single_product_gallery_layout', 'gallery');
                $wrapper_classes[]          = 'woocommerce-product-gallery-' . $product_image_single_style;

                return $wrapper_classes;
            });

            add_action('woocommerce_grouped_product_list_before_label', array(
                $this,
                'grouped_product_column_image'
            ), 10, 1);

            // Elementor Admin
            add_action('admin_action_elementor', array($this, 'register_elementor_wc_hook'), 1);
            add_filter('woocommerce_cross_sells_columns', array($this, 'woocommerce_cross_sells_columns'));

        }

        public function woocommerce_cross_sells_columns() {
            return wc_get_default_products_per_row();
        }

        public function register_elementor_wc_hook() {
            wc()->frontend_includes();
            require_once get_theme_file_path('inc/woocommerce/woocommerce-template-hooks.php');
            require_once get_theme_file_path('inc/woocommerce/template-hooks.php');
            carafity_include_hooks_product_blocks();
        }

        public function add_extra_item_to_nav_menu($items, $args) {
            if ($args->theme_location == 'my-account') {
                $items .= '<li><a href="' . esc_url(wp_logout_url(home_url())) . '">' . esc_html__('Logout', 'carafity') . '</a></li>';
            }

            return $items;
        }

        public function add_location_menu($locations) {
            $locations['my-account'] = esc_html__('My Account', 'carafity');

            return $locations;
        }

        /**
         * Sets up theme defaults and registers support for various WooCommerce features.
         *
         * Note that this function is hooked into the after_setup_theme hook, which
         * runs before the init hook. The init hook is too late for some features, such
         * as indicating support for post thumbnails.
         *
         * @return void
         * @since 2.4.0
         */
        public function setup() {
            add_theme_support(
                'woocommerce', apply_filters(
                    'carafity_woocommerce_args', array(
                        'product_grid' => array(
                            'default_columns' => 3,
                            'default_rows'    => 4,
                            'min_columns'     => 1,
                            'max_columns'     => 6,
                            'min_rows'        => 1,
                        ),
                    )
                )
            );


            /**
             * Add 'carafity_woocommerce_setup' action.
             *
             * @since  2.4.0
             */
            do_action('carafity_woocommerce_setup');
        }


        public function action_woocommerce_before_template_part($template_name, $template_path, $located, $args) {
            $product_style = carafity_get_theme_option('wocommerce_block_style', 0);
            if ($product_style != 0 && ($template_name == 'single-product/up-sells.php' || $template_name == 'single-product/related.php' || $template_name == 'cart/cross-sells.php')) {
                $template_custom = 'content-product-' . $product_style . '.php';
                add_filter('wc_get_template_part', function ($template, $slug, $name) use ($template_custom) {
                    if ($slug == 'content' && $name == 'product') {
                        return get_theme_file_path('woocommerce/' . $template_custom);
                    } else {
                        return $template;
                    }
                }, 10, 3);
            }
        }

        public function action_woocommerce_after_template_part($template_name, $template_path, $located, $args) {
            $product_style = carafity_get_theme_option('wocommerce_block_style', 0);
            if ($product_style != 0 && ($template_name == 'single-product/up-sells.php' || $template_name == 'single-product/related.php' || $template_name == 'cart/cross-sells.php')) {
                add_filter('wc_get_template_part', function ($template, $slug, $name) {
                    if ($slug == 'content' && $name == 'product') {
                        return get_theme_file_path('woocommerce/content-product.php');
                    } else {
                        return $template;
                    }
                }, 10, 3);
            }
        }

        private function init_shortcodes() {
            foreach ($this->list_shortcodes as $shortcode) {
                add_filter('shortcode_atts_' . $shortcode, array($this, 'set_shortcode_attributes'), 10, 3);

                add_action('woocommerce_shortcode_before_' . $shortcode . '_loop', array(
                    $this,
                    'shortcode_loop_start'
                ));
                add_action('woocommerce_shortcode_after_' . $shortcode . '_loop', array(
                    $this,
                    'shortcode_loop_end'
                ));
            }
        }

        public function shortcode_loop_end($atts = array()) {

            if (isset($atts['style'])) {
                if ($atts['style'] !== '') {

                    add_filter('wc_get_template_part', function ($template, $slug, $name) {
                        if ($slug == 'content' && $name == 'product') {
                            return get_theme_file_path('woocommerce/content-product.php');
                        } else {
                            return $template;
                        }
                    }, 10, 3);
                }
            }
        }

        public function shortcode_loop_start($atts = array()) {

            if (isset($atts['style'])) {
                if ($atts['style'] !== '') {
                    $template_custom = 'content-product-' . $atts['style'] . '.php';

                    add_filter('wc_get_template_part', function ($template, $slug, $name) use ($template_custom) {
                        if ($slug == 'content' && $name == 'product') {
                            return get_theme_file_path('woocommerce/' . $template_custom);
                        } else {
                            return $template;
                        }
                    }, 10, 3);
                }
            }

            if (isset($atts['product_layout']) && $atts['product_layout'] === 'carousel') {
                wc_set_loop_prop('product-carousel', 'swiper-wrapper');
            }
        }

        public function set_shortcode_attributes($out, $pairs, $atts) {
            $out = wp_parse_args($atts, $out);

            return $out;
        }


        /**
         * Assign styles to individual theme mod.
         *
         * @return void
         * @since 2.1.0
         * @deprecated 2.3.1
         */
        public function set_carafity_style_theme_mods() {
            if (function_exists('wc_deprecated_function')) {
                wc_deprecated_function(__FUNCTION__, '2.3.1');
            } else {
                _deprecated_function(__FUNCTION__, '2.3.1');
            }
        }

        /**
         * Add WooCommerce specific classes to the body tag
         *
         * @param array $classes css classes applied to the body tag.
         *
         * @return array $classes modified to include 'woocommerce-active' class
         */
        public function woocommerce_body_class($classes) {
            $classes[] = 'woocommerce-active';

            // Remove `no-wc-breadcrumb` body class.
            $key = array_search('no-wc-breadcrumb', $classes, true);

            if (false !== $key) {
                unset($classes[$key]);
            }

            $style   = carafity_get_theme_option('wocommerce_block_style', 1);
            $layout  = carafity_get_theme_option('woocommerce_archive_layout', 'default');
            $sidebar = carafity_get_theme_option('woocommerce_archive_sidebar', 'left');

            $classes[] = 'product-block-style-' . $style;

            if (carafity_is_product_archive()) {
                $classes[] = 'carafity-archive-product';

                if (is_active_sidebar('sidebar-woocommerce-shop')) {

                    if ($layout == 'default') {
                        $classes[] = 'carafity-sidebar-'.$sidebar;
                    }else{
                        $classes[] = 'carafity-full-width-content shop_filter_'.$layout;
                    }
                } else {
                    $classes[] = 'carafity-full-width-content';
                }

            }

            if (is_product()) {
                $classes[] = 'carafity-full-width-content';
                $classes[] = 'single-product-' . carafity_get_theme_option('single_product_gallery_layout', 'gallery');
            }
            return $classes;
        }

        /**
         * WooCommerce specific scripts & stylesheets
         *
         * @since 1.0.0
         */
        public function woocommerce_scripts() {


            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_enqueue_style('carafity-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce/woocommerce.css', array(), SUPPRE_VERSION);
            wp_style_add_data('carafity-woocommerce-style', 'rtl', 'replace');

            if (defined('WC_VERSION') && version_compare(WC_VERSION, '3.3', '<')) {
                wp_enqueue_style('carafity-woocommerce-legacy', get_template_directory_uri() . '/assets/css/woocommerce/woocommerce-legacy.css', array(), SUPPRE_VERSION);
                wp_style_add_data('carafity-woocommerce-legacy', 'rtl', 'replace');
            }

            if (is_shop() || is_product() || is_product_taxonomy()) {
                wp_enqueue_script('tooltipster');
                wp_enqueue_style('tooltipster');
                wp_enqueue_script('carafity-shop-select', get_template_directory_uri() . '/assets/js/woocommerce/shop-select' . $suffix . '.js', array('jquery'), SUPPRE_VERSION, true);
                wp_enqueue_script('carafity-shop', get_template_directory_uri() . '/assets/js/woocommerce/shop' . $suffix . '.js', array(
                    'jquery', 'carafity-waypoints'
                ), SUPPRE_VERSION, true);
            }
            if (carafity_elementor_check_type('carafity-products')) {
                wp_enqueue_script('tooltipster');
                wp_enqueue_style('tooltipster');
            }

            wp_enqueue_script('carafity-products-ajax-search', get_template_directory_uri() . '/assets/js/woocommerce/product-ajax-search' . $suffix . '.js', array(
                'jquery'
            ), SUPPRE_VERSION, true);
            wp_enqueue_script('carafity-products', get_template_directory_uri() . '/assets/js/woocommerce/main' . $suffix . '.js', array(
                'jquery',
            ), SUPPRE_VERSION, true);

            wp_enqueue_script('carafity-input-quantity', get_template_directory_uri() . '/assets/js/woocommerce/quantity' . $suffix . '.js', array('jquery'), SUPPRE_VERSION, true);

            if (is_active_sidebar('sidebar-woocommerce-shop')) {
                wp_enqueue_script('carafity-off-canvas', get_template_directory_uri() . '/assets/js/woocommerce/off-canvas' . $suffix . '.js', array(), SUPPRE_VERSION, true);
            }
            wp_enqueue_script('carafity-cart-canvas', get_template_directory_uri() . '/assets/js/woocommerce/cart-canvas' . $suffix . '.js', array(), SUPPRE_VERSION, true);

            wp_register_script('carafity-single-product-video', get_template_directory_uri() . '/assets/js/woocommerce/single-video360' . $suffix . '.js', array(
                'jquery',
                'sticky-kit'
            ), SUPPRE_VERSION, true);

            if (is_product()) {

                wp_register_script('carafity-countdown-single', get_template_directory_uri() . '/assets/js/woocommerce/single-countdown' . $suffix . '.js', array('jquery'), SUPPRE_VERSION, true);

                wp_enqueue_script('carafity-sticky-add-to-cart', get_template_directory_uri() . '/assets/js/sticky-add-to-cart' . $suffix . '.js', array(), SUPPRE_VERSION, true);

                wp_enqueue_script('sticky-kit');

                if (carafity_is_elementor_activated()) {
                    wp_enqueue_script('swiper');
                }

                wp_enqueue_script('carafity-single-product', get_template_directory_uri() . '/assets/js/woocommerce/single' . $suffix . '.js', array(
                    'jquery',
                    'sticky-kit'
                ), SUPPRE_VERSION, true);

            }

            if (is_cart()) {
                if (carafity_is_elementor_activated()) {
                    wp_enqueue_script('swiper');
                }
                wp_enqueue_script('carafity-product-page-cart', get_template_directory_uri() . '/assets/js/woocommerce/page-cart' . $suffix . '.js', array(
                    'jquery',
                    'swiper'
                ), SUPPRE_VERSION, true);
            }

        }

        /**
         * Related Products Args
         *
         * @param array $args related products args.
         *
         * @return  array $args related products args
         * @since 1.0.0
         */
        public function related_products_args($args) {
            $product_items = 4;
            $args          = apply_filters(
                'carafity_related_products_args', array(
                    'posts_per_page' => $product_items,
                    'columns'        => $product_items,
                )
            );

            return $args;
        }


        public function upsell_products_args($args) {
            $args['columns'] = apply_filters('carafity_upsell_products_column', 4);
            return $args;
        }

        /**
         * Products per page
         *
         * @return integer number of products
         * @since  1.0.0
         */
        public function products_per_page() {
            return intval(apply_filters('carafity_products_per_page', 12));
        }

        /**
         * Query WooCommerce Extension Activation.
         *
         * @param string $extension Extension class name.
         *
         * @return boolean
         */
        public function is_woocommerce_extension_activated($extension = 'WC_Bookings') {
            return class_exists($extension) ? true : false;
        }

        public function widgets_init() {
            register_sidebar(array(
                'name'          => esc_html__('WooCommerce Shop', 'carafity'),
                'id'            => 'sidebar-woocommerce-shop',
                'description'   => esc_html__('Add widgets here to appear in your sidebar on blog posts and archive pages.', 'carafity'),
                'before_widget' => '<div id="%1$s" class="widget %2$s carafity-widget-woocommerce">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="gamma widget-title">',
                'after_title'   => '</h2>',
            ));
        }

        public function set_sidebar($name) {
            $layout = carafity_get_theme_option('woocommerce_archive_layout', 'default');
            if (carafity_is_product_archive()) {
                if (is_active_sidebar('sidebar-woocommerce-shop') && $layout == 'default') {
                    $name = 'sidebar-woocommerce-shop';
                } else {
                    $name = '';
                }
            }
            if (is_product()) {
                $name = '';
            }
            return $name;
        }

        public function grouped_product_column_image($grouped_product_child) {
            echo '<td class="woocommerce-grouped-product-image">' . $grouped_product_child->get_image('medium') . '</td>';
        }

    }

endif;

return new Carafity_WooCommerce();
