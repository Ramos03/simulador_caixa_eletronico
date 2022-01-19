<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('us_nome');
            $table->string('us_cpf')->unique();
            $table->string('us_password');
            $table->string('us_email');
            $table->date('us_dt_nascimento');
            $table->string('us_status');
            $table->timestamp('us_created_at')->useCurrent();           
            $table->timestamp('us_updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
