<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
class Carafity_Elementor_Search extends Elementor\Widget_Base {
    public function get_name() {
        return 'carafity-search';
    }

    public function get_title() {
        return esc_html__('Carafity Search Form', 'carafity');
    }

    public function get_icon() {
        return 'eicon-site-search';
    }

    public function get_categories() {
        return array('carafity-addons');
    }

    protected function register_controls() {
        $this->start_controls_section(
            'search-form-style',
            [
                'label' => esc_html__('Style', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'layout_style',
            [
                'label'   => esc_html__('Layout Style', 'carafity'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'layout-1' => esc_html__('Layout 1', 'carafity'),
                    'layout-2' => esc_html__('Layout 2', 'carafity'),
                ],
                'default' => 'layout-1',
            ]
        );

        $this->add_control(
            'style_layout_1',
            [
                'label'        => esc_html__('Style', 'carafity'),
                'type'         => Controls_Manager::SELECT,
                'options'      => [
                    'style-1' => esc_html__('Style 1', 'carafity'),
                    'style-2' => esc_html__('Style 2', 'carafity'),
                ],
                'default'      => 'style-1',
                'prefix_class' => 'search-carafity-',
                'condition'    => [
                    'layout_style' => 'layout-1'
                ]
            ]
        );

        $this->add_responsive_control(
            'border_width',
            [
                'label'      => esc_html__('Border width', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} form input[type=search]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label'     => esc_html__('Border Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} form input[type=search]' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_color_focus',
            [
                'label'     => esc_html__('Border Color Focus', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} form input[type=search]:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_form',
            [
                'label'     => esc_html__('Background Form', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} form input[type=search]' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_button',
            [
                'label'     => esc_html__('Background Button', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} form button[type=submit]' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_iconbutton',
            [
                'label'     => esc_html__('Color Icon Button', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .widget form button[type=submit] i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_form',
            [
                'label'     => esc_html__('Color Icon', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .widget_product_search form::before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'search_typography',
                'selector' => '{{WRAPPER}} .content',
                'condition'    => [
                    'layout_style' => 'layout-2'
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'     => esc_html__('Icon size', 'carafity'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .site-header-search > a i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition'    => [
                    'layout_style' => 'layout-2'
                ]
            ]
        );
        $this->add_responsive_control(
            'icon_spacing',
            [
                'label'     => esc_html__('Spacing', 'carafity'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .carafity-icon-search' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'    => [
                    'layout_style' => 'layout-2'
                ]
            ]
        );
        $this->add_control(
            'border_radius_input',
            [
                'label'      => esc_html__('Border Radius Input', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .widget_product_search form input[type=search]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ($settings['layout_style'] === 'layout-1') {
            if (carafity_is_woocommerce_activated()) {
                carafity_product_search();
            } else {
                ?>
                <div class="site-search widget_search">
                    <?php get_search_form(); ?>
                </div>
                <?php
            }

        }

        if ($settings['layout_style'] === 'layout-2') {
            add_action('wp_footer', 'carafity_header_search_popup', 1);
            ?>
            <div class="site-header-search">
                <a href="#" class="button-search-popup">
                    <i class="carafity-icon-search"></i>
                    <span class="content"><?php echo esc_html__('Search', 'carafity'); ?></span>
                </a>
            </div>
            <?php
        }

    }
}

$widgets_manager->register(new Carafity_Elementor_Search());
