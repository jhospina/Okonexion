<?php

class ControladorApp extends \BaseController {

    function conectar() {

        //$key_app = Input::get("key_app");
        $key_app = "I2uqHDXS3RR8lgmaCOG9eZmcO15w7O6x0kxFoKYfbpbCLDdNR";

        $apps = Aplicacion::where("key_app", $key_app)->get();
        foreach ($apps as $app)
            break;

        $noticias = ContenidoApp::where("tipo", Contenido_Noticias::nombre)->where("id_aplicacion", $app->id)->get();

        $data_noticias = array();
        $n = 1;

        foreach ($noticias as $noticia) {
            $data_noticias["titulo" . $n] = $noticia->titulo;
            $data_noticias["descripcion" . $n] = $noticia->contenido;
            $data_noticias["imagen" . $n] = Contenido_Noticias::obtenerUrlMiniaturaImagen(Contenido_Noticias::obtenerImagen($noticia->id, Contenido_Noticias::IMAGEN_URL),  Contenido_Noticias::IMAGEN_NOMBRE_MINIATURA_BG);
            $n++;
        }

        return Aplicacion::prepararDatosParaApp($data_noticias);
    }

}
