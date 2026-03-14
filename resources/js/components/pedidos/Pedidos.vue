<script>
    export default {
        name: "Pedidos",
        props: ['rota_create'],
        data() {
            return {

            }
        },

        mounted() {
        },

        methods: {
            criarPedido() {
                window.location.href = `${this.rota_create}`;
            }
        }
    }
</script>
