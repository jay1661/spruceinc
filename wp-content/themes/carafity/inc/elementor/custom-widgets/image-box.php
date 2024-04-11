<?php
// Image Box
use Elementor\Controls_Manager;
add_action( 'elementor/element/image-box/section_image/before_section_end', function ( $element, $args ) {
    $element->add_control(
        'image-box_style_theme',
        [
            'label' => esc_html__('Theme Style', 'carafity'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'prefix_class' => 'image-box-style-carafity-',
        ]
    );
    $element->add_control(
        'image_style_color',
        [
            'label'     => esc_html__('Color Border', 'carafity'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => [
                '{{WRAPPER}} .elementor-image-box-img:before' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'image-box_style_theme' => 'yes',
            ],
        ]
    );

}, 10, 2 );
