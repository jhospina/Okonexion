<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaRelacionContenidosTerminosApp extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('relacion_contenidos_terminos_App', function($table) {
            $table->integer("id_usuario");
            $table->integer("id_aplicacion");
            $table->integer("id_contenido");
            $table->integer("id_termino");     
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('relacion_contenidos_terminos_App');
    } 

}
