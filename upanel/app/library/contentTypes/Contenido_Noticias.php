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

}
