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
}
add_action('wp_enqueue_scripts', 'wc_pageloader_enqueue_assets');