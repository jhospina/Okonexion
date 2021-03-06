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

        if (!User::tieneEspacio($app->id_usuario))
            return;

        return Contenido_Encuestas::contestar($respuesta, $id_encuesta, $id_dispositivo, $app->id_usuario);
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
        $id_padre = $data["id_padre"];
        $app = Aplicacion::buscar($key_app);
        if (is_null($app))
            return null;

        return Contenido_PQR::registrar($app->id, $app->id_usuario, $dispositivo, $nombre, $email, $asunto, $descripcion, Contenido_PQR::tipo($tipo), $id_padre);
    }

    function recibirPqr() {
        $data = Input::all();
        $ids_pqr = array();

        foreach ($data as $index => $id)
            if (strpos($index, "id_pqr") !== false)
                $ids_pqr[] = $id;

        return Contenido_PQR::obtenerPrqUsuario($ids_pqr);
    }

    function enviar_metaRegistrar() {
        $data = Input::all();
        $key_app = $data["key_app"];
        if (isset($data["clave"])) {
            $clave = $data["clave"];
            $valor = $data["valor"];
        }


        $app = Aplicacion::buscar($key_app);
        if (is_null($app))
            return null;

        if (!User::tieneEspacio($app->id_usuario))
            return;

        //Si la cadena JSON viene con más de un metadato a registrar, realiza el proceso de registro de todos los metadatos
        if (count($data) > 3 && isset($data["clave0"])) {
            $n = count($data) - 1;

            for ($i = 0; $i < ($n / 2); $i++) {

                $clave = $data["clave" . $i];
                $valor = $data["valor" . $i];

                if (Aplicacion::existeMetadato($clave, $app->id))
                    continue;

                Aplicacion::agregarMetadato($clave, $valor, $app->id, $app->id_usuario);
            }
            return null;
        }


        return json_encode(Aplicacion::agregarMetadato($clave, $valor, $app->id, $app->id_usuario));
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
