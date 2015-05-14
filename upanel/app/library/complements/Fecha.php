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
    function agregarDias($num){
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

}
