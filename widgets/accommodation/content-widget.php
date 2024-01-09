<?php

namespace mphbe\widgets;

class AccommodationContentWidget extends AbstractAccommodationWidget
{
    public function get_name()
    {
        return 'mphbe-accommodation-content';
    }

    public function get_title()
    {
        return __('Accommodation Type Content', 'mphb-elementor');
    }

    public function get_icon()
    {
        return 'eicon-post-content';
    }

    /**
     * Adds different input fields to allow the user to change and customize the
     * widget settings.
     */
    protected function register_controls()
    {
        $this->start_controls_section('section_parameters', array(
            'label' => __('Parameters', 'mphb-elementor')
        ));

        $this->add_accommodation_control();

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     */
    protected function mphb_render($settings)
    {

        if ($this->accommodation_id) {
            $query = new \WP_Query(array(
                'p' => $this->accommodation_id,
                'post_type' => 'mphb_room_type',
            ));

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    the_content();
                }
            }
            wp_reset_postdata();
        } else {
            the_content();
        }
    }
}
