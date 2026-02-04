<?php
/**
 * Plugin Name: Simple A/B Testing
 * Plugin URI: https://github.com/mattdlgado/simple-ab-testing
 * Description: A/B/N testing plugin using HTML data attributes for Gutenberg
 * Version: 1.0.0
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

/**
 * Enqueue A/B testing JavaScript
 */
function simple_ab_testing_enqueue_scripts() {
    wp_enqueue_script(
        'simple-ab-testing',
        plugin_dir_url(__FILE__) . 'assets/js/ab-testing.js',
        array(), // No dependencies
        '1.0.0',
        array(
            'in_footer' => true,
            'strategy' => 'defer' // Load with defer strategy for early execution
        )
    );
}
add_action('wp_enqueue_scripts', 'simple_ab_testing_enqueue_scripts', 1); // Priority 1 for early loading
