<?php

class Monedas {

    const USD = "USD";
    const USD_SIMBOL = "&#36;";
    const USD_FORMAT = "2|,|.";
    const EUR = "EUR";
    const EUR_SIMBOL = "&euro;";
    const EUR_FORMAT = "2|.|,";
    const COP = "COP";
    const COP_SIMBOL = "&#36;";
    const COP_FORMAT = "0|.|,";

    
    static function actual(){
        return Instancia::obtenerValorMetadato(ConfigInstancia::info_moneda);
    }
    
    /** Retorna una array con el codigo de las moneas disponibles
     * 
     * @return type
     */
    static function listado() {
        $class = new ReflectionClass("Monedas");
        $mons = array();
        foreach ($class->getConstants() as $index => $value) {
            if (strlen($index) == 3) {
                $mons[$index] = $value;
            }
        }
        return $mons;
    }

    static function constantes() {
        $class = new ReflectionClass("Monedas");
        return $class->getConstants();
    }

    /** Obtiene el simbolo del la moneda dada por su identificador constante
     * 
     * @param type $id Identificador constante de la moneda
     * @return type
     */
    static function simbolo($id) {
        $mons = Monedas::constantes();
        return (isset($mons[$id . "_SIMBOL"])) ? $mons[$id . "_SIMBOL"] : null;
    }

    /** Obtiene el formato de valro de la moneda dada por su identificador constante
     * 
     * @param type $id Identificador constante de la moneda
     * @return type
     */
    static function formato($id) {
        $mons = Monedas::constantes();
        if (isset($mons[$id . "_FORMAT"]))
            $format = $mons[$id . "_FORMAT"];
        else
            return null;

        $params = explode("|", $format);

        return array(intval($params[0]), $params[1], $params[2]);
    }
    
    static function nomenclatura($id,$numero){ 
        return Monedas::simbolo($id)."".$numero." ".$id;
    }

    /** Retorna un numero formateado indicado por la moneda 
     * 
     * @param String $id Identificador constante de la moneda
     * @param Int $numero
     * @return String
     */
    static function formatearNumero($id, $numero) {
        if(is_null($numero))
            return null;
        
        list($cantDecimales, $sepadorMillar, $sepadorDecimal) = Monedas::formato($id);
        return (is_numeric($numero)) ? number_format($numero, $cantDecimales, $sepadorDecimal, $sepadorMillar) : null;
    }

    static function desformatearNumero($id, $numero) {
        list($cantDecimales, $sepadorMillar, $sepadorDecimal) = Monedas::formato($id);
        $numero = str_replace($sepadorMillar, "", $numero);
        $numero = str_replace($sepadorDecimal, ".", $numero);
        return doubleval($numero);
    }

}
