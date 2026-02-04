<?php
/**
 * Database operations class for A/B Testing statistics
 *
 * @package SimpleABTesting
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Simple_AB_Testing_Database {
    
    /**
     * Create the database table for A/B testing statistics
     */
    public static function create_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'ab_testing_stats';
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            test_name varchar(255) NOT NULL,
            variant varchar(50) NOT NULL,
            views bigint(20) NOT NULL DEFAULT 0,
            conversions bigint(20) NOT NULL DEFAULT 0,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY test_variant (test_name, variant),
            KEY test_name (test_name)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Track a view for a specific test and variant
     *
     * @param string $test_name Test name
     * @param string $variant Variant name (A, B, C, etc.)
     * @return bool Success status
     */
    public static function track_view($test_name, $variant) {
        global $wpdb;
        
        // Sanitize inputs
        $test_name = sanitize_text_field($test_name);
        $variant = sanitize_text_field($variant);
        
        if (empty($test_name) || empty($variant)) {
            return false;
        }
        
        $table_name = $wpdb->prefix . 'ab_testing_stats';
        
        // Use INSERT ON DUPLICATE KEY UPDATE
        $result = $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO $table_name (test_name, variant, views, conversions, created_at, updated_at)
                VALUES (%s, %s, 1, 0, NOW(), NOW())
                ON DUPLICATE KEY UPDATE views = views + 1, updated_at = NOW()",
                $test_name,
                $variant
            )
        );
        
        return $result !== false;
    }
    
    /**
     * Track a conversion for a specific test and variant
     *
     * @param string $test_name Test name
     * @param string $variant Variant name (A, B, C, etc.)
     * @return bool Success status
     */
    public static function track_conversion($test_name, $variant) {
        global $wpdb;
        
        // Sanitize inputs
        $test_name = sanitize_text_field($test_name);
        $variant = sanitize_text_field($variant);
        
        if (empty($test_name) || empty($variant)) {
            return false;
        }
        
        $table_name = $wpdb->prefix . 'ab_testing_stats';
        
        // Use INSERT ON DUPLICATE KEY UPDATE
        $result = $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO $table_name (test_name, variant, views, conversions, created_at, updated_at)
                VALUES (%s, %s, 0, 1, NOW(), NOW())
                ON DUPLICATE KEY UPDATE conversions = conversions + 1, updated_at = NOW()",
                $test_name,
                $variant
            )
        );
        
        return $result !== false;
    }
    
    /**
     * Get all statistics from the database
     *
     * @return array Array of statistics objects
     */
    public static function get_all_stats() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'ab_testing_stats';
        
        $results = $wpdb->get_results(
            "SELECT * FROM $table_name ORDER BY test_name ASC, variant ASC",
            ARRAY_A
        );
        
        return $results ? $results : array();
    }
    
    /**
     * Get statistics for a specific test
     *
     * @param string $test_name Test name
     * @return array Array of statistics objects for the test
     */
    public static function get_stats_by_test($test_name) {
        global $wpdb;
        
        $test_name = sanitize_text_field($test_name);
        $table_name = $wpdb->prefix . 'ab_testing_stats';
        
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE test_name = %s ORDER BY variant ASC",
                $test_name
            ),
            ARRAY_A
        );
        
        return $results ? $results : array();
    }
}
