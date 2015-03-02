<?php

class TerminoContenidoApp extends Eloquent {

    public $timestamps = false;
    protected $table = 'terminosContenidosApp';
    protected $fillable = array('id_taxonomia', 'nombre');

}
