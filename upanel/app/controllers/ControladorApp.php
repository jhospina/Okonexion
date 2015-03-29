<?php

class ControladorApp extends \BaseController {

    function descargar_noticias() {

        $key_app = Input::get("key_app");
        $cant=Input::get("cant");
        //$base_info = Input::get("num_base_info");
       //$key_app = "I2uqHDXS3RR8lgmaCOG9eZmcO15w7O6x0kxFoKYfbpbCLDdNR";

        $apps = Aplicacion::where("key_app", $key_app)->get();
        foreach ($apps as $app)
            break;

        return Contenido_Noticias::cargarDatosJson($app->id,16,  intval($cant));
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
