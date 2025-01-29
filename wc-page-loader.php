<?php
/*
Plugin Name: WC Page Loader
Plugin URI: https://cloubox.com.br/
Description: Pré-carregamento de páginas no WordPress com barra de progresso no topo.
Version: 1.0.0
Author: Cloubox
Author URI: https://cloubox.com.br/
Text Domain: wc-page-loader
*/

// Bloquear acesso direto
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Adiciona funções externas do plugin
require_once plugin_dir_path(__FILE__) . 'includes/page-config.php';

// Enfileirar estilos e scripts
function wc_pageloader_enqueue_assets() {
    // Enfileirar estilos personalizados do plugin
    wp_enqueue_style(
        'wc-pageloader-styles',
        plugin_dir_url(__FILE__) . 'assets/styles.css',
        [],
        filemtime(plugin_dir_path(__FILE__) . 'assets/styles.css')
    );

    // Enfileirar scripts personalizados do plugin
    wp_enqueue_script(
        'wc-pageloader-scripts',
        plugin_dir_url(__FILE__) . 'assets/scripts.js',
        ['jquery'], // Dependência do jQuery
        filemtime(plugin_dir_path(__FILE__) . 'assets/scripts.js'),
        true // Carregar no footer
    );

    // Passar a configuração para o JavaScript
    wp_localize_script('wc-pageloader-scripts', 'wcPageLoaderSettings', [
        'enabled' => get_option('wc_pageloader_enabled', false),
    ]);
}
add_action('wp_enqueue_scripts', 'wc_pageloader_enqueue_assets');

// Adiciona link de configurações na listagem de plugins
function wc_pageloader_settings_link($links) {
    $settings_link = '<a href="/wp-admin/options-general.php?page=wc-pageloader">' . __('Configurações') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wc_pageloader_settings_link');