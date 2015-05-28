<?php

class ConfigInstancia extends Eloquent {

    protected $table = 'instanciasMetadatos';
    public $timestamps = false;

    const periodoPrueba_activado = "periodoPrueba_activado";
    const periodoPrueba_numero_dias = "periodoPrueba_numero_dias";
    //************************************************************
    //CONFIGURACION DE SUSCRIPCION********************************
    //************************************************************
    const suscripcion_tipo_bronce = "bronce";
    const suscripcion_tipo_plata = "plata";
    const suscripcion_tipo_oro = "oro";
    const suscripcion_valor_1mes_bronce = "suscripcion_valor_1mes_bronce";
    const suscripcion_valor_3mes_bronce = "suscripcion_valor_3mes_bronce";
    const suscripcion_valor_6mes_bronce = "suscripcion_valor_6mes_bronce";
    const suscripcion_valor_12mes_bronce = "suscripcion_valor_12mes_bronce";
    const suscripcion_valor_1mes_plata = "suscripcion_valor_1mes_plata";
    const suscripcion_valor_3mes_plata = "suscripcion_valor_3mes_plata";
    const suscripcion_valor_6mes_plata = "suscripcion_valor_6mes_plata";
    const suscripcion_valor_12mes_plata = "suscripcion_valor_12mes_plata";
    const suscripcion_valor_1mes_oro = "suscripcion_valor_1mes_oro";
    const suscripcion_valor_3mes_oro = "suscripcion_valor_3mes_oro";
    const suscripcion_valor_6mes_oro = "suscripcion_valor_6mes_oro";
    const suscripcion_valor_12mes_oro = "suscripcion_valor_12mes_oro";
    const suscripcion_valor_3mes_descuento = "suscripcion_valor_3mes_descuento";
    const suscripcion_valor_6mes_descuento = "suscripcion_valor_6mes_descuento";
    const suscripcion_valor_12mes_descuento = "suscripcion_valor_12mes_descuento";
    //************************************************************
    //ID DE PRODUCTOS DE SUSCRIPCION****************************************
    //************************************************************
    const producto_suscripcion_bronce1 = "producto_suscripcion_bronce1";
    const producto_suscripcion_bronce3 = "producto_suscripcion_bronce3";
    const producto_suscripcion_bronce6 = "producto_suscripcion_bronce6";
    const producto_suscripcion_bronce12 = "producto_suscripcion_bronce12";
    const producto_suscripcion_plata1 = "producto_suscripcion_plata1";
    const producto_suscripcion_plata3 = "producto_suscripcion_plata3";
    const producto_suscripcion_plata6 = "producto_suscripcion_plata6";
    const producto_suscripcion_plata12 = "producto_suscripcion_plata12";
    const producto_suscripcion_oro1 = "producto_suscripcion_oro1";
    const producto_suscripcion_oro3 = "producto_suscripcion_oro3";
    const producto_suscripcion_oro6 = "producto_suscripcion_oro6";
    const producto_suscripcion_oro12 = "producto_suscripcion_oro12";
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
            ConfigInstancia::suscripcion_valor_1mes_bronce => "required",
            ConfigInstancia::suscripcion_valor_3mes_bronce => "required",
            ConfigInstancia::suscripcion_valor_6mes_bronce => "required",
            ConfigInstancia::suscripcion_valor_12mes_bronce => "required",
            ConfigInstancia::suscripcion_valor_1mes_plata => "required",
            ConfigInstancia::suscripcion_valor_3mes_plata => "required",
            ConfigInstancia::suscripcion_valor_6mes_plata => "required",
            ConfigInstancia::suscripcion_valor_12mes_plata => "required",
            ConfigInstancia::suscripcion_valor_1mes_oro => "required",
            ConfigInstancia::suscripcion_valor_3mes_oro => "required",
            ConfigInstancia::suscripcion_valor_6mes_oro => "required",
            ConfigInstancia::suscripcion_valor_12mes_oro => "required",
            ConfigInstancia::suscripcion_valor_3mes_descuento => "required",
            ConfigInstancia::suscripcion_valor_6mes_descuento => "required",
            ConfigInstancia::suscripcion_valor_12mes_descuento => "required",
        );
        return (isset($valid[$config])) ? $valid[$config] : false;
    }

    static function registrar($data) {
        $configs = ConfigInstancia::obtenerListadoConfig();
        foreach ($data as $config => $valor) {

            if (strpos($config, "valor") !== false)
                $valor = Monedas::desformatearNumero(Monedas::actual(), $valor);

            if (in_array($config, $configs))
                Instancia::actualizarMetadato($config, $valor);
        }
    }

}
