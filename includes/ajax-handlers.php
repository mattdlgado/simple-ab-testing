<?php
/**
 * AJAX handlers for A/B Testing
 *
 * @package SimpleABTesting
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle view tracking AJAX request
 */
function simple_ab_testing_handle_track_view() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'simple_ab_testing_nonce')) {
        wp_send_json_error(array('message' => 'Invalid nonce'), 403);
        return;
    }
    
    // Get and validate parameters
    $test_name = isset($_POST['test_name']) ? sanitize_text_field($_POST['test_name']) : '';
    $variant = isset($_POST['variant']) ? sanitize_text_field($_POST['variant']) : '';
    
    if (empty($test_name) || empty($variant)) {
        wp_send_json_error(array('message' => 'Missing required parameters'), 400);
        return;
    }
    
    // Track the view
    $success = Simple_AB_Testing_Database::track_view($test_name, $variant);
    
    if ($success) {
        wp_send_json_success(array(
            'message' => 'View tracked successfully',
            'test_name' => $test_name,
            'variant' => $variant
        ));
    } else {
        wp_send_json_error(array('message' => 'Failed to track view'), 500);
    }
}

/**
 * Handle conversion tracking AJAX request
 */
function simple_ab_testing_handle_track_conversion() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'simple_ab_testing_nonce')) {
        wp_send_json_error(array('message' => 'Invalid nonce'), 403);
        return;
    }
    
    // Get and validate parameters
    $test_name = isset($_POST['test_name']) ? sanitize_text_field($_POST['test_name']) : '';
    $variant = isset($_POST['variant']) ? sanitize_text_field($_POST['variant']) : '';
    
    if (empty($test_name) || empty($variant)) {
        wp_send_json_error(array('message' => 'Missing required parameters'), 400);
        return;
    }
    
    // Track the conversion
    $success = Simple_AB_Testing_Database::track_conversion($test_name, $variant);
    
    if ($success) {
        wp_send_json_success(array(
            'message' => 'Conversion tracked successfully',
            'test_name' => $test_name,
            'variant' => $variant
        ));
    } else {
        wp_send_json_error(array('message' => 'Failed to track conversion'), 500);
    }
}

/**
 * Handle CSV export AJAX request
 */
function simple_ab_testing_handle_export_csv() {
    // Check nonce
    if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'simple_ab_testing_export')) {
        wp_die('Invalid nonce', 'Security Error', array('response' => 403));
        return;
    }
    
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access', 'Security Error', array('response' => 403));
        return;
    }
    
    // Export CSV
    Simple_AB_Testing_Exporter::export_csv();
}

/**
 * Handle JSON export AJAX request
 */
function simple_ab_testing_handle_export_json() {
    // Check nonce
    if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'simple_ab_testing_export')) {
        wp_die('Invalid nonce', 'Security Error', array('response' => 403));
        return;
    }
    
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access', 'Security Error', array('response' => 403));
        return;
    }
    
    // Export JSON
    Simple_AB_Testing_Exporter::export_json();
}
