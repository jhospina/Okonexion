<?php

Class ProcesoApp extends Eloquent {

    protected $fillable = array('id_aplicacion', 'atendido_por', 'actividad', 'observaciones', 'fecha_creacion', 'fecha_finalizacion', 'json_config');
    public $timestamps = false;
    protected $table = "procesosApp";

    //********************************************************
    //ESTADOS DE LA APLICACION********************************
    //********************************************************

    const ACTIVIDAD_CONSTRUIR = "CO";
    const ACTIVIDAD_ACTUALIZAR = "AC";

    //****************************************************
    //RELACIONES CON OTROS MODELOS***************************
    //****************************************************

    public function user() {
        return $this->belongsTo('User', "atendido_por");
    }

    /** Obtiene el numero de la version actual de una aplicacion
     * 
     * @param type $id_app Id de la aplicacion a consultar
     * @return type
     */
    public static function obtenerNumeroVersion($id_app) {
        $procesos = ProcesoApp::where("id_aplicacion", $id_app)->orderBy("id", "DESC")->get();
        if (count($procesos) > 0)
            return count($procesos);
        return null;
    }

    public static function obtenerUltimoProceso($id_app) {
        $procesos = ProcesoApp::where("id_aplicacion", $id_app)->take(1)->orderBy("id", "DESC")->get();
        if (count($procesos) > 0) {
            foreach ($procesos as $proceso) {
                return $proceso;
            }
        }

        return null;
    }

}
