<?php
/**
 * Exporter class for A/B Testing statistics
 *
 * @package SimpleABTesting
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Simple_AB_Testing_Exporter {
    
    /**
     * Export statistics as CSV
     */
    public static function export_csv() {
        // Get all statistics
        $stats = Simple_AB_Testing_Database::get_all_stats();
        
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=ab-testing-stats-' . date('Y-m-d-H-i-s') . '.csv');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Add CSV headers
        fputcsv($output, array(
            'Test Name',
            'Variant',
            'Views',
            'Conversions',
            'Conversion Rate (%)'
        ));
        
        // Add data rows
        foreach ($stats as $stat) {
            $conversion_rate = 0;
            if ($stat['views'] > 0) {
                $conversion_rate = round(($stat['conversions'] / $stat['views']) * 100, 2);
            }
            
            fputcsv($output, array(
                $stat['test_name'],
                $stat['variant'],
                $stat['views'],
                $stat['conversions'],
                $conversion_rate
            ));
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Export statistics as JSON
     */
    public static function export_json() {
        // Get all statistics
        $stats = Simple_AB_Testing_Database::get_all_stats();
        
        // Group by test name
        $grouped_stats = array();
        foreach ($stats as $stat) {
            $test_name = $stat['test_name'];
            
            if (!isset($grouped_stats[$test_name])) {
                $grouped_stats[$test_name] = array(
                    'test_name' => $test_name,
                    'variants' => array(),
                    'totals' => array(
                        'views' => 0,
                        'conversions' => 0,
                        'conversion_rate' => 0
                    )
                );
            }
            
            // Calculate conversion rate
            $conversion_rate = 0;
            if ($stat['views'] > 0) {
                $conversion_rate = round(($stat['conversions'] / $stat['views']) * 100, 2);
            }
            
            // Add variant data
            $grouped_stats[$test_name]['variants'][] = array(
                'variant' => $stat['variant'],
                'views' => (int)$stat['views'],
                'conversions' => (int)$stat['conversions'],
                'conversion_rate' => $conversion_rate,
                'created_at' => $stat['created_at'],
                'updated_at' => $stat['updated_at']
            );
            
            // Update totals
            $grouped_stats[$test_name]['totals']['views'] += (int)$stat['views'];
            $grouped_stats[$test_name]['totals']['conversions'] += (int)$stat['conversions'];
        }
        
        // Calculate total conversion rates
        foreach ($grouped_stats as $test_name => $test_data) {
            if ($test_data['totals']['views'] > 0) {
                $grouped_stats[$test_name]['totals']['conversion_rate'] = round(
                    ($test_data['totals']['conversions'] / $test_data['totals']['views']) * 100,
                    2
                );
            }
        }
        
        // Convert to array for JSON output
        $output_data = array(
            'exported_at' => current_time('mysql'),
            'tests' => array_values($grouped_stats)
        );
        
        // Set headers for JSON download
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=ab-testing-stats-' . date('Y-m-d-H-i-s') . '.json');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        echo json_encode($output_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
