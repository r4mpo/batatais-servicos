// Batatais Serviços — script principal da landing e páginas públicas
// ================================================================

$(document).ready(function () {
    // Alternar visibilidade da senha no cadastro
    const iconeAlternarSenha = document.getElementById('toggle_password_visibility');
    const iconeAlternarConfirmacao = document.getElementById('toggle_password_confirmation_visibility');

    const campoSenha = document.getElementById('password');
    const campoConfirmacaoSenha = document.getElementById('password_confirmation');

    const checkboxTermos = document.getElementById('terms_privacy_checkbox');

    function alternarVisibilidadeSenha(campo, icone) {
        if (campo.type === "password") {
            campo.type = "text";
            icone.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            campo.type = "password";
            icone.classList.replace("fa-eye-slash", "fa-eye");
        }
    }

    iconeAlternarSenha.onclick = function () {
        alternarVisibilidadeSenha(campoSenha, iconeAlternarSenha);
    };

    iconeAlternarConfirmacao.onclick = function () {
        alternarVisibilidadeSenha(campoConfirmacaoSenha, iconeAlternarConfirmacao);
    };

    checkboxTermos.onchange = function () {
        const botaoEnviar = document.getElementById('btn-send');
        botaoEnviar.disabled = !this.checked;
    };


    // Tooltips Bootstrap (landing)
    var listaGatilhosTooltip = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var listaTooltips = listaGatilhosTooltip.map(function (elementoGatilho) {
        return new bootstrap.Tooltip(elementoGatilho);
    });

    // Rolagem suave para âncoras internas
    $('a[href^="#"]').on('click', function (evento) {
        evento.preventDefault();
        var alvo = $(this.getAttribute('href'));
        if (alvo.length) {
            $('html, body').stop().animate({
                scrollTop: alvo.offset().top - 100
            }, 1000);
        }
    });

    // Remove foco visual após clique em botões
    $('button').on('click', function () {
        $(this).blur();
    });

    // Sombra na navbar após rolar a página
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 100) {
            $('.navbar').addClass('scrolled');
        } else {
            $('.navbar').removeClass('scrolled');
        }
    });

    // Botão flutuante “voltar ao topo”
    const botaoVoltarTopo = $('<button class="btn btn-primary btn-floating" id="backToTopBtn" style="position: fixed; bottom: 30px; right: 30px; display: none; z-index: 99;"><i class="fas fa-arrow-up"></i></button>');
    $('body').append(botaoVoltarTopo);

    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 300) {
            $('#backToTopBtn').fadeIn();
        } else {
            $('#backToTopBtn').fadeOut();
        }
    });

    $('#backToTopBtn').on('click', function () {
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    });

    // Estilo injetado do botão flutuante e da navbar com sombra
    $('<style>')
        .text(`
            .btn-floating {
                width: 50px !important;
                height: 50px !important;
                padding: 0 !important;
                border-radius: 50% !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
                transition: all 0.3s !important;
            }
            .btn-floating:hover {
                transform: translateY(-3px) !important;
                box-shadow: 0 6px 12px rgba(0,0,0,0.3) !important;
            }
            .navbar.scrolled {
                box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
            }
        `)
        .appendTo('head');
});
