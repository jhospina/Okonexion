<?php

class ControladorApp extends \BaseController {

    const configKey_App = "key_app";

    function descargar_noticias() {
        $key_app = Input::get("key_app");
        $cant = Input::get("cant_om");
        $app = Aplicacion::buscar($key_app);
        if (is_null($app))
            return null;
        return Contenido_Noticias::cargarDatosJson($app->id, Contenido_Noticias::NUMERO_REGISTROS_DESCARGAR, intval($cant));
    }

    function descargar_institucional() {
        $key_app = Input::get("key_app");
        $app = Aplicacion::buscar($key_app);
        if (is_null($app))
            return null;
        return Contenido_Institucional::cargarDatosJson($app->id, $app->id_usuario);
    }

    function descargar_encuestas_vigente() {
        $key_app = Input::get("key_app");
        $app = Aplicacion::buscar($key_app);
        if (is_null($app))
            return null;
        return Contenido_Encuestas::obtenerJSON_encuesta_vigente($app->id_usuario);
    }

    function descargar_encuestas_archivadas() {
        $key_app = Input::get("key_app");
        $cant = Input::get("cant_om");
        $app = Aplicacion::buscar($key_app);
        if (is_null($app))
            return null;
        return Contenido_Encuestas::obtenerJSON_encuestas_archivadas($app->id, Contenido_Encuestas::NUMERO_REGISTROS_DESCARGAR, intval($cant));
    }

    /** Recibe las peticiones cuando un usuario de dispositivo responde a una encuesta
     * 
     * @return type
     */
    function enviar_encuestas_respuesta() {
        $data = Input::all();
        $key_app = $data["key_app"];
        $respuesta = intval($data["respuesta"]);
        $id_dispositivo = $data["id_dispositivo"];
        $id_encuesta = intval($data["id_encuesta"]);

        $app = Aplicacion::buscar($key_app);
        if (is_null($app))
            return null;

        Contenido_Encuestas::contestar($respuesta, $id_encuesta, $id_dispositivo, $app->id_usuario);
    }

    function enviarPqr() {
        $data = Input::all();
        $key_app = $data["key_app"];
        $dispositivo = $data["dispositivo"];
        $nombre = $data["nombre"];
        $email = $data["email"];
        $asunto = $data["asunto"];
        $descripcion = $data["descripcion"];
        $tipo = $data["tipo_pqr"];
        $app = Aplicacion::buscar($key_app);
        if (is_null($app))
            return null;

        return Contenido_PQR::registrar($app->id, $app->id_usuario, $dispositivo, $nombre, $email, $asunto, $descripcion, Contenido_PQR::tipo($tipo));
    }

    function recibirPqr() {
        $data = Input::all();
        $ids_pqr = array();

        foreach ($data as $index => $id)
            if (strpos($index, "id_pqr") !== false)
                $ids_pqr[] = $id;

        return Contenido_PQR::obtenerPrqUsuario($ids_pqr);
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
