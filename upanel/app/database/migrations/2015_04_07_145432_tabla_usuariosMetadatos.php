<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaUsuariosMetadatos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
        Schema::create('usuariosMetadatos', function($table) {
            $table->bigIncrements('id');
            $table->integer("id_usuario");
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
        Schema::drop('usuariosMetadatos');
    }

}
