<?php

namespace mphbe\widgets;

use \Elementor\Controls_Manager;

class ServicesWidget extends AbstractWidget
{
    public function get_name()
    {
        return 'mphbe-services';
    }

    public function get_title()
    {
        return __('Services', 'mphb-elementor');
    }

    public function get_icon()
    {
        // Elementor icon class ( https://pojome.github.io/elementor-icons/ ) or
        // Font Awesome icon class ( https://fontawesome.com/ ), like:
        return 'eicon-menu-card';
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

        $this->add_control('ids', array(
            'type'        => Controls_Manager::TEXT,
            'label'       => __('IDs', 'mphb-elementor'),
            'description' => __('Comma-separated IDs of services that will be shown. All services by default.', 'mphb-elementor'),
            'default'     => ''
        ));

        $this->add_control('posts_per_page', array(
            'type'        => Controls_Manager::NUMBER,
            'label'       => __('Count per page', 'mphb-elementor'),
            'description' => __('-1 to display all.', 'mphb-elementor'),
            'default'     => '',
            'min'         => -1
        ));

        $this->add_control('class', array(
            'type'        => Controls_Manager::TEXT,
            'label'       => __('Class', 'mphb-elementor'),
            'description' => __('Custom CSS class for shortcode wrapper.', 'mphb-elementor'),
            'default'     => ''
        ));

        $this->end_controls_section();

        $this->start_controls_section('section_order', array(
            'label'       => __('Order', 'mphb-elementor')
        ));

        $this->add_control('orderby', array(
            'type'        => Controls_Manager::SELECT,
            'label'       => __('Order By', 'mphb-elementor'),
            'default'     => 'menu_order',
            'options'     => array(
                'none'           => __('No order', 'mphb-elementor'),
                'ID'             => __('Post ID', 'mphb-elementor'),
                'author'         => __('Post author', 'mphb-elementor'),
                'title'          => __('Post title', 'mphb-elementor'),
                'name'           => __('Post name (post slug)', 'mphb-elementor'),
                'date'           => __('Post date', 'mphb-elementor'),
                'modified'       => __('Last modified date', 'mphb-elementor'),
                'parent'         => __('Parent ID', 'mphb-elementor'),
                'rand'           => __('Random order', 'mphb-elementor'),
                'comment_count'  => __('Number of comments', 'mphb-elementor'),
                'relevance'      => __('Relevance', 'mphb-elementor'),
                'menu_order'     => __('Page order', 'mphb-elementor'),
                'meta_value'     => __('Meta value', 'mphb-elementor'),
                'meta_value_num' => __('Numeric meta value', 'mphb-elementor'),
                'post__in'       => __('Price', 'mphb-elementor')
            )
        ));

        $this->add_control('order', array(
            'type'        => Controls_Manager::SELECT,
            'label'       => __('Order', 'mphb-elementor'),
            'default'     => 'DESC',
            'options'     => array(
                'ASC'            => __('Ascending (1, 2, 3)', 'mphb-elementor'),
                'DESC'           => __('Descending (3, 2, 1)', 'mphb-elementor')
            )
        ));

        $this->add_control('meta_key', array(
            'type'        => Controls_Manager::TEXT,
            'label'       => __('Meta Name', 'mphb-elementor'),
            'description' => __('Custom field name. Required if "orderby" is one of the "meta_value", "meta_value_num" or "meta_value_*".', 'mphb-elementor'),
            'default'     => ''
        ));

        $this->add_control('meta_type', array(
            'type'        => Controls_Manager::SELECT,
            'label'       => __('Meta Type', 'mphb-elementor'),
            'description' => __('Specified type of the custom field. Can be used in conjunction with "orderby" = "meta_value".', 'mphb-elementor'),
            'default'     => '',
            'options'     => array(
                ''               => __('Any', 'mphb-elementor'),
                'NUMERIC'        => __('Numeric', 'mphb-elementor'),
                'BINARY'         => __('Binary', 'mphb-elementor'),
                'CHAR'           => __('String', 'mphb-elementor'),
                'DATE'           => __('Date', 'mphb-elementor'),
                'TIME'           => __('Time', 'mphb-elementor'),
                'DATETIME'       => __('Date and time', 'mphb-elementor'),
                'DECIMAL'        => __('Decimal number', 'mphb-elementor'),
                'SIGNED'         => __('Signed number', 'mphb-elementor'),
                'UNSIGNED'       => __('Unsigned number', 'mphb-elementor')
            )
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

        do_action('mphbe_before_services_widget_render', $atts);

        $shortcode = MPHB()->getShortcodes()->getServices();
        echo $shortcode->render($atts, null, $shortcode->getName()); // phpcs:ignore

        do_action('mphbe_after_services_widget_render', $atts);

    }
}
