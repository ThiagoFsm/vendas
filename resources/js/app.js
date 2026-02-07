// resources/js/app.js

/**
 * Primeiro carregamos as dependências (Axios, Lodash, etc)
 * que o Laravel configura por padrão no bootstrap.js.
 */
require('./bootstrap');

// Define o Vue globalmente
window.Vue = require('vue').default;

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
