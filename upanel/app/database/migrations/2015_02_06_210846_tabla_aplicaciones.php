<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaAplicaciones extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('aplicaciones', function($table) {
            $table->increments('id');
            $table->integer("id_usuario");
            $table->string("nombre");
            $table->string("url_logo")->nullable();
            $table->string("key_app", 50);
            $table->char("diseno", 2);
            $table->char("estado", 2)->default("DI"); //indica el estado de la aplicacion ["CO"=> En Construcción,"CD"=>"En cola para desarrollo","DE"=>"En desarrollo","PC" => En Proceso de Actualización,"AC"=> Activa, "PE"=>En Proceso de Eliminación]
            $table->text("configuracion")->nullable(); //Aqui se almacena un texto JSON con todos los parametros de la configuracion
            $table->text("url_android")->nullable();
            $table->text("url_windows")->nullable();
            $table->text("url_iphone")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('aplicaciones');
    }

}
