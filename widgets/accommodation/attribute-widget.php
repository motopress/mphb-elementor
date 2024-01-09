<?php

namespace mphbe\widgets;

use Elementor\Controls_Manager;
use MPHB\Views\SingleRoomTypeView;

class AccommodationAttributeWidget extends AbstractAccommodationWidget
{
    private $show_label = true;

    public function get_name()
    {
        return 'mphbe-accommodation-attribute';
    }

    public function get_title()
    {
        return __('Accommodation Type Attribute', 'mphb-elementor');
    }

    public function get_icon()
    {
        return 'eicon-meta-data';
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

        $this->add_accommodation_attribute_control();

        $this->add_control(
            'show_attribute_label',
            [
                'label' => esc_html__('Show label', 'mphb-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function mphb_render($settings)
    {

        $attribute = $settings['attribute'];
        $this->show_label = $settings['show_attribute_label'] == 'yes';

        $current_accommodation = MPHB()->getCurrentRoomType();

        MPHB()->setCurrentRoomType($this->accommodation_id);

        $this->render_attribute($attribute);

        MPHB()->setCurrentRoomType($current_accommodation ? $current_accommodation->getId() : get_the_ID());
    }

    private function render_attribute($attribute)
    {
        $custom_attribute = '';

        if (in_array($attribute, array_keys($this->custom_attributes))) {
            $custom_attribute = $attribute;
            $attribute = 'custom';
        }

        do_action('mphb-templates/blocks/attribute/before', $attribute, $custom_attribute);

        switch ($attribute):
            case 'adults':
                $this->beforeAdultsRender();
                ?><div class="mphb-single-room-type-attribute mphb-room-type-adults-capacity"><?php
                SingleRoomTypeView::renderAdults();
                ?></div><?php
                $this->afterAdultsRender();
                break;
            case 'children':
                $this->beforeChildrenRender();
                ?><div class="mphb-single-room-type-attribute mphb-room-type-children-capacity"><?php
                SingleRoomTypeView::renderChildren();
                ?></div><?php
                $this->afterChildrenRender();
                break;
            case 'capacity':
                $this->beforeCapacityRender();
                ?><div class="mphb-single-room-type-attribute mphb-room-type-total-capacity"><?php
                SingleRoomTypeView::renderTotalCapacity();
                ?></div><?php
                $this->afterCapacityRender();
                break;
            case 'amenities':
                $this->beforeFacilitiesRender();
                ?><div class="mphb-single-room-type-attribute mphb-room-type-facilities"><?php
                SingleRoomTypeView::renderFacilities();
                ?></div><?php
                $this->afterFacilitiesRender();
                break;
            case 'view':
                $this->beforeViewRender();
                ?><div class="mphb-single-room-type-attribute mphb-room-type-view"><?php
                SingleRoomTypeView::renderView();
                ?></div><?php
                $this->afterViewRender();
                break;
            case 'size':
                $this->beforeSizeRender();
                ?><div class="mphb-single-room-type-attribute mphb-room-type-size"><?php
                SingleRoomTypeView::renderSize();
                ?></div><?php
                $this->afterSizeRender();
                break;
            case 'bed-types':
                $this->beforeBedTypesRender();
                ?><div class="mphb-single-room-type-attribute mphb-room-type-bed-type"><?php
                SingleRoomTypeView::renderBedType();
                ?></div><?php
                $this->afterBedTypesRender();
                break;
            case 'categories':
                $this->beforeCategoriesRender();
                ?><div class="mphb-single-room-type-attribute mphb-room-type-categories"><?php
                SingleRoomTypeView::renderCategories();
                ?></div><?php
                $this->afterCategoriesRender();
                break;
            case 'custom':
                $this->beforeCustomAttribute($custom_attribute);
                ?><div class="mphb-single-room-type-attribute <?php echo esc_attr('mphb-room-type-' . $custom_attribute . ' mphb-room-type-custom-attribute'); ?>"><?php
                SingleRoomTypeView::renderCustomAttributes();
                ?></div><?php
                $this->afterCustomAttribute();
                break;
            default:
                ?><div class="mphb-single-room-type-attribute mphb-room-type-undefined-attribute"><?php
                esc_html_e('Please choose an attribute from available ones.', 'mphb-elementor');
                ?></div><?php
                break;
        endswitch;

        do_action('mphb-templates/blocks/attribute/after', $attribute, $custom_attribute);
    }

    private function beforeAdultsRender()
    {
        remove_action('mphb_render_single_room_type_before_adults', array('\MPHB\Views\SingleRoomTypeView', '_renderAdultsListItemOpen'), 10);
        remove_action('mphb_render_single_room_type_after_adults', array('\MPHB\Views\SingleRoomTypeView', '_renderAdultsListItemClose'), 20);

        if (!$this->show_label) {
            remove_action('mphb_render_single_room_type_before_adults', array('\MPHB\Views\SingleRoomTypeView', '_renderAdultsTitle'), 20);
        }
    }

    private function afterAdultsRender()
    {
        add_action('mphb_render_single_room_type_before_adults', array('\MPHB\Views\SingleRoomTypeView', '_renderAdultsListItemOpen'), 10);
        add_action('mphb_render_single_room_type_after_adults', array('\MPHB\Views\SingleRoomTypeView', '_renderAdultsListItemClose'), 20);

        if (!$this->show_label) {
            add_action('mphb_render_single_room_type_before_adults', array('\MPHB\Views\SingleRoomTypeView', '_renderAdultsTitle'), 20);
        }
    }

    private function beforeChildrenRender()
    {
        remove_action('mphb_render_single_room_type_before_children', array('\MPHB\Views\SingleRoomTypeView', '_renderChildrenListItemOpen'), 10);
        remove_action('mphb_render_single_room_type_after_children', array('\MPHB\Views\SingleRoomTypeView', '_renderChildrenListItemClose'), 20);

        if (!$this->show_label) {
            remove_action('mphb_render_single_room_type_before_children', array('\MPHB\Views\SingleRoomTypeView', '_renderChildrenTitle'), 20);
        }
    }

    private function afterChildrenRender()
    {
        add_action('mphb_render_single_room_type_before_children', array('\MPHB\Views\SingleRoomTypeView', '_renderChildrenListItemOpen'), 10);
        add_action('mphb_render_single_room_type_after_children', array('\MPHB\Views\SingleRoomTypeView', '_renderChildrenListItemClose'), 20);

        if (!$this->show_label) {
            add_action('mphb_render_single_room_type_before_children', array('\MPHB\Views\SingleRoomTypeView', '_renderChildrenTitle'), 20);
        }
    }

    private function beforeCapacityRender()
    {
        remove_action('mphb_render_single_room_type_before_total_capacity', array('\MPHB\Views\SingleRoomTypeView', '_renderTotalCapacityListItemOpen'), 10);
        remove_action('mphb_render_single_room_type_after_total_capacity', array('\MPHB\Views\SingleRoomTypeView', '_renderTotalCapacityListItemClose'), 20);

        if (!$this->show_label) {
            remove_action('mphb_render_single_room_type_before_total_capacity', array('\MPHB\Views\SingleRoomTypeView', '_renderTotalCapacityTitle'), 20);
        }
    }

    private function afterCapacityRender()
    {
        add_action('mphb_render_single_room_type_before_total_capacity', array('\MPHB\Views\SingleRoomTypeView', '_renderTotalCapacityListItemOpen'), 10);
        add_action('mphb_render_single_room_type_after_total_capacity', array('\MPHB\Views\SingleRoomTypeView', '_renderTotalCapacityListItemClose'), 20);

        if (!$this->show_label) {
            add_action('mphb_render_single_room_type_before_total_capacity', array('\MPHB\Views\SingleRoomTypeView', '_renderTotalCapacityTitle'), 20);
        }
    }

    private function beforeFacilitiesRender()
    {
        remove_action('mphb_render_single_room_type_before_facilities', array('\MPHB\Views\SingleRoomTypeView', '_renderFacilitiesListItemOpen'), 10);
        remove_action('mphb_render_single_room_type_after_facilities', array('\MPHB\Views\SingleRoomTypeView', '_renderFacilitiesListItemClose'), 20);

        if (!$this->show_label) {
            remove_action('mphb_render_single_room_type_before_facilities', array('\MPHB\Views\SingleRoomTypeView', '_renderFacilitiesTitle'), 20);
        }
    }

    private function afterFacilitiesRender()
    {
        add_action('mphb_render_single_room_type_before_facilities', array('\MPHB\Views\SingleRoomTypeView', '_renderFacilitiesListItemOpen'), 10);
        add_action('mphb_render_single_room_type_after_facilities', array('\MPHB\Views\SingleRoomTypeView', '_renderFacilitiesListItemClose'), 20);

        if (!$this->show_label) {
            add_action('mphb_render_single_room_type_before_facilities', array('\MPHB\Views\SingleRoomTypeView', '_renderFacilitiesTitle'), 20);
        }
    }

    private function beforeViewRender()
    {
        remove_action('mphb_render_single_room_type_before_view', array('\MPHB\Views\SingleRoomTypeView', '_renderViewListItemOpen'), 10);
        remove_action('mphb_render_single_room_type_after_view', array('\MPHB\Views\SingleRoomTypeView', '_renderViewListItemClose'), 20);

        if (!$this->show_label) {
            remove_action('mphb_render_single_room_type_before_view', array('\MPHB\Views\SingleRoomTypeView', '_renderViewTitle'), 20);
        }
    }

    private function afterViewRender()
    {
        add_action('mphb_render_single_room_type_before_view', array('\MPHB\Views\SingleRoomTypeView', '_renderViewListItemOpen'), 10);
        add_action('mphb_render_single_room_type_after_view', array('\MPHB\Views\SingleRoomTypeView', '_renderViewListItemClose'), 20);

        if (!$this->show_label) {
            add_action('mphb_render_single_room_type_before_view', array('\MPHB\Views\SingleRoomTypeView', '_renderViewTitle'), 20);
        }
    }

    private function beforeSizeRender()
    {
        remove_action('mphb_render_single_room_type_before_size', array('\MPHB\Views\SingleRoomTypeView', '_renderSizeListItemOpen'), 10);
        remove_action('mphb_render_single_room_type_after_size', array('\MPHB\Views\SingleRoomTypeView', '_renderSizeListItemClose'), 20);

        if (!$this->show_label) {
            remove_action('mphb_render_single_room_type_before_size', array('\MPHB\Views\SingleRoomTypeView', '_renderSizeTitle'), 20);
        }
    }

    private function afterSizeRender()
    {
        add_action('mphb_render_single_room_type_before_size', array('\MPHB\Views\SingleRoomTypeView', '_renderSizeListItemOpen'), 10);
        add_action('mphb_render_single_room_type_after_size', array('\MPHB\Views\SingleRoomTypeView', '_renderSizeListItemClose'), 20);

        if (!$this->show_label) {
            add_action('mphb_render_single_room_type_before_size', array('\MPHB\Views\SingleRoomTypeView', '_renderSizeTitle'), 20);
        }
    }

    private function beforeBedTypesRender()
    {
        remove_action('mphb_render_single_room_type_before_bed_type', array('\MPHB\Views\SingleRoomTypeView', '_renderBedTypeListItemOpen'), 10);
        remove_action('mphb_render_single_room_type_after_bed_type', array('\MPHB\Views\SingleRoomTypeView', '_renderBedTypeListItemClose'), 20);

        if (!$this->show_label) {
            remove_action('mphb_render_single_room_type_before_bed_type', array('\MPHB\Views\SingleRoomTypeView', '_renderBedTypeTitle'), 20);
        }
    }

    private function afterBedTypesRender()
    {
        add_action('mphb_render_single_room_type_before_bed_type', array('\MPHB\Views\SingleRoomTypeView', '_renderBedTypeListItemOpen'), 10);
        add_action('mphb_render_single_room_type_after_bed_type', array('\MPHB\Views\SingleRoomTypeView', '_renderBedTypeListItemClose'), 20);

        if (!$this->show_label) {
            add_action('mphb_render_single_room_type_before_bed_type', array('\MPHB\Views\SingleRoomTypeView', '_renderBedTypeTitle'), 20);
        }
    }

    private function beforeCategoriesRender()
    {
        remove_action('mphb_render_single_room_type_before_categories', array('\MPHB\Views\SingleRoomTypeView', '_renderCategoriesListItemOpen'), 10);
        remove_action('mphb_render_single_room_type_after_categories', array('\MPHB\Views\SingleRoomTypeView', '_renderCategoriesListItemClose'), 20);

        if (!$this->show_label) {
            remove_action('mphb_render_single_room_type_before_categories', array('\MPHB\Views\SingleRoomTypeView', '_renderCategoriesTitle'), 20);
        }
    }

    private function afterCategoriesRender()
    {
        add_action('mphb_render_single_room_type_before_categories', array('\MPHB\Views\SingleRoomTypeView', '_renderCategoriesListItemOpen'), 10);
        add_action('mphb_render_single_room_type_after_categories', array('\MPHB\Views\SingleRoomTypeView', '_renderCategoriesListItemClose'), 20);

        if (!$this->show_label) {
            add_action('mphb_render_single_room_type_before_categories', array('\MPHB\Views\SingleRoomTypeView', '_renderCategoriesTitle'), 20);
        }
    }

    private function beforeCustomAttribute($custom_attribute)
    {
        remove_action('mphb_render_single_room_type_before_custom_attribute', array('\MPHB\Views\SingleRoomTypeView', '_renderCustomAttributesListItemOpen'), 10, 1);
        remove_action('mphb_render_single_room_type_after_custom_attribute', array('\MPHB\Views\SingleRoomTypeView', '_renderCustomAttributesListItemClose'), 20);

        if (!$this->show_label) {
            remove_action('mphb_render_single_room_type_before_custom_attribute', array('\MPHB\Views\SingleRoomTypeView', '_renderCustomAttributesTitle'), 20, 1);
        }

        global $mphbAttributes;

        foreach ($mphbAttributes as $key => $attribute) {
            if ($key != $custom_attribute) {
                $mphbAttributes[$key]['visible'] = false;
            }
        }
    }

    private function afterCustomAttribute()
    {
        add_action('mphb_render_single_room_type_before_custom_attribute', array('\MPHB\Views\SingleRoomTypeView', '_renderCustomAttributesListItemOpen'), 10, 1);
        add_action('mphb_render_single_room_type_after_custom_attribute', array('\MPHB\Views\SingleRoomTypeView', '_renderCustomAttributesListItemClose'), 20);

        if (!$this->show_label) {
            add_action('mphb_render_single_room_type_before_custom_attribute', array('\MPHB\Views\SingleRoomTypeView', '_renderCustomAttributesTitle'), 20, 1);
        }

        global $mphbAttributes;

        $mphbAttributes = $this->custom_attributes;
    }
}
