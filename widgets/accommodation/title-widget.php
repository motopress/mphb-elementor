<?php

namespace mphbe\widgets;

use Elementor\Controls_Manager;
use MPHB\Views\SingleRoomTypeView;

class AccommodationTitleWidget extends AbstractAccommodationWidget
{

    private $id = false;
    private $link_to_post = false;

    public function get_name()
    {
        return 'mphbe-accommodation-title';
    }

    public function get_title()
    {
        return __('Accommodation Type Title', 'mphb-elementor');
    }

    public function get_icon()
    {
        return 'eicon-post-title';
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

        $this->add_control(
            'link_to_post',
            [
                'label' => esc_html__('Link to post', 'mphb-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
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

        $this->link_to_post = $settings['link_to_post'] === 'yes';
        $this->id = isset($this->accommodation_id) ? $this->accommodation_id : false;

        $this->apply_title_params();

        if ($this->id) {
            $query = new \WP_Query(array(
                'p' => $this->id,
                'post_type' => 'mphb_room_type',
            ));

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    SingleRoomTypeView::renderTitle();
                }
            }
            wp_reset_postdata();
        } else {
            SingleRoomTypeView::renderTitle();
        }

        $this->restore_title_params();
    }

    private function apply_title_params()
    {
        if ($this->link_to_post) {
            add_action('mphb_render_single_room_type_before_title', array($this, 'render_link_open'), 15);
            add_action('mphb_render_single_room_type_after_title', array($this, 'render_link_close'), 5);
        }
    }

    private function restore_title_params()
    {
        if ($this->link_to_post) {
            remove_action('mphb_render_single_room_type_before_title', array($this, 'render_link_open'), 15);
            remove_action('mphb_render_single_room_type_after_title', array($this, 'render_link_close'), 5);
        }
    }

    public function render_link_open()
    {
        $permalink = get_permalink($this->id);
        ?>
        <a href="<?php echo esc_url($permalink); ?>">
        <?php
    }

    public function render_link_close()
    {
        ?>
        </a>
        <?php
    }
}
