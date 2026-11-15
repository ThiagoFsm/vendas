export async function marcarComoFeito(pedido_id, produto_id, callback) {
    try {
        await axios.post(`/vendas/producao/${produto_id}/${pedido_id}`);

        window.Toast.fire({
            icon: 'success',
            title: `Pedido ${pedido_id} atualizado!`,
        });

        if (typeof callback === 'function') {
            callback();
        }
    } catch (error) {
        window.Toast.fire({
            icon: 'error',
            title: `Não foi possível atualizar o pedido ${pedido_id}.`
        });
    }
}
