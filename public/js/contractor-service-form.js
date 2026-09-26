document.addEventListener('DOMContentLoaded', function () {
    var busca = document.getElementById('professional-search');
    var oculto = document.getElementById('professional_id');
    var resultados = document.getElementById('professional-results');
    var escolhido = document.getElementById('professional-selected');
    var rotulo = document.getElementById('professional-selected-label');
    var limpar = document.getElementById('professional-clear');
    var aguardar;

    if (busca && oculto && resultados) {
        busca.addEventListener('input', function () {
            clearTimeout(aguardar);
            var termo = busca.value.trim();
            if (termo.length < 2) {
                resultados.innerHTML = '';
                return;
            }
            aguardar = setTimeout(function () {
                fetch(busca.dataset.url + '?q=' + encodeURIComponent(termo), {
                    headers: { 'Accept': 'application/json' }
                })
                    .then(function (resposta) { return resposta.json(); })
                    .then(function (itens) {
                        resultados.innerHTML = '';
                        itens.forEach(function (item) {
                            var botao = document.createElement('button');
                            botao.type = 'button';
                            botao.className = 'list-group-item list-group-item-action';
                            botao.textContent = item.name + ' — ' + item.profession;
                            botao.addEventListener('click', function () {
                                oculto.value = item.id;
                                rotulo.textContent = botao.textContent;
                                escolhido.classList.remove('d-none');
                                resultados.innerHTML = '';
                                busca.value = '';
                            });
                            resultados.appendChild(botao);
                        });
                    })
                    .catch(function () {});
            }, 250);
        });
    }

    if (limpar && oculto && escolhido && busca && resultados) {
        limpar.addEventListener('click', function () {
            oculto.value = '';
            rotulo.textContent = '';
            escolhido.classList.add('d-none');
            resultados.innerHTML = '';
            busca.value = '';
        });
    }

    var valor = document.getElementById('service_value_reais');
    var cep = document.getElementById('address_postal_code');

    if (valor) {
        valor.addEventListener('input', function () {
            valor.value = formatarReais(valor.value);
        });
    }

    if (cep) {
        cep.addEventListener('input', function () {
            var digitos = cep.value.replace(/\D/g, '').slice(0, 8);
            cep.value = digitos.length > 5 ? digitos.slice(0, 5) + '-' + digitos.slice(5) : digitos;
            if (digitos.length === 8) {
                preencherViaCep(digitos);
            }
        });
    }

    function formatarReais(texto) {
        var digitos = String(texto).replace(/\D/g, '').replace(/^0+/, '');
        if (digitos === '') {
            return '';
        }
        var preenchido = digitos.padStart(3, '0');
        var inteiro = preenchido.slice(0, -2).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return inteiro + ',' + preenchido.slice(-2);
    }

    function preencherViaCep(digitos) {
        fetch('https://viacep.com.br/ws/' + digitos + '/json/')
            .then(function (resposta) {
                if (!resposta.ok) {
                    return null;
                }
                return resposta.json();
            })
            .then(function (dados) {
                if (!dados || dados.erro) {
                    return;
                }
                definir('address_street', dados.logradouro);
                definir('address_neighborhood', dados.bairro);
                definir('address_city', dados.localidade);
                definir('address_state', dados.uf);
                var numero = document.getElementById('address_number');
                if (numero) {
                    numero.focus();
                }
            })
            .catch(function () {});
    }

    function definir(id, texto) {
        var campo = document.getElementById(id);
        if (campo && texto) {
            campo.value = texto;
        }
    }
});
