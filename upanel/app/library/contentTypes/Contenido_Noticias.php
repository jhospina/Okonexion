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

    static function validar($data) {
        if (strlen($data[Contenido_Noticias::configTitulo]) < 1) {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, "Debes escribir un titulo.", 2));
        }

        if (strlen($data[Contenido_Noticias::configDescripcion]) < 5) {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, "Debes escribir una descripción.", 2));
        }

        return null;
    }

}
