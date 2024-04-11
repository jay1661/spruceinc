<?php
// nested-tabs
use Elementor\Controls_Manager;

add_action('elementor/element/nested-tabs/section_tabs/before_section_end', function ($element, $args) {
    /** @var \Elementor\Element_Base $element */
    $element->add_control(
        'tabs_style',
        [
            'label'        => esc_html__('Style', 'carafity'),
            'type'         => Controls_Manager::SELECT,
            'default'      => '',
            'options'      => [
                ''   => esc_html__('default', 'carafity'),
                '1' => esc_html__('Style 1', 'carafity'),
            ],
            'prefix_class' => 'elementor-tabs-style',
        ]
    );
}, 10, 2);