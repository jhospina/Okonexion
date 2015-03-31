<?php

class ControladorApp extends \BaseController {

    const NUMERO_REGISTROS_DESCARGAR = 16;

    function descargar_noticias() {
        $key_app = Input::get("key_app");
        $cant = Input::get("cant_om");
        
        $app=  Aplicacion::buscar($key_app);

        return Contenido_Noticias::cargarDatosJson($app->id, ControladorApp::NUMERO_REGISTROS_DESCARGAR, intval($cant));
    }

    function descargar_institucional() {
        $key_app = Input::get("key_app");
       $app=Aplicacion::buscar($key_app);
        return Contenido_Institucional::cargarDatosJson($app->id,$app->id_usuario);
    }

    //RUTA DE ACCESO: usuarios/uploads/{usuario}/{imagen}/{mime_type}
    //Carga una imagen
    function cargarImagen($usuario, $imagen, $mime_type) {
        //$mime_type="jpg";
        Header("Content-type:image/" . $mime_type);

        $url = str_replace("/$mime_type", ".$mime_type", Util::obtenerUrlActual());

        switch ($mime_type) {
            case "jpg":
                $img = imagecreatefromjpeg(Util::convertirUrlPath($url));
                imagejpeg($img);
                break;
            case "jpeg":
                $img = imagecreatefromjpeg(Util::convertirUrlPath(str_replace("/$mime_type", ".jpg", Util::obtenerUrlActual())));
                imagejpeg($img);
                break;
            case "png":
                $img = imagecreatefrompng(Util::convertirUrlPath($url));
                imagepng($img);
                break;
            case "gif":
                $img = imagecreatefromgif(Util::convertirUrlPath($url));
                imagegif($img);
                break;
        }
    }

}
