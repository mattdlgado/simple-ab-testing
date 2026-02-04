<?php
/**
 * Admin page for A/B Testing statistics
 *
 * @package SimpleABTesting
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check user capabilities
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

// Get all statistics
$stats = Simple_AB_Testing_Database::get_all_stats();

// Group statistics by test name
$grouped_stats = array();
foreach ($stats as $stat) {
    $test_name = $stat['test_name'];
    
    if (!isset($grouped_stats[$test_name])) {
        $grouped_stats[$test_name] = array(
            'variants' => array(),
            'totals' => array(
                'views' => 0,
                'conversions' => 0
            )
        );
    }
    
    $grouped_stats[$test_name]['variants'][] = $stat;
    $grouped_stats[$test_name]['totals']['views'] += (int)$stat['views'];
    $grouped_stats[$test_name]['totals']['conversions'] += (int)$stat['conversions'];
}

// Generate export nonce
$export_nonce = wp_create_nonce('simple_ab_testing_export');
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <?php if (empty($stats)): ?>
        <div class="notice notice-info">
            <p><?php _e('No hay datos de pruebas A/B todavía. Comienza agregando pruebas A/B a tu sitio y los datos comenzarán a aparecer aquí.', 'simple-ab-testing'); ?></p>
        </div>
    <?php else: ?>
        <div style="margin: 20px 0;">
            <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=ab_testing_export_csv&nonce=' . $export_nonce)); ?>" 
               class="button button-primary">
                <span class="dashicons dashicons-download" style="vertical-align: middle; margin-top: 3px;"></span>
                <?php _e('Exportar CSV', 'simple-ab-testing'); ?>
            </a>
            
            <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=ab_testing_export_json&nonce=' . $export_nonce)); ?>" 
               class="button button-secondary" style="margin-left: 10px;">
                <span class="dashicons dashicons-media-code" style="vertical-align: middle; margin-top: 3px;"></span>
                <?php _e('Exportar JSON', 'simple-ab-testing'); ?>
            </a>
        </div>
        
        <?php foreach ($grouped_stats as $test_name => $test_data): ?>
            <div style="margin-bottom: 40px;">
                <h2><?php echo esc_html($test_name); ?></h2>
                
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 20%;"><?php _e('Variante', 'simple-ab-testing'); ?></th>
                            <th style="width: 20%;"><?php _e('Vistas', 'simple-ab-testing'); ?></th>
                            <th style="width: 20%;"><?php _e('Conversiones', 'simple-ab-testing'); ?></th>
                            <th style="width: 20%;"><?php _e('Tasa de Conversión', 'simple-ab-testing'); ?></th>
                            <th style="width: 20%;"><?php _e('Última Actualización', 'simple-ab-testing'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($test_data['variants'] as $variant): ?>
                            <?php
                            $views = (int)$variant['views'];
                            $conversions = (int)$variant['conversions'];
                            $conversion_rate = $views > 0 ? round(($conversions / $views) * 100, 2) : 0;
                            ?>
                            <tr>
                                <td><strong><?php echo esc_html($variant['variant']); ?></strong></td>
                                <td><?php echo number_format_i18n($views); ?></td>
                                <td><?php echo number_format_i18n($conversions); ?></td>
                                <td>
                                    <strong style="color: <?php echo $conversion_rate > 0 ? '#46b450' : '#999'; ?>;">
                                        <?php echo esc_html($conversion_rate); ?>%
                                    </strong>
                                </td>
                                <td><?php echo esc_html(mysql2date(get_option('date_format') . ' ' . get_option('time_format'), $variant['updated_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f0f0f1; font-weight: bold;">
                            <td><?php _e('Total', 'simple-ab-testing'); ?></td>
                            <td><?php echo number_format_i18n($test_data['totals']['views']); ?></td>
                            <td><?php echo number_format_i18n($test_data['totals']['conversions']); ?></td>
                            <td>
                                <?php
                                $total_rate = $test_data['totals']['views'] > 0 
                                    ? round(($test_data['totals']['conversions'] / $test_data['totals']['views']) * 100, 2)
                                    : 0;
                                ?>
                                <strong style="color: <?php echo $total_rate > 0 ? '#46b450' : '#999'; ?>;">
                                    <?php echo esc_html($total_rate); ?>%
                                </strong>
                            </td>
                            <td>-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    .wrap h2 {
        margin-top: 20px;
        margin-bottom: 10px;
        font-size: 1.3em;
        color: #1d2327;
    }
    
    .wp-list-table th {
        font-weight: 600;
    }
    
    .wp-list-table td {
        vertical-align: middle;
    }
    
    .button .dashicons {
        font-size: 16px;
        width: 16px;
        height: 16px;
    }
</style>
