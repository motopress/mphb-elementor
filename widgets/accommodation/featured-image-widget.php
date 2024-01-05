<?php

namespace mphbe\widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class AccommodationFeaturedImageWidget extends AbstractAccommodationWidget
{
    public function get_name()
    {
        return 'mphbe-accommodation-featured-image';
    }

    public function get_title()
    {
        return __('Accommodation Type Featured Image', 'mphb-elementor');
    }

    public function get_icon()
    {
        return 'eicon-featured-image';
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

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'featured_image',
                'exclude' => ['custom'],
                'include' => [],
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

        $linkToPost = $settings['link_to_post'] === 'yes';
        $size = isset($settings['featured_image_size']) ? $settings['featured_image_size'] : null;

        ?>
        <div class="mphb-single-room-type-thumbnail">
            <?php if ($linkToPost) : ?>
                <a href="<?php echo esc_url(get_permalink($this->accommodation_id)); ?>">
            <?php endif; ?>
                <?php echo mphb_tmpl_get_room_type_image($this->accommodation_id, $size); ?>
            <?php if ($linkToPost) : ?>
                </a>
            <?php endif; ?>
        </div>
        <?php

    }
}
