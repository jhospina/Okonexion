<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaContenidosApp extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() { 
        Schema::create('contenidosApp', function($table) {
            $table->increments('id');
            $table->integer("id_usuario");
            $table->integer("id_aplicacion");
            $table->string("tipo",20);
            $table->string("titulo")->nullable();
            $table->text("contenido")->nullable();
            $table->string("estado",20);
            $table->string("mime_type",100)->nullable();
            $table->integer("contenido_padre")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('contenidosApp');
    }

}
