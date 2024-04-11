<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Plugin;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor image gallery widget.
 *
 * Elementor widget that displays a set of images in an aligned grid.
 *
 * @since 1.0.0
 */
class Carafity_Elementor_Image_Gallery extends Carafity_Base_Widgets_Swiper {

    /**
     * Get widget name.
     *
     * Retrieve image gallery widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'carafity-image-gallery';
    }

    /**
     * Get widget title.
     *
     * Retrieve image gallery widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Carafity Image Gallery', 'carafity');
    }

    public function get_script_depends() {
        return [
            'isotope',
            'carafity-elementor-image-gallery',
            'carafity-elementor-swiper'
        ];
    }

    public function get_style_depends() {
        return ['magnific-popup'];
    }

    public function get_categories() {
        return ['carafity-addons'];
    }

    /**
     * Get widget icon.
     *
     * Retrieve image gallery widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @return array Widget keywords.
     * @since  2.1.0
     * @access public
     *
     */
    public function get_keywords() {
        return ['image', 'photo', 'visual', 'gallery'];
    }

    /**
     * Register image gallery widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_gallery',
            [
                'label' => esc_html__('Image Gallery', 'carafity'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'filter_title',
            [
                'label'       => esc_html__('Filter Title', 'carafity'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => esc_html__('List Item', 'carafity'),
                'default'     => esc_html__('List Item', 'carafity'),
            ]
        );

        $repeater->add_control(
            'wp_gallery',
            [
                'label'      => esc_html__('Add Images', 'carafity'),
                'type'       => Controls_Manager::GALLERY,
                'show_label' => false,
                'dynamic'    => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'filter',
            [
                'label'       => '',
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'filter_title' => esc_html__('Gallery 1', 'carafity'),
                    ],
                ],
                'title_field' => '{{{ filter_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__('Layout', 'carafity'),
            ]
        );

        $this->add_control(
            'show_filter_bar',
            [
                'label'     => esc_html__('Filter Bar', 'carafity'),
                'type'      => Controls_Manager::SWITCHER,
                'label_off' => esc_html__('Off', 'carafity'),
                'label_on'  => esc_html__('On', 'carafity'),
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'thumbnail',
                'separator' => 'none',
                'default'   => 'maisonco-gallery-image'
            ]
        );


        $this->add_responsive_control(
            'column',
            [
                'label'     => esc_html__('Columns', 'carafity'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 3,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                    '{{WRAPPER}} .isotope-grid .grid__item' => 'flex: 0 0 calc(100% / {{VALUE}});max-width: calc(100% / {{VALUE}})',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'gutter',
            [
                'label'      => esc_html__('Gutter', 'carafity'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .d-grid' => 'grid-gap:{{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .isotope-grid' => 'margin-left: calc({{SIZE}}{{UNIT}} / -2); margin-right: calc({{SIZE}}{{UNIT}} / -2);',
                    '{{WRAPPER}} .isotope-grid .grid__item' => 'padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-right: calc({{SIZE}}{{UNIT}} / 2); margin-bottom: {{SIZE}}{{UNIT}};',

                ],
                'condition'  => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label' => esc_html__( 'Link', 'carafity' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'file',
                'options' => [
                    'file' => esc_html__( 'Media File', 'carafity' ),
                    'custom' => esc_html__( 'Custom URL', 'carafity' ),
                    'custom-link-item' => esc_html__( 'Custom URL in Image', 'carafity' ),
                    'none' => esc_html__( 'None', 'carafity' ),
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'carafity' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'carafity' ),
                'condition' => [
                    'link_to' => 'custom',
                ],
                'show_label' => false,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'open_lightbox',
            [
                'label' => esc_html__( 'Lightbox', 'carafity' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__( 'Default', 'carafity' ),
                    'yes' => esc_html__( 'Yes', 'carafity' ),
                    'no' => esc_html__( 'No', 'carafity' ),
                ],
                'condition' => [
                    'link_to' => 'file',
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

        $this->start_controls_section(
            'section_design_filter',
            [
                'label'     => esc_html__('Filter Bar', 'carafity'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_filter_bar' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_filter',
                'selector' => '{{WRAPPER}} .elementor-galerry__filter',
            ]
        );

        $this->add_responsive_control(
            'filter_item_spacing',
            [
                'label'     => esc_html__('Space Between', 'carafity'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-galerry__filter:not(:last-child)'  => 'margin-right: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-galerry__filter:not(:first-child)' => 'margin-left: calc({{SIZE}}{{UNIT}}/2)',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_spacing',
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
                    '{{WRAPPER}} .elementor-galerry__filters' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_padding',
            [
                'label'      => esc_html__('Filter Padding', 'carafity'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50
                    ]
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-galerry__filter' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'filter_align',
            [
                'label'        => esc_html__('Alignment', 'carafity'),
                'type'         => Controls_Manager::CHOOSE,
                'default'      => 'top',
                'options'      => [
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
                    ]
                ],
                'toggle'       => false,
                'prefix_class' => 'elementor-filter-',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_design_image',
            [
                'label' => esc_html__('Image', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_radius',
            [
                'label'      => esc_html__('Border Radius', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .grid__item a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
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
                    '{{WRAPPER}} .grid__item img' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_gallery_shadow',
                'selector' => '{{WRAPPER}} img',
            ]
        );
        $this->add_control(
            'image_gallery_opacity',
            [
                'label'     => esc_html__('Opacity Hover', 'carafity'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max'  => 1,
                        'min'  => 0.1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .grid__item a:hover:before' => 'opacity: {{SIZE}};',
                ],
            ]
        );


        $this->end_controls_section();

        // Carousel options
        $this->add_control_carousel(['enable_carousel' => 'yes']);

    }

    /**
     * Render image gallery widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('wrapper', 'class', 'elementor-opal-image-gallery');
        $this->add_render_attribute('item', 'class', 'grid__item masonry-item__all');

        // Carousel
        $this->get_data_elementor_columns();

        $this->add_render_attribute('link', 'data-elementor-lightbox-slideshow', $this->get_id());
        $total_image = 0;
        $li_html = $content_galery = '';
        foreach ($settings['filter'] as $index => $item) {
            if (!empty($item['wp_gallery'])) {
                if ($settings['show_filter_bar'] == 'yes') {
                    $total_image += count($item['wp_gallery']);

                    ob_start();
                    ?>
                    <li class="elementor-galerry__filter"
                        data-filter=".gallery_group_<?php echo esc_attr($index); ?>"><?php echo esc_html($item['filter_title']); ?>
                        <span class="count"><?php echo count($item['wp_gallery']); ?></span></li>
                    <?php
                    $li_html .= ob_get_clean();
                }

                foreach ($item['wp_gallery'] as $items => $attachment) {
                    $link = $this->get_link_url( $attachment, $settings );

                    $image_url      = Group_Control_Image_Size::get_attachment_image_src($attachment['id'], 'thumbnail', $settings);

                    $this->add_render_attribute('item', 'class', 'gallery_group_' . esc_attr($index));
                    ob_start();
                    ?>
                    <div <?php $this->print_render_attribute_string('item'); ?>>
                        <?php
                        $link_key = 'link_' . $items;
                        if ( $link ) {

                            if ( 'custom' !== $settings['link_to'] && 'custom-link-item' !== $settings['link_to'] ) {
                                $this->add_lightbox_data_attributes($link_key, $attachment['id'], $settings['open_lightbox'], $this->get_id());
                            }
                            if ( Plugin::$instance->editor->is_edit_mode() ) {
                                $this->add_render_attribute( $link_key, [
                                    'class' => 'elementor-clickable',
                                ] );
                            }

                            $this->add_link_attributes( $link_key, $link );
                        }else {
                            $this->add_render_attribute( $link_key,'class', 'elementor-clickable' );
                        }
                        ?>
                        <a <?php $this->print_render_attribute_string( $link_key ); ?>>
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(Elementor\Control_Media::get_image_alt($attachment)); ?>"/>
                        </a>
                    </div>
                    <?php
                    $content_galery .= ob_get_clean();
                    $this->remove_render_attribute('item', 'class', 'gallery_group_' . esc_attr($index));
                }
            }
        }

        if ($settings['show_filter_bar'] == 'yes') {
            $this->add_render_attribute('row', 'class', 'isotope-grid d-flex flex-wrap');
            $this->remove_render_attribute('row', 'class', 'd-grid');
            ?>
            <ul class="elementor-galerry__filters" data-related="isotope-<?php echo esc_attr($this->get_id()); ?>">
                <li class="elementor-galerry__filter elementor-active"
                    data-filter=".masonry-item__all"><?php echo esc_html__('All', 'carafity'); ?>
                    <span class="count"><?php echo esc_html($total_image); ?></span></li>
                <?php
                printf('%s', $li_html);
                ?>
            </ul>
        <?php } ?>

        <div <?php $this->print_render_attribute_string('wrapper');?>>
            <div <?php $this->print_render_attribute_string('row') ?>>
                <?php
                printf('%s',$content_galery);
                ?>
            </div>
        </div>
        <?php $this->render_swiper_pagination_navigation();?>

        <?php
    }

    private function get_link_url( $attachment, $instance ) {
        if ( 'none' == $instance['link_to'] ) {
            return false;
        }

        if ( 'custom' == $instance['link_to'] ) {
            if ( empty( $instance['link']['url'] ) ) {
                return false;
            }

            return $instance['link'];
        }

        if ('custom-link-item' === $instance['link_to']) {
            return [
                'url' => get_post_meta($attachment['id'], 'carafity_custom_media_link', true),
                'is_external' => 'true'
            ];
        }

        return [
            'url' => wp_get_attachment_url( $attachment['id'] ),
        ];
    }

}

$widgets_manager->register(new Carafity_Elementor_Image_Gallery());





