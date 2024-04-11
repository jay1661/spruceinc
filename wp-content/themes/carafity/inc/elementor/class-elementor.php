<?php

use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Carafity_Elementor')) :

    /**
     * The Carafity Elementor Integration class
     */
    class Carafity_Elementor {
        private $suffix = '';

        public function __construct() {
            $this->suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

            add_action('wp', [$this, 'register_auto_scripts_frontend']);
            add_action('elementor/elements/categories_registered', [$this, 'register_widget_category']);
            add_action('wp_enqueue_scripts', [$this, 'add_scripts'], 15);
            add_action('elementor/widgets/register', array($this, 'customs_widgets'));
            add_action('elementor/widgets/register', array($this, 'include_widgets'));
            add_action('elementor/frontend/after_enqueue_scripts', [$this, 'add_js']);

            // Custom Animation Scroll
            add_filter('elementor/controls/animations/additional_animations', [$this, 'add_animations_scroll']);

            // Elementor Fix Noitice WooCommerce
            add_action('elementor/editor/before_enqueue_scripts', array($this, 'woocommerce_fix_notice'));

            // Backend
            add_action('elementor/editor/after_enqueue_styles', [$this, 'add_style_editor'], 99);

            // Add Icon Custom
            add_action('elementor/icons_manager/native', [$this, 'add_icons_native']);
            add_action('elementor/controls/controls_registered', [$this, 'add_icons']);

            if (!carafity_is_elementor_pro_activated()) {
                require trailingslashit(get_template_directory()) . 'inc/elementor/custom-css.php';
                require trailingslashit(get_template_directory()) . 'inc/elementor/sticky-section.php';
                if (is_admin()) {
                    add_action('manage_elementor_library_posts_columns', [$this, 'admin_columns_headers']);
                    add_action('manage_elementor_library_posts_custom_column', [$this, 'admin_columns_content'], 10, 2);
                }
            }

            add_filter('elementor/fonts/additional_fonts', [$this, 'additional_fonts']);
            add_action('wp_enqueue_scripts', [$this, 'elementor_kit']);

            require get_theme_file_path('inc/elementor/modules/settings.php');
        }

        public function elementor_kit() {
            $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
            Elementor\Plugin::$instance->kits_manager->frontend_before_enqueue_styles();
            $myvals = get_post_meta($active_kit_id, '_elementor_page_settings', true);
            if (!empty($myvals)) {
                $css = '';
                foreach ($myvals['system_colors'] as $key => $value) {
                    $css .= $value['color'] !== '' ? '--' . $value['_id'] . ':' . $value['color'] . ';' : '';
                }

                $var = "body{{$css}}";
                wp_add_inline_style('carafity-style', $var);
            }
        }

        public function additional_fonts($fonts) {
            $fonts["Carafity Heading"] = 'system';
            return $fonts;
        }

        public function admin_columns_headers($defaults) {
            $defaults['shortcode'] = esc_html__('Shortcode', 'carafity');

            return $defaults;
        }

        public function admin_columns_content($column_name, $post_id) {
            if ('shortcode' === $column_name) {
                ob_start();
                ?>
                <input class="elementor-shortcode-input" type="text" readonly onfocus="this.select()" value="[hfe_template id='<?php echo esc_attr($post_id); ?>']"/>
                <?php
                ob_get_contents();
            }
        }

        public function add_js() {

            wp_enqueue_script('carafity-elementor-frontend', get_theme_file_uri('/assets/js/elementor-frontend.js'), [], SUPPRE_VERSION);
        }

        public function add_style_editor() {

            wp_enqueue_style('carafity-elementor-editor-icon', get_theme_file_uri('/assets/css/admin/elementor/icons.css'), [], SUPPRE_VERSION);
        }

        public function add_scripts() {

            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_enqueue_style('carafity-elementor', get_template_directory_uri() . '/assets/css/base/elementor.css', '', SUPPRE_VERSION);
            wp_style_add_data('carafity-elementor', 'rtl', 'replace');

            // Add Scripts

            $e_swiper_latest     = Plugin::$instance->experiments->is_feature_active('e_swiper_latest');
            $e_swiper_asset_path = $e_swiper_latest ? 'assets/lib/swiper/v8/' : 'assets/lib/swiper/';
            $e_swiper_version    = $e_swiper_latest ? '8.4.5' : '5.3.6';
            wp_register_script(
                'swiper',
                plugins_url('elementor/' . $e_swiper_asset_path . 'swiper.js', 'elementor'),
                [],
                $e_swiper_version,
                true
            );
        }

        public function register_auto_scripts_frontend() {
            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_register_script('carafity-elementor-swiper', get_theme_file_uri('/assets/js/elementor-swiper' . $suffix . '.js'), array('jquery', 'elementor-frontend'), SUPPRE_VERSION, true);
            // Register auto scripts frontend

            $files  = glob(get_theme_file_path('/assets/js/elementor/*' . $suffix . '.js'));
            foreach ($files as $file) {
                $file_name = wp_basename($file);
                $handle    = str_replace($suffix.".js", '', $file_name);
                $scr       = get_theme_file_uri('/assets/js/elementor/' . $file_name);
                if (file_exists($file)) {
                    wp_register_script('carafity-elementor-' . $handle, $scr, ['jquery', 'elementor-frontend'], SUPPRE_VERSION, true);
                }
            }
        }

        public function register_widget_category($this_cat) {
            $this_cat->add_category(
                'carafity-addons',
                [
                    'title' => esc_html__('Carafity Addons', 'carafity'),
                    'icon'  => 'fa fa-plug',
                ]
            );
            return $this_cat;
        }

        public function add_animations_scroll($animations) {
            $animations['Carafity Animation'] = [
                'opal-move-up'    => 'Move Up',
                'opal-move-down'  => 'Move Down',
                'opal-move-left'  => 'Move Left',
                'opal-move-right' => 'Move Right',
                'opal-flip'       => 'Flip',
                'opal-helix'      => 'Helix',
                'opal-scale-up'   => 'Scale',
                'opal-am-popup'   => 'Popup',
            ];

            return $animations;
        }

        public function customs_widgets() {
            $files = glob(get_theme_file_path('/inc/elementor/custom-widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }

        /**
         * @param $widgets_manager Elementor\Widgets_Manager
         */
        public function include_widgets($widgets_manager) {
            require 'base-swiper-widget.php';
            $files = glob(get_theme_file_path('/inc/elementor/widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }

        public function woocommerce_fix_notice() {
            if (carafity_is_woocommerce_activated()) {
                remove_action('woocommerce_cart_is_empty', 'woocommerce_output_all_notices', 5);
                remove_action('woocommerce_shortcode_before_product_cat_loop', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_account_content', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10);
            }
        }


        public function add_icons( $manager ) {
            $new_icons = json_decode( '{"carafity-icon-angle-down":"angle-down","carafity-icon-angle-left":"angle-left","carafity-icon-angle-right":"angle-right","carafity-icon-angle-up":"angle-up","carafity-icon-arrow-down":"arrow-down","carafity-icon-arrow-drop-down-fill":"arrow-drop-down-fill","carafity-icon-arrow-left":"arrow-left","carafity-icon-arrow-right":"arrow-right","carafity-icon-arrow-up":"arrow-up","carafity-icon-artisan-made":"artisan-made","carafity-icon-calendar":"calendar","carafity-icon-canvas-menu":"canvas-menu","carafity-icon-card-shield":"card-shield","carafity-icon-cavas-menu":"cavas-menu","carafity-icon-checked-rounded":"checked-rounded","carafity-icon-checked":"checked","carafity-icon-chevron-double-left":"chevron-double-left","carafity-icon-chevron-double-right":"chevron-double-right","carafity-icon-clock-1":"clock-1","carafity-icon-clock-2":"clock-2","carafity-icon-clock":"clock","carafity-icon-close-menu":"close-menu","carafity-icon-close":"close","carafity-icon-communication":"communication","carafity-icon-compare":"compare","carafity-icon-config":"config","carafity-icon-copy":"copy","carafity-icon-credit-card-1":"credit-card-1","carafity-icon-credit-card-lock":"credit-card-lock","carafity-icon-customer-service":"customer-service","carafity-icon-envelope-1":"envelope-1","carafity-icon-eye":"eye","carafity-icon-fast":"fast","carafity-icon-filter":"filter","carafity-icon-free-delivery":"free-delivery","carafity-icon-free-shipping":"free-shipping","carafity-icon-google-plus-g":"google-plus-g","carafity-icon-grid-2":"grid-2","carafity-icon-headphones-1":"headphones-1","carafity-icon-headphones":"headphones","carafity-icon-heart-circle":"heart-circle","carafity-icon-heart":"heart","carafity-icon-help":"help","carafity-icon-import":"import","carafity-icon-knitting-1":"knitting-1","carafity-icon-knitting":"knitting","carafity-icon-language-1":"language-1","carafity-icon-linkedin-in":"linkedin-in","carafity-icon-list-ul":"list-ul","carafity-icon-location":"location","carafity-icon-locator":"locator","carafity-icon-long-arrow-left":"long-arrow-left","carafity-icon-long-arrow-right":"long-arrow-right","carafity-icon-lowest-price":"lowest-price","carafity-icon-mail-send":"mail-send","carafity-icon-map-marker-alt":"map-marker-alt","carafity-icon-nature":"nature","carafity-icon-one-click":"one-click","carafity-icon-package-check":"package-check","carafity-icon-pen":"pen","carafity-icon-performance":"performance","carafity-icon-phone-1":"phone-1","carafity-icon-phone-2":"phone-2","carafity-icon-phone-3":"phone-3","carafity-icon-phone":"phone","carafity-icon-pin":"pin","carafity-icon-play-1":"play-1","carafity-icon-play-circle":"play-circle","carafity-icon-plus-1":"plus-1","carafity-icon-plus-2":"plus-2","carafity-icon-plus-circle":"plus-circle","carafity-icon-popular":"popular","carafity-icon-question1":"question1","carafity-icon-quote-1":"quote-1","carafity-icon-quote-2":"quote-2","carafity-icon-quote":"quote","carafity-icon-refresh":"refresh","carafity-icon-responsive-design":"responsive-design","carafity-icon-right-arrow-cicrle":"right-arrow-cicrle","carafity-icon-search2":"search2","carafity-icon-seo-1":"seo-1","carafity-icon-seo":"seo","carafity-icon-shipping":"shipping","carafity-icon-shopping-bag":"shopping-bag","carafity-icon-shopping-cart":"shopping-cart","carafity-icon-sliders-v":"sliders-v","carafity-icon-small-batch":"small-batch","carafity-icon-star-3":"star-3","carafity-icon-star2":"star2","carafity-icon-store-1":"store-1","carafity-icon-supply":"supply","carafity-icon-support-1":"support-1","carafity-icon-support-2":"support-2","carafity-icon-support":"support","carafity-icon-sustainable-1":"sustainable-1","carafity-icon-sustainable":"sustainable","carafity-icon-tag":"tag","carafity-icon-telephone":"telephone","carafity-icon-text":"text","carafity-icon-top-brand":"top-brand","carafity-icon-touch-controls":"touch-controls","carafity-icon-truck-1":"truck-1","carafity-icon-twitte-1":"twitte-1","carafity-icon-typography":"typography","carafity-icon-user":"user","carafity-icon-verification":"verification","carafity-icon-360":"360","carafity-icon-bars":"bars","carafity-icon-cart-empty":"cart-empty","carafity-icon-check-square":"check-square","carafity-icon-circle":"circle","carafity-icon-cloud-download-alt":"cloud-download-alt","carafity-icon-comment":"comment","carafity-icon-comments":"comments","carafity-icon-contact":"contact","carafity-icon-credit-card":"credit-card","carafity-icon-dot-circle":"dot-circle","carafity-icon-edit":"edit","carafity-icon-envelope":"envelope","carafity-icon-expand-alt":"expand-alt","carafity-icon-external-link-alt":"external-link-alt","carafity-icon-file-alt":"file-alt","carafity-icon-file-archive":"file-archive","carafity-icon-folder-open":"folder-open","carafity-icon-folder":"folder","carafity-icon-frown":"frown","carafity-icon-gift":"gift","carafity-icon-grid":"grid","carafity-icon-grip-horizontal":"grip-horizontal","carafity-icon-heart-fill":"heart-fill","carafity-icon-history":"history","carafity-icon-home":"home","carafity-icon-info-circle":"info-circle","carafity-icon-instagram":"instagram","carafity-icon-level-up-alt":"level-up-alt","carafity-icon-list":"list","carafity-icon-map-marker-check":"map-marker-check","carafity-icon-meh":"meh","carafity-icon-minus-circle":"minus-circle","carafity-icon-minus":"minus","carafity-icon-mobile-android-alt":"mobile-android-alt","carafity-icon-money-bill":"money-bill","carafity-icon-pencil-alt":"pencil-alt","carafity-icon-plus":"plus","carafity-icon-random":"random","carafity-icon-reply-all":"reply-all","carafity-icon-reply":"reply","carafity-icon-search-plus":"search-plus","carafity-icon-search":"search","carafity-icon-shield-check":"shield-check","carafity-icon-shopping-basket":"shopping-basket","carafity-icon-sign-out-alt":"sign-out-alt","carafity-icon-smile":"smile","carafity-icon-spinner":"spinner","carafity-icon-square":"square","carafity-icon-star":"star","carafity-icon-store":"store","carafity-icon-sync":"sync","carafity-icon-tachometer-alt":"tachometer-alt","carafity-icon-thumbtack":"thumbtack","carafity-icon-ticket":"ticket","carafity-icon-times-circle":"times-circle","carafity-icon-times-square":"times-square","carafity-icon-times":"times","carafity-icon-trophy-alt":"trophy-alt","carafity-icon-truck":"truck","carafity-icon-video":"video","carafity-icon-wishlist-empty":"wishlist-empty","carafity-icon-adobe":"adobe","carafity-icon-amazon":"amazon","carafity-icon-android":"android","carafity-icon-angular":"angular","carafity-icon-apper":"apper","carafity-icon-apple":"apple","carafity-icon-atlassian":"atlassian","carafity-icon-behance":"behance","carafity-icon-bitbucket":"bitbucket","carafity-icon-bitcoin":"bitcoin","carafity-icon-bity":"bity","carafity-icon-bluetooth":"bluetooth","carafity-icon-btc":"btc","carafity-icon-centos":"centos","carafity-icon-chrome":"chrome","carafity-icon-codepen":"codepen","carafity-icon-cpanel":"cpanel","carafity-icon-discord":"discord","carafity-icon-dochub":"dochub","carafity-icon-docker":"docker","carafity-icon-dribbble":"dribbble","carafity-icon-dropbox":"dropbox","carafity-icon-drupal":"drupal","carafity-icon-ebay":"ebay","carafity-icon-facebook-f":"facebook-f","carafity-icon-facebook":"facebook","carafity-icon-figma":"figma","carafity-icon-firefox":"firefox","carafity-icon-google-plus":"google-plus","carafity-icon-google":"google","carafity-icon-grunt":"grunt","carafity-icon-gulp":"gulp","carafity-icon-html5":"html5","carafity-icon-joomla":"joomla","carafity-icon-link-brand":"link-brand","carafity-icon-linkedin":"linkedin","carafity-icon-mailchimp":"mailchimp","carafity-icon-opencart":"opencart","carafity-icon-paypal":"paypal","carafity-icon-pinterest-p":"pinterest-p","carafity-icon-reddit":"reddit","carafity-icon-skype":"skype","carafity-icon-slack":"slack","carafity-icon-snapchat":"snapchat","carafity-icon-spotify":"spotify","carafity-icon-trello":"trello","carafity-icon-twitter":"twitter","carafity-icon-vimeo":"vimeo","carafity-icon-whatsapp":"whatsapp","carafity-icon-wordpress":"wordpress","carafity-icon-yoast":"yoast","carafity-icon-youtube":"youtube"}', true );
			$icons     = $manager->get_control( 'icon' )->get_settings( 'options' );
			$new_icons = array_merge(
				$new_icons,
				$icons
			);
			// Then we set a new list of icons as the options of the icon control
			$manager->get_control( 'icon' )->set_settings( 'options', $new_icons ); 
        }

        public function add_icons_native($tabs) {

            $tabs['opal-custom'] = [
                'name'          => 'carafity-icon',
                'label'         => esc_html__('Carafity Icon', 'carafity'),
                'prefix'        => 'carafity-icon-',
                'displayPrefix' => 'carafity-icon-',
                'labelIcon'     => 'fab fa-font-awesome-alt',
                'ver'           => SUPPRE_VERSION,
                'fetchJson'     => get_theme_file_uri('/inc/elementor/icons.json'),
                'native'        => true,
            ];

            return $tabs;
        }
    }

endif;

return new Carafity_Elementor();
