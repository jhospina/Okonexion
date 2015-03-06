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

    /** Recorta un texto hasta la longitud dada.
     * 
     * @param type $texto El texto a recortar
     * @param type $longitud La longitud en caracteres
     * @return string El texto recortado
     */
    static function recortarTexto($texto, $longitud) {
        $texto = strip_tags($texto);
        $texto_final = "";
        $palabras = explode(" ", $texto);
        for ($i = 0; $i < count($palabras); $i++) {
            $texto_final.=$palabras[$i] . " ";
            if (strlen($texto_final) >= $longitud)
                return substr($texto_final, 0, strlen($texto_final) - 1) . "...";
        }
    }

    /** Da formato de salida a un listado de objetos dado por la propiedad a imprimir del objeto.
     *  
     * @param Object $objetos Listado de objetos de una misma clase
     * @param String $propiedad  El nombre del a propiedad del objeto a imprimir
     * @param String $separador [, ] Un String separador entre cada propiedad del objeto
     * @param String $prefijo [null] Un String a colocar antecedido de la propiedad  del objeto 
     * @param String $sufijo [null] Un String a colocar posteriormente al valor de la propiedad del objeto
     * @return string Retorna un string en formato indicado. 
     */
    static function formatearResultadosObjetos($objetos, $propiedad, $separador = ", ", $prefijo = null, $sufijo = null) {
        $contenido = ""; //Almacena el resultado final

        foreach ($objetos as $objeto)
            $contenido.=$prefijo . $objeto[$propiedad] . $sufijo . $separador;

        return substr($contenido, 0, strlen($contenido) - strlen($separador));
    }

    /** Extraer la extension de un archivo
     * 
     * @param type $nombre
     * @return type
     */
    static function extraerExtensionArchivo($nombre) {
        $desc = explode(".", $nombre);
        return end($desc);
    }

    static function extraerNombreArchivo($nombre) {
        $desc = explode(".", $nombre);
        return $desc[count($desc) - 2];
    }

}
