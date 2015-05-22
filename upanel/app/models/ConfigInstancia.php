<?php

class ConfigInstancia extends Eloquent {

    protected $table = 'instanciasMetadatos';
    public $timestamps = false;

    const periodoPrueba_activado = "periodoPrueba_activado";
    const periodoPrueba_numero_dias = "periodoPrueba_numero_dias";
    //************************************************************
    //CONFIGURACION DE SUSCRIPCION********************************
    //************************************************************

    const suscripcion_valor_1mes = "suscripcion_valor_1mes";
    const suscripcion_valor_3mes = "suscripcion_valor_3mes";
    const suscripcion_valor_6mes = "suscripcion_valor_6mes";
    const suscripcion_valor_12mes = "suscripcion_valor_12mes";
    const suscripcion_valor_3mes_descuento = "suscripcion_valor_3mes_descuento";
    const suscripcion_valor_6mes_descuento = "suscripcion_valor_6mes_descuento";
    const suscripcion_valor_12mes_descuento = "suscripcion_valor_12mes_descuento";
    //************************************************************
    //CONFIGURACION DE DATOS****************************************
    //************************************************************

    const info_moneda = "info_moneda";
    //************************************************************
    //CONFIGURACION VISUAL****************************************
    //************************************************************
    const visual_logo = "visual_logo";

    //************************************************************
    //CONFIGURACION DE FACTURACIÓN********************************
    //************************************************************


    static function obtenerListadoConfig() {
        $class = new ReflectionClass("ConfigInstancia");
        return $class->getConstants();
    }

    /** Valida los datos de configuracion
     * 
     * @param type $data
     * @return type
     */
    static function validar($data) {
        $valid = array();
        foreach ($data as $config => $valor) {
            $reglas = ConfigInstancia::reglasDeValidacion($config);
            if ($reglas) {
                $valid[$config] = ConfigInstancia::reglasDeValidacion($config);
            }
        }
        $validator = Validator::make($data, $valid);

        return $validator->passes();
    }

    /** Retorna los parametros de validacion del campo de configuracion indicado
     * 
     * @param type $config El ID config 
     * @return string
     */
    static function reglasDeValidacion($config) {
        $valid = array(
            ConfigInstancia::periodoPrueba_numero_dias => "required|numeric",
            ConfigInstancia::suscripcion_valor_1mes => "required",
            ConfigInstancia::suscripcion_valor_3mes => "required",
            ConfigInstancia::suscripcion_valor_6mes => "required",
            ConfigInstancia::suscripcion_valor_12mes => "required",
            ConfigInstancia::suscripcion_valor_3mes_descuento => "required",
            ConfigInstancia::suscripcion_valor_6mes_descuento => "required",
            ConfigInstancia::suscripcion_valor_12mes_descuento => "required",
        );
        return (isset($valid[$config])) ? $valid[$config] : false;
    }

    static function registrar($data) {
        $configs = ConfigInstancia::obtenerListadoConfig();
        foreach ($data as $config => $valor) {
            if (in_array($config, $configs))
                Instancia::actualizarMetadato($config, $valor);
        }
    }

}
