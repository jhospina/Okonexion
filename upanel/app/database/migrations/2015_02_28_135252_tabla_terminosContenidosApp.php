<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaTerminosContenidosApp extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('terminosContenidosApp', function($table) {
            $table->increments('id');
            $table->integer("id_taxonomia");
            $table->string("nombre");
            $table->integer("contador");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('terminosContenidosApp');
    }

}
