<?php

Class MetaFacturacion extends Eloquent {

    protected $table = 'facturacionMetadatos';
    public $timestamps = false;

    const PRODUCTO_ID = "producto_id";
    const PRODUCTO_VALOR = "producto_valor";
    const PRODUCTO_DESCUENTO = "producto_descuento";
    const MONEDA_ID = "moneda_id";

}
