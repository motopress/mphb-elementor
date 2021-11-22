<?php

namespace mphbe\widgets;

abstract class AbstractGalleryWidget extends AbstractWidget
{
    /**
     * Retrieve the list of scripts the counter widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     */
    public function get_script_depends()
    {
        return array('mphb-flexslider');
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     */
    protected function render()
    {
        $action = isset($_REQUEST['action']) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';

        if ( in_array($action, array('elementor_render_widget', 'elementor_ajax', 'elementor')) ) {
            $script = 'jQuery(".mphb-flexslider-gallery-wrapper:not(.mphb-flexslider)").each(function (index, wrapper) {'
                . 'var gallery = new MPHB.FlexsliderGallery(wrapper);'
                . 'gallery.initSliders();'
            . '});';

            if ($action == 'elementor') {
                $script = 'jQuery(document).ready(function () {' . $script . '});';
            }

            echo '<script>', $script, '</script>'; // phpcs:ignore
        }
    }
}
