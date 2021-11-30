<?php

namespace mphbe\widgets;

use \Elementor\Controls_Manager;

class SearchFormWidget extends AbstractWidget
{
    public function get_name()
    {
        return 'mphbe-search-form';
    }

    public function get_title()
    {
        return __('Search Availability Form', 'mphb-elementor');
    }

    public function get_icon()
    {
        // Elementor icon class ( https://pojome.github.io/elementor-icons/ ) or
        // Font Awesome icon class ( https://fontawesome.com/ ), like:
        return 'eicon-search';
    }

    /**
     * Adds different input fields to allow the user to change and customize the
     * widget settings.
     */
    protected function _register_controls()
    {
        $minAdults   = MPHB()->settings()->main()->getMinAdults();
        $maxAdults   = MPHB()->settings()->main()->getSearchMaxAdults();
        $minChildren = MPHB()->settings()->main()->getMinChildren();
        $maxChildren = MPHB()->settings()->main()->getSearchMaxChildren();
        $dateFormat  = MPHB()->settings()->dateTime()->getDateFormat();

        $this->start_controls_section('section_parameters', array(
            'label'       => __('Parameters', 'mphb-elementor')
        ));

        $this->add_control('adults', array(
            'type'        => Controls_Manager::NUMBER,
            'label'       => __('Adults', 'mphb-elementor'),
            'description' => __('The number of adults presetted in the search form.', 'mphb-elementor'),
            'default'     => $minAdults,
            'min'         => $minAdults,
            'max'         => $maxAdults
        ));

        $this->add_control('children', array(
            'type'        => Controls_Manager::NUMBER,
            'label'       => __('Children', 'mphb-elementor'),
            'description' => __('The number of children presetted in the search form.', 'mphb-elementor'),
            'default'     => $minChildren,
            'min'         => $minChildren,
            'max'         => $maxChildren
        ));

        $this->add_control('check_in_date', array(
            'type'        => Controls_Manager::TEXT,
            'label'       => __('Check-in date', 'mphb-elementor'),
            'description' => __('Check-in date presetted in the search form.', 'mphb-elementor'),
            'placeholder' => $dateFormat,
            'default'     => ''
        ));

        $this->add_control('check_out_date', array(
            'type'        => Controls_Manager::TEXT,
            'label'       => __('Check-out date', 'mphb-elementor'),
            'description' => __('Check-out date presetted in the search form.', 'mphb-elementor'),
            'placeholder' => $dateFormat,
            'default'     => ''
        ));

        $this->add_control('attributes', array(
            'type'        => Controls_Manager::TEXT,
            'label'       => __('Attributes', 'mphb-elementor'),
            'description' => __('Custom attributes for advanced search.', 'mphb-elementor'),
            'placeholder' => __('Slugs of attributes', 'mphb-elementor'),
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

        $className = ! empty( $atts[ 'class' ] ) ? $atts[ 'class' ] : '';

        $atts[ 'class' ]  = ! empty( $atts[ 'widget_styles' ] ) ? $atts[ 'widget_styles' ] : '';
        $atts[ 'class' ] .= ! empty( $atts[ 'hide_labels' ] ) ? $atts[ 'hide_labels' ] === 'yes' ? 'mphbs-hide-labels ' : '' : '';
        $atts[ 'class' ] .= ! empty( $atts[ 'no_paddings' ] ) ? $atts[ 'no_paddings' ] === 'yes' ? 'mphbs-no-paddings ' : '' : '';
        $atts[ 'class' ] .= ! empty( $atts[ 'hide_tips' ] ) ? $atts[ 'hide_tips' ] === 'yes' ? 'mphbs-hide-rf-tip ' : '' : '';
        $atts[ 'class' ] .= ! empty( $atts[ 'multiple_lines' ] ) ? $atts[ 'multiple_lines' ] === 'yes' ? 'mphbs-wrap ' : '' : '';
        $atts[ 'class' ] .= ! empty( $atts[ 'stretch_btn' ] ) ? $atts[ 'stretch_btn' ] === 'yes' ? 'mphbs-fluid-button ' : '' : '';
        $atts[ 'class' ] .= ! empty( $atts[ 'fields_width' ] ) ? $atts[ 'fields_width' ] : '';
        $atts[ 'class' ] .= $className;

        do_action('mphbe_before_search_form_widget_render', $atts);

        $shortcode = MPHB()->getShortcodes()->getSearch();
        echo $shortcode->render($atts, null, $shortcode->getName()); // phpcs:ignore

        do_action('mphbe_after_search_form_widget_render', $atts);

    }
}
