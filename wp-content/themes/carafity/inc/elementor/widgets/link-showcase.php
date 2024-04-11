<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\Schemes;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Carafity_Elementor_Link_Showcase extends Widget_Base {

    public function get_categories() {
        return array('carafity-addons');
    }

    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'carafity-link-showcase';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Carafity Link Showcase', 'carafity');
    }

    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-tabs';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @return array Widget keywords.
     * @since 2.1.0
     * @access public
     *
     */
    public function get_keywords() {
        return ['tabs', 'accordion', 'toggle', 'link', 'showcase'];
    }

    public function get_script_depends() {
        return ['carafity-elementor-link-showcase'];
    }

    /**
     * Register tabs widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__('Items', 'carafity'),
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'title',
            [
                'label'       => esc_html__('Title', 'carafity'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Title', 'carafity'),
                'placeholder' => esc_html__('Title', 'carafity'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label'   => esc_html__('Choose Image', 'carafity'),
                'type'    => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ]
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'       => esc_html__('Link to', 'carafity'),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://your-link.com', 'carafity'),
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => esc_html__('Items', 'carafity'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'title'    => esc_html__('Title #1', 'carafity'),
                        'subtitle' => esc_html__('Subtitle #1', 'carafity'),
                        'link'     => esc_html__('#', 'carafity'),
                    ],
                    [
                        'title'    => esc_html__('Title #2', 'carafity'),
                        'subtitle' => esc_html__('Subtitle #2', 'carafity'),
                        'link'     => esc_html__('#', 'carafity'),
                    ],
                    [
                        'title'    => esc_html__('Title #3', 'carafity'),
                        'subtitle' => esc_html__('Subtitle #3', 'carafity'),
                        'link'     => esc_html__('#', 'carafity'),
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'image',
                'default'   => 'full',
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_content_style',
            [
                'label' => esc_html__('Content', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'min-height',
            [
                'label'      => esc_html__('Height', 'carafity'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'vh'],
                'selectors'  => [
                    '{{WRAPPER}} .link-showcase-item' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'title_position',
            [
                'label' => esc_html__('Position', 'carafity'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'carafity'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'carafity'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'carafity'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .link-showcase-title-wrapper' => 'justify-content: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );


        $this->add_responsive_control(
            'title_width', [
            'label' => esc_html__( 'Width', 'carafity' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                '%' => [
                    'min' => 10,
                    'max' => 50,
                ],
                'px' => [
                    'min' => 20,
                    'max' => 600,
                ],
            ],
            'default' => [
                'unit' => '%',
            ],
            'size_units' => [ '%', 'px' ],
            'selectors' => [
                '{{WRAPPER}} .link-showcase-title-wrapper' => 'flex-basis: {{SIZE}}{{UNIT}}',
            ],
        ] );


        $this->add_responsive_control( 'title_space_between', [
            'label' => esc_html__( 'Gap between title', 'carafity' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}} .link-showcase-title-inner' => 'display: flex; flex-direction: column; gap: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_responsive_control(
            'title_spacing', [
            'label' => esc_html__( 'Distance from content', 'carafity' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}} .elementor-link-showcase-inner' => 'gap: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'border',
                'selector'  => '{{WRAPPER}} .link-showcase-title-wrapper',
                'separator' => 'before',
            ]
        );


        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Text Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,

                'selectors' => [
                    '{{WRAPPER}} .elementor-link-showcase-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} .elementor-link-showcase-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name'     => 'text_stroke',
                'selector' => '{{WRAPPER}} .elementor-link-showcase-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'text_shadow',
                'selector' => '{{WRAPPER}} .elementor-link-showcase-title',
            ]
        );

        $this->add_control(
            'blend_mode',
            [
                'label'     => esc_html__('Blend Mode', 'carafity'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    ''            => esc_html__('Normal', 'carafity'),
                    'multiply'    => 'Multiply',
                    'screen'      => 'Screen',
                    'overlay'     => 'Overlay',
                    'darken'      => 'Darken',
                    'lighten'     => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation'  => 'Saturation',
                    'color'       => 'Color',
                    'difference'  => 'Difference',
                    'exclusion'   => 'Exclusion',
                    'hue'         => 'Hue',
                    'luminosity'  => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-link-showcase-title' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );


        $this->add_responsive_control(
            'text_padding',
            [
                'label'      => esc_html__('Padding', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .link-showcase-title-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_image_style',
            [
                'label' => esc_html__('Image', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label'      => esc_html__('Padding', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .link-showcase-contnet-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render tabs widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['items']) && is_array($settings['items'])) {
            $items = $settings['items'];
            // Row
            $this->add_render_attribute('wrapper', 'class', 'elementor-link-showcase-wrapper');
            $this->add_render_attribute('row', 'class', 'elementor-link-showcase-inner');
            $this->add_render_attribute('row', 'role', 'tablist');
            $id_int = substr($this->get_id_int(), 0, 3);
            ?>
            <div <?php $this->print_render_attribute_string('wrapper'); ?>>
                <div <?php $this->print_render_attribute_string('row'); ?>>
                    <div class="link-showcase-item link-showcase-title-wrapper">
                        <div class="link-showcase-title-inner">
                            <?php foreach ($items as $index => $item) :
                                $count = $index + 1;
                                $item_title_setting_key = $this->get_repeater_setting_key('item_title', 'items', $index);
                                $this->add_render_attribute($item_title_setting_key, [
                                    'id'            => 'elementor-link-showcase-title-' . $id_int . $count,
                                    'class'         => [
                                        'elementor-link-showcase-title',
                                        ($index == 0) ? 'elementor-active' : '',
                                        'elementor-repeater-item-' . $item['_id']
                                    ],
                                    'data-tab'      => $count,
                                    'role'          => 'tab',
                                    'aria-controls' => 'elementor-link-showcase-content-' . $id_int . $count,
                                ]);

                                $title = $item['title'];
                                if (!empty($item['link']['url'])) {
                                    $title = '<a href="' . esc_url($item['link']['url']) . '">' . $title . '</a>';
                                }
                                ?>
                                <div <?php $this->print_render_attribute_string($item_title_setting_key); ?>>
                                    <?php echo wp_kses_post($title); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="link-showcase-item link-showcase-contnet-wrapper">
                        <div class="link-showcase-contnet-inner">
                            <?php foreach ($items as $index => $item) :
                                $count = $index + 1;
                                $item_content_setting_key = $this->get_repeater_setting_key('item_content', 'items', $index);
                                $this->add_render_attribute($item_content_setting_key, [
                                    'id'            => 'elementor-link-showcase-content-' . $id_int . $count,
                                    'class'         => [
                                        'elementor-link-showcase-content',
                                        'elementor-repeater-item-' . $item['_id'],
                                        ($index == 0) ? 'elementor-active' : '',
                                    ],
                                    'data-tab'      => $count,
                                    'role'          => 'tab',
                                    'aria-controls' => 'elementor-link-showcase-title-' . $id_int . $count,
                                ]);
                                ?>
                                <div <?php $this->print_render_attribute_string($item_content_setting_key); ?>>
                                    <?php $this->render_image($settings, $item); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    private function render_image($settings, $item) {
        if (!empty($item['image']['url'])) :
            ?>
            <?php
            $item['image_size']             = $settings['image_size'];
            $item['image_custom_dimension'] = $settings['image_custom_dimension'];
            echo Group_Control_Image_Size::get_attachment_image_html($item, 'image');
            ?>
        <?php
        endif;
    }
}

$widgets_manager->register(new Carafity_Elementor_Link_Showcase());
