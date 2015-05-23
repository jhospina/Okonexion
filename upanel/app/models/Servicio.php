<?php

class Servicio extends Eloquent {

    protected $table = 'servicios';
    public $timestamps = false;

    const ESTADO_ACTIVO = "ON";
    const ESTADO_INACTIVO = "OF";
    const COL_NOMBRE = "nombre_servicio";
    const COL_COSTO = "costo_servicio";
    const COL_DESCRIPCION = "descripcion_servicio";
    const COL_ESTADO = "estado_servicio";
    const COL_ID = "id_servicio";

    static function validar($data) {
        $validator = Validator::make($data, Servicio::reglasDeValidacion());

        return $validator->passes();
    }

    static function reglasDeValidacion() {
        $valid = array(
            Servicio::COL_NOMBRE => "required|min:5",
            Servicio::COL_COSTO => "required"
        );
        return $valid;
    }

    function registrar($data) {
        $this->nombre = $data[Servicio::COL_NOMBRE];
        $this->costo = Monedas::desformatearNumero(Instancia::obtenerValorMetadato(ConfigInstancia::info_moneda), $data[Servicio::COL_COSTO]);
        if (strlen($data[Servicio::COL_DESCRIPCION]) > 0)
            $this->descripcion = $data[Servicio::COL_DESCRIPCION];
        $this->estado = Servicio::ESTADO_INACTIVO;
        return $this->save();
    }

}
