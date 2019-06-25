<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username');
            $table->string('name');
            $table->enum('type', ['pessoal', 'institucional', 'local']);
            $table->binary('status');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->integer('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
