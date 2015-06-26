<?php

class Util {

    const RUTA_MENSAJE_MODAL = "interfaz/mensaje/modal";

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

    /** Da formato de salida a un array
     *  
     * @param Object $array Listado de objetos de una misma clase
     * @param String $separador [, ] Un String separador entre cada propiedad del objeto
     * @param String $prefijo [null] Un String a colocar antecedido de la propiedad  del objeto 
     * @param String $sufijo [null] Un String a colocar posteriormente al valor de la propiedad del objeto
     * @return string Retorna un string en formato indicado. 
     */
    static function formatearResultadosArray($array, $separador = ", ", $prefijo = null, $sufijo = null) {
        $contenido = ""; //Almacena el resultado final
        if (count($array) == 0)
            return null;
        foreach ($array as $indice => $valor)
            $contenido.=$prefijo . $valor . $sufijo . $separador;
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

    /** Elimna la extesion de un nombre de archivo y devuelve el nombre del archivo
     * 
     * @param type $url
     * @return type
     */
    static function eliminarExtensionArchivo($url) {
        $nombre = explode("/", $url);
        end($nombre);
        $nombre = explode(".", pos($nombre));
        reset($nombre);
        return str_replace("." . $nombre[1], "", $url);
    }

    /** Retorna la url actual
     * 
     * @return string La url
     */
    static function obtenerUrlActual() {
        $url = Util::crearUrl($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        return $url;
    }

    static function crearUrl($url) {
        return($_SERVER['HTTPS']) ? "https://" . $url : "http://" . $url;
    }

    static function esConexionSegura() {
        if (isset($_SERVER['HTTPS']))
            return $_SERVER['HTTPS'];
        else
            return false;
    }

    /** Filtra una url obteniendo unicamente la url, sin datos enviados por Get. 
     * 
     * @param type $url
     */
    static function filtrarUrl($url) {
        if (strpos($url, "?") !== false) {
            $parts = explode("?", $url);
            return $parts[0];
        } else
            return $url;
    }

    static function obtenerDominioDeUrl($url) {
        $url = explode('://', $url);
        $ext = $url[0];
        $url = explode('/', $url[1]);
        return $ext . "://" . $url[0];
    }

    /** Reemplaza todos los caracteres especiales por codigo html
     * 
     * @param type $texto El texto a descodificar
     * @return type 
     */
    static function descodificarTexto($texto) {
        $buscar = array("Á", "É", "Í", "Ó", "Ú", "á", "é", "í", "ó", "ú", "ñ", "Ñ", "\"");
        $reemplazar = array("&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&Ntilde;", "&ntilde;", "&quot;");
        return str_replace($buscar, $reemplazar, $texto);
    }

    /** Indica si un color esta en formato RGB
     * 
     * @param type $color
     * @return type
     */
    static function esColorRGB($color) {
        return preg_match('/rgb\([0-9]+,[0-9]+,[0-9]+\)$/', Util::eliminarEspacios($color));
    }

    /** Convierte un color RGB en formato Hexadecimal
     * 
     * @param type $rgb [array|r,g,b|rgb(#,#,#)]
     * @return type Color hexadecimal
     */
    static function rgb2hex($rgb) {
        $rgb = Util::eliminarEspacios($rgb);
        if (is_string($rgb)) {
            if (strpos($rgb, "rgb") !== false) {
                $rgb = str_replace("rgb(", "", str_replace(")", "", $rgb));
            }
            $rgb = explode(",", $rgb);
        }

        $hex = "#";
        $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

        return $hex; // returns the hex value including the number sign (#)
    }

    /** Elimina todos los espacios en blanco de un texto
     * 
     * @param type $texto
     * @return type
     */
    static function eliminarEspacios($texto) {
        return (is_string($texto)) ? str_replace(" ", "", $texto) : $texto;
    }

    /** Retorna un color màs oscuro que el ingresado
     * 
     * @param type $color
     * @param type $cant
     * @return string
     */
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

    /** Obtiene un array asociativo con los nombre de los mess del año
     * 
     */
    static function obtenerNombreMeses() {
        return array("01" => trans("otros.fecha.enero"),
            "02" => trans("otros.fecha.febrero"),
            "03" => trans("otros.fecha.marzo"),
            "04" => trans("otros.fecha.abril"),
            "05" => trans("otros.fecha.mayo"),
            "06" => trans("otros.fecha.abril"),
            "07" => trans("otros.fecha.julio"),
            "08" => trans("otros.fecha.agosto"),
            "09" => trans("otros.fecha.septiembre"),
            "10" => trans("otros.fecha.octubre"),
            "11" => trans("otros.fecha.noviembre"),
            "12" => trans("otros.fecha.diciembre"));
    }

    static function convertirMayusculas($cadena) {
        $cadena = mb_strtoupper($cadena, 'utf-8');
        return ($cadena);
    }

    public static function obtenerTiempoActual($formato24 = true) {
        date_default_timezone_set('America/Bogota');
        return ($formato24) ? date('Y-m-d H:i:s') : date('Y-m-d h:i:sa');
    }

    /** Calcula diferencia entre dos fechas, la fecha1 es mayor que la fecha2 retorna false.
     * 
     * @param type $fecha1 La fecha menor
     * @param type $fecha2 La fecha mayor
     * @return string
     */
    public static function calcularDiferenciaFechas($fecha1, $fecha2) {
        $minuto = 60;
        $hora = $minuto * 60;
        $dia = $hora * 24;
        $mes = $dia * 30;
        $ano = $mes * 12;

        //formateamos las fechas a segundos tipo 1374998435
        $diferencia = strtotime($fecha2) - strtotime($fecha1);
        //comprobamos el tiempo que ha pasado en segundos entre las dos fechas
        //floor devuelve el número entero anterior
        if ($diferencia <= $minuto) {
            $tiempo = floor($diferencia) . " " . trans("otros.time.segundos");
        } else if ($diferencia >= $minuto && $diferencia < $minuto * 2) {
            $tiempo = "1 " . trans("otros.time.minuto");
        } else if ($diferencia >= $minuto * 2 && $diferencia < $hora) {
            $tiempo = floor($diferencia / $minuto) . " " . trans("otros.time.minutos");
        } else if ($diferencia >= $hora && $diferencia < $hora * 2) {
            $tiempo = "1 " . trans("otros.time.hora");
        } else if ($diferencia >= $hora * 2 && $diferencia < $dia) {
            $tiempo = floor($diferencia / $hora) . " " . trans("otros.time.horas");
        } else if ($diferencia >= $dia && $diferencia < $dia * 2) {
            $tiempo = "1 " . trans("otros.time.dia");
        } else if ($diferencia >= $dia * 2 && $diferencia < $mes) {
            $tiempo = floor($diferencia / $dia) . " " . trans("otros.time.dias");
        } else if ($diferencia >= $mes && $diferencia < $mes * 2) {
            $tiempo = "1 " . trans("otros.time.mes");
        } else if ($diferencia >= $mes * 2 && $diferencia < $ano) {
            $tiempo = floor($diferencia / $mes) . " " . trans("otros.time.meses");
        } else if ($diferencia >= $ano && $diferencia < $ano * 2) {
            $tiempo = "1 " . trans("otros.time.ano");
        } else if ($diferencia >= $ano * 2) {
            $tiempo = floor($diferencia / $ano) . " " . trans("otros.time.anos");
        }


        return (intval($tiempo) <= 0) ? false : strtolower($tiempo);
    }

    /** Convierte un valor entero en un valor booleano
     * 
     * @param type $int El valor entero a convertir
     * @return type Retorna un valor booleano
     */
    static function convertirIntToBoolean($int) {
        return (intval($int) > 0);
    }

    /** Convierte un valor booleano en un entero binario
     * 
     * @param type $bool El valor booleano a convertir
     * @return type
     */
    static function convertirBooleanToInt($bool) {
        if (is_bool($bool))
            return ($bool) ? 1 : 0;
        else
            return null;
    }

    /** Obtiene la direccion IP del usuario
     * 
     * @return type
     */ 
    static function obtenerDireccionIP() {
        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
            return $_SERVER["HTTP_FORWARDED"];
        } else {
            return $_SERVER["REMOTE_ADDR"];
        }
    }

}
