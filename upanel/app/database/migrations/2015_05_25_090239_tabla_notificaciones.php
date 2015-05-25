<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaNotificaciones extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('notificaciones', function($table) {
            $table->bigIncrements('id');
            $table->integer("instancia");
            $table->integer("id_usuario");
            $table->integer("id_objetivo")->nullable();
            $table->string('tipo', 4);
            $table->boolean("visto");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('notificaciones');
    }

}
