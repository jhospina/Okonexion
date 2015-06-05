<?php

class Servicio extends Eloquent {

    protected $table = 'servicios';
    public $timestamps = false;

    const ESTADO_ACTIVO = "ON";
    const ESTADO_INACTIVO = "OF";
    const COL_ESTADO = "estado_servicio";
    const COL_ID = "id_servicio";
    const CONFIG_NOMBRE = "servicio_nombre-";
    const CONFIG_DESCRIPCION = "servicio_descripcion-";
    const CONFIG_COSTO = "servicio_costo-";
    const CONFIG_IMAGEN = "servicio_imagen";

    static function validar($data) {

        $nombre = json_decode($data[Servicio::CONFIG_NOMBRE], true);
        $descripcion = json_decode($data[Servicio::CONFIG_DESCRIPCION], true);
        $costo = json_decode($data[Servicio::CONFIG_COSTO], true);

        //Comprueba y valida el nombre del servicio en los distintos idiomas
        foreach ($nombre as $index => $valor) {
            $config = substr($index, 0, strpos($index, "-") + 1);
            $validator = Validator::make(array($config => $valor), Servicio::reglasDeValidacion($config));
            if (!$validator->passes())
                return false;
        }

        //Valida el valor de los costos
        foreach ($costo as $index => $valor) {
            $config = substr($index, 0, strpos($index, "-") + 1);
            $validator = Validator::make(array($config => $valor), Servicio::reglasDeValidacion($config));
            if (!$validator->passes())
                return false;
        }

        return true;
    }

    static function reglasDeValidacion($config) {
        $valid = array(
            Servicio::CONFIG_NOMBRE => "required|min:5",
            Servicio::CONFIG_COSTO => "required"
        );

        if (isset($valid[$config])) {
            return array($config => $valid[$config]);
        }

        return null;
    }

    function registrar($data) {
        $this->instancia = Auth::user()->instancia;
        $this->estado = Servicio::ESTADO_INACTIVO;
        return $this->save();
    }

    function setNombre($nombre, $clave) {
        return Instancia::agregarMetadato($clave . $this->id, $nombre);
    }

    function setDescripcion($descripcion, $clave) {
        return Instancia::agregarMetadato($clave . $this->id, $descripcion);
    }

    function setCosto($costo, $clave) {
        return Instancia::agregarMetadato($clave . $this->id, $costo);
    }

    function setImagen($url, $clave) {
        return Instancia::agregarMetadato($clave . $this->id, $url);
    }

    function getNombre($idioma = null) {
        if (is_null($idioma))
            $idioma = Idioma::actual();

        return Instancia::obtenerValorMetadato(Servicio::CONFIG_NOMBRE . $idioma . $this->id);
    }

    function getCosto($moneda = null) {
        if (is_null($moneda))
            $moneda = Monedas::actual();
        return Instancia::obtenerValorMetadato(Servicio::CONFIG_COSTO . $moneda . $this->id);
    }

}
