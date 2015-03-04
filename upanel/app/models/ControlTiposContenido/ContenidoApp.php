<?php

class ContenidoApp extends Eloquent {

    protected $table = 'contenidosApp';

    const ESTADO_PUBLICO = "publico";
    const ESTADO_GUARDADO = "guardado";
    const TIPO_IMAGEN = "imagen";

    static function agregarImagen($nombre, $URL, $id_usuario = null, $id_aplicacion = null) {
        if (is_null($id_aplicacion))
            $id_aplicacion = Aplicacion::ID();
        if (is_null($id_usuario))
            $id_usuario = Auth::user()->id;

        $contenido = new ContenidoApp;
        $contenido->id_aplicacion = $id_aplicacion;
        $contenido->id_usuario = $id_usuario;
        $contenido->tipo = ContenidoApp::TIPO_IMAGEN;
        $contenido->titulo = $nombre;
        $contenido->contenido = $URL;
        $contenido->estado = ContenidoApp::ESTADO_PUBLICO;
        $contenido->mime_type = mime_content_type(Util::convertirUrlPath($URL));
        if (@$contenido->save())
            return $contenido->id;
        else
            return null;
    }

    static function agregarMetaDato($id_contenido, $clave, $valor) {
        $meta = new MetaContenidoApp;
        $meta->id_contenido = $id_contenido;
        $meta->clave = $clave;
        $meta->valor = $valor;
        $meta->save();
    }

    static function establecerTerminosTaxonomia($id_contenido, $terms, $taxonomia) {
        //Escribir codigo aqui para establecer terminos de una taxononomia
    }

}
