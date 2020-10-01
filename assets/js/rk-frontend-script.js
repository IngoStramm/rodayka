var rk_formularios_init = function () {
    // Exibe esconde campos condicionais do formulário do Elementor
    // Usa como referência o texto no "label"
    // O label do input[radio] deve ser igual ao label do input[text] que será exibido/escondido
    // o formulário (ou uma div de nível superior) deve possuir a classe css "rk-formulario"
    var formularios = document.querySelectorAll('.rk-formulario');
    Array.prototype.forEach.call(formularios, function (formulario, i) {
        var radios = formulario.querySelectorAll('input[type="radio"]');
        Array.prototype.forEach.call(radios, function (radio, i) {
            var radio_label = formulario.querySelector('label[for="' + radio.id + '"]');
            var labels = formulario.querySelectorAll('label');
            Array.prototype.forEach.call(labels, function (label, i) {
                if (radio_label.getAttribute('for') !== label.getAttribute('for') && radio_label.innerHTML === label.innerHTML) {
                    var input_id = label.getAttribute('for');
                    var input_toggle = document.getElementById(input_id);
                    var wrap_toggle = input_toggle.parentNode;
                    wrap_toggle.style.display = 'none';
                    [].forEach.call(document.querySelectorAll('input[type="radio"]'), function (el) {
                        el.addEventListener('change', function () {
                            if (radio.checked === true) {
                                wrap_toggle.style.display = 'block';
                            } else {
                                wrap_toggle.style.display = 'none';
                            }
                        });
                    });
                }
            });
        });
    });
};

var rk_section_bgi = function () {
    var secao_bgi = document.querySelectorAll('.rk-secao-bgi');
    Array.prototype.forEach.call(secao_bgi, function (secao, i) {
        var src = secao.getAttribute('data-rk-bgi');
        if (src === null) { return; }
        secao.style.backgroundImage = 'url(' + src + ')';
    });
};

document.addEventListener('DOMContentLoaded', function () {
    rk_formularios_init();
    rk_section_bgi();
});