<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaUsuarios extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //Crea el esquema de la tabla
        Schema::create('usuarios', function($table) {
            $table->bigIncrements('id');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('empresa')->nullable();
            $table->string('dni',20)->nullable();
            $table->string('email', 100)->unique(); 
            $table->string('cod_ver_email', 30)->nullable(); // Codigo de verificacion de email
            $table->boolean('email_confirmado')->default(0); // Indica si el email del usuario ha sido confirmado
            $table->text('password');
            $table->string('pais', 30)->nullable();
            $table->string('region', 30)->nullable();
            $table->string('ciudad', 30)->nullable();
            $table->string('direccion', 30)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('celular', 30)->nullable();
            $table->string('url_imagen')->nullable();
            $table->char('estado',2)->default('SP');//Indca la situación actual del usuario. SP=> Sin Pagar, SV =>Suscripción vigente, SC => Suscripcion Caducada
            $table->char('tipo',2)->default('RE');// Indica el tipo de usuario. RE=> Regular, SG=> Soporte General, AD=> Administrador 
            $table->integer('id_app')->nullable();//Indica el ID de identificación de la App del usuario
            $table->datetime('suscripcion_fin')->nullable();//Indica la fecha hasta donde el usuario estara suscrito
            $table->string('remember_token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
       Schema::drop('usuarios');
    }

}
