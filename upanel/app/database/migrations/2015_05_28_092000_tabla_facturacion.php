<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaFacturacion extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('facturacion', function($table) {
            $table->bigIncrements('id');
            $table->integer("instancia");
            $table->integer("id_usuario");
            $table->string("estado", 2);
            $table->string("gateway", 3)->nullable();
            $table->double("iva")->default(0);
            $table->double("subtotal")->default(0);
            $table->double("total")->default(0);
            $table->datetime("fecha_creacion");
            $table->datetime("fecha_vencimiento");
            $table->boolean("eliminado")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('facturacion');
    }

}
