<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaInstancias extends Migration {

	
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //Crea el esquema de la tabla
        Schema::create('instancias', function($table) {
            $table->bigIncrements('id');
            $table->string('empresa');
            $table->string('nit');
            $table->string('web');
            $table->string('email'); 
            $table->string('nombre_contacto', 150)->nullable();
            $table->string('pais', 30)->nullable();
            $table->string('region', 30)->nullable();
            $table->string('ciudad', 30)->nullable();
            $table->string('codigo_postal', 30)->nullable();
            $table->string('direccion', 30)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('celular', 30)->nullable();
            $table->char('estado',2)->default('VI');//Indca la situación actual de la instancia. VI => Vigente, CA=> Caducado
            $table->datetime('fin_suscripcion')->nullable();//Indica la fecha hasta donde el usuario estara suscrito
            $table->integer("id_administrador");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
       Schema::drop('instancias');
    }

}
