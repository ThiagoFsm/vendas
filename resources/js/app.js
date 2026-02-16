// resources/js/app.js

/**
 * Primeiro carregamos as dependências (Axios, Lodash, etc)
 * que o Laravel configura por padrão no bootstrap.js.
 */
require('./bootstrap');
import 'bootstrap-icons/font/bootstrap-icons.css';

// 1. Importar o v-money
import money from 'v-money';

// Define o Vue globalmente
window.Vue = require('vue').default;

// 2. Registrar o plugin v-money com a configuração global para Real (BRL)
// Isso permite que você use v-money em qualquer input do seu projeto
const dinheiro = {
    decimal: ',',
    thousands: '.',
    prefix: '',
    precision: 2,
    masked: false
};

Vue.use(money, dinheiro);
// Cria um "atalho" global
Vue.prototype.$dinheiro = dinheiro;

/**
 * Diretiva v-select simplificada.
 * Uso: <select v-model="item.sabor_id" v-select>
 */
Vue.directive('select', {
    inserted: function (el) {
        $(el).select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: $(el).data('placeholder') || 'Selecione'
        }).on('change', function () {
            // Dispara um evento de mudança nativo que o Vue/v-model entende
            el.dispatchEvent(new Event('change', { bubbles: true }));
        });
    },
    update: function (el, binding) {
        // Sincroniza o Select2 quando o valor no Vue muda (ex: reset de formulário)
        $(el).val($(el).val()).trigger('change.select2');
    },
    unbind: function (el) {
        $(el).select2('destroy');
    }
});

// remover mensagem de modo de desenvolvimento do console
Vue.config.productionTip = false;
Vue.config.devtools = false;

/**
 * Importamos o arquivo que faz o registro automático dos componentes.
 * Como o arquivo chama 'components.js', basta apontar para o caminho dele.
 */
require('./components');

/**
 * Finalmente, instanciamos o Vue e o acoplamos ao ID #app do seu HTML.
 */
const app = new Vue({
    el: '#app',
});
