<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('Carafity_Customize')) {

    class Carafity_Customize {


        public function __construct() {
            add_action('customize_register', array($this, 'customize_register'));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         */
        public function customize_register($wp_customize) {

            /**
             * Theme options.
             */
            require_once get_theme_file_path('inc/customize-control/editor.php');
            $this->init_carafity_blog($wp_customize);

            $this->init_carafity_social($wp_customize);

            if (carafity_is_woocommerce_activated()) {
                $this->init_woocommerce($wp_customize);
            }

            do_action('carafity_customize_register', $wp_customize);
        }


        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_carafity_blog($wp_customize) {

            $wp_customize->add_section('carafity_blog_archive', array(
                'title' => esc_html__('Blog', 'carafity'),
            ));

            // =========================================
            // Select Style
            // =========================================

            $wp_customize->add_setting('carafity_options_blog_style', array(
                'type'              => 'option',
                'default'           => 'standard',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('carafity_options_blog_style', array(
                'section' => 'carafity_blog_archive',
                'label'   => esc_html__('Blog style', 'carafity'),
                'type'    => 'select',
                'choices' => array(
                    'standard' => esc_html__('Blog Standard', 'carafity'),
                    //====start_premium
                    'style-1'  => esc_html__('Blog Grid', 'carafity'),
                    'modern'  => esc_html__('Blog Modern', 'carafity'),
                    //====end_premium
                ),
            ));

            $wp_customize->add_setting('carafity_options_blog_columns', array(
                'type'              => 'option',
                'default'           => 1,
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('carafity_options_blog_columns', array(
                'section' => 'carafity_blog_archive',
                'label'   => esc_html__('Colunms', 'carafity'),
                'type'    => 'select',
                'choices' => array(
                    1 => esc_html__('1', 'carafity'),
                    2 => esc_html__('2', 'carafity'),
                    3 => esc_html__('3', 'carafity'),
                    4 => esc_html__('4', 'carafity'),
                ),
            ));

            $wp_customize->add_setting('carafity_options_blog_archive_sidebar', array(
                'type'              => 'option',
                'default'           => 'right',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('carafity_options_blog_archive_sidebar', array(
                'section' => 'carafity_blog_archive',
                'label'   => esc_html__('Sidebar Position', 'carafity'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'carafity'),
                    'right' => esc_html__('Right', 'carafity'),
                ),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_carafity_social($wp_customize) {

            $wp_customize->add_section('carafity_social', array(
                'title' => esc_html__('Socials', 'carafity'),
            ));
            $wp_customize->add_setting('carafity_options_social_share', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('carafity_options_social_share', array(
                'type'    => 'checkbox',
                'section' => 'carafity_social',
                'label'   => esc_html__('Show Social Share', 'carafity'),
            ));
            $wp_customize->add_setting('carafity_options_social_share_facebook', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('carafity_options_social_share_facebook', array(
                'type'    => 'checkbox',
                'section' => 'carafity_social',
                'label'   => esc_html__('Share on Facebook', 'carafity'),
            ));
            $wp_customize->add_setting('carafity_options_social_share_twitter', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('carafity_options_social_share_twitter', array(
                'type'    => 'checkbox',
                'section' => 'carafity_social',
                'label'   => esc_html__('Share on Twitter', 'carafity'),
            ));
            $wp_customize->add_setting('carafity_options_social_share_linkedin', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('carafity_options_social_share_linkedin', array(
                'type'    => 'checkbox',
                'section' => 'carafity_social',
                'label'   => esc_html__('Share on Linkedin', 'carafity'),
            ));
            $wp_customize->add_setting('carafity_options_social_share_google-plus', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('carafity_options_social_share_google-plus', array(
                'type'    => 'checkbox',
                'section' => 'carafity_social',
                'label'   => esc_html__('Share on Google+', 'carafity'),
            ));

            $wp_customize->add_setting('carafity_options_social_share_pinterest', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('carafity_options_social_share_pinterest', array(
                'type'    => 'checkbox',
                'section' => 'carafity_social',
                'label'   => esc_html__('Share on Pinterest', 'carafity'),
            ));
            $wp_customize->add_setting('carafity_options_social_share_email', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('carafity_options_social_share_email', array(
                'type'    => 'checkbox',
                'section' => 'carafity_social',
                'label'   => esc_html__('Share on Email', 'carafity'),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_woocommerce($wp_customize) {

            $wp_customize->add_panel('woocommerce', array(
                'title' => esc_html__('Woocommerce', 'carafity'),
            ));

            $wp_customize->add_section('carafity_woocommerce_archive', array(
                'title'      => esc_html__('Archive', 'carafity'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
                'priority'   => 1,
            ));

            $wp_customize->add_setting('carafity_options_woocommerce_archive_layout', array(
                'type'              => 'option',
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('carafity_options_woocommerce_archive_layout', array(
                'section' => 'carafity_woocommerce_archive',
                'label'   => esc_html__('Layout Style', 'carafity'),
                'type'    => 'select',
                'choices' => array(
                    'default'  => esc_html__('Sidebar', 'carafity'),
                    //====start_premium
                    'canvas'   => esc_html__('Canvas Filter', 'carafity'),
                    'menu'     => esc_html__('Menu Filter', 'carafity'),
                    'dropdown' => esc_html__('Dropdown Filter', 'carafity'),
                    //====end_premium
                ),
            ));

            $wp_customize->add_setting('carafity_options_woocommerce_archive_sidebar', array(
                'type'              => 'option',
                'default'           => 'left',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('carafity_options_woocommerce_archive_sidebar', array(
                'section' => 'carafity_woocommerce_archive',
                'label'   => esc_html__('Sidebar Position', 'carafity'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'carafity'),
                    'right' => esc_html__('Right', 'carafity'),

                ),
            ));

            $wp_customize->add_setting('carafity_options_woocommerce_shop_pagination', array(
                'type'              => 'option',
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('carafity_options_woocommerce_shop_pagination', array(
                'section' => 'carafity_woocommerce_archive',
                'label'   => esc_html__('Products pagination', 'carafity'),
                'type'    => 'select',
                'choices' => array(
                    'default'  => esc_html__('Pagination', 'carafity'),
                    'more-btn' => esc_html__('Load More', 'carafity'),
                    'infinit'  => esc_html__('Infinit Scroll', 'carafity'),
                ),
            ));

            // =========================================
            // Single Product
            // =========================================

            $wp_customize->add_section('carafity_woocommerce_single', array(
                'title'      => esc_html__('Single Product', 'carafity'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('carafity_options_single_product_gallery_layout', array(
                'type'              => 'option',
                'default'           => 'horizontal',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('carafity_options_single_product_gallery_layout', array(
                'section' => 'carafity_woocommerce_single',
                'label'   => esc_html__('Style', 'carafity'),
                'type'    => 'select',
                'choices' => array(
                    'horizontal'   => esc_html__('Horizontal', 'carafity'),
                    'vertical'   => esc_html__('Vertical', 'carafity'),
                    'gallery'   => esc_html__('Gallery', 'carafity'),
                    'sticky'    => esc_html__('Sticky', 'carafity'),
                ),
            ));

            $wp_customize->add_setting('carafity_options_single_product_content_meta', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'carafity_sanitize_editor',
            ));

            $wp_customize->add_control(new Carafity_Customize_Control_Editor($wp_customize, 'carafity_options_single_product_content_meta', array(
                'section' => 'carafity_woocommerce_single',
                'label'   => esc_html__('Single extra description', 'carafity'),
            )));


            // =========================================
            // Product
            // =========================================


            $wp_customize->add_section('carafity_woocommerce_product', array(
                'title'      => esc_html__('Product Block', 'carafity'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));
            $attribute_array      = [
                '' => esc_html__('None', 'carafity')
            ];
            $attribute_taxonomies = wc_get_attribute_taxonomies();

            if (!empty($attribute_taxonomies)) {
                foreach ($attribute_taxonomies as $tax) {
                    if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) {
                        $attribute_array[$tax->attribute_name] = $tax->attribute_label;
                    }
                }
            }

            $wp_customize->add_setting('carafity_options_wocommerce_attribute', array(
                'type'              => 'option',
                'default'           => '',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('carafity_options_wocommerce_attribute', array(
                'section' => 'carafity_woocommerce_product',
                'label'   => esc_html__('Attributes Show', 'carafity'),
                'type'    => 'select',
                'choices' => $attribute_array,
            ));

            $wp_customize->add_setting('carafity_options_wocommerce_block_style', array(
                'type'              => 'option',
                'default'           => '',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('carafity_options_wocommerce_block_style', array(
                'section' => 'carafity_woocommerce_product',
                'label'   => esc_html__('Style', 'carafity'),
                'type'    => 'select',
                'choices' => array(
                    '' => esc_html__('Style 1', 'carafity'),
                ),
            ));

            $wp_customize->add_setting('carafity_options_woocommerce_product_hover', array(
                'type'              => 'option',
                'default'           => 'none',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('carafity_options_woocommerce_product_hover', array(
                'section' => 'carafity_woocommerce_product',
                'label'   => esc_html__('Animation Image Hover', 'carafity'),
                'type'    => 'select',
                'choices' => array(
                    'none'          => esc_html__('None', 'carafity'),
                    'bottom-to-top' => esc_html__('Bottom to Top', 'carafity'),
                    'top-to-bottom' => esc_html__('Top to Bottom', 'carafity'),
                    'right-to-left' => esc_html__('Right to Left', 'carafity'),
                    'left-to-right' => esc_html__('Left to Right', 'carafity'),
                    'swap'          => esc_html__('Swap', 'carafity'),
                    'fade'          => esc_html__('Fade', 'carafity'),
                    'zoom-in'       => esc_html__('Zoom In', 'carafity'),
                    'zoom-out'      => esc_html__('Zoom Out', 'carafity'),
                ),
            ));
        }
    }
}
return new Carafity_Customize();
