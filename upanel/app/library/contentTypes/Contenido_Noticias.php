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
    const IMAGEN_ID = "ID";
    //********************************************************
    //ATRIBUTOS DE MINIATURAS*********************************
    //********************************************************
    const IMAGEN_FORMATO_NOMBRE_MINIATURA = "-%dx%d";
    const IMAGEN_ANCHO_MINIATURA_BG = 621; // Miniatura Grande
    const IMAGEN_ALTURA_MINIATURA_BG = 483;
    const IMAGEN_ANCHO_MINIATURA_MD = 450; // Miniatura Media
    const IMAGEN_ALTURA_MINIATURA_MD = 350;
    const IMAGEN_ANCHO_MINIATURA_SM = 207; // Miniatura Pequeña
    const IMAGEN_ALTURA_MINIATURA_SM = 161;
    const IMAGEN_NOMBRE_MINIATURA_BG = "BG"; //Nombre de miniaturas
    const IMAGEN_NOMBRE_MINIATURA_MD = "MD";
    const IMAGEN_NOMBRE_MINIATURA_SM = "SM";

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

    /** Agrega un nuevo contenido de noticias
     * 
     * @param type $data Un array con los datos de la noticia a agregar. 
     * @param type $estado El estado en el que debe quedar la noticia
     */
    static function agregar($data, $estado) {
        $contenido = new ContenidoApp;
        $contenido->id_aplicacion = Aplicacion::ID();
        $contenido->id_usuario = Auth::user()->id;
        $contenido->tipo = Contenido_Noticias::nombre;
        $contenido->titulo = $data[Contenido_Noticias::configTitulo];
        $contenido->contenido = $data[Contenido_Noticias::configDescripcion];
        $contenido->estado = $estado;

        @$contenido->save();

        if (strlen($data[Contenido_Noticias::configImagen . "_id"]) > 0) {
            ContenidoApp::agregarMetaDato($contenido->id, Contenido_Noticias::configImagen . "_principal", $data[Contenido_Noticias::configImagen . "_id"]);
            $imagenPrincipal = ContenidoApp::find($data[Contenido_Noticias::configImagen . "_id"]);
            Contenido_Noticias::crearMiniaturaImagen($imagenPrincipal->contenido, Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_BG);
            Contenido_Noticias::crearMiniaturaImagen($imagenPrincipal->contenido, Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_MD);
            Contenido_Noticias::crearMiniaturaImagen($imagenPrincipal->contenido, Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_SM);
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

    /** Edita el contenido de una noticia
     * 
     * @param type $id_noticia El id de la noticia a editar
     * @param type $data Los datos a editar en un array
     * @param type $estado El estado en que debe quedar la noticia
     */
    static function editar($id_noticia, $data, $estado) {
        $contenido = ContenidoApp::find($id_noticia);
        $contenido->tipo = Contenido_Noticias::nombre;
        $contenido->titulo = $data[Contenido_Noticias::configTitulo];
        $contenido->contenido = $data[Contenido_Noticias::configDescripcion];
        $contenido->estado = $estado;

        @$contenido->save();

        if (!Contenido_Noticias::tieneImagen($id_noticia)) {
            if (strlen($data[Contenido_Noticias::configImagen . "_id"]) > 0) {
                ContenidoApp::agregarMetaDato($contenido->id, Contenido_Noticias::configImagen . "_principal", $data[Contenido_Noticias::configImagen . "_id"]);
                $imagenPrincipal = ContenidoApp::find($data[Contenido_Noticias::configImagen . "_id"]);
                Contenido_Noticias::crearMiniaturaImagen($imagenPrincipal->contenido, Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_BG);
                Contenido_Noticias::crearMiniaturaImagen($imagenPrincipal->contenido, Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_MD);
                Contenido_Noticias::crearMiniaturaImagen($imagenPrincipal->contenido, Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_SM);
            }
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
                    return $imagen->mime_type;
                case Contenido_Noticias::IMAGEN_TITULO:
                    return $imagen->titulo;
                case Contenido_Noticias::IMAGEN_URL:
                    return $imagen->contenido;
                case Contenido_Noticias::IMAGEN_ID:
                    return $imagen->id;
                default :
                    return $imagen;
            }
        } else {
            return null;
        }
    }

    /** Crear una imagen miniatura en formato de noticia para la URL de imagen dada. 
     * 
     * @param String $url La Url de la imagen original
     * @param String $tamano El tamaño de la miniatura a obtener
     */
    static function crearMiniaturaImagen($url, $tamano = null) {

        if (is_null($tamano))
            $tamano = Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_BG;

        $imagen = new Imagen($url);
        switch ($tamano) {
            case Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_BG:
                $imagen->crearCopia(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_BG, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_BG, Contenido_Noticias::obtenerNombreMiniatura(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_BG, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_BG), $imagen->getRuta());
                break;
            case Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_MD:
                $imagen->crearCopia(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_MD, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_MD, Contenido_Noticias::obtenerNombreMiniatura(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_MD, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_MD), $imagen->getRuta());
                break;
            case Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_SM:
                $imagen->crearCopia(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_SM, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_SM, Contenido_Noticias::obtenerNombreMiniatura(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_SM, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_SM), $imagen->getRuta());
                break;
            default :
                $imagen->crearCopia(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_BG, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_BG, Contenido_Noticias::obtenerNombreMiniatura(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_BG, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_BG), $imagen->getRuta());
                break;
        }
    }

    /** Obtiene la url de una imagen miniatura dada por la url de la imagen original
     * 
     * @param type $url Url de la imagen original
     * @param String $tamano El tamaño de la miniatura a obtener
     * @return String Retorna la Url de la imagen miniatura si existe, si no retornara la url de la imagen original dada. 
     */
    static function obtenerUrlMiniaturaImagen($url, $tamano = null) {

        if (is_null($tamano))
            $tamano = Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_BG;

        $nombre_imagen = Util::extraerNombreArchivo($url);

        switch ($tamano) {
            case Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_BG:
                $imagen_min = str_replace($nombre_imagen, $nombre_imagen . Contenido_Noticias::obtenerNombreMiniatura(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_BG, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_BG), $url);
                break;
            case Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_MD:
                $imagen_min = str_replace($nombre_imagen, $nombre_imagen . Contenido_Noticias::obtenerNombreMiniatura(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_MD, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_MD), $url);
                break;
            case Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_SM:
                $imagen_min = str_replace($nombre_imagen, $nombre_imagen . Contenido_Noticias::obtenerNombreMiniatura(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_SM, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_SM), $url);
                break;
            default :
                $imagen_min = str_replace($nombre_imagen, $nombre_imagen . Contenido_Noticias::obtenerNombreMiniatura(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_BG, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_BG), $url);
                break;
        }

        if (Util::existeURL($imagen_min))
            return $imagen_min;
        else
            return $url;
    }

    /** Retorna un texto con el formato indicado para el nombre de una miniatura
     * 
     * @param type $ancho
     * @param type $altura
     * @return type
     */
    static function obtenerNombreMiniatura($ancho, $altura) {
        return sprintf(Contenido_Noticias::IMAGEN_FORMATO_NOMBRE_MINIATURA, $ancho, $altura);
    }

    static function eliminarImagen($id_imagen) {
        //Formato de miniaturas de la imagen
        $miniaturas = array(Contenido_Noticias::obtenerNombreMiniatura(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_BG, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_BG),
            Contenido_Noticias::obtenerNombreMiniatura(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_MD, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_MD),
            Contenido_Noticias::obtenerNombreMiniatura(Contenido_Noticias::IMAGEN_ANCHO_MINIATURA_SM, Contenido_Noticias::IMAGEN_ALTURA_MINIATURA_SM)
        );

        //Elima la imagen y sus posibles miniaturas
        if (ContenidoApp::eliminarImagen($id_imagen, $miniaturas))
            MetaContenidoApp::where("clave", Contenido_Noticias::configImagen . "_principal")->where("valor", $id_imagen)->delete();
    }

}
