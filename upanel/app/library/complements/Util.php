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

        return $palabra;
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

        return $texto;
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

    /** Extrae el nombre de un archivo. (Puede ser una url)
     * 
     * @param String $nombre El nombre del archivo, con extension.
     * @return String El nombre de archivo
     */
    static function extraerNombreArchivo($nombre) {
        $nombre = explode("/", $nombre);
        end($nombre);
        $nombre = explode(".", pos($nombre));
        reset($nombre);
        return $nombre[0];
    }

    /** Indica si una url existe
     * 
     * @param String $url La Url a verificar
     * @return boolean
     */
    static function existeURL($url) {
        $url = @parse_url($url);
        if (!$url)
            return false;

        $url = array_map('trim', $url);
        $url['port'] = (!isset($url['port'])) ? 80 : (int) $url['port'];

        $path = (isset($url['path'])) ? $url['path'] : '/';
        $path .= (isset($url['query'])) ? "?$url[query]" : '';

        if (isset($url['host']) && $url['host'] != gethostbyname($url['host'])) {

            $fp = fsockopen($url['host'], $url['port'], $errno, $errstr, 30);

            if (!$fp)
                return false; //socket not opened

            fputs($fp, "HEAD $path HTTP/1.1\r\nHost: $url[host]\r\n\r\n"); //socket opened
            $headers = fread($fp, 4096);
            fclose($fp);

            if (preg_match('#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers)) {//matching header
                return true;
            } else
                return false;
        } // if parse url
        else
            return false;
    }

    static function eliminarExtensionArchivo($url) {
        $nombre = explode("/", $url);
        end($nombre);
        $nombre = explode(".", pos($nombre));
        reset($nombre);
        return str_replace("." . $nombre[1], "", $url);
    }

    static function obtenerUrlActual() {
        $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return $url;
    }

    static function descodificarTexto($texto) {
        $buscar = array("Á", "É", "Í", "Ó", "Ú", "á", "é", "í", "ó", "ú", "ñ", "Ñ", "\"");
        $reemplazar = array("&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&Ntilde;", "&ntilde;", "&quot;");
        return str_replace($buscar, $reemplazar, $texto);
    }

    static function oscurecerColor($color, $cant) {
//voy a extraer las tres partes del color
        $rojo = substr($color, 1, 2);
        $verd = substr($color, 3, 2);
        $azul = substr($color, 5, 2);

//voy a convertir a enteros los string, que tengo en hexadecimal
        $introjo = hexdec($rojo);
        $intverd = hexdec($verd);
        $intazul = hexdec($azul);



//ahora verifico que no quede como negativo y resto
        if ($introjo - $cant >= 0)
            $introjo = $introjo - $cant;
        if ($intverd - $cant >= 0)
            $intverd = $intverd - $cant;
        if ($intazul - $cant >= 0)
            $intazul = $intazul - $cant;

//voy a convertir a hexadecimal, lo que tengo en enteros
        $rojo = dechex($introjo);
        $verd = dechex($intverd);
        $azul = dechex($intazul);

//voy a validar que los string hexadecimales tengan dos caracteres
        if (strlen($rojo) < 2)
            $rojo = "0" . $rojo;
        if (strlen($verd) < 2)
            $verd = "0" . $verd;
        if (strlen($azul) < 2)
            $azul = "0" . $azul;

//voy a construir el color hexadecimal
        $oscuridad = "#" . $rojo . $verd . $azul;

//la función devuelve el valor del color hexadecimal resultante
        return $oscuridad;
    }

}
