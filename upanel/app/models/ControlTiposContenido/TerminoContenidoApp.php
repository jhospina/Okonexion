<?php

class TerminoContenidoApp extends Eloquent {

    public $timestamps = false;
    protected $table = 'terminosContenidosApp';
    protected $fillable = array('id_taxonomia', 'nombre');

    public function contenidos() {
        return $this->belongsToMany('ContenidoApp', 'relacion_contenidos_terminos_App', 'id_termino', 'id_contenido');
    }

}
