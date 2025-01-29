jQuery(document).ready(function($) {
    if (typeof wcPageLoaderSettings !== 'undefined' && wcPageLoaderSettings.enabled) {
        // Cria a barra de carregamento e a anexa ao <html>
        const loaderBar = $('<div>', { id: 'wc-loader-bar' });
        $('html').append(loaderBar);

        // Função para atualizar a cor da barra com base nas configurações
        const updateBarColor = () => {
            const color = (typeof wcPageLoaderSettings !== 'undefined' && wcPageLoaderSettings.barColor)
                ? wcPageLoaderSettings.barColor
                : '#007bff'; // Cor padrão
            loaderBar.css('background-color', color);
        };

        // Aplica a cor inicial da barra
        updateBarColor();

        // Função para reexecutar scripts
        const reexecuteScripts = (doc) => {
            const scripts = $(doc).find('script');
            scripts.each(function() {
                const script = document.createElement('script');
                script.type = this.type;
                if (this.src) {
                    script.src = this.src;
                } else {
                    script.textContent = this.textContent;
                }
                document.body.appendChild(script);
            });
        };

        // Evento para links
        $('a').on('click', function(e) {
            const link = $(this).attr('href');
            if (link && link.startsWith(window.location.origin)) {
                e.preventDefault();
                loaderBar.css('width', '30%'); // Início do carregamento

                fetch(link)
                    .then(response => {
                        if (!response.ok) throw new Error('Erro ao carregar a página.');
                        loaderBar.css('width', '70%'); // Progresso do carregamento
                        return response.text();
                    })
                    .then(html => {
                        loaderBar.css('width', '100%'); // Finalização do carregamento

                        setTimeout(() => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');

                            // Atualiza o HEAD (scripts, styles, etc.)
                            $('head').find('script, link[rel="stylesheet"]').remove();
                            $('head').append($(doc).find('head').children());

                            // Atualiza o BODY
                            $('body').html($(doc).find('body').html());

                            // Reexecuta scripts
                            reexecuteScripts(doc);

                            // Move o scroll para o topo da página
                            window.scrollTo(0, 0);

                            // URL sem recarregar
                            history.pushState({}, '', link);

                            // Reanexa a barra ao <html>, reseta o progresso e reaplica a cor
                            $('html').append(loaderBar);
                            loaderBar.css('width', '0'); // Reseta a largura
                            updateBarColor(); // Garante que a cor configurada seja reaplicada
                        }, 300);
                    })
                    .catch(error => {
                        console.error(error);
                        window.location.href = link; // Em caso de erro, carrega normalmente
                    });
            } else {
                window.location.href = link; // Links externos ou sem `href`
            }
        });

        // Atualiza a cor da barra ao voltar para uma página anterior
        window.addEventListener('popstate', function() {
            updateBarColor();
            window.scrollTo(0, 0); // Garante que o scroll volte ao topo ao navegar com o botão "voltar"
        });
    }
});