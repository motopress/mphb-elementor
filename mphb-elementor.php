<?php

/*
 * Plugin Name: Hotel Booking & Elementor Integration
 * Plugin URI: https://motopress.com/products/hotel-booking-elementor-integration/
 * Description: Manage hotel booking shortcodes in Elementor builder.
 * Version: 1.1.4
 * Author: MotoPress
 * Author URI: https://motopress.com/
 * License: GPLv2 or later
 * Text Domain: mphb-elementor
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit('Press Enter to proceed...');
}

add_action('plugins_loaded', function () {
    define( 'MPHB_ELEMENTOR_PLUGIN_FILE', __FILE__ );

    require __DIR__ . '/plugin.php';
    MPHBElementor::create();
});
