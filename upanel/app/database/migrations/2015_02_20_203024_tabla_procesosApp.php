<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaProcesosApp extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('procesosApp', function($table) {
            $table->increments('id');
            $table->integer("id_aplicacion");
            $table->integer("atendido_por")->nullable();
            $table->char("actividad", 2); //Indica la actividad realizada
            $table->string("observaciones")->nullable();
            $table->datetime("fecha_creacion");
            $table->datetime("fecha_inicio")->nullable();
            $table->datetime("fecha_finalizacion")->nullable();
            $table->text("json_config");
            $table->text("url_android")->nullable();
            $table->text("url_windows")->nullable();
            $table->text("url_iphone")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('procesosApp');
    }

}
