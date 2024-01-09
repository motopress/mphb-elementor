<?php

namespace mphbe\widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Plugin;
use MPHB\Views\LoopRoomTypeView;
use MPHB\Views\SingleRoomTypeView;

class AccommodationGalleryWidget extends AbstractAccommodationWidget
{
    private $gallery_params;
    private $is_slider = false;

    public function get_name()
    {
        return 'mphbe-accommodation-gallery';
    }

    public function get_title()
    {
        return __('Accommodation Type Gallery', 'mphb-elementor');
    }

    public function get_icon()
    {
        return 'eicon-gallery-group';
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

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'exclude' => ['custom'],
                'include' => [],
                'default' => '',
            ]
        );

        $this->add_control(
            'is_slider',
            [
                'label' => esc_html__('Display as slider', 'mphb-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
                'description' => esc_html__('Check it out on the frontend once applied.', 'mphb-elementor')
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'mphb-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 4,
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label' => esc_html__('Link to', 'mphb-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'mphb-elementor'),
                    'none' => esc_html__('None', 'mphb-elementor'),
                    'file' => esc_html__('File', 'mphb-elementor'),
                ],
            ]
        );

        $this->add_control(
            'is_lightbox',
            [
                'label' => esc_html__('Open in lightbox', 'mphb-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'is_slider!' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     */
    protected function mphb_render($settings)
    {

        $this->gallery_params = array(
            'link' => $settings['link_to'],
            'columns' => $settings['columns'],
            'size' => $settings['image_size'],
            'lightbox' => $settings['is_lightbox']
        );

        $this->is_slider = 'yes' == $settings['is_slider'] && !Plugin::$instance->editor->is_edit_mode();

        $current_room_type = MPHB()->getCurrentRoomType();

        MPHB()->setCurrentRoomType($this->accommodation_id);
        $this->apply_gallery_params();

        if ($this->is_slider) {
            LoopRoomTypeView::renderGallery();
        } else {
            SingleRoomTypeView::renderGallery();
        }

        $this->restore_gallery_params();
        MPHB()->setCurrentRoomType($current_room_type ? $current_room_type->getId() : get_the_ID());
    }


    private function apply_gallery_params()
    {
        if ($this->is_slider) {
            add_filter('mphb_loop_room_type_gallery_main_slider_image_link', array($this, 'filter_gallery_link'));
            add_filter('mphb_loop_room_type_gallery_main_slider_columns', array($this, 'filter_gallery_columns'));
            add_filter('mphb_loop_room_type_gallery_main_slider_image_size', array($this, 'filter_gallery_image_size'));
            add_filter('mphb_loop_room_type_gallery_use_nav_slider', array($this, 'filter_gallery_nav_slider'));

            add_action('mphb_render_loop_room_type_before_gallery', array($this, 'remove_default_slider_wrapper'), 1);
            add_action('mphb_render_loop_room_type_before_gallery', array($this, 'render_slider_wrapper_open'));
            add_action('mphb_render_loop_room_type_after_gallery', array($this, 'render_slider_wrapper_close'));
            add_filter('mphb_loop_room_type_gallery_main_slider_wrapper_class', array($this, 'filter_slider_classes'));
            add_filter('mphb_loop_room_type_gallery_main_slider_flexslider_options', array($this, 'filter_slider_attributes'));
        } else {
            add_filter('mphb_single_room_type_gallery_image_link', array($this, 'filter_gallery_link'));
            add_filter('mphb_single_room_type_gallery_columns', array($this, 'filter_gallery_columns'));
            add_filter('mphb_single_room_type_gallery_image_size', array($this, 'filter_gallery_image_size'));
            add_filter('mphb_single_room_type_gallery_use_magnific', array($this, 'filter_gallery_lightbox'));
        }
    }

    private function restore_gallery_params()
    {
        if ($this->is_slider) {
            remove_filter('mphb_loop_room_type_gallery_main_slider_image_link', array($this, 'filter_gallery_link'));
            remove_filter('mphb_loop_room_type_gallery_main_slider_columns', array($this, 'filter_gallery_columns'));
            remove_filter('mphb_loop_room_type_gallery_main_slider_image_size', array($this, 'filter_gallery_image_size'));
            remove_filter('mphb_loop_room_type_gallery_use_nav_slider', array($this, 'filter_gallery_nav_slider'));

            remove_action('mphb_render_loop_room_type_before_gallery', array($this, 'remove_default_slider_wrapper'), 1);
            remove_action('mphb_render_loop_room_type_before_gallery', array($this, 'render_slider_wrapper_open'));
            remove_action('mphb_render_loop_room_type_after_gallery', array($this, 'render_slider_wrapper_close'));
            remove_filter('mphb_loop_room_type_gallery_main_slider_wrapper_class', array($this, 'filter_slider_classes'));
            remove_filter('mphb_loop_room_type_gallery_main_slider_flexslider_options', array($this, 'filter_slider_attributes'));
        } else {
            remove_filter('mphb_single_room_type_gallery_image_link', array($this, 'filter_gallery_link'));
            remove_filter('mphb_single_room_type_gallery_columns', array($this, 'filter_gallery_columns'));
            remove_filter('mphb_single_room_type_gallery_image_size', array($this, 'filter_gallery_image_size'));
            remove_filter('mphb_single_room_type_gallery_use_magnific', array($this, 'filter_gallery_lightbox'));
        }
    }

    public function filter_gallery_link($link)
    {
        if ($this->gallery_params['link']) {
            $link = $this->gallery_params['link'];
        }

        if ($this->is_slider) {
            $link = 'none';
        }

        return $link;
    }

    public function filter_gallery_columns($columns)
    {
        if ($this->gallery_params['columns']) {
            $columns = $this->gallery_params['columns'];
        }

        return $columns;
    }

    public function filter_gallery_image_size($size)
    {
        if ($this->gallery_params['size']) {
            return $this->gallery_params['size'];
        }

        return $size;
    }

    public function filter_gallery_lightbox($lightbox)
    {
        if ($this->gallery_params['lightbox']) {
            return $this->gallery_params['lightbox'] == 'yes';
        }

        return $lightbox;
    }

    public function filter_gallery_nav_slider()
    {
        return false;
    }

    public function remove_default_slider_wrapper()
    {
        remove_action('mphb_render_loop_room_type_before_gallery', array('\MPHB\Views\LoopRoomTypeView', '_renderImagesWrapperOpen'), 10);
        remove_action('mphb_render_loop_room_type_after_gallery', array('\MPHB\Views\LoopRoomTypeView', '_renderImagesWrapperClose'), 20);
    }

    public function filter_slider_classes($class)
    {
        return 'mphb-flexslider-gallery-wrapper';
    }

    public function render_slider_wrapper_open()
    {
        ?>
        <div class="mphb-room-type-gallery-wrapper mphb-single-room-type-gallery-wrapper">
        <?php
    }

    public function render_slider_wrapper_close()
    {
        ?>
        </div>
        <?php
    }

    public function filter_slider_attributes($atts)
    {
        $atts['minItems'] = 1;
        $atts['maxItems'] = (int)$this->gallery_params['columns'] ? (int)$this->gallery_params['columns'] : 1;
        $atts['move'] = 1;

        $atts['itemWidth'] = floor(100 / $atts['maxItems']);

        return $atts;
    }
}
