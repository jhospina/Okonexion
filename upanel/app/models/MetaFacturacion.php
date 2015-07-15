<?php

Class MetaFacturacion extends Eloquent {

    protected $table = 'facturacionMetadatos';
    public $timestamps = false;

    const PRODUCTO_ID = "producto_id";
    const PRODUCTO_VALOR = "producto_valor";
    const PRODUCTO_DESCUENTO = "producto_descuento";
    const PRODUCTO_PROCESADO = "producto_procesado";
    const PRODUCTO_OBSERVACIONES = "producto_observacion";
    const MONEDA_ID = "moneda_id";
    const CLIENTE_INFO = "cliente_info";
    const METODO_PAGO = "metodo_pago";
    const TRANSACCION_ID = "transaccion_id";
    const TRANSACCION_ID_ORDEN = "transaccion_id_orden";
    const TRANSACCION_CODIGO_TRAZABILIDAD = "transaccion_codigo_trazabilidad";
    const TRANSACCION_CODIGO_AUTORIZACION = "transaccion_codigo_autorizacion";
    const TRANSACCION_FECHA_OPERACION = "transaccion_fecha_operacion";
    const FECHA_PAGO = "fecha_pago";

}
