<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!carafity_is_contactform_activated()) {
    return;
}

use Elementor\Controls_Manager;

class Carafity_Elementor_ContactForm extends Elementor\Widget_Base {

    public function get_name() {
        return 'carafity-contactform';
    }

    public function get_title() {
        return esc_html__('Carafity Contact Form', 'carafity');
    }

    public function get_categories() {
        return array('carafity-addons');
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    protected function register_controls() {
        $this->start_controls_section(
            'contactform7',
            [
                'label' => esc_html__('General', 'carafity'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $cf7               = get_posts('post_type="wpcf7_contact_form"&numberposts=-1');
        $contact_forms[''] = esc_html__('Please select form', 'carafity');
        if ($cf7) {
            foreach ($cf7 as $cform) {
                $contact_forms[$cform->ID] = $cform->post_title;
            }
        } else {
            $contact_forms[0] = esc_html__('No contact forms found', 'carafity');
        }

        $this->add_control(
            'cf_id',
            [
                'label'   => esc_html__('Select contact form', 'carafity'),
                'type'    => Controls_Manager::SELECT,
                'options' => $contact_forms,
                'default' => ''
            ]
        );

        $this->add_control(
            'form_name',
            [
                'label'   => esc_html__('Form name', 'carafity'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Contact form', 'carafity'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!$settings['cf_id']) {
            return;
        }
        $args['id']    = $settings['cf_id'];
        $args['title'] = $settings['form_name'];

        echo carafity_do_shortcode('contact-form-7', $args);
    }
}

$widgets_manager->register(new Carafity_Elementor_ContactForm());
