<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaTaxonomiasContenidosApp extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('taxonomiasContenidosApp', function($table) {
            $table->increments('id');
            $table->integer("id_usuario");
            $table->integer("id_aplicacion");
            $table->string("nombre");
            $table->text("descripcion")->nullable();
            $table->integer("tax_padre")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('taxonomiasContenidosApp');
    }

}
