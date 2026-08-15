import Vue from 'vue';

/**
 * Registra automaticamente todos os componentes Vue presentes na pasta components
 */
const files = import.meta.glob('./components/**/*.vue', { eager: true });

Object.entries(files).forEach(([path, definition]) => {
    // Extrai apenas o nome do arquivo sem extensão (ex: CriarPedido)
    const filename = path.split('/').pop().replace(/\.\w+$/, '');

    // Converte CamelCase para kebab-case (CriarPedido -> criar-pedido)
    const componentName = filename.replace(/([a-z0-9])([A-Z])/g, '$1-$2').toLowerCase();

    Vue.component(componentName, definition.default || definition);
});
