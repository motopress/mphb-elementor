<?php

namespace mphbe\widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit('Press Enter to proceed...');
}

class RatesWidget extends Widget_Base
{
    public function get_name()
    {
        return 'mphbe-rates';
    }

    public function get_title()
    {
        return __('Accommodation Rates List', 'mphb-elementor');
    }

    public function get_icon()
    {
        // Elementor icon class ( https://pojome.github.io/elementor-icons/ ) or
        // Font Awesome icon class ( https://fontawesome.com/ ), like:
        return 'eicon-share';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @return string[] Widget categories.
     */
    public function get_categories() {
        return array('motopress-hotel-booking');
    }

    /**
     * Adds different input fields to allow the user to change and customize the
     * widget settings.
     */
    protected function _register_controls()
    {
        $this->start_controls_section('section_parameters', array(
            'label'       => __('Parameters', 'mphb-elementor')
        ));

        // "id" required, but it will cause Backbone error "A "url" property or function must be specified"
        $this->add_control('type_id', array(
            'type'        => Controls_Manager::TEXT,
            'label'       => __('ID', 'mphb-elementor'),
            'description' => __('ID of accommodation type.', 'mphb-elementor'),
            'default'     => ''
        ));

        $this->add_control('class', array(
            'type'        => Controls_Manager::TEXT,
            'label'       => __('Class', 'mphb-elementor'),
            'description' => __('Custom CSS class for shortcode wrapper.', 'mphb-elementor'),
            'default'     => ''
        ));

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     */
    protected function render()
    {
        $atts = $this->get_settings();
        $atts['id'] = $atts['type_id'];
        $shortcode = new \MPHB\Shortcodes\RoomRatesShortcode();
        echo $shortcode->render($atts, null, MPHB()->getShortcodes()->getRoomRates()->getName());
    }

    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live
     * preview.
     */
    protected function _content_template() {}
}
