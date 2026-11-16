<script>
export default {
    name: "Sabores",
    props: ['rota_sabores', 'rota_store'],

    data() {
        return {
            sabores: [],
            carregando: false,
            salvando: false,
            modal: false,
            editando: false,
            saborIdEditando: null,

            // Filtros e busca
            termoPesquisa: '',
            filtroStatus: '',

            // Formulário
            form: {
                descricao: '',
                ativo: true
            }
        };
    },

    async mounted() {
        await this.buscarSabores();
    },

    computed: {
        hasFiltrosAtivos() {
            return Boolean(this.termoPesquisa || this.filtroStatus);
        },

        saboresFiltrados() {
            if (!this.sabores || !Array.isArray(this.sabores)) return [];

            return this.sabores.filter(sabor => {
                // 1. Filtro por Descrição do Sabor
                if (this.termoPesquisa && this.termoPesquisa.trim()) {
                    const normalizar = (texto) => (texto || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                    const termo = normalizar(this.termoPesquisa.trim());
                    const descricao = normalizar(sabor.descricao);

                    if (!descricao.includes(termo)) {
                        return false;
                    }
                }

                // 2. Filtro por Status Ativo/Inativo
                if (this.filtroStatus === 'ativo' && !sabor.ativo) {
                    return false;
                }
                if (this.filtroStatus === 'inativo' && sabor.ativo) {
                    return false;
                }

                return true;
            });
        }
    },

    methods: {
        GerarCorSabor(id) {
            const cores = ['#cd5c5c', '#1e90ff', '#bc8f8f', '#db7093', '#8fbc8f', '#9370db', '#e67e22', '#16a085'];
            return cores[id % cores.length];
        },

        limparFiltros() {
            this.termoPesquisa = '';
            this.filtroStatus = '';
        },

        async buscarSabores() {
            this.carregando = true;
            try {
                const endpoint = this.rota_sabores || '/vendas/sabores';
                const response = await axios.get(endpoint);
                this.sabores = response.data || [];
            } catch (error) {
                window.Toast.fire({
                    icon: 'error',
                    title: 'Erro ao carregar lista de sabores.'
                });
            } finally {
                this.carregando = false;
            }
        },

        abrirModalCriar() {
            this.editando = false;
            this.saborIdEditando = null;
            this.form = {
                descricao: '',
                ativo: true
            };
            this.modal = true;
        },

        abrirModalEditar(sabor) {
            this.editando = true;
            this.saborIdEditando = sabor.id;
            this.form = {
                descricao: sabor.descricao,
                ativo: Boolean(sabor.ativo)
            };
            this.modal = true;
        },

        fecharModal() {
            this.modal = false;
            this.salvando = false;
        },

        async salvarSabor() {
            if (!this.form.descricao || !this.form.descricao.trim()) {
                window.Toast.fire({
                    icon: 'warning',
                    title: 'Informe a descrição do sabor.'
                });
                return;
            }

            this.salvando = true;

            try {
                const payload = {
                    descricao: this.form.descricao.trim(),
                    ativo: this.form.ativo
                };

                let response;
                if (this.editando && this.saborIdEditando) {
                    response = await axios.post(`/vendas/sabores/${this.saborIdEditando}/update`, payload);
                } else {
                    const endpoint = this.rota_store || '/vendas/sabores/store';
                    response = await axios.post(endpoint, payload);
                }

                window.Toast.fire({
                    icon: 'success',
                    title: response.data?.message || 'Sabor salvo com sucesso!'
                });

                this.fecharModal();
                await this.buscarSabores();

            } catch (error) {
                const msg = error.response?.data?.message || 'Erro ao salvar sabor.';
                window.Toast.fire({
                    icon: 'error',
                    title: msg
                });
            } finally {
                this.salvando = false;
            }
        },

        async toggleAtivo(sabor) {
            try {
                const response = await axios.post(`/vendas/sabores/${sabor.id}/toggle-ativo`);
                sabor.ativo = response.data?.ativo ?? !sabor.ativo;

                window.Toast.fire({
                    icon: 'success',
                    title: response.data?.message || 'Status alterado com sucesso!'
                });
            } catch (error) {
                window.Toast.fire({
                    icon: 'error',
                    title: 'Não foi possível alterar o status do sabor.'
                });
            }
        },

        async excluirSabor(sabor) {
            Swal.fire({
                title: 'Excluir sabor?',
                text: `Deseja realmente excluir o sabor "${sabor.descricao}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await axios.post(`/vendas/sabores/${sabor.id}/destroy`);

                        window.Toast.fire({
                            icon: response.data?.action === 'deactivated' ? 'info' : 'success',
                            title: response.data?.message || 'Sabor processado com sucesso!'
                        });

                        await this.buscarSabores();

                    } catch (error) {
                        window.Toast.fire({
                            icon: 'error',
                            title: 'Erro ao excluir sabor.',
                            text: error.response?.data?.message || error.message
                        });
                    }
                }
            });
        }
    }
};
</script>
