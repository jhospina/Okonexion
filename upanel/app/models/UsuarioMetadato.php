<?php

class UsuarioMetadato extends Eloquent {

    protected $table = 'usuariosMetadatos';
    public $timestamps = false;

    const OP_IDIOMA = "op_idioma";
    const SUSCRIPCION_TIPO = "suscripcion_tipo";
    const HASH_CREAR_FACTURA = "hash_crear_factura"; //Codigo hash de seguridad para crear una factura
    const FACTURACION_ID_PROCESO = "facturacion_id_proceso"; // Identificador de factura en proceso para orden de pago

    /** Obtiene un array de con el nombre de las opciones del usuario
     * 
     * @return type
     */

    static function obtenerNombreOpciones() {
        $class = new ReflectionClass("UsuarioMetadato");
        return $class->getConstants();
    }

    //Obtiene un array con el valor de las opciones predeterminada
    static function obtenerDefinicionOpcionesPredeterminada() {
        $opciones[UsuarioMetadato::OP_IDIOMA] = "es"; // Idioma español
        return $opciones;
    }

}
