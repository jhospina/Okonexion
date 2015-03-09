<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Triggers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //TABLA: relacion_contenidos_terminos_app
        //ACCION: INSERTAR
        DB::unprepared('CREATE TRIGGER TriggerInsertarTablaRelacionContenidosTerminosApp AFTER INSERT ON `relacion_contenidos_terminos_App` FOR EACH ROW
                   BEGIN
                      UPDATE terminosContenidosApp as terminos SET contador=(SELECT COUNT(*) FROM relacion_contenidos_terminos_App where id_termino=terminos.id);
                   END
                   ');
        //ACCION: ELIMINAR
        DB::unprepared('CREATE TRIGGER TriggerEliminarTablaRelacionContenidosTerminosApp AFTER DELETE ON `relacion_contenidos_terminos_App` FOR EACH ROW
                   BEGIN
                      UPDATE terminosContenidosApp as terminos SET contador=(SELECT COUNT(*) FROM relacion_contenidos_terminos_App where id_termino=terminos.id);
                   END
                   ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::unprepared('DROP TRIGGER TriggerInsertarTablaRelacionContenidosTerminosApp');
        DB::unprepared('DROP TRIGGER TriggerEliminarTablaRelacionContenidosTerminosApp');
    }

}
