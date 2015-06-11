<?php

class Fecha {

    const FORMATO = "Y-m-d H:i:s";

    var $ano;
    var $mes;
    var $dia;
    var $hora;
    var $min;
    var $seg;
    var $object;

    /** EL constructor debe recibir una cadena de fecha en formato Y-m-d H:i:s
     * 
     * @param type $fecha
     */
    function __construct($fecha) {
        $this->asignarAtributos($fecha);
    }

    function __toString() {
        return $this->object->format(Fecha::FORMATO);
    }

    /** Agrega un numero definido de emes a la fecha objeto y lo reasigna a la fecha
     * 
     * @param type $num El numero de meses a añadir
     * @return String Retorna la nueva fecha
     */
    function agregarMeses($num) {
        $this->object->add(date_interval_create_from_date_string("$num months"));
        $nueva_fecha = $this->object->format(Fecha::FORMATO);
        $this->asignarAtributos($this->object->format(Fecha::FORMATO));
        return $nueva_fecha;
    }

    /** Agrega un numero definido de semanas a la fecha objeto y lo reasigna a la fecha
     * 
     * @param type $num El numero de semanas a añadir
     * @return String Retorna la nueva fecha
     */
    function agregarSemanas($num) {
        $this->object->add(date_interval_create_from_date_string("$num weeks"));
        $nueva_fecha = $this->object->format(Fecha::FORMATO);
        $this->asignarAtributos($this->object->format(Fecha::FORMATO));
        return $nueva_fecha;
    }

    /** Agrega un numero definido de dias a la fecha objeto y lo reasigna a la fecha
     * 
     * @param type $num El numero de dias a añadir
     * @return String Retorna la nueva fecha
     */
    function agregarDias($num) {
        $this->object->add(date_interval_create_from_date_string("$num days"));
        $nueva_fecha = $this->object->format(Fecha::FORMATO);
        $this->asignarAtributos($this->object->format(Fecha::FORMATO));
        return $nueva_fecha;
    }

    /** Asigna los atributos de fecha al objeto de la clase
     * 
     * @param type $fecha Cadena de fecha en formato Y-m-d H:i:s
     */
    private function asignarAtributos($fecha) {

        list($ano, $mes, $dia, $hora, $min, $seg) = sscanf($fecha, "%d-%d-%d %d:%d:%d");

        $this->ano = $ano;
        $this->mes = $mes;
        $this->dia = $dia;
        $this->hora = $hora;
        $this->min = $min;
        $this->seg = $seg;
        $this->object = new DateTime($fecha);
    }

    static function formatear($fecha) {
        $meses = Util::obtenerNombreMeses();

        $ft = explode(" ", $fecha);

        $fecha = explode("-", $ft[0]);

        return trans("otros.fecha.formato_01", array("dia" => $fecha[2], "mes" => $meses[$fecha[1]], "ano" => $fecha[0], "hora" => $ft[1]));
    }

    static function calcularDiferencia($fecha1, $fecha2) {
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
        } elseif ($diferencia >= $minuto && $diferencia < $minuto * 2) {
            $tiempo = "1 " . trans("otros.time.minuto");
        } elseif ($diferencia >= $minuto * 2 && $diferencia < $hora) {
            $tiempo = floor($diferencia / $minuto) . " " . trans("otros.time.minutos");
        } elseif ($diferencia >= $hora && $diferencia < $hora * 2) {
            $tiempo = "1 " . trans("otros.time.hora");
        } elseif ($diferencia >= $hora * 2 && $diferencia < $dia) {
            $tiempo = floor($diferencia / $hora) . " " . trans("otros.time.horas");
        } elseif ($diferencia >= $dia && $diferencia < $dia * 2) {
            $tiempo = "1 " . trans("otros.time.dia");
        } elseif ($diferencia >= $dia * 2 && $diferencia < $mes) {
            $tiempo = floor($diferencia / $dia) . " " . trans("otros.time.dias");
        } elseif ($diferencia >= $mes && $diferencia < $mes * 2) {
            $tiempo = "1 " . trans("otros.time.mes") . " " . Fecha::calcularDiferencia($fecha1, date(Fecha::FORMATO, intval($diferencia - $mes) + strtotime($fecha1)));
        } elseif ($diferencia >= $mes * 2 && $diferencia < $ano) {
            $tiempo = floor($diferencia / $mes) . " " . trans("otros.time.meses") . " " . Fecha::calcularDiferencia($fecha1, date(Fecha::FORMATO, intval($diferencia - $mes * floor($diferencia / $mes) + strtotime($fecha1))));
        } elseif ($diferencia >= $ano && $diferencia < $ano * 2) {
            $tiempo = "1 " . trans("otros.time.ano"). " " . Fecha::calcularDiferencia($fecha1, date(Fecha::FORMATO, intval($diferencia - $ano) + strtotime($fecha1)));
        } elseif ($diferencia >= $ano * 2) {
            $tiempo = floor($diferencia / $ano) . " " . trans("otros.time.anos");
        }


        return (intval($tiempo) <= 0 ) ? false : strtolower($tiempo);
    }

}
