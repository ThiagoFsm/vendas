import Vue from 'vue';

/**
 * O primeiro argumento é o caminho da pasta (./components).
 * O segundo (true) diz para buscar em subpastas.
 * O terceiro é a expressão regular para arquivos .vue.
 */
const files = require.context('./components', true, /\.vue$/i);

files.keys().map(key => {
    // Extrai apenas o nome do arquivo sem a extensão
    // Ex: ./pedidos/CriarPedido.vue -> CriarPedido
    const filename = key.split('/').pop().split('.')[0];

    // Converte CamelCase para kebab-case (CriarPedido -> criar-pedido)
    const componentName = filename.replace(/([a-z0-9])([A-Z])/g, '$1-$2').toLowerCase();

    return Vue.component(componentName, files(key).default);
});
