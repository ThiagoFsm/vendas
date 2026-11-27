import './bootstrap';

import Swal from 'sweetalert2';
window.Swal = Swal;

window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

// 1. IMPORTAR O VUE PRIMEIRO
import Vue from 'vue';
window.Vue = Vue;

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

import './components';

const app = new Vue({
    el: '#app',
    mounted() {
        let storageSuccess = null;
        try {
            storageSuccess = sessionStorage.getItem('toast_success');
            if (storageSuccess) {
                sessionStorage.removeItem('toast_success');
            }
        } catch (e) {
            // Ignora erro caso sessionStorage esteja indisponível
        }

        const successMessage = (window.session && window.session.success) || storageSuccess;
        if (successMessage) {
            window.Toast.fire({
                icon: 'success',
                title: successMessage
            });
        }

        if (window.session && window.session.error) {
            window.Toast.fire({
                icon: 'error',
                title: 'Ops! Algo deu errado',
                text: window.session.error,
                showConfirmButton: true
            });
        }
    }
});
