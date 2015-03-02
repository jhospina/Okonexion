<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaConfiguracionApp extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('configuracionApp', function($table) {
            $table->increments('id');
            $table->integer("id_aplicacion");
            $table->string("clave");
            $table->string("valor");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
     Schema::drop('configuracionApp');
    }

}
