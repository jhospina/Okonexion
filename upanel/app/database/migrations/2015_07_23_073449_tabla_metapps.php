<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaMetapps extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('metapps', function($table) {
            $table->bigIncrements('id');
            $table->integer("id_app");
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
         Schema::drop('metapps');
    }

}
