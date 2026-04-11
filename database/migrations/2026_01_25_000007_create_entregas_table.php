<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bairro_id')->nullable();
            $table->string('rua')->nullable();
            $table->string('numero')->nullable();
            $table->date('data')->nullable();
            $table->string('periodo')->nullable();
            $table->decimal('valor_uber')->nullable();
            $table->unsignedBigInteger('entregador_id')->nullable();
            $table->timestamps();

            $table->foreign('entregador_id' ,'entregas_entregador_id_fk')->references('id')->on('vendedores');
            $table->foreign('bairro_id' ,'entregas_bairro_id_fk')->references('id')->on('bairros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entregas');
    }
}
