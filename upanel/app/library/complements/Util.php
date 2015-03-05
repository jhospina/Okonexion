<?php

class Util {

    /** Elimina la plularidad de una palabra
     * 
     * @param String $palabra
     * @return String Retorna la palabra en singular, de lo contrario null
     */
    static function eliminarPluralidad($palabra) {

        if (!is_string($palabra))
            return null;

        if ($palabra[strlen($palabra) - 2] == "e" && $palabra[strlen($palabra) - 1] == "s")
            return substr($palabra, 0, strlen($palabra) - 2);

        if ($palabra[strlen($palabra) - 1] == "s")
            return substr($palabra, 0, strlen($palabra) - 1);
    }

    /** Obtiene el timeStamp del servidor indicado por año-mes-dia-hora-minutos-segundos
     * 
     * @return String
     */
    static function obtenerTimeStamp() {
        date_default_timezone_set('America/Bogota');
        return date("YmdGis");
    }

    /** Convierte una URL en una direccion Path del servidor
     * 
     * @param String $URL La url a convertir
     * @return String Retorna el Path en caso de exito, de lo contrario null
     */
    static function convertirUrlPath($URL) {

        //Verifica que seea una URL valida
        if (filter_var($URL, FILTER_VALIDATE_URL) === false)
            return null;


        $URL = str_replace("\\", "/", $URL);

        //HTTP NORMAL
        if (strpos($URL, "http") !== false)
            $URL = str_replace("http://", "", $URL);

        //HTTP SECURE
        if (strpos($URL, "https") !== false)
            $URL = str_replace("https://", "", $URL);

        $public = public_path("");
        $desc = explode("/", $public);
        $ultimo = end($desc);
        $posRaiz = strpos($URL, $ultimo);
        return $public . substr($URL, $posRaiz + strlen($ultimo));
    }

}