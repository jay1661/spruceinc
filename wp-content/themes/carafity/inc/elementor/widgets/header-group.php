<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

class Carafity_Elementor_Header_Group extends Elementor\Widget_Base {

    public function get_name() {
        return 'carafity-header-group';
    }

    public function get_title() {
        return esc_html__('Carafity Header Group', 'carafity');
    }

    public function get_icon() {
        return 'eicon-lock-user';
    }

    public function get_categories() {
        return array('carafity-addons');
    }

    protected function register_controls() {

        $this->start_controls_section(
            'header_group_config',
            [
                'label' => esc_html__('Config', 'carafity'),
            ]
        );

        $this->add_control(
            'show_search',
            [
                'label' => esc_html__('Show search', 'carafity'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'show_account',
            [
                'label' => esc_html__('Show account', 'carafity'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hidden_title_account',
            [
                'label' => esc_html__('Hidden Title Account', 'carafity'),
                'type'  => Controls_Manager::SWITCHER,
                'condition' => [
                    'show_account' => 'yes',
                ],
                'selectors'      => [
                    '{{WRAPPER}} .site-header-account a span' => 'display: none;',
                ],
            ]
        );

        $this->add_control(
            'show_wishlist',
            [
                'label' => esc_html__('Show wishlist', 'carafity'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'show_cart',
            [
                'label' => esc_html__('Show cart', 'carafity'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_dropdown',
            [
                'label' => esc_html__('Hide dropdown', 'carafity'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'header_group_icon',
            [
                'label' => esc_html__('Icon', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:not(:hover) i:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:not(:hover):before'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div span'   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:hover i:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:hover:before'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:hover span'   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'     => esc_html__('Font Size', 'carafity'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a i:before' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:before'   => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'icon_padding',
            [
                'label'      => esc_html__('Padding', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .header-group-action > div a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'icon-border',
            [
                'label' => esc_html__('Icon Border', 'carafity'),
                'type'  => Controls_Manager::SWITCHER,
                'prefix_class' => 'icon-border-',
            ]
        );

        $this->add_responsive_control(
            'border_width',
            [
                'label'      => esc_html__('Border Width', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .header-group-action > div' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'icon-border' => 'yes',
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
                    '{{WRAPPER}} .header-group-action > div'   => 'border-color: {{VALUE}};',
                    'condition' => [
                        'icon-border' => 'yes',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_min-width',
            [
                'label'     => esc_html__('Min Height', 'carafity'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.icon-border-yes .header-group-action > div' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon-border' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_min-height',
            [
                'label'     => esc_html__('Min Height', 'carafity'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.icon-border-yes .header-group-action > div' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon-border' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'header_group_conut',
            [
                'label' => esc_html__('Count', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label'     => esc_html__('Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action .count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'count_background_color',
            [
                'label'     => esc_html__('Background Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action .count' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'elementor-header-group-wrapper');
        if('yes' == $settings['hide_dropdown']) {

            $this->add_render_attribute('wrapper', 'class', 'header-group-hide-dropdown');
        }
        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <div class="header-group-action">
                <?php if ($settings['show_search'] === 'yes') {
                    carafity_header_search_button();
                }
                if ($settings['show_account'] === 'yes') {
                    carafity_header_account();
                }
                if ($settings['show_wishlist'] === 'yes' && carafity_is_woocommerce_activated()) {
                    carafity_header_wishlist();
                }
                if ($settings['show_cart'] === 'yes' && carafity_is_woocommerce_activated()) {
                    carafity_header_cart();
                }
                ?>

            </div>
        </div>
        <?php
    }
}

$widgets_manager->register(new Carafity_Elementor_Header_Group());
