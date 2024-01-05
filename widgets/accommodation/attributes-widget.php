<?php

namespace mphbe\widgets;

use Elementor\Controls_Manager;
use MPHB\Views\SingleRoomTypeView;

class AccommodationAttributesWidget extends AbstractAccommodationWidget
{
    private $hidden_attributes = [];
    private $removed_actions = [];
    private $setting_to_action = [
        'capacity' => [
            'renderTotalCapacity',
            'renderAdults',
            'renderChildren',
        ],
        'amenities' => [ 'renderFacilities' ],
        'view' => [ 'renderView' ],
        'size' => [ 'renderSize' ],
        'bed-types' => [ 'renderBedType' ],
        'categories' => [ 'renderCategories' ]
    ];

    public function get_name()
    {
        return 'mphbe-accommodation-attributes';
    }

    public function get_title()
    {
        return __('Accommodation Type Attributes', 'mphb-elementor');
    }

    public function get_icon()
    {
        return 'eicon-bullet-list';
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

        $attributes = $this->get_accommodation_attributes_to_select();

        $this->add_control(
            'selected_attributes',
            [
                'label' => esc_html__('Attributes', 'mphb-elementor'),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => $attributes,
                'default' => array_keys($attributes)
            ]
        );

        $this->end_controls_section();
    }

    protected function mphb_render($settings)
    {

        $selected_attributes = $settings['selected_attributes'];

        $this->hidden_attributes = array_diff(array_keys($this->get_accommodation_attributes_to_select()), $selected_attributes);

        $current_accommodation = MPHB()->getCurrentRoomType();

        MPHB()->setCurrentRoomType($this->accommodation_id);
        $this->apply_attributes_params();

        SingleRoomTypeView::renderAttributes();

        $this->restore_attributes_params();
        MPHB()->setCurrentRoomType($current_accommodation ? $current_accommodation->getId() : get_the_ID());
    }

    private function apply_attributes_params()
    {
        add_action('mphb_render_single_room_type_before_attributes', array($this, 'removeAttributesTitle'), 0);
        add_action('mphb_render_single_room_type_before_attributes', array($this, 'filter_attributes'));

        global $mphbAttributes;
        foreach ($this->custom_attributes as $slug => $attribute) {
            if ($this->should_hide_attr($slug)) {
                $mphbAttributes[$slug]['visible'] = false;
            }
        }
    }

    private function restore_attributes_params()
    {
        remove_action('mphb_render_single_room_type_before_attributes', array($this, 'removeAttributesTitle'), 0);
        remove_action('mphb_render_single_room_type_before_attributes', array($this, 'filter_attributes'));

        foreach ($this->removed_actions as $action => $priority) {
            add_action('mphb_render_single_room_type_attributes', array('\MPHB\Views\SingleRoomTypeView', $action), $priority);
        }

        global $mphbAttributes;
        $mphbAttributes = $this->custom_attributes;
    }

    public function removeAttributesTitle()
    {
        remove_action('mphb_render_single_room_type_before_attributes', array('\MPHB\Views\SingleRoomTypeView', '_renderAttributesTitle'), 10);
    }

    public function filter_attributes()
    {
        foreach ($this->setting_to_action as $setting => $actions) {
            if ($this->should_hide_attr($setting)) {
                foreach ($actions as $action) {
                    $priority = has_action('mphb_render_single_room_type_attributes', array('\MPHB\Views\SingleRoomTypeView', $action));

                    if ($priority) {
                        remove_action( 'mphb_render_single_room_type_attributes', array( '\MPHB\Views\SingleRoomTypeView', $action ), $priority );
                        $this->removed_actions[ $action ] = $priority;
                    }
                }
            }
        }
    }

    private function should_hide_attr($attr)
    {
        return in_array($attr, $this->hidden_attributes);
    }
}
