<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

abstract class Carafity_Base_Widgets_Swiper extends Widget_Base {

    protected function add_control_carousel($condition = []) {
        $this->start_controls_section(
            'section_carousel_options',
            [
                'label'     => esc_html__('Carousel Options', 'carafity'),
                'type'      => Controls_Manager::SECTION,
                'condition' => $condition
            ]
        );

        $slides_to_show = range(1, 10);
        $slides_to_show = array_combine($slides_to_show, $slides_to_show);

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label'              => esc_html__('Slides to Show', 'carafity'),
                'type'               => Controls_Manager::TEXT,
                'frontend_available' => true,
                'default'            => 3,
                'render_type'        => 'template',
                'selectors'          => [
                    '{{WRAPPER}} .swiper' . $this->swiper_class() . ':not(.swiper-initialized) .swiper-slide' => 'flex: 0 0 calc(100% / {{VALUE}}); width: calc(100% / {{VALUE}}); margin-right:{{spaceBetween.SIZE}}{{spaceBetween.UNIT}}',
                ],
                'condition'          => [
                    'direction' => ''
                ]
            ]
        );

        $this->add_responsive_control(
            'slides_to_scroll',
            [
                'label'              => esc_html__('Slides to Scroll', 'carafity'),
                'type'               => Controls_Manager::SELECT,
                'description'        => esc_html__('Set how many slides are scrolled per swipe.', 'carafity'),
                'options'            => [
                                            '' => esc_html__('Default', 'carafity'),
                                        ] + $slides_to_show,
                'frontend_available' => true,
                'condition'          => [
                    'slides_to_show!' => '1',
                    'direction'       => ''
                ],
            ]
        );

        $this->add_responsive_control(
            'spaceBetween',
            [
                'label'              => esc_html__('Space Between', 'carafity'),
                'type'               => Controls_Manager::SLIDER,
                'range'              => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'default'            => [
                    'size' => 30
                ],
                'size_units'         => ['px'],
                'render_type'        => 'template',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'swiper_overflow',
            [
                'label'              => esc_html__('Overflow', 'carafity'),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'none',
                'options'            => [
                    'none'  => esc_html__('None', 'carafity'),
                    'left'  => esc_html__('Overflow to the left', 'carafity'),
                    'right' => esc_html__('Overflow to the right', 'carafity'),
                ],
                'frontend_available' => true,
                'prefix_class'       => 'overflow-to-',
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label'              => esc_html__('Navigation', 'carafity'),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'none',
                'options'            => [
                    'both'   => esc_html__('Arrows and Dots', 'carafity'),
                    'arrows' => esc_html__('Arrows', 'carafity'),
                    'dots'   => esc_html__('Dots', 'carafity'),
                    'none'   => esc_html__('None', 'carafity'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'lazyload',
            [
                'label'              => esc_html__('Lazyload', 'carafity'),
                'type'               => Controls_Manager::SWITCHER,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'mousewheel',
            [
                'label'              => esc_html__('Mousewheel control', 'carafity'),
                'type'               => Controls_Manager::SWITCHER,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoheight',
            [
                'label'              => esc_html__('Auto Height', 'carafity'),
                'type'               => Controls_Manager::SWITCHER,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'              => esc_html__('Autoplay', 'carafity'),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'yes',
                'options'            => [
                    'yes' => esc_html__('Yes', 'carafity'),
                    'no'  => esc_html__('No', 'carafity'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label'              => esc_html__('Pause on Hover', 'carafity'),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'yes',
                'options'            => [
                    'yes' => esc_html__('Yes', 'carafity'),
                    'no'  => esc_html__('No', 'carafity'),
                ],
                'condition'          => [
                    'autoplay' => 'yes',
                ],
                'render_type'        => 'none',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'pause_on_interaction',
            [
                'label'              => esc_html__('Pause on Interaction', 'carafity'),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'yes',
                'options'            => [
                    'yes' => esc_html__('Yes', 'carafity'),
                    'no'  => esc_html__('No', 'carafity'),
                ],
                'condition'          => [
                    'autoplay' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label'              => esc_html__('Autoplay Speed', 'carafity'),
                'type'               => Controls_Manager::NUMBER,
                'default'            => 5000,
                'condition'          => [
                    'autoplay' => 'yes',
                ],
                'render_type'        => 'none',
                'frontend_available' => true,
            ]
        );

        // Loop requires a re-render so no 'render_type = none'
        $this->add_control(
            'infinite',
            [
                'label'              => esc_html__('Infinite Loop', 'carafity'),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'yes',
                'options'            => [
                    'yes' => esc_html__('Yes', 'carafity'),
                    'no'  => esc_html__('No', 'carafity'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'effect',
            [
                'label'              => esc_html__('Effect', 'carafity'),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'slide',
                'options'            => [
                    'slide' => esc_html__('Slide', 'carafity'),
                    'fade'  => esc_html__('Fade', 'carafity'),
                ],
                'condition'          => [
                    'slides_to_show' => '1',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'speed',
            [
                'label'              => esc_html__('Animation Speed', 'carafity'),
                'type'               => Controls_Manager::NUMBER,
                'default'            => 500,
                'render_type'        => 'none',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'direction',
            [
                'label'              => esc_html__('Direction', 'carafity'),
                'type'               => Controls_Manager::SELECT,
                'default'            => '',
                'frontend_available' => true,
                'options'            => [
                    ''         => esc_html__('Horizontal', 'carafity'),
                    'vertical' => esc_html__('Vertical', 'carafity'),
                ],
            ]
        );

        $this->add_control(
            'rtl',
            [
                'label'   => esc_html__('Direction Right/Left', 'carafity'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'ltr',
                'options' => [
                    'ltr' => esc_html__('Left', 'carafity'),
                    'rtl' => esc_html__('Right', 'carafity'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->register_controls_navigation();
        $this->register_controls_dots();
    }

    protected function register_controls_dots() {
        $this->start_controls_section(
            'carousel_dots',
            [
                'label'      => esc_html__('Carousel Dots', 'carafity'),
                'conditions' => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'enable_carousel',
                            'operator' => '==',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => 'navigation',
                            'operator' => '!==',
                            'value'    => 'none',
                        ],
                        [
                            'name'     => 'navigation',
                            'operator' => '!==',
                            'value'    => 'arrows',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'dots_position',
            [
                'label'        => esc_html__('Position', 'carafity'),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'outside',
                'options'      => [
                    'outside' => esc_html__('Outside', 'carafity'),
                    'inside'  => esc_html__('Inside', 'carafity'),
                ],
                'prefix_class' => 'elementor-pagination-position-',
                'condition'    => [
                    'navigation' => ['dots', 'both'],
                ],
            ]
        );

        $this->add_control(
            'dots_size',
            [
                'label'     => esc_html__('Size', 'carafity'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 5,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation' => ['dots', 'both'],
                ],
            ]
        );

        $this->start_controls_tabs('tabs_carousel_dots_style');

        $this->start_controls_tab(
            'tab_carousel_dots_normal',
            [
                'label' => esc_html__('Normal', 'carafity'),
            ]
        );

        $this->add_control(
            'carousel_dots_color',
            [
                'label'     => esc_html__('Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'carousel_dots_opacity',
            [
                'label'     => esc_html__('Opacity', 'carafity'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max'  => 1,
                        'min'  => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_carousel_dots_hover',
            [
                'label' => esc_html__('Hover', 'carafity'),
            ]
        );

        $this->add_control(
            'carousel_dots_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .swiper-pagination-bullet:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'carousel_dots_opacity_hover',
            [
                'label'     => esc_html__('Opacity', 'carafity'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max'  => 1,
                        'min'  => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet:hover' => 'opacity: {{SIZE}};',
                    '{{WRAPPER}} .swiper-pagination-bullet:focus' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_carousel_dots_activate',
            [
                'label' => esc_html__('Activate', 'carafity'),
            ]
        );

        $this->add_control(
            'carousel_dots_color_activate',
            [
                'label'     => esc_html__('Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'carousel_dots_opacity_activate',
            [
                'label'     => esc_html__('Opacity', 'carafity'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max'  => 1,
                        'min'  => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'dots_vertical_value',
            [
                'label'      => esc_html__('Spacing vertical', 'carafity'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'dots_horizontal_value',
            [
                'label'      => esc_html__('Spacing horizontal', 'carafity'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-pagination-vertical' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'direction' => 'vertical'
                ]
            ]
        );


        $this->end_controls_section();
    }

    protected function register_controls_navigation() {
        $this->start_controls_section(
            'section_style_navigation',
            [
                'label'      => esc_html__('Carousel Navigation', 'carafity'),
                'conditions' => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'enable_carousel',
                            'operator' => '==',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => 'navigation',
                            'operator' => '!==',
                            'value'    => 'none',
                        ],
                        [
                            'name'     => 'navigation',
                            'operator' => '!==',
                            'value'    => 'dots',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'heading_style_arrows',
            [
                'label'     => esc_html__('Arrows', 'carafity'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_size',
            [
                'label'     => esc_html__('Size', 'carafity'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 20,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_width',
            [
                'label'      => esc_html__('Width', 'carafity'),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'unit' => 'px',
                ],
                'size_units' => ['%', 'px', 'vw'],
                'range'      => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_height',
            [
                'label'      => esc_html__('Height', 'carafity'),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'unit' => 'px',
                ],
                'size_units' => ['%', 'px', 'vw'],
                'range'      => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(

            Group_Control_Border::get_type(),
            [
                'name'      => 'arrows_border',
                'selector'  => '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'arrows_radius',
            [
                'label'      => esc_html__('Border Radius', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('arrows_tabs');

        $this->start_controls_tab('arrows_normal',
            [
                'label' => esc_html__('Normal', 'carafity'),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'arrows_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next',
            ]
        );

        $this->add_control(
            'arrows_color',
            [
                'label'     => esc_html__('Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next'         => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev svg, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->add_control(
            'arrows_background_color',
            [
                'label'     => esc_html__('Background Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('arrows_hover',
            [
                'label' => esc_html__('Hover', 'carafity'),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'arrows_box_shadow_hover',
                'selector' => '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next:hover',
            ]
        );

        $this->add_control(
            'arrows_color_hover',
            [
                'label'     => esc_html__('Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next:hover'         => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev:hover svg, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next:hover svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->add_control(
            'arrows_background_color_hover',
            [
                'label'     => esc_html__('Background Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->add_control(
            'arrows_border_color_hover',
            [
                'label'     => esc_html__('Border Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'arrows_next_heading',
            [
                'label'     => esc_html__('Next button', 'carafity'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->add_control(
            'arrows_next_vertical',
            [
                'label'        => esc_html__('Next Vertical', 'carafity'),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'options'      => [
                    'top'    => [
                        'title' => esc_html__('Top', 'carafity'),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'carafity'),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'prefix_class' => 'elementor-swiper-button-next-vertical-',
                'condition'    => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_next_vertical_value',
            [
                'type'       => Controls_Manager::SLIDER,
                'show_label' => false,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'top: unset; bottom: unset; {{arrows_next_vertical.value}}: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'navigation'           => ['arrows', 'both'],
                    'arrows_next_vertical' => ['top', 'bottom'],
                ],
            ]
        );

        $this->add_control(
            'arrows_next_horizontal',
            [
                'label'        => esc_html__('Next Horizontal', 'carafity'),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'options'      => [
                    'left'  => [
                        'title' => esc_html__('Left', 'carafity'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'carafity'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'elementor-swiper-button-next-horizontal-',
                'condition'    => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );
        $this->add_responsive_control(
            'next_horizontal_value',
            [
                'type'       => Controls_Manager::SLIDER,
                'show_label' => false,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => -45,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'left: unset; right: unset;{{arrows_next_horizontal.value}}: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'navigation'             => ['arrows', 'both'],
                    'arrows_next_horizontal' => ['left', 'right'],
                ],

            ]
        );

        $this->add_control(
            'arrows_prev_heading',
            [
                'label'     => esc_html__('Prev button', 'carafity'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->add_control(
            'arrows_prev_vertical',
            [
                'label'        => esc_html__('Prev Vertical', 'carafity'),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'render_type'  => 'ui',
                'options'      => [
                    'top'    => [
                        'title' => esc_html__('Top', 'carafity'),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'carafity'),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'prefix_class' => 'elementor-swiper-button-prev-vertical-',
                'condition'    => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_prev_vertical_value',
            [
                'type'       => Controls_Manager::SLIDER,
                'show_label' => false,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev' => 'top: unset; bottom: unset; {{arrows_prev_vertical.value}}: {{SIZE}}{{UNIT}};',
                ],

                'condition' => [
                    'navigation'           => ['arrows', 'both'],
                    'arrows_prev_vertical' => ['top', 'bottom'],
                ],
            ]
        );

        $this->add_control(
            'arrows_prev_horizontal',
            [
                'label'        => esc_html__('Prev Horizontal', 'carafity'),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'options'      => [
                    'left'  => [
                        'title' => esc_html__('Left', 'carafity'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'carafity'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'elementor-swiper-button-prev-horizontal-',
                'condition'    => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );
        $this->add_responsive_control(
            'arrows_prev_horizontal_value',
            [
                'type'       => Controls_Manager::SLIDER,
                'show_label' => false,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev' => 'left: unset; right: unset; {{arrows_prev_horizontal.value}}: {{SIZE}}{{UNIT}};',
                ],

                'condition' => [
                    'navigation'             => ['arrows', 'both'],
                    'arrows_prev_horizontal' => ['left', 'right'],
                ],
            ]
        );

        $this->end_controls_section();
    }


    public function render_swiper_pagination_navigation() {
        $settings        = $this->get_settings_for_display();
        $enable_carousel = $settings['enable_carousel'] === 'yes';
        $show_dots       = (in_array($settings['navigation'], ['dots', 'both']));
        $show_arrows     = (in_array($settings['navigation'], ['arrows', 'both']));

        if ($show_dots && $enable_carousel) : ?>
            <div class="swiper-pagination"></div>
        <?php endif; ?>
        <?php if ($show_arrows && $enable_carousel) {
            ?>
            <div class="elementor-swiper-button elementor-swiper-button-prev">
                <i class="carafity-icon-angle-left" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?php echo esc_html__('Previous', 'carafity'); ?></span>
            </div>
            <div class="elementor-swiper-button elementor-swiper-button-next">
                <i class="carafity-icon-angle-right" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?php echo esc_html__('Next', 'carafity'); ?></span>
            </div>
        <?php };
    }

    public function swiper_class() {
        return Elementor\Plugin::$instance->experiments->is_feature_active('e_swiper_latest') ? '' : '-container';
    }

    protected function get_data_elementor_columns() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('row', 'class', 'd-grid');
        $this->add_render_attribute('item', 'class', 'grid-item');
        $enable_carousel = $settings['enable_carousel'] === 'yes';
        $class           = $settings['slides_to_show'] == 'auto' ? 'swiper-autowidth' : '';
        if ($enable_carousel) {
            $this->add_render_attribute([
                'item'    => [
                    'class' => 'swiper-slide',
                ],
                'wrapper' => [
                    'class' => 'carafity-swiper swiper' . $this->swiper_class() . ' ' . $class,
                    'dir'   => $settings['rtl'] ? $settings['rtl'] : 'ltr',
                ],
                'row'     => [
                    'class' => 'swiper-wrapper',
                ],
            ]);
            $this->remove_render_attribute('row', 'class', 'd-grid');
        }
    }
}