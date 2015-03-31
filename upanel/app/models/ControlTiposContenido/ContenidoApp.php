<?php

class ContenidoApp extends Eloquent {

    protected $table = 'contenidosApp';

    const ESTADO_PUBLICO = "publico";
    const ESTADO_GUARDADO = "guardado";
    const ESTADO_ARCHIVADO = "archivado";
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

    /** Elimina una imagen del sistema
     * 
     * @param type $id_imagen El Id de la imagen a eliminar
     * @param Array $miniaturas Un array con las miniaturas de la imagen, si es que existes, para eliminar tambien. 
     * @return boolean
     */
    static function eliminarImagen($id_imagen, $miniaturas = null) {
        $imagen = ContenidoApp::find($id_imagen);

        $img = new Imagen($imagen->contenido);

        if (File::exists(Util::convertirUrlPath($imagen->contenido))) {
            File::delete(Util::convertirUrlPath($imagen->contenido));

            //Elimina imagenes miniatura de la imagen original si existeb
            for ($i = 0; $i < count($miniaturas); $i++) {

                $url = $img->getRuta_url() . $img->getNombre() . $miniaturas[$i] . "." . $img->getExtension();

                if (Util::existeURL($url))
                    File::delete(Util::convertirUrlPath($url));
            }

            $imagen->delete();
            return true;
        }

        return false;
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
        $meta->id_usuario=Auth::user()->id;
        $meta->clave = $clave;
        $meta->valor = $valor;
        $meta->save();
    }

    /** Obtiene el valor de un metadado dado por su valor clave
     * 
     * @param type $id_contenido Id del contenido al que pertenece el metadato
     * @param type $clave El valor clave que identifica el metadato
     * @return type Retorna el valor del metadato en caso de exito, de lo contrario Null. 
     */
    static function obtenerMetadato($id_contenido, $clave) {
        $metas = MetaContenidoApp::where("id_contenido", $id_contenido)->where("clave", $clave)->get();
        foreach ($metas as $meta)
            return $meta;
        return null;
    }
    
    /** Actualiza el valor de un metadato de un contenido
     * 
     * @param Int $id_contenido Id del contenido al que pertenece el metadato
     * @param String $clave La clave del metadato
     * @param String $valor El valor del metadato a actualizar
     * @return boolean El resultado de la operacion
     */
    static function actualizarMetadato($id_contenido,$clave,$valor){
        $meta=ContenidoApp::obtenerMetadato($id_contenido, $clave);
        $meta->valor=$valor;
        return $meta->save();
    }

    /** Obtiene el de un metadato de un contenido
     * 
     * @param type $id_contenido
     * @param type $clave
     * @return String Retorna el valro del metatado o null si no existe
     */
    static function obtenerValorMetadato($id_contenido, $clave) {
        $metas = MetaContenidoApp::where("id_contenido", $id_contenido)->where("clave", $clave)->get();
        foreach ($metas as $meta)
            return $meta->valor;
        return null;
    }

    /** Indica si el metadato de un tipo de contenido exite 
     * 
     * @param Int $id_contenido Id del tipo de contenido
     * @param String $clave La clave del metadato
     * @return boolean
     */
    static function existeMetadato($id_contenido, $clave) {
        $metas = MetaContenidoApp::where("id_contenido", $id_contenido)->where("clave", $clave)->get();

        if (count($metas) > 0)
            return true;
        else
            return false;
    }

    /** Elimina todos los metadatos de un contenido
     * 
     * @param type $id_contenido El id del contenido
     */
    static function vaciarMetadatos($id_contenido) {
        $metas = MetaContenidoApp::where("id_contenido", $id_contenido)->delete();
    }

    /** Establece los terminos(categorias) a un contenido
     * 
     * @param int $id_contenido ID del contenido
     * @param array $terms Arreglo que contiene los Ids de los terminos a asociar con el contenido 
     */
    static function establecerTerminos($id_contenido, $terms) {
        ContenidoApp::eliminarTerminos($id_contenido);
        $contenido = ContenidoApp::find($id_contenido);
        foreach ($terms as $indice => $id_term) {
            $contenido->terminos()->attach($id_term);
        }
    }

    /** Elimina todos los terminos establecidos a un contenido
     * 
     * @param type $id_contenido Id del contenido
     */
    static function eliminarTerminos($id_contenido) {
        DB::table("relacion_contenidos_terminos_App")->where("id_contenido", $id_contenido)->delete();
    }

    public function obtenerNombreTabla() {
        return $this->table;
    }

    static function nombreTabla() {
        $cont = new ContenidoApp;
        return $cont->obtenerNombreTabla();
    }

    //***********************************************
    //MODIFICACION DE ATRIBUTOS*********************
    //***********************************************
    /*
      function getContenidoAttribute($contenido) {
      return str_replace("\"", "'", $contenido);
      } */

    //***********************************************
    //RELACION CON OTROS MODELOS*********************
    //***********************************************

    public function terminos() {
        return $this->belongsToMany('TerminoContenidoApp', 'relacion_contenidos_terminos_App', 'id_contenido', 'id_termino');
    }

}
