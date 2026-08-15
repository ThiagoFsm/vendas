export async function marcarComoFeito(pedido_id, produto_id, callbackFechar) {
    try {
        const response = await axios.post('/vendas/producao/marcar-produto-feito', {
            pedido_id: pedido_id,
            produto_id: produto_id
        });
        if (response.status === 200 || response.status === 201) {
            window.Toast.fire({ icon: 'success', title: `Pedido ${pedido_id} atualizado!` });
            if(callbackFechar) {
                setTimeout(callbackFechar, 2000);
            }
        }
    } catch (error) {
        window.Toast.fire({ icon:'error', title: `Não foi possível atualizar o pedido ${pedido_id}.` });
    }
}
