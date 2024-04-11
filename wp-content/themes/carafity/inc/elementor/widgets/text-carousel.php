<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class Carafity_Elementor_TextCarousel extends Carafity_Base_Widgets_Swiper {
    /**
     * Get widget name.
     *
     * Retrieve textcarousel widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'carafity-text-carousel';
    }

    /**
     * Get widget title.
     *
     * Retrieve textcarousel widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Carafity TextCarousel', 'carafity');
    }

    /**
     * Get widget icon.
     *
     * Retrieve textcarousel widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-carousel';
    }

    public function get_script_depends() {
        return ['carafity-elementor-text-carousel', 'carafity-elementor-swiper'];
    }

    public function get_categories() {
        return array('carafity-addons');
    }

    /**
     * Register textcarousel widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_textcarousel',
            [
                'label' => esc_html__('TextCarousel', 'carafity'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'textcarousel_title',
            [
                'label'   => esc_html__('Content', 'carafity'),
                'type'    => Controls_Manager::TEXTAREA,
            ]
        );

        $repeater->add_control(
            'textcarousel_link',
            [
                'label'       => esc_html__('Link to', 'carafity'),
                'placeholder' => esc_html__('https://your-link.com', 'carafity'),
                'type'        => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'textcarousel',
            [
                'label'       => esc_html__('Items', 'carafity'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ textcarousel_title }}}',
            ]
        );


        $this->add_responsive_control(
            'column',
            [
                'label'        => esc_html__('Columns', 'carafity'),
                'type'         => \Elementor\Controls_Manager::SELECT,
                'default'      => 1,
                'options'      => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
                'prefix_class' => 'textcarousel-column-',
            ]
        );

        $this->add_responsive_control(
            'textcarousel_alignment',
            [
                'label'       => esc_html__('Alignment', 'carafity'),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    'left'   => [
                        'title' => esc_html__('Left', 'carafity'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'carafity'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'carafity'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'label_block' => false,
                'default'      => 'center',
                'selectors'   => [
                    '{{WRAPPER}} .elementor-textcarousel-item' => 'text-align: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'enable_carousel',
            [
                'label' => esc_html__('Enable Carousel', 'carafity'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'view',
            [
                'label'   => esc_html__('View', 'carafity'),
                'type'    => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );
        $this->end_controls_section();

        // Title.
        $this->start_controls_section(
            'section_style_textcarousel_title',
            [
                'label' => esc_html__('Title', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_text_color',
            [
                'label'     => esc_html__('Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .textcarousel-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_text_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .textcarousel-title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .textcarousel-title',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'size_units' => ['px', 'em', '%'],
                'label'      => esc_html__('Spacing', 'carafity'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .textcarousel-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Carousel options
        $this->add_control_carousel(['enable_carousel' => 'yes']);

    }

    /**
     * Render textcarousel widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['textcarousel']) && is_array($settings['textcarousel'])) {
            $this->add_render_attribute('wrapper', 'class', 'elementor-textcarousel-item-wrapper');
            // Row
            $this->add_render_attribute('row', 'class', 'row');
            $this->add_render_attribute('row', 'class', 'alignment-' . esc_attr($settings['textcarousel_alignment']));
            // Carousel
            $this->get_data_elementor_columns();
            // Item
            $this->add_render_attribute('item', 'class', 'column-item elementor-textcarousel-item');
            ?>
            <div <?php $this->print_render_attribute_string('wrapper'); // WPCS: XSS ok. ?>>

                <div <?php $this->print_render_attribute_string('row'); // WPCS: XSS ok. ?>>
                    <?php foreach ($settings['textcarousel'] as $textcarousel): ?>
                        <div <?php $this->print_render_attribute_string('item'); // WPCS: XSS ok. ?>>
                            <div class="inner">
                                <?php if ($textcarousel['textcarousel_title']) { ?>
                                    <div class="textcarousel-title"><?php echo esc_html($textcarousel['textcarousel_title']); ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php $this->render_swiper_pagination_navigation();?>
            <?php
        }
    }
}

$widgets_manager->register(new Carafity_Elementor_TextCarousel());

