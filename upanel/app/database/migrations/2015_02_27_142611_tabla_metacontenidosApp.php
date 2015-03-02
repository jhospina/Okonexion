<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaMetacontenidosApp extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('metacontenidosApp', function($table) {
            $table->increments('id');
            $table->integer("id_contenido");
            $table->string("clave");
            $table->text("valor");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('metacontenidosApp');
    }

}
