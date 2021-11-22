<?php

namespace mphbe\widgets;

use \Elementor\Controls_Manager;

class AvailabilityWidget extends AbstractWidget
{
    public function get_name()
    {
        return 'mphbe-availability';
    }

    public function get_title()
    {
        return __('Booking Form', 'mphb-elementor');
    }

    public function get_icon()
    {
        // Elementor icon class ( https://pojome.github.io/elementor-icons/ ) or
        // Font Awesome icon class ( https://fontawesome.com/ ), like:
        return 'eicon-form-horizontal';
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
            'description' => __('ID of Accommodation Type to check availability.', 'mphb-elementor'),
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

        $atts[ 'id' ]     = $atts[ 'type_id' ];

       $className = ! empty( $atts[ 'class' ] ) ? $atts[ 'class' ] : '';

        $atts[ 'class' ]  = ! empty( $atts[ 'widget_styles' ] ) ? $atts[ 'widget_styles' ] : '';
        $atts[ 'class' ] .= ! empty( $atts[ 'hide_labels' ] ) ? $atts[ 'hide_labels' ] === 'yes' ? 'mphbs-hide-labels ' : '' : '';
        $atts[ 'class' ] .= ! empty( $atts[ 'no_paddings' ] ) ? $atts[ 'no_paddings' ] === 'yes' ? 'mphbs-no-paddings ' : '' : '';
        $atts[ 'class' ] .= ! empty( $atts[ 'hide_tips' ] ) ? $atts[ 'hide_tips' ] === 'yes' ? 'mphbs-hide-rf-tip ' : '' : '';
        $atts[ 'class' ] .= ! empty( $atts[ 'stretch_btn' ] ) ? $atts[ 'stretch_btn' ] === 'yes' ? 'mphbs-fluid-button ' : '' : '';
        $atts[ 'class' ] .= ! empty( $atts[ 'fields_width' ] ) ? $atts[ 'fields_width' ] : '';
        $atts[ 'class' ] .= $className;

        do_action('mphbe_before_availability_widget_render', $atts);

        $shortcode = MPHB()->getShortcodes()->getBookingForm();
        echo $shortcode->render($atts, null, $shortcode->getName()); // phpcs:ignore

        do_action('mphbe_after_availability_widget_render', $atts);

    }
}
