<?php

namespace mphbe\widgets;

abstract class AbstractCalendarWidget extends AbstractWidget
{
    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     */
    protected function render()
    {
		$action = isset($_REQUEST['action']) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';

        if ( in_array($action, array('elementor_render_widget', 'elementor_ajax', 'elementor')) ) {
            $script = 'jQuery(".mphb-calendar.mphb-datepick:not(.is-datepick)").each(function (_, element) {'
                . 'new MPHB.RoomTypeCalendar(jQuery(element));'
            . '});';

            if ($action == 'elementor') {
                $script = 'jQuery(document).ready(function () {' . $script . '});';
            }

            echo '<script>', $script, '</script>'; // phpcs:ignore
        }
    }
}
