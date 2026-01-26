<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_produto_id');
            $table->unsignedBigInteger('sabor_id');
            $table->unsignedBigInteger('tamanho_id');
            $table->decimal('valor_produto');
            $table->timestamps();

            $table->foreign('tipo_produto_id', 'produtos_tipo_produto_id_fk')->references('id')->on('tipo_produtos');
            $table->foreign('sabor_id', 'produtos_sabor_id_fk')->references('id')->on('sabores');
            $table->foreign('tamanho_id', 'produtos_tamanho_id_fk')->references('id')->on('tamanhos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
