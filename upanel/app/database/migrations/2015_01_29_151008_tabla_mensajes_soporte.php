<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaMensajesSoporte extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 //Crea el esquema de la tabla
        Schema::create('mensajes_soporte', function($table) {
            $table->increments('id');
            $table->integer("id_usuario");
            $table->integer("id_ticket");
            $table->text("mensaje",1000);
            $table->string("url_adjunto")->nullable();
            $table->datetime("fecha");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mensajes_soporte');
	}

}
