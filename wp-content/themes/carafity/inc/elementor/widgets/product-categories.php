<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!carafity_is_woocommerce_activated()) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

/**
 * Elementor Carafity_Elementor_Products_Categories
 * @since 1.0.0
 */
class Carafity_Elementor_Products_Categories extends Carafity_Base_Widgets_Swiper {

    public function get_categories() {
        return array('carafity-addons');
    }

    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'carafity-product-categories';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Product Categories', 'carafity');
    }

    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-tabs';
    }

    public function get_script_depends() {
        return ['carafity-elementor-product-categories', 'carafity-elementor-swiper'];
    }

    public function on_export($element) {
        unset($element['settings']['category_image']['id']);

        return $element;
    }

    protected function register_controls() {

        //Section Query
        $this->start_controls_section(
            'section_setting',
            [
                'label' => esc_html__('Categories', 'carafity'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $repeater = new Repeater();

        $repeater->add_control(
            'categories_name',
            [
                'label' => esc_html__('Alternate Name', 'carafity'),
                'type'  => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'categories',
            [
                'label'       => esc_html__('Categories', 'carafity'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'options'     => $this->get_product_categories(),
                'multiple'    => false,
            ]
        );

        $repeater->add_control(
            'categories_type',
            [
                'label'   => esc_html__('Type', 'carafity'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'cate_image',
                'options' => [
                    'cate_image' => esc_html__('Image', 'carafity'),
                    'cate_icon'  => esc_html__('Icon', 'carafity'),
                ]
            ]
        );

        $repeater->add_control(
            'category_icon',
            [
                'label'     => esc_html__('Icon', 'carafity'),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'categories_type' => 'cate_icon'
                ]
            ]
        );

        $repeater->add_control(
            'category_image',
            [
                'label'      => esc_html__('Choose Image', 'carafity'),
                'default'    => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
                'condition'  => [
                    'categories_type' => 'cate_image'
                ]
            ]

        );

        $this->add_control(
            'categories_list',
            [
                'label'       => esc_html__('Items', 'carafity'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ categories }}}',
            ]
        );
        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'      => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `brand_image_size` and `brand_image_custom_dimension`.
                'default'   => 'full',
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label'     => esc_html__('Alignment', 'carafity'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
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
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .product-cat-caption' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_cate_layout',
            [
                'label'   => esc_html__('Layout', 'carafity'),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('Layout 1', 'carafity'),
                    '2' => esc_html__('Layout 2', 'carafity'),
                ]
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'     => esc_html__('Columns', 'carafity'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 1,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition' => ['enable_carousel!' => 'yes']

            ]
        );
        $this->add_responsive_control(
            'product_gutter',
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
                'condition' => ['enable_carousel!' => 'yes']
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
            'product_cate_style',
            [
                'label' => esc_html__('Box', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'box_bg',
            [
                'label'     => __('Background Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-cat' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'box_padding',
            [
                'label'      => esc_html__('Padding', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .layout-2 .product-cat-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    '{{WRAPPER}} .layout-1 .product-cat .product-cat-caption'                => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'content-stretch',
            [
                'label' => esc_html__('Stretch', 'carafity'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'content-stretch-'
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
                'condition' => [
                    'content-stretch' => '',
                    'product_cate_layout' => '1'
                ],
                'selectors'  => [
                    '{{WRAPPER}} .layout-1 .product-cat' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .layout-1 .category-product-img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'vertical_position',
            [
                'label'        => esc_html__('Vertical Position', 'carafity'),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
                    'top'    => [
                        'title' => esc_html__('Top', 'carafity'),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__('Middle', 'carafity'),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'carafity'),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'prefix_class' => 'product-cat-valign-',
                'separator'    => 'none',
                'condition' => [
                    'product_cate_layout' => '1'
                ],
            ]
        );

        $this->add_control(
            'box_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .product-cat' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .grid-item',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'image_box_border',
                'selector'  => '{{WRAPPER}} .product-cat',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'image_style',
            [
                'label' => esc_html__('Image', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_shadow',
                'selector' => '{{WRAPPER}} .category-product-img img',
            ]
        );


        $this->add_responsive_control(
            'image_width',
            [
                'label'          => esc_html__('Width', 'carafity'),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units'     => ['%', 'px', 'vw'],
                'range'          => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .category-product-img img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_max_width',
            [
                'label'          => esc_html__('Max Width', 'carafity'),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units'     => ['%', 'px', 'vw'],
                'range'          => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .category-product-img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_height',
            [
                'label'          => esc_html__('Height', 'carafity'),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units'     => ['px', 'vh'],
                'range'          => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .category-product-img img' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .category-product-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_hover_border_radius',
            [
                'label'      => esc_html__('Hover Border Radius', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .product-cat-link:hover .category-product-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .category-product-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label'      => esc_html__('Margin', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .category-product-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'show_mask',
            [
                'label'        => esc_html__('Mask', 'carafity'),
                'type'         => Controls_Manager::SWITCHER,
                'prefix_class' => 'show-mask-',
                'condition' => [
                    'product_cate_layout' => '2',
                ],
            ]
        );

        $this->add_control(
            'mask_shape',
            [
                'label' => esc_html__( 'Shape', 'carafity' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'circle' => esc_html__( 'Circle', 'carafity' ),
                    'flower' => esc_html__( 'Flower', 'carafity' ),
                    'sketch' => esc_html__( 'Sketch', 'carafity' ),
                    'triangle' => esc_html__( 'Triangle', 'carafity' ),
                    'blob' => esc_html__( 'Blob', 'carafity' ),
                    'hexagon' => esc_html__( 'Hexagon', 'carafity' ),
                    'custom' => esc_html__( 'Custom', 'carafity' ),
                ],
                'default' => 'circle',
                'selectors'  => [
                    '{{WRAPPER}} .category-product-img-inner img' => '-webkit-mask-image: url( ' . ELEMENTOR_ASSETS_URL . '/mask-shapes/{{VALUE}}.svg );',
                ],
                'condition' => [
                    'show_mask' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'mask_image',
            [
                'label' => esc_html__( 'Image', 'carafity' ),
                'type' => Controls_Manager::MEDIA,
                'media_type' => 'image',
                'should_include_svg_inline_option' => true,
                'library_type' => 'image/svg+xml',
                'dynamic' => [
                    'active' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .category-product-img-inner img' => '-webkit-mask-image: url( {{URL}} );',
                ],
                'condition' => [
                    'show_mask' => 'yes',
                    'mask_shape' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'mask_size',
            [
                'label' => esc_html__( 'Size', 'carafity' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'contain' => esc_html__( 'Fit', 'carafity' ),
                    'cover' => esc_html__( 'Fill', 'carafity' ),
                ],
                'default' => 'contain',

                'selectors'  => [
                    '{{WRAPPER}} .category-product-img-inner img' => '-webkit-mask-size: {{VALUE}};',
                ],
                'condition' => [
                    'show_mask' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'mask_position',
            [
                'label' => esc_html__( 'Shape', 'carafity' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'carafity' ),
                    'center left' => esc_html__( 'Center Left', 'carafity' ),
                    'center right' => esc_html__( 'Center Right', 'carafity' ),
                    'top center' => esc_html__( 'Top Center', 'carafity' ),
                    'top left' => esc_html__( 'Top Left', 'carafity' ),
                    'top right' => esc_html__( 'Top Right', 'carafity' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'carafity' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'carafity' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'carafity' ),
                    'custom' => esc_html__( 'Custom', 'carafity' ),
                ],
                'default' => 'center center',
                'selectors'  => [
                    '{{WRAPPER}} .category-product-img-inner img' => '-webkit-mask-position: {{VALUE}};',
                ],
                'condition' => [
                    'show_mask' => 'yes',
                ],
            ]
        );


        $this->add_responsive_control(
            'mask_position_x',
            [
                'label' => esc_html__( 'X Position', 'carafity' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                    ],
                    'em' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],

                'selectors'  => [
                    '{{WRAPPER}} .category-product-img-inner img' => '-webkit-mask-position-x: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_mask' => 'yes',
                    'mask_position' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'mask_position_y',
            [
                'label' => esc_html__( 'Y Position', 'carafity' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                    ],
                    'em' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],

                'selectors'  => [
                    '{{WRAPPER}} .category-product-img-inner img' => '-webkit-mask-position-y: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_mask' => 'yes',
                    'mask_position' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'mask_repeat',
            [
                'label' => esc_html__( 'Repeat', 'carafity' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'no-repeat' => esc_html__( 'No-repeat', 'carafity' ),
                    'repeat' => esc_html__( 'Repeat', 'carafity' ),
                    'repeat-x' => esc_html__( 'Repeat-x', 'carafity' ),
                    'repeat-Y' => esc_html__( 'Repeat-y', 'carafity' ),
                    'round' => esc_html__( 'Round', 'carafity' ),
                    'space' => esc_html__( 'Space', 'carafity' ),
                ],
                'default' => 'no-repeat',
                'selectors'  => [
                    '{{WRAPPER}} .category-product-img-inner img'  => '-webkit-mask-repeat: {{VALUE}};',
                ],
                'condition' => [
                    'show_mask' => 'yes',
                    'mask_size!' => 'cover',
                ],
            ]
        );


        $this->add_control(
            'show_border_mask',
            [
                'label'        => esc_html__('Show Border Mask', 'carafity'),
                'type'         => Controls_Manager::SWITCHER,
                'prefix_class' => 'show-border-mask-',
                'condition' => [
                    'product_cate_layout' => '2',
                ],
            ]
        );



        $this->add_responsive_control(
            'border_mask_width',
            [
                'label'          => esc_html__('Width', 'carafity'),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units'     => ['px'],
                'range'          => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .category-product-img a:before ' => 'border-width: {{SIZE}}{{UNIT}} 0;',
                    '{{WRAPPER}} .category-product-img a:after' => 'border-width: 0 {{SIZE}}{{UNIT}};',
                ],

                'condition' => [
                    'show_border_mask' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'border_mask_color',
            [
                'label'     => esc_html__('Border Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-cat .category-product-img a:after,{{WRAPPER}} .product-cat .category-product-img a:before' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_border_mask' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'border_mask_color_hover',
            [
                'label'     => esc_html__('Hover Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-cat:hover .category-product-img a:after,{{WRAPPER}} .product-cat:hover  .category-product-img a:before' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_border_mask' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'title_style',
            [
                'label' => esc_html__('Title', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tilte_typography',
                'selector' => '{{WRAPPER}} .cat-title',
            ]
        );


        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__('Margin', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .cat-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label'      => esc_html__('Padding', 'carafity'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .cat-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tab_title');
        $this->start_controls_tab(
            'tab_title_normal',
            [
                'label' => esc_html__('Normal', 'carafity'),
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .cat-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_title_hover',
            [
                'label' => esc_html__('Hover', 'carafity'),
            ]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label'     => esc_html__('Hover Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-cat-link:hover .cat-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'total_style',
            [
                'label' => esc_html__('Total', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'total_hidden',
            [
                'label' => esc_html__('Hidden', 'carafity'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'total-hidden-'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'total_typography',
                'selector' => '{{WRAPPER}} .cat-total',
            ]
        );
        $this->add_control(
            'total_color',
            [
                'label'     => esc_html__('Color Count', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .cat-total' => 'color: {{VALUE}};',
                ],
            ]
        );$this->add_control(
            'total_color_hover',
            [
                'label'     => esc_html__('Color Count Hover', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .cat-total:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'number_style',
            [
                'label' => esc_html__('Number', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'product_cate_layout' => '5'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'number_typography',
                'selector' => '{{WRAPPER}} .product-cat-caption:before',
                'condition' => [
                    'product_cate_layout' => '5'
                ],
            ]
        );
        $this->add_control(
            'number_color',
            [
                'label'     => esc_html__('Color Count', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-cat-caption:before' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'product_cate_layout' => '5'
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'icon_style',
            [
                'label' => esc_html__('Icon', 'carafity'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_space',
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
                    '{{WRAPPER}} .category-product-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size_1',
            [
                'label'     => esc_html__('Size', 'carafity'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 6,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .category-product-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tab_icon');
        $this->start_controls_tab(
            'tab_icon_normal',
            [
                'label' => esc_html__('Normal', 'carafity'),
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .category-product-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_background_color',
            [
                'label'     => esc_html__('Background Color', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .category-product-icon'                  => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .layout-2 .category-product-icon:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_hover',
            [
                'label' => esc_html__('Hover', 'carafity'),
            ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-cat-link:hover .category-product-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_background_color_hover',
            [
                'label'     => esc_html__('Background Color Hover', 'carafity'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-cat-link:hover .category-product-icon'                  => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .layout-2 .product-cat-link:hover .category-product-icon:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Carousel options
        $this->add_control_carousel(['enable_carousel' => 'yes']);
    }

    protected function get_product_categories() {
        $categories = get_terms(array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
            )
        );
        $results    = array();
        if (!is_wp_error($categories)) {
            foreach ($categories as $category) {
                $results[$category->slug] = $category->name;
            }
        }
        return $results;
    }

    /**
     * Render tabs widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['categories_list']) && is_array($settings['categories_list'])) {
            $this->add_render_attribute('wrapper', 'class', 'elementor-categories-item-wrapper');
            $this->add_render_attribute('row', 'class', 'layout-' . esc_attr($settings['product_cate_layout']));

            // Carousel
            $this->get_data_elementor_columns();

            // Item
            $this->add_render_attribute('item', 'class', 'elementor-categories-item');

            ?>
            <div <?php $this->print_render_attribute_string('wrapper'); // WPCS: XSS ok. ?>>
                <div <?php $this->print_render_attribute_string('row'); // WPCS: XSS ok. ?>>
                    <?php foreach ($settings['categories_list'] as $index => $item) { ?>

                        <?php
                        $class_item            = 'elementor-repeater-item-' . $item['_id'];
                        $tab_title_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);
                        $this->add_render_attribute($tab_title_setting_key, ['class' => ['product-cat', $class_item],]);
                        ?>
                        <div <?php $this->print_render_attribute_string('item'); // WPCS: XSS ok. ?>>
                            <?php if (empty($item['categories'])) {
                                echo esc_html__('Choose Category', 'carafity');
                                return;
                            }
                            $category = get_term_by('slug', $item['categories'], 'product_cat');
                            if ($category && !is_wp_error($category)) {
                                $query = new WP_Query(array(
                                    'tax_query' => array(
                                        array(
                                            'taxonomy'         => 'product_cat',
                                            'field'            => 'id',
                                            'terms'            => $category->term_id,
                                            'include_children' => true,
                                        ),
                                    ),
                                    'nopaging'  => true,
                                    'fields'    => 'ids',
                                ));

                                if (!empty($item['category_image']['id'])) {
                                    $image = Group_Control_Image_Size::get_attachment_image_src($item['category_image']['id'], 'image', $settings);
                                } else {
                                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                                    if (!empty($thumbnail_id)) {
                                        $image = wp_get_attachment_url($thumbnail_id);
                                    } else {
                                        $image = wc_placeholder_img_src();
                                    }
                                } ?>

                                <div <?php $this->print_render_attribute_string($tab_title_setting_key); ?>>
                                    <?php if ($settings['product_cate_layout'] != '1'){ ?>
                                    <div class="product-cat-link">
                                        <?php } ?>
                                        <?php

                                            if ($item['categories_type'] == 'cate_image') { ?>
                                                <div class="category-product-img <?php echo esc_attr($class_item); ?>">
                                                <?php if ($settings['product_cate_layout'] === '2'){ ?> <div class="category-product-img-inner"><?php } ?>
                                                        <a href="<?php echo esc_url(get_term_link($category)); ?>" title="<?php echo esc_attr($category->name); ?>">
                                                            <img src="<?php echo esc_url_raw($image); ?>"

                                                         alt="<?php echo esc_attr($category->name); ?>"></a>
                                                    <?php if ($settings['product_cate_layout'] === '2'){ ?> </div><?php } ?>
                                                </div>
                                            <?php } ?>
                                            <?php if ($item['categories_type'] == 'cate_icon') { ?>
                                                <?php if (!empty($item['category_icon']['value'])) { ?>
                                                    <div class="category-product-icon"><?php \Elementor\Icons_Manager::render_icon($item['category_icon'], ['aria-hidden' => 'true']); ?></div>
                                                <?php } ?>
                                            <?php }
                                        ?>
                                        <div class="product-cat-caption">
                                            <div class="product-content">
                                                <div class="cat-title">
                                                    <a href="<?php echo esc_url(get_term_link($category)); ?>" title="<?php echo esc_attr($category->name); ?>"><span data-hover="<?php echo empty($item['categories_name']) ? esc_html($category->name) : sprintf('%s', $item['categories_name']); ?>">
                                                        <?php echo empty($item['categories_name']) ? esc_html($category->name) : sprintf('%s', $item['categories_name']); ?>
                                                    </span>
                                                    </a>
                                                </div>

                                                <div class="cat-total">
                                                    <?php echo esc_html($query->post_count); ?> <?php echo esc_html__('Products', 'carafity'); ?>
                                                </div>


                                                <?php
                                                if ($settings['product_cate_layout'] === '1'){
                                                    ?>
                                                    <div class="categories_button">
                                                        <a class="product-cat-button button-outline" href="<?php echo esc_url(get_term_link($category)); ?>" title="<?php echo esc_attr($category->name); ?>">
                                                            <?php echo esc_html__('Shop Now', 'carafity'); ?> <i class="carafity-icon-long-arrow-right"></i>
                                                        </a>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                            </div>

                                        </div>
                                <?php if ($settings['product_cate_layout'] != '1'){ ?></div>   <?php } ?>
                                </div>
                                <?php
                                wp_reset_postdata();
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php $this->render_swiper_pagination_navigation();?>
            <?php
        }
    }

}

$widgets_manager->register(new Carafity_Elementor_Products_Categories());

