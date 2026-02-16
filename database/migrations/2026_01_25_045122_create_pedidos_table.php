<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->integer('quantidade_itens');
            $table->decimal('valor_total', 10, 2);
            $table->decimal('valor_antecipado', 10, 2)->nullable();
            $table->decimal('valor_restante', 10, 2)->nullable();
            $table->boolean('pago')->default(false);
            $table->morphs('entrega_retirada');
            $table->boolean('ativo');
            $table->timestamps();

            $table->foreign('cliente_id', 'pedidos_cliente_id_fk')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
