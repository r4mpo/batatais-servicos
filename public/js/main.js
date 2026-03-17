// Batatais Serviços - JavaScript Principal
// ==========================================

$(document).ready(function () {
    // Função para alternar a visibilidade da senha
    const passwordToggleIcon = document.getElementById('toggle_password_visibility');
    const passwordConfirmationToggleIcon = document.getElementById('toggle_password_confirmation_visibility');

    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');

    const termsPrivacyCheckbox = document.getElementById('terms_privacy_checkbox');

    function togglePasswordVisibility(inputElement, iconElement) {
        if (inputElement.type === "password") {
            inputElement.type = "text";
            iconElement.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            inputElement.type = "password";
            iconElement.classList.replace("fa-eye-slash", "fa-eye");
        }
    }

    passwordToggleIcon.onclick = function () {
        togglePasswordVisibility(passwordInput, passwordToggleIcon);
    };

    passwordConfirmationToggleIcon.onclick = function () {
        togglePasswordVisibility(passwordConfirmationInput, passwordConfirmationToggleIcon);
    };

    termsPrivacyCheckbox.onchange = function () {
        const submitButton = document.getElementById('btn-send');
        submitButton.disabled = !this.checked;
    };


    // Inicializar tooltips do Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Smooth scroll para links internos
    $('a[href^="#"]').on('click', function (e) {
        e.preventDefault();
        var target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });

    // Feedback visual para botões
    $('button').on('click', function () {
        $(this).blur();
    });

    // Animação de scroll suave
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 100) {
            $('.navbar').addClass('scrolled');
        } else {
            $('.navbar').removeClass('scrolled');
        }
    });

    // Botão voltar ao topo
    const backToTopBtn = $('<button class="btn btn-primary btn-floating" id="backToTopBtn" style="position: fixed; bottom: 30px; right: 30px; display: none; z-index: 99;"><i class="fas fa-arrow-up"></i></button>');
    $('body').append(backToTopBtn);

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

    // Estilo do botão flutuante
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
