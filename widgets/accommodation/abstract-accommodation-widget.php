<?php

namespace mphbe\widgets;

use Elementor\Controls_Manager;

abstract class AbstractAccommodationWidget extends AbstractWidget
{
    private $accommodations_select = [];
    private $accommodation_attributes_select = [];
    protected $custom_attributes = [];
    protected $accommodation_id = '';

    abstract protected function mphb_render($settings);

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->accommodation_id = !empty($settings['accommodation_id']) ? (int) $settings['accommodation_id'] : get_the_ID();

        if ('mphb_room_type' !== get_post_type($this->accommodation_id)) {
            ?>
            <div>
                <div style="background: #f6f7f7; border-radius: 10px; padding: 3em 1em;">
                    <p style="text-align: center; font-size: 1.5em; margin: 0;"><i class="<?php echo esc_attr($this->get_icon())?>"></i></p>
                    <p style="text-align: center; font-weight: 700; font-size: 1.5em; margin: 0;"><?php echo esc_html($this->get_title()); ?></p>
                </div>
            </div>
            <?php
        } else {
            $this->mphb_render($settings);
        }
    }

    protected function add_accommodation_control()
    {
        $this->add_control(
            'accommodation_id',
            [
                'label' => esc_html__('Accommodation Type', 'mphb-elementor'),
                'description' => esc_html__('Leave blank to use current.', 'mphb-elementor'),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => false,
                'options' => $this->get_accommodations_to_select(),
            ]
        );
    }

    protected function add_accommodation_attribute_control()
    {
        $this->add_control(
            'attribute',
            [
                'label' => esc_html__('Attribute', 'mphb-elementor'),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => false,
                'options' => $this->get_accommodation_attributes_to_select(),
            ]
        );
    }

    protected function get_accommodations_to_select()
    {

        if (!$this->accommodations_select) {
            $this->fill_accommodations();
        }

        return $this->accommodations_select;
    }

    private function fill_accommodations()
    {

        $query = new \WP_Query(array(
            'post_type' => 'mphb_room_type',
            'posts_per_page' => -1
        ));

        if (!$query->have_posts()) {
            return;
        }

        while ($query->have_posts()) {
            $query->the_post();

            $this->accommodations_select[get_the_ID()] = get_the_title() . ' #' . get_the_ID();
        }

        wp_reset_postdata();
    }

    protected function get_accommodation_attributes_to_select()
    {
        if (!$this->accommodation_attributes_select) {
            $this->fill_accommodation_attributes();
        }

        return $this->accommodation_attributes_select;
    }

    private function fill_accommodation_attributes()
    {
        global $mphbAttributes;

        $this->accommodation_attributes_select = array(
            'capacity' =>  __('Total Capacity', 'mphb-elementor'),
            'amenities' =>  __('Amenities', 'mphb-elementor'),
            'view' =>  __('View', 'mphb-elementor'),
            'size' =>  __('Size', 'mphb-elementor'),
            'bed-types' =>  __('Bed Types', 'mphb-elementor'),
            'categories' =>  __('Categories', 'mphb-elementor'),
        );

        foreach ($mphbAttributes as $customAttribute) {
            $this->accommodation_attributes_select[$customAttribute['attributeName']] = $customAttribute['title'];
        }

        $this->custom_attributes = $mphbAttributes;
    }
}
