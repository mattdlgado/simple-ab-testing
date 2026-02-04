<?php
/**
 * Plugin Name: Simple A/B Testing
 * Plugin URI: https://github.com/mattdlgado/simple-ab-testing
 * Description: A/B/N testing plugin using HTML data attributes for Gutenberg
 * Version: 1.1.0
 * Author: Matt Delgado
 * Author URI: https://github.com/mattdlgado
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple-ab-testing
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include required files
require_once plugin_dir_path(__FILE__) . 'admin/class-database.php';
require_once plugin_dir_path(__FILE__) . 'admin/class-exporter.php';
require_once plugin_dir_path(__FILE__) . 'includes/ajax-handlers.php';

/**
 * Plugin activation hook
 */
function simple_ab_testing_activate() {
    Simple_AB_Testing_Database::create_table();
}
register_activation_hook(__FILE__, 'simple_ab_testing_activate');

/**
 * Enqueue A/B testing JavaScript
 */
function simple_ab_testing_enqueue_scripts() {
    wp_enqueue_script(
        'simple-ab-testing',
        plugin_dir_url(__FILE__) . 'assets/js/ab-testing.js',
        array(), // No dependencies
        '1.1.0',
        array(
            'in_footer' => true,
            'strategy' => 'defer' // Load with defer strategy for early execution
        )
    );
    
    // Localize script with AJAX URL and nonce
    wp_localize_script(
        'simple-ab-testing',
        'simpleABTesting',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('simple_ab_testing_nonce')
        )
    );
}
add_action('wp_enqueue_scripts', 'simple_ab_testing_enqueue_scripts', 1); // Priority 1 for early loading

/**
 * Register admin menu
 */
function simple_ab_testing_admin_menu() {
    add_menu_page(
        __('A/B Testing', 'simple-ab-testing'),           // Page title
        __('A/B Testing', 'simple-ab-testing'),           // Menu title
        'manage_options',                                  // Capability
        'simple-ab-testing',                              // Menu slug
        'simple_ab_testing_render_admin_page',            // Callback function
        'dashicons-chart-bar',                            // Icon
        30                                                 // Position
    );
}
add_action('admin_menu', 'simple_ab_testing_admin_menu');

/**
 * Render admin page
 */
function simple_ab_testing_render_admin_page() {
    require_once plugin_dir_path(__FILE__) . 'admin/admin-page.php';
}

/**
 * Register AJAX handlers for logged-in users
 */
add_action('wp_ajax_ab_testing_track_view', 'simple_ab_testing_handle_track_view');
add_action('wp_ajax_ab_testing_track_conversion', 'simple_ab_testing_handle_track_conversion');
add_action('wp_ajax_ab_testing_export_csv', 'simple_ab_testing_handle_export_csv');
add_action('wp_ajax_ab_testing_export_json', 'simple_ab_testing_handle_export_json');

/**
 * Register AJAX handlers for non-logged-in users
 */
add_action('wp_ajax_nopriv_ab_testing_track_view', 'simple_ab_testing_handle_track_view');
add_action('wp_ajax_nopriv_ab_testing_track_conversion', 'simple_ab_testing_handle_track_conversion');
