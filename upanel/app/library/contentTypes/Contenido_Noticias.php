<?php

class Contenido_Noticias {

    const nombre = "noticias";
    const icono = "glyphicon-globe";
    //********************************************************
    //DATOS DE CONFIGURACION EN APP***************************
    //********************************************************

    const configTitulo = "titulo"; //Indica el titulo de la noticia
    const configDescripcion = "descripcion"; //Indica la descripcion de la imagen
    const configImagen = "imagen"; //Indica la url del imagen principal de la noticia
    //********************************************************
    //CONFIGURACION DE ADMINISTRACION*************************
    //********************************************************
    const taxonomia = "categorias_noticias"; //Indica el prefijo de la taxonomia de este tipo de contenido
    //********************************************************
    //ATRIBUTOS DE IMAGEN*************************
    //********************************************************
    const IMAGEN_MIME_TYPE = "MIME_TYPE";
    const IMAGEN_URL = "URL";
    const IMAGEN_TITULO = "TITULO";

    /** Prepara los requisitos de administracion de las noticias
     * 
     * @param Aplicacion $app El modelo objeto de la aplicacion del usuario 
     */
    static function prepararRequisitos($app) {

        if (TaxonomiaContenidoApp::existe($nombreTax))
            return;

        //Crear una taxonomia de categorias para las noticias
        $tax = new TaxonomiaContenidoApp;
        $tax->id_aplicacion = $app->id;
        $tax->id_usuario = Auth::user()->id;
        $tax->nombre = Contenido_Noticias::taxonomia;
        $tax->descripcion = "Taxonomia de Categorias de Noticias";
        $tax->save();

        //Crea la categoria "Sin Categoria" dentro de la taxonomia anteriormente creada
        $term = new TerminoContenidoApp;
        $term->id_taxonomia = $tax->id;
        $term->nombre = "Sin categoria";
        $term->save();
    }

    static function agregar($data, $estado) {
        $contenido = new ContenidoApp;
        $contenido->id_aplicacion = Aplicacion::ID();
        $contenido->id_usuario = Auth::user()->id;
        $contenido->tipo = Contenido_Noticias::nombre;
        $contenido->titulo = $data[Contenido_Noticias::configTitulo];
        $contenido->contenido = $data[Contenido_Noticias::configDescripcion];
        $contenido->estado = $estado;

        @$contenido->save();

        if (strlen($data[Contenido_Noticias::configImagen . "_id"]) > 0)
            ContenidoApp::agregarMetaDato($contenido->id, Contenido_Noticias::configImagen . "_principal", $data[Contenido_Noticias::configImagen . "_id"]);

        $cats = array(); //almacenara los ID de las categorias

        foreach ($data as $indice => $valor) {
            if (strpos($indice, "term-") !== false) {
                $cats[] = $valor;
            }
        }

        //Si no se tiene ninguna categoria elegida se le asinga la primera "Sin categoria"
        if (count($cats) == 0) {
            $cats[] = 1;
        }
        //Establece las categorias de la noticia
        ContenidoApp::establecerTerminos($contenido->id, $cats);
    }

    static function editar($id_noticia, $data, $estado) {
        $contenido = ContenidoApp::find($id_noticia);
        $contenido->tipo = Contenido_Noticias::nombre;
        $contenido->titulo = $data[Contenido_Noticias::configTitulo];
        $contenido->contenido = $data[Contenido_Noticias::configDescripcion];
        $contenido->estado = $estado;

        @$contenido->save();

        if (!Contenido_Noticias::tieneImagen($id_noticia)) {
            if (strlen($data[Contenido_Noticias::configImagen . "_id"]) > 0)
                ContenidoApp::agregarMetaDato($contenido->id, Contenido_Noticias::configImagen . "_principal", $data[Contenido_Noticias::configImagen . "_id"]);
        }

        $cats = array(); //almacenara los ID de las categorias

        foreach ($data as $indice => $valor) {
            if (strpos($indice, "term-") !== false) {
                $cats[] = $valor;
            }
        }
        //Si no se tiene ninguna categoria elegida se le asinga la primera "Sin categoria"
        if (count($cats) == 0) {
            $cats[] = 1;
        }
        //Establece las categorias de la noticia
        ContenidoApp::establecerTerminos($contenido->id, $cats);
    }

    static function validar($data) {
        if (strlen($data[Contenido_Noticias::configTitulo]) < 1) {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, "Debes escribir un titulo.", 2));
        }

        if (strlen($data[Contenido_Noticias::configDescripcion]) < 5) {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, "Debes escribir una descripción.", 2));
        }

        return null;
    }

    /** Indica si una noticia contiene imagen principap
     * 
     * @param Int $id_noticia Id de la noticia
     * @return boolean
     */
    static function tieneImagen($id_noticia) {
        $meta = MetaContenidoApp::where("clave", Contenido_Noticias::configImagen . "_principal")->where("id_contenido", $id_noticia)->get();
        if (count($meta) > 0)
            return true;
        else
            return false;
    }

    /** Obtiene la imagen de una noticia, si se indica el atributo retornara solo este. 
     *  
     * @param Int $id_noticia Id de la notica a la que pertene la imagen
     * @param String $atributo (Opcional) Atributo de la imagen a obtener
     * @return type Si no se indica el atributo, retorna el objeto modelo que contiene la imagen. Si se indica el atributo [TITULO, URL, MIME_TYPE] se retorna el atributo de la imagen. En caso de que la imagen no existe retornara NULL. 
     */
    static function obtenerImagen($id_noticia, $atributo = null) {

        $metas = MetaContenidoApp::where("clave", Contenido_Noticias::configImagen . "_principal")->where("id_contenido", $id_noticia)->get();
        if (count($metas) > 0) {
            foreach ($metas as $meta)
                break;

            $imagen = ContenidoApp::find($meta->valor);
            //Retorna el atributo indicado
            switch ($atributo) {
                case Contenido_Noticias::IMAGEN_MIME_TYPE:
                    $imagen->mime_type;
                case Contenido_Noticias::IMAGEN_TITULO:
                    return $imagen->titulo;
                case Contenido_Noticias::IMAGEN_URL:
                    return $imagen->contenido;
                default :
                    return $imagen;
                    break;
            }
        } else {
            return null;
        }
    }

}
