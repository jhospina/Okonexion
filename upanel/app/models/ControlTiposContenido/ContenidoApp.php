<?php

class ContenidoApp extends Eloquent {

    protected $table = 'contenidosApp';

    const ESTADO_PUBLICO = "publico";
    const ESTADO_GUARDADO = "guardado";
    const TIPO_IMAGEN = "imagen";

    /** Añade una imagen como contenido
     * 
     * @param type $nombre Nombre de la imagen
     * @param type $URL URL del a imagen
     * @param type $id_usuario Id del usuario autor del contenido
     * @param type $id_aplicacion Id de la aplicacion controladora
     * @return Int Retorna el Id del contenido en caso de exito, del o contrario Null
     */
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

    static function eliminarImagen($id_imagen) {
        $imagen = ContenidoApp::find($id_imagen);

        if (File::exists(Util::convertirUrlPath($imagen->contenido))) {
            File::delete(Util::convertirUrlPath($imagen->contenido));
            $imagen->delete();
        }
    }

    /** Agrega un metadato a un contenido
     * 
     * @param Int $id_contenido Id del contenido
     * @param String $clave La clave del metadato
     * @param String $valor El valor del metadato
     */
    static function agregarMetaDato($id_contenido, $clave, $valor) {
        $meta = new MetaContenidoApp;
        $meta->id_contenido = $id_contenido;
        $meta->clave = $clave;
        $meta->valor = $valor;
        $meta->save();
    }

    static function obtenerMetadato($id_contenido, $clave) {
        $metas = MetaContenidoApp::where("id_contenido", $id_contenido)->where("clave", $clave)->get();
        foreach ($metas as $meta)
            return $meta;
        return null;
    }

    /** Establece los terminos(categorias) a un contenido
     * 
     * @param int $id_contenido ID del contenido
     * @param array $terms Arreglo que contiene los Ids de los terminos a asociar con el contenido 
     */
    static function establecerTerminos($id_contenido, $terms) {
        $contenido = ContenidoApp::find($id_contenido);
        foreach ($terms as $indice => $id_term) {
            $contenido->terminos()->attach($id_term);
        }
    }

    function getContenidoAttribute($contenido) {
        return str_replace("\"", "'", $contenido);
    }

    //***********************************************
    //RELACION CON OTROS MODELOS*********************
    //***********************************************

    public function terminos() {
        return $this->belongsToMany('TerminoContenidoApp', 'relacion_contenidos_terminos_App', 'id_contenido', 'id_termino');
    }

}
