<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;

class Carafity_Elementor_Team_Box extends Carafity_Base_Widgets_Swiper {

    /**
     * Get widget name.
     *
     * Retrieve teambox widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'carafity-team-box';
    }

    /**
     * Get widget title.
     *
     * Retrieve teambox widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Team Box', 'carafity');
    }

    /**
     * Get widget icon.
     *
     * Retrieve teambox widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-person';
    }

    public function get_script_depends() {
        return ['carafity-elementor-team-box', 'carafity-elementor-swiper'];
    }

    public function get_categories() {
        return array('carafity-addons');
    }

    /**
     * Register teambox widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_team',
            [
                'label' => esc_html__('Team', 'carafity'),
            ]
        );
        $repeater = new Repeater();


        $repeater->add_control(
            'teambox_image',
            [
                'label'      => esc_html__('Choose Image', 'carafity'),
                'default'    => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
            ]
        );

        $repeater->add_control(
            'job',
            [
                'label'   => esc_html__('Job', 'carafity'),
                'default' => 'Designer',
                'type'    => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'name',
            [
                'label'   => esc_html__('Name', 'carafity'),
                'default' => 'John Doe',
                'type'    => Controls_Manager::TEXT,
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label'       => esc_html__('Link to', 'carafity'),
                'placeholder' => esc_html__('https://your-link.com', 'carafity'),
                'type'        => Controls_Manager::URL,
            ]
        );


        $repeater->add_control(
            'facebook',
            [
                'label'       => esc_html__('Facebook', 'carafity'),
                'placeholder' => esc_html__('https://www.facebook.com/opalwordpress', 'carafity'),
                'default'     => 'https://www.facebook.com/opalwordpress',
                'type'        => Controls_Manager::TEXT,
            ]
        );
        $repeater->add_control(
            'twitter',
            [
                'label'       => esc_html__('Twitter', 'carafity'),
                'placeholder' => esc_html__('https://twitter.com/opalwordpress', 'carafity'),
                'default'     => 'https://twitter.com/opalwordpress',
                'type'        => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'instagram',
            [
                'label'       => esc_html__('Instagram', 'carafity'),
                'placeholder' => esc_html__('https://www.instagram.com/user/WPOpalTheme', 'carafity'),
                'default'     => 'https://www.instagram.com/user/WPOpalTheme',
                'type'        => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'pinterest',
            [
                'label'       => esc_html__('Pinterest', 'carafity'),
                'placeholder' => esc_html__('https://plus.pinterest.com/u/0/+WPOpal', 'carafity'),
                'default'     => 'https://plus.pinterest.com/u/0/+WPOpal',
                'type'        => Controls_Manager::TEXT,
            ]
        );
        $this->add_control(
            'teambox',
            [
                'label'       => esc_html__('Items', 'carafity'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'      => 'teambox_image',
                'default'   => 'full',
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'     => esc_html__('Columns', 'carafity'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 1,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 6 => 6],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'teambox_gutter',
            [
                'label'      => esc_html__('Gutter', 'carafity'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .d-grid' => 'grid-gap:{{SIZE}}{{UNIT}}',
                ],
                'condition'  => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_control(
            'teambox_layout',
            [
                'label'   => esc_html__('Layout', 'carafity'),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('Layout 1', 'carafity'),
                ]
            ]
        );

        $this->add_control(
            'enable_carousel',
            [
                'label' => esc_html__('Enable Carousel', 'carafity'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'teambox_style_image',
            [
                'label' => esc_html__('Image', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label'      => esc_html__('Margin', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .team-top' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_padding',
            [
                'label'      => esc_html__('Padding', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .team-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .team-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'teambox_style_content',
            [
                'label' => esc_html__('Content', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_title',
            [
                'label'     => esc_html__('Name', 'carafity'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'name_bottom_space',
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
                    '{{WRAPPER}} .team-content .team-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label'     => esc_html__('Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-name' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'name_typography',
                'selector' => '{{WRAPPER}} .team-name',

            ]
        );

        $this->add_control(
            'heading_job',
            [
                'label'     => esc_html__('Job', 'carafity'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'job_bottom_space',
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
                    '{{WRAPPER}} .layout-1 .team-job' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .layout-2 .team-job' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'job_color',
            [
                'label'     => esc_html__('Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-job' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'job_typography',
                'selector' => '{{WRAPPER}} .team-job',

            ]
        );


        $this->add_control(
            'heading_social',
            [
                'label'     => esc_html__('Social', 'carafity'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'social_color',
            [
                'label'     => esc_html__('Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-icon-socials ul a' => 'color: {{VALUE}};',
                ],

            ]
        );
        $this->add_control(
            'social_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-icon-socials ul a:hover' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'social_typography',
                'selector' => '{{WRAPPER}} .team-icon-socials ul a',

            ]
        );


        $this->end_controls_section();

        $this->add_control_carousel(['enable_carousel' => 'yes']);

    }

    /**
     * Render teambox widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings['teambox'])) {
            return;
        }

        $this->add_render_attribute('wrapper', 'class', 'elementor-teambox-item-wrapper');
        $this->add_render_attribute('row', 'class', 'layout-' . esc_attr($settings['teambox_layout']));
        $this->get_data_elementor_columns();
        // Item
        $this->add_render_attribute('item', 'class', 'elementor-teambox-item');
        $this->add_render_attribute('details', 'class', 'details');
        ?>

        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <div <?php $this->print_render_attribute_string('row'); ?>>
                <?php foreach ($settings['teambox'] as $teambox): ?>
                    <div <?php $this->print_render_attribute_string('item'); ?>>
                        <div class="team-top">
                            <?php $this->render_image($settings, $teambox); ?>
                            <div class="team-icon-socials">
                                <ul>
                                    <?php foreach ($this->get_socials() as $key => $social): ?>
                                        <?php if (!empty($teambox[$key])) : ?>
                                            <li class="social">
                                                <a href="<?php echo esc_url($teambox[$key]) ?>">
                                                    <i class="carafity-icon-<?php echo esc_attr($social); ?>"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="team-content">
                            <div class="team-name"><?php echo esc_html($teambox['name']); ?></div>
                            <div class="team-job"><?php echo esc_html($teambox['job']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php $this->render_swiper_pagination_navigation();?>
        <?php
    }

    private function render_image($settings, $teambox) {
        if (!empty($teambox['teambox_image']['url'])) :
            ?>
            <div class="team-image">
                <?php
                $teambox['teambox_image_size']             = $settings['teambox_image_size'];
                $teambox['teambox_image_custom_dimension'] = $settings['teambox_image_custom_dimension'];
                echo Group_Control_Image_Size::get_attachment_image_html($teambox, 'teambox_image');
                ?>
            </div>
        <?php
        endif;
    }

    private function get_socials()
    {
        return array(
            'facebook' => 'facebook-f',
            'twitter' => 'twitter',
            'instagram' => 'instagram',
            'pinterest' => 'pinterest-p',
        );
    }

}

$widgets_manager->register(new Carafity_Elementor_Team_Box());
