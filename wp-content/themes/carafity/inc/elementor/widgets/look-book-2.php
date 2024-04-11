<?php

namespace Elementor;

use WP_Query;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Carafity_Elementor_Lookbook_2 extends Widget_Base {

    public function get_name() {
        return 'carafity-lookbook-2';
    }

    public function get_title() {
        return esc_html__('Carafity Look Book', 'carafity');
    }

    public function get_icon() {
        return 'eicon-image-hotspot';
    }

    public function get_script_depends() {
        return ['carafity-elementor-look-book-2', 'tooltipster'];
    }

    public function get_categories() {
        return array('carafity-addons');
    }

    protected function register_controls() {


        $this->start_controls_section('image_lookbook_image_section',
            [
                'label' => esc_html__('Image', 'carafity'),
            ]
        );

        $this->add_control('image_lookbook_image',
            [
                'label'       => __('Choose Image', 'carafity'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'label_block' => true
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'    => 'background_image', // Actually its `image_size`.
                'default' => 'full'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('image_lookbook_icons_settings',
            [
                'label' => esc_html__('Hotspots', 'carafity'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_responsive_control('lookbook_main_icons_horizontal_position',
            [
                'label'      => esc_html__('Horizontal Position', 'carafity'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'default'    => [
                    'size' => 50,
                    'unit' => '%'
                ],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $repeater->add_responsive_control('lookbook_main_icons_vertical_position',
            [
                'label'      => esc_html__('Vertical Position', 'carafity'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'default'    => [
                    'size' => 50,
                    'unit' => '%'
                ],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        if (carafity_is_woocommerce_activated()) {
            $repeater->add_control('image_lookbook_content',
                [
                    'label'   => esc_html__('Content to Show', 'carafity'),
                    'type'    => Controls_Manager::SELECT,
                    'options' => [
                        'text_editor'       => esc_html__('Text Editor', 'carafity'),
                        'elementor_product' => esc_html__('Product', 'carafity'),
                    ],
                    'default' => 'text_editor'
                ]
            );
        } else {
            $repeater->add_control('image_lookbook_content',
                [
                    'label'   => esc_html__('Content to Show', 'carafity'),
                    'type'    => Controls_Manager::SELECT,
                    'options' => [
                        'text_editor' => esc_html__('Text Editor', 'carafity'),
                    ],
                    'default' => 'text_editor'
                ]
            );
        }

        $repeater->add_control('image_lookbook_tooltips_texts',
            [
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => 'Lorem ipsum',
                'dynamic'     => ['active' => true],
                'label_block' => true,
                'condition'   => [
                    'image_lookbook_content' => 'text_editor'
                ]
            ]);

        if (carafity_is_woocommerce_activated()) {
            $repeater->add_control('image_lookbook_tooltips_product',
                [
                    'label'       => esc_html__('Products name', 'carafity'),
                    'type'        => 'products',
                    'multiple'    => false,
                    'label_block' => true,
                    'condition'   => [
                        'image_lookbook_content' => 'elementor_product'
                    ],
                ]
            );
        }

        $this->add_control('image_lookbook_icons',
            [
                'label'  => esc_html__('Hotspots', 'carafity'),
                'type'   => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('image_lookbook_tooltips_section',
            [
                'label' => esc_html__('Tooltips', 'carafity'),
            ]
        );

        $this->add_control(
            'image_lookbook_trigger_type',
            [
                'label'   => esc_html__('Trigger', 'carafity'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'click' => esc_html__('Click', 'carafity'),
                    'hover' => esc_html__('Hover', 'carafity'),
                ],
                'default' => 'hover'
            ]
        );

        $this->add_control(
            'image_lookbook_arrow',
            [
                'label'     => esc_html__('Show Arrow', 'carafity'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__('Show', 'carafity'),
                'label_off' => esc_html__('Hide', 'carafity'),
            ]
        );

        $this->add_control(
            'image_lookbook_tooltips_position',
            [
                'label'       => esc_html__('Positon', 'carafity'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => [
                    'top'    => esc_html__('Top', 'carafity'),
                    'bottom' => esc_html__('Bottom', 'carafity'),
                    'left'   => esc_html__('Left', 'carafity'),
                    'right'  => esc_html__('Right', 'carafity'),
                ],
                'description' => esc_html__('Sets the side of the tooltip. The value may one of the following: \'top\', \'bottom\', \'left\', \'right\'. It may also be an array containing one or more of these values. When using an array, the order of values is taken into account as order of fallbacks and the absence of a side disables it', 'carafity'),
                'default'     => ['top', 'bottom'],
                'label_block' => true,
                'multiple'    => true
            ]
        );

        $this->add_control('image_lookbook_tooltips_distance_position',
            [
                'label'   => esc_html__('Spacing', 'carafity'),
                'type'    => Controls_Manager::NUMBER,
                'title'   => esc_html__('The distance between the origin and the tooltip in pixels, default is 6', 'carafity'),
                'default' => 6,
            ]
        );

        $this->add_control('image_lookbook_min_width',
            [
                'label'       => esc_html__('Min Width', 'carafity'),
                'type'        => Controls_Manager::SLIDER,
                'range'       => [
                    'px' => [
                        'min' => 0,
                        'max' => 800,
                    ],
                ],
                'description' => esc_html__('Set a minimum width for the tooltip in pixels, default: 0 (auto width)', 'carafity'),
            ]
        );

        $this->add_control('image_lookbook_max_width',
            [
                'label'       => esc_html__('Max Width', 'carafity'),
                'type'        => Controls_Manager::SLIDER,
                'range'       => [
                    'px' => [
                        'min' => 0,
                        'max' => 800,
                    ],
                ],
                'description' => esc_html__('Set a maximum width for the tooltip in pixels, default: null (no max width)', 'carafity'),
            ]
        );

        $this->add_control('image_lookbook_anim',
            [
                'label'       => esc_html__('Animation', 'carafity'),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                    'fade'  => esc_html__('Fade', 'carafity'),
                    'grow'  => esc_html__('Grow', 'carafity'),
                    'swing' => esc_html__('Swing', 'carafity'),
                    'slide' => esc_html__('Slide', 'carafity'),
                    'fall'  => esc_html__('Fall', 'carafity'),
                ],
                'default'     => 'fade',
                'label_block' => true,
            ]
        );

        $this->add_control('image_lookbook_anim_dur',
            [
                'label'   => esc_html__('Animation Duration', 'carafity'),
                'type'    => Controls_Manager::NUMBER,
                'title'   => esc_html__('Set the animation duration in milliseconds, default is 350', 'carafity'),
                'default' => 350,
            ]
        );

        $this->add_control('image_lookbook_delay',
            [
                'label'   => esc_html__('Delay', 'carafity'),
                'type'    => Controls_Manager::NUMBER,
                'title'   => esc_html__('Set the animation delay in milliseconds, default is 10', 'carafity'),
                'default' => 10,
            ]
        );

        $this->add_control('image_lookbook_hide',
            [
                'label'        => esc_html__('Hide on Mobiles', 'carafity'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => 'Show',
                'label_off'    => 'Hide',
                'description'  => esc_html__('Hide tooltips on mobile phones', 'carafity'),
                'return_value' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('image_lookbook_image_style_settings',
            [
                'label' => esc_html__('Main Image', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'lookbook_image_min_height',
            [
                'label'      => esc_html__('Min Height', 'carafity'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 100,
                        'max' => 2000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'vh'],
                'selectors'  => [
                    '{{WRAPPER}} .carafity-image-lookbook-container .carafity-addons-image-lookbook-ib-img' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'lookbook_image_max_height',
            [
                'label'      => esc_html__('Max Height', 'carafity'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 100,
                        'max' => 2000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'vh'],
                'selectors'  => [
                    '{{WRAPPER}} .carafity-image-lookbook-container .carafity-addons-image-lookbook-ib-img' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control('image_lookbook_image_padding',
            [
                'label'      => esc_html__('Padding', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .carafity-image-lookbook-container .carafity-addons-image-lookbook-ib-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('image_lookbook_hotspots_style_settings',
            [
                'label' => esc_html__('Hotspots', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hotspots_color',
            [
                'label'     => esc_html__('Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .lookbook-dot:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hotspots_shadow',
            [
                'label'     => esc_html__('Shadow Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .lookbook-dot:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    protected function render() {
        // get our input from the widget settings.
        $settings  = $this->get_settings_for_display();
        $image_src = $settings['image_lookbook_image'];

        $image_src_size = Group_Control_Image_Size::get_attachment_image_src($image_src['id'], 'background_image', $settings);
        if (empty($image_src_size)) {
            $image_src_size = $image_src['url'];
        }

        $image_lookbook_settings = [
            'anim'        => $settings['image_lookbook_anim'],
            'animDur'     => !empty($settings['image_lookbook_anim_dur']) ? $settings['image_lookbook_anim_dur'] : 350,
            'delay'       => !empty($settings['image_lookbook_anim_delay']) ? $settings['image_lookbook_anim_delay'] : 10,
            'arrow'       => ($settings['image_lookbook_arrow'] == 'yes') ? true : false,
            'distance'    => !empty($settings['image_lookbook_tooltips_distance_position']) ? $settings['image_lookbook_tooltips_distance_position'] : 6,
            'minWidth'    => !empty($settings['image_lookbook_min_width']['size']) ? $settings['image_lookbook_min_width']['size'] : 0,
            'maxWidth'    => !empty($settings['image_lookbook_max_width']['size']) ? $settings['image_lookbook_max_width']['size'] : 'null',
            'side'        => !empty($settings['image_lookbook_tooltips_position']) ? $settings['image_lookbook_tooltips_position'] : array(
                'right',
                'left'
            ),
            'hideMobiles' => ($settings['image_lookbook_hide'] == true) ? true : false,
            'trigger'     => $settings['image_lookbook_trigger_type'],
            'id'          => $this->get_id()
        ];
        ?>
        <div id="carafity-image-lookbook-<?php echo esc_attr($this->get_id()); ?>" class="carafity-image-lookbook-container" data-settings='<?php echo wp_json_encode($image_lookbook_settings); ?>'>
            <img class="carafity-addons-image-lookbook-ib-img" alt="Background" src="<?php echo esc_url($image_src_size); ?>">
            <?php foreach ($settings['image_lookbook_icons'] as $index => $item) {
                $list_item_key = 'img_hotspot_' . $index;
                $this->add_render_attribute($list_item_key, 'class',
                    [
                        'elementor-repeater-item-' . $item['_id'],
                        'tooltip-wrapper',
                    ]);
                ?>
                <div <?php $this->print_render_attribute_string($list_item_key); ?> data-tooltip-content="#tooltip_content">
                    <div class="lookbook-dot"></div>
                    <div class="carafity-image-lookbook-tooltips-wrapper">
                        <div id="tooltip_content"><?php
                            if (($item['image_lookbook_content'] == 'elementor_product') && carafity_is_woocommerce_activated()) {
                                $this->render_product($item['image_lookbook_tooltips_product']);
                            } else {
                                ?>
                                <div class="carafity-image-lookbook-tooltips-text">
                                    <?php $this->print_text_editor($item['image_lookbook_tooltips_texts']); ?>
                                </div>
                                <?php
                            } ?></div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php
    }

    public function render_product($product_id) {
        $args      = array(
            'post_type'      => 'product',
            'posts_per_page' => 1,
            'post__in'       => array($product_id)
        );
        $the_query = new WP_Query($args);
        if ($the_query->have_posts()) :
            ?>
            <ul class="products tooltipster-product">
                <?php
                while ($the_query->have_posts()) : $the_query->the_post();
                    wc_get_template_part('content-product', 'list-1');
                endwhile;
                ?>
            </ul>
        <?php
        endif;
        wp_reset_postdata();
    }
}

$widgets_manager->register(new Carafity_Elementor_Lookbook_2());