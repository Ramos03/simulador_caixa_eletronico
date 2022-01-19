<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas', function (Blueprint $table) {
            $table->id('co_id');
            $table->bigInteger('co_id_usuario')->unsigned()->index()->nullable();
            $table->foreign('co_id_usuario')->references('id')->on('users');
            $table->string('co_tipo');
            $table->integer('co_saldo');
            $table->string('co_status');
            $table->timestamp('co_created_at')->useCurrent();           
            $table->timestamp('co_updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contas');
    }
}
