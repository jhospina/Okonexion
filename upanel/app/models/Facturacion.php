<?php

Class Facturacion extends Eloquent {

    protected $table = 'facturacion';
    public $timestamps = false;

    const ESTADO_SIN_PAGAR = "SP";

    /** Crear una nueva factura, con estado SIN PAGAR
     * 
     * @param double $iva [0] $iva El valor del iva, si aplica. 
     * @param int $vencimiento [1] El tiempo de vencimiento para la factura dado en numero de dias
     * @param int $id_usuario [null] El id del usuario al que se le creara la factura
     * @return int Retorna el id de la factura creada, de lo contrario retorna Null
     */
    static function nueva($iva = 0, $vencimiento = 1, $id_usuario = null) {

        $fecha_creacion = Util::obtenerTiempoActual();

        $fact = new Facturacion();
        $fact->instancia = Auth::user()->instancia;

        if (is_null($id_usuario))
            $id_usuario = Auth::user()->id;

        $fact->id_usuario = $id_usuario;

        $fact->estado = Facturacion::ESTADO_SIN_PAGAR;
        $fact->iva = $iva;
        $fact->total = 0;
        $fact->fecha_creacion = $fecha_creacion;
        $fecha_venc = new Fecha($fecha_creacion);
        $fact->fecha_vencimiento = $fecha_venc->agregarDias($vencimiento);

        return ($fact->save()) ? $fact->id : null;
    }

    /** Se agrega un producto a una factura dada pro su id
     * 
     * @param int $id_factura
     * @param array $producto
     * @return boolean
     */
    static function agregarProducto($id_factura, $producto) {
        $n = 1;
        //Intenta determinar el numero del producto en el listado de la factura
        while (Facturacion::existeMetadato(MetaFacturacion::PRODUCTO_ID . $n, $id_factura))
            $n++;

        if (!isset($producto[MetaFacturacion::PRODUCTO_ID]) || !isset($producto[MetaFacturacion::PRODUCTO_VALOR]) || !isset($producto[MetaFacturacion::PRODUCTO_DESCUENTO]))
            return false;

        $total_producto = doubleval($producto[MetaFacturacion::PRODUCTO_VALOR]);
        $descuento_producto = doubleval($producto[MetaFacturacion::PRODUCTO_DESCUENTO]);


        $fact = Facturacion::find($id_factura);

        if ($fact->estado != Facturacion::ESTADO_SIN_PAGAR)
            return false;

        //Agrega el nuevo producto a la factura y lo almacena en la base de datos
        Facturacion::agregarMetadato(MetaFacturacion::PRODUCTO_ID . $n, $producto[MetaFacturacion::PRODUCTO_ID], $id_factura);
        Facturacion::agregarMetadato(MetaFacturacion::PRODUCTO_VALOR . $n, $total_producto, $id_factura);
        Facturacion::agregarMetadato(MetaFacturacion::PRODUCTO_DESCUENTO . $n, $descuento_producto, $id_factura);

        //ATENCIÓN: Bajo la premisa de que el del valor producto ya contiene el descuento, se hace el siquiere calculo.
        //Se recalcula el valor total de la factura
        if (is_null($iva = $fact->iva))
            $total_producto = $total_producto + ($total_producto * (doubleval($iva) / 100));

        $fact->total = $fact->total + $total_producto;
        return $fact->save();
    }

    /**
     * Indica si una opcion de configuracion existe, dada por su dato clave. 
     *
     * @param String clave
     * @return boolean
     */
    static function existeMetadato($clave, $id_factura) {
        $configs = MetaFacturacion::where("id_factura", $id_factura)->where("clave", "=", $clave)->get();
        return (count($configs) > 0);
    }

    /** Agrega una nuevo metadato a la factura, si el metadato ya existe, lo actualiza
     * 
     * @param String $clave La clave del metadato
     * @param String $valor El valor del metadato
     * @param Int $id_factura (Opcional) Si no se especifica se toma el de la sesion actual
     */
    static function agregarMetadato($clave, $valor, $id_factura) {
        $meta = new MetaFacturacion;

        if (Facturacion::existeMetadato($clave, $id_factura))
            return MetaFacturacion::actualizarMetadato($clave, $valor, $id_factura);

        $meta->id_factura = $id_factura;
        $meta->clave = $clave;
        $meta->valor = $valor;
        return $meta->save();
    }

    /** Actualiza el valor de un metadato de una factura, y si no existe lo crea
     * 
     * @param String $clave La clave del metadato
     * @param String $valor El valor del metadato a actualizar
     * @param Int $id_factura (Opcional) Si no se especifica se toma el de la sesion actual
     * @return boolean El resultado de la operacion
     */
    static function actualizarMetadato($clave, $valor, $id_factura) {
        $meta = MetaFacturacion::obtenerMetadato($clave, $id_factura);
        //Si no existe lo agrega
        if (is_null($meta))
            return MetaFacturacion::agregarMetadato($clave, $valor, $id_factura);

        $meta->valor = $valor;

        return $meta->save();
    }

    /** Obtiene el objeto de un metadado dado por su valor clave
     * 
     * @param type $clave El valor clave que identifica el metadato
     * @param Int $id_factura (Opcional) Si no se especifica se toma el de la sesion actual
     * @return type Retorna el valor del metadato en caso de exito, de lo contrario Null. 
     */
    static function obtenerMetadato($clave, $id_factura) {
        $metas = MetaFacturacion::where("id_factura", $id_factura)->where("clave", $clave)->get();
        foreach ($metas as $meta)
            return $meta;
        return null;
    }

    static function eliminarMetadato($clave, $id_factura) {
        $config = MetaFacturacion::obtenerMetadato($clave, $id_factura);
        return $config->delete();
    }

}
