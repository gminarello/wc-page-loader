<?php
// Bloquear acesso direto
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Adiciona a página de configurações ao menu do WordPress
function pageloader_add_settings_page() {
    add_options_page(
        'Configurações do Page Loader', // Título da página
        'Page Loader', // Título do menu
        'manage_options', // Capacidade necessária
        'wc-pageloader', // Slug da página
        'pageloader_settings_page_html' // Função que renderiza a página
    );
}
add_action('admin_menu', 'pageloader_add_settings_page');

// Registra configurações no banco de dados
function wc_pageloader_register_settings() {
    register_setting('wc_pageloader_settings', 'wc_pageloader_enabled');
    register_setting('wc_pageloader_settings', 'wc_pageloader_bar_color');
}
add_action('admin_init', 'wc_pageloader_register_settings');

// Página de configurações do plugin
function pageloader_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>Page Loader</h1>
        <br>
        <h2>Configurações</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('wc_pageloader_settings');
            do_settings_sections('wc_pageloader_settings');
            ?>
            <table class="form-table">
                <!-- Configuração do status do Page Loader -->
                <tr valign="top">
                    <th scope="row">Status:</th>
                    <td>
                        <input type="checkbox" name="wc_pageloader_enabled" value="1" <?php checked(1, get_option('wc_pageloader_enabled'), true); ?> />
                        <label for="wc_pageloader_enabled">Ativar o pré-carregamento</label>
                        <p class="description">Ative para habilitar o pré-carregamento de páginas.</p>
                    </td>
                </tr>

                <!-- Configuração da cor da barra de pré-carregamento -->
                <tr valign="top">
                    <th scope="row">Cor da barra de progresso:</th>
                    <td>
                        <input type="text" name="wc_pageloader_bar_color" value="<?php echo esc_attr(get_option('wc_pageloader_bar_color', '#007bff')); ?>" class="my-color-field" data-default-color="#007bff" />
                        <p class="description">Selecione a cor da barra de progresso do pré-carregamento.</p>
                    </td>
                </tr>
                
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}