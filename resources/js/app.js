require('./bootstrap');

import Swal from 'sweetalert2';
window.Swal = Swal;

// 1. IMPORTAR O VUE PRIMEIRO
window.Vue = require('vue').default;

// 2. TOAST NOTIFICATION
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const toastOptions = {
    position: "top-right",
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false
};

// Agora o Vue já existe, então podemos usar
Vue.use(Toast, toastOptions);

// 3. DINHEIRO / V-MONEY
import money from 'v-money';
const dinheiro = {
    decimal: ',',
    thousands: '.',
    prefix: '',
    precision: 2,
    masked: false
};

Vue.use(money, dinheiro);
Vue.prototype.$dinheiro = dinheiro;

// 4. SELECT2 & JQUERY
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;
import 'select2';
import 'select2/dist/css/select2.css';
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.css';

Vue.directive('select', {
    inserted: function (el) {
        $(el).select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: $(el).data('placeholder') || 'Selecione',
            allowClear: true
        }).on('select2:select select2:unselect', function () {
            el.dispatchEvent(new Event('change', { bubbles: true }));
        });
    },
    componentUpdated: function (el) {
        $(el).trigger('change.select2');
    },
    unbind: function (el) {
        $(el).select2('destroy');
    }
});

// 5. CONFIGURAÇÕES FINAIS E COMPONENTES
Vue.config.productionTip = false;
Vue.config.devtools = false;

require('./components');

const app = new Vue({
    el: '#app',
    mounted() {
        // Verifica se existem mensagens de sessão passadas pelo Blade
        if (window.session && window.session.success) {
            this.$toast.success(window.session.success);
        }
        // Usamos .error para a primeira mensagem de erro da sessão
        if (window.session && window.session.error) {
            this.$toast.error(window.session.error);
        }
    }
});
