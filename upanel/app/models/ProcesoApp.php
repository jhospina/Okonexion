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

}
