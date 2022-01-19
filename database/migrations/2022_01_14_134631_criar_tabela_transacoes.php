<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaTransacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacoes', function (Blueprint $table) {
            $table->id('tr_id');
            $table->bigInteger('tr_id_usuario')->unsigned()->index()->nullable();
            $table->foreign('tr_id_usuario')->references('id')->on('users'); 
            $table->bigInteger('tr_id_conta')->unsigned()->index()->nullable();
            $table->foreign('tr_id_conta')->references('co_id')->on('contas');
            $table->string('tr_tipo_operacao');
            $table->string('tr_detalhes');
            $table->string('tr_ip');
            $table->timestamp('tr_created_at')->useCurrent();           
            $table->timestamp('tr_updated_at')->useCurrent();        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transacoes');
    }
}
