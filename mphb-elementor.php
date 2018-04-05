<?php

/*
 * Plugin Name: Hotel Booking Shortcodes for Elementor
 * Plugin URI: https://motopress.com/
 * Description: Manage hotel booking shortcodes in Elementor builder.
 * Version: 1.0.0
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
    require __DIR__ . '/plugin.php';
    MPHBElementor::create();
});