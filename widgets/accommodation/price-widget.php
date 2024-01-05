<?php

namespace mphbe\widgets;

use MPHB\Views\SingleRoomTypeView;

class AccommodationPriceWidget extends AbstractAccommodationWidget
{
    public function get_name()
    {
        return 'mphbe-accommodation-price';
    }

    public function get_title()
    {
        return __('Accommodation Type Price', 'mphb-elementor');
    }

    public function get_icon()
    {
        return 'eicon-product-price';
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

        $current_room_type = MPHB()->getCurrentRoomType();

        MPHB()->setCurrentRoomType($this->accommodation_id);

        SingleRoomTypeView::renderDefaultOrForDatesPrice();

        MPHB()->setCurrentRoomType($current_room_type ? $current_room_type->getId() : get_the_ID());
    }
}
