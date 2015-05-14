<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaTicketsSoporte extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //Crea el esquema de la tabla
        Schema::create('tickets_soporte', function($table) {
            $table->increments('id');
            $table->integer("usuario_cliente");
            $table->integer("usuario_soporte")->nullable();
            $table->char("tipo",2); //indica el tipo de soporte. [SG: Soporte General, SC: Soporte Comercial, SF: Soporte de Facturacion, ST: Soporte Tecnico]
            $table->string("asunto");
            $table->text("mensaje",1000);
            $table->string("url_adjunto")->nullable();
            $table->char("estado",2)->default("AB");//Indica el estado en el que se encuetra el ticket. [AB: Abierto, RE: Respondido, PR: Procesando, EN: Enviado, CE:Cerrado]
            $table->datetime("fecha");
            $table->integer("instancia");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
       Schema::drop('tickets_soporte');
    }

}
