<?php
// Icon List
use Elementor\Controls_Manager;
add_action( 'elementor/element/icon-list/section_icon/before_section_end', function ( $element, $args ) {
    $element->add_control(
        'icon_style_theme',
        [
            'label' => esc_html__('Theme Style', 'carafity'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'prefix_class' => 'icon-list-style-carafity-',
        ]
    );
    $element->add_control(
        'icon_style_color',
        [
            'label'     => esc_html__('Color Line Hover', 'carafity'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => [
                '{{WRAPPER}} .elementor-icon-list-item a span:before' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'icon_style_theme' => 'yes',
            ],
        ]
    );

}, 10, 2 );
