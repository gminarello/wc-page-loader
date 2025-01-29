jQuery(document).ready(function ($) {
    // Verificar se o pré-carregamento está habilitado
    if (typeof wcPageLoaderSettings !== 'undefined' && wcPageLoaderSettings.enabled) {
        // Criar a barra de carregamento e adicioná-la ao body
        const loaderBar = $('<div>', { id: 'wc-loader-bar' });
        $('body').append(loaderBar);

        // Função de pré-carregamento de páginas
        $('a').on('click', function (e) {
            const link = $(this).attr('href');

            // Verificar se o link é válido e está no mesmo domínio
            if (link && link.startsWith(window.location.origin)) {
                e.preventDefault(); // Prevenir redirecionamento imediato

                // Iniciar a animação da barra de carregamento
                loaderBar.css('width', '30%');

                // Pré-carregar a página usando fetch
                fetch(link)
                    .then((response) => {
                        if (response.ok) {
                            loaderBar.css('width', '70%'); // Atualizar a barra para 70%
                            return response.text();
                        } else {
                            throw new Error('Erro ao carregar a página.');
                        }
                    })
                    .then(() => {
                        loaderBar.css('width', '100%'); // Completar a barra
                        setTimeout(() => {
                            window.location.href = link; // Redirecionar após o carregamento
                        }, 300); // Pequeno delay para exibir a barra completa
                    })
                    .catch((error) => {
                        console.error(error);
                        window.location.href = link; // Redirecionar mesmo em caso de erro
                    });
            }
        });
    }
});