<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaIntanciasMetadatos extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {  
        Schema::create('instanciasMetadatos', function($table) {
            $table->bigIncrements('id');
            $table->integer("instancia");
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
        Schema::drop('instanciasMetadatos');
    }

}
