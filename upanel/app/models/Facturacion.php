<?php

Class Facturacion extends Eloquent {

    protected $table = 'facturacion';
    public $timestamps = false;

    const ESTADO_SIN_PAGAR = "SP";
    const ESTADO_PAGADO = "PA";
    const ESTADO_VENCIDA = "VE";
    const TIPOPAGO_TARJETA_CREDITO_ATRAVES_2CHECKOUTS = "T2";

    /** Retorna la descripcion del estado de la factura
     * 
     * @param type $id
     * @return type
     */
    static function estado($id) {
        $estados = array(Facturacion::ESTADO_SIN_PAGAR => trans("atributos.estado.factura.sin.pagar"),
            Facturacion::ESTADO_PAGADO => trans("atributos.estado.factura.pagado"),
            Facturacion::ESTADO_VENCIDA => trans("atributos.estado.factura.vencida")
        );

        return (isset($estados[$id])) ? $estados[$id] : null;
    }

    static function tipo($id_tipo) {
        return trans("atributos.tipo.pago.factura." . Facturacion::TIPOPAGO_TARJETA_CREDITO_ATRAVES_2CHECKOUTS);
    }

    /** Crear una nueva factura, con estado SIN PAGAR
     * 
     * @param double $iva [0] $iva El valor del iva, si aplica. 
     * @param int $vencimiento [1] El tiempo de vencimiento para la factura dado en numero de dias
     * @param int $id_factura [null] El id del usuario al que se le creara la factura
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
     * @param array $producto [MetaFacturacion::PRODUCTO_ID][MetaFacturacion::PRODUCTO_VALOR][MetaFacturacion::PRODUCTO_DESCUENTO] 
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
        Facturacion::agregarMetadato(MetaFacturacion::PRODUCTO_PROCESADO . $n, Util::convertirBooleanToInt(false), $id_factura);


        //ATENCIÓN: Bajo la premisa de que el del valor producto ya contiene el descuento, se hace el siquiere calculo.
        //Se recalcula el valor total de la factura
        $fact->subtotal = $fact->subtotal + $total_producto;

        //Suma el valor del impuesto al total de la factura
        $iva = $fact->iva;
        $valor_iva = ($iva / 100) * $fact->subtotal;
        $fact->total = $valor_iva + $fact->subtotal;



        return $fact->save();
    }

    static function obtenerProductos($id_factura) {
        $buscar = MetaFacturacion::where("id_factura", $id_factura)->where("clave", "LIKE", MetaFacturacion::PRODUCTO_ID . "%")->get();

        if (is_null($buscar))
            return null;

        //Cantidad de productos agregados a la factura
        $cant = count($buscar);

        $productos = array();

        while ($cant > 0) {
            $producto = array();
            $producto[MetaFacturacion::PRODUCTO_ID] = Facturacion::obtenerValorMetadato(MetaFacturacion::PRODUCTO_ID . $cant, $id_factura);
            $producto[MetaFacturacion::PRODUCTO_VALOR] = Facturacion::obtenerValorMetadato(MetaFacturacion::PRODUCTO_VALOR . $cant, $id_factura);
            $producto[MetaFacturacion::PRODUCTO_DESCUENTO] = Facturacion::obtenerValorMetadato(MetaFacturacion::PRODUCTO_DESCUENTO . $cant, $id_factura);
            $productos[] = $producto;
            $cant--;
        }

        return $productos;
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
    static function agregarMetadato($clave, $valor, $id_factura, $id_usuario = null) {
        $meta = new MetaFacturacion;

        if (Facturacion::existeMetadato($clave, $id_factura))
            return Facturacion::actualizarMetadato($clave, $valor, $id_factura);

        if (is_null($id_usuario))
            $id_usuario = Auth::user()->id;
        $meta->id_usuario = $id_usuario;
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
        $meta = Facturacion::obtenerMetadato($clave, $id_factura);

        //Si no existe lo agrega
        if (is_null($meta))
            return Facturacion::agregarMetadato($clave, $valor, $id_factura);

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
        $config = Facturacion::obtenerMetadato($clave, $id_factura);
        return $config->delete();
    }

    /** Obtiene el valor de un metadato dado por su nombre clave
     * 
     * @param type $clave
     * @param Int $id_factura (Opcional) Si no se especifica se toma el de la sesion actual
     * @return String Retorna el valro del metatado o null si no existe
     */
    static function obtenerValorMetadato($clave, $id_factura) {
        $metas = MetaFacturacion::where("id_factura", $id_factura)->where("clave", $clave)->get();
        foreach ($metas as $meta)
            return $meta->valor;
        return null;
    }

    /** Valida los productos adquiridos por el usuarios y los hace cumplir
     * 
     * @param type $id_factura El id de la factura 
     */
    static function validarProductos($id_factura) {
        $productos = Facturacion::obtenerProductos($id_factura);
        foreach ($productos as $producto) {




            //Valida la suscripcion y las aplica
            if (strpos($producto[MetaFacturacion::PRODUCTO_ID], "suscripcion") !== false) {

                //Crea una notificacion
                Notificacion::crear(Notificacion::TIPO_SUSCRIPCION_REALIZADA);

                //SI el usuario tiene una suscripcion vigente, se le añade el tiempo si afectar el tiempo de la suscripcion actual
                if (Auth::user()->estado == User::ESTADO_SUSCRIPCION_VIGENTE)
                    $fecha = new Fecha(Auth::user()->fin_suscripcion);
                else
                    $fecha = new Fecha(Util::obtenerTiempoActual());

                Auth::user()->fin_suscripcion = $fecha->agregarMeses(ConfigInstancia::obtenerCantidadMesesProductosSuscripcion($producto[MetaFacturacion::PRODUCTO_ID]));
                Auth::user()->estado = User::ESTADO_SUSCRIPCION_VIGENTE;
                Auth::user()->save();

                if (strpos($producto[MetaFacturacion::PRODUCTO_ID], ConfigInstancia::suscripcion_tipo_bronce) !== false)
                    User::agregarMetaDato(UsuarioMetadato::SUSCRIPCION_TIPO, ConfigInstancia::suscripcion_tipo_bronce);
                elseif (strpos($producto[MetaFacturacion::PRODUCTO_ID], ConfigInstancia::suscripcion_tipo_plata) !== false)
                    User::agregarMetaDato(UsuarioMetadato::SUSCRIPCION_TIPO, ConfigInstancia::suscripcion_tipo_plata);
                elseif (strpos($producto[MetaFacturacion::PRODUCTO_ID], ConfigInstancia::suscripcion_tipo_oro) !== false)
                    User::agregarMetaDato(UsuarioMetadato::SUSCRIPCION_TIPO, ConfigInstancia::suscripcion_tipo_oro);
            }
        }
    }

    /** Valida el pago de una factura
     * 
     * @param Facturacion $factura El objeto de la factura
     * @param int $id_transaccion //El id de la trasaccion
     * @param string $tipo_pago El tipo de pago realizado
     */
    static function validarPago($factura, $id_transaccion, $tipo_pago) {
        Facturacion::generarJSONCliente($factura->id);
        //Se almacena el numero de la transaccion arrojada por el servidor de pagos
        Facturacion::agregarMetadato(MetaFacturacion::TRANSACCION_ID, $id_transaccion, $factura->id);
        //Se registra la fecha de pago
        Facturacion::agregarMetadato(MetaFacturacion::FECHA_PAGO, Util::obtenerTiempoActual(), $factura->id);
        //Se establece el estado de la factura como pagado
        $factura->estado = Facturacion::ESTADO_PAGADO;
        //Se establece tipo de pago de la factura
        $factura->tipo_pago = $tipo_pago;
        //Se elimina la factura en proceso del cliente
        User::eliminarMetadato(UsuarioMetadato::FACTURACION_ID_PROCESO);
        $factura->save();
        //Se validan y se efectuan los productos pagados por el usuario
        Facturacion::validarProductos($factura->id);
    }

    static function generarJSONCliente($id_factura) {
        $json = array();
        $json["empresa"] = Auth::user()->empresa;
        $json["dni"] = Auth::user()->dni;
        $json["nombre"] = Auth::user()->nombres . " " . Auth::user()->apellidos;
        $json["email"] = Auth::user()->email;
        $json["direccion"] = Auth::user()->direccion;
        $json["lugar"] = Auth::user()->ciudad . ", " . Auth::user()->region;
        $json["pais"] = Paises::obtenerNombre(Auth::user()->pais);
        //Almacena una copia de los datos del cliente con la que se hizo la factura
        Facturacion::agregarMetadato(MetaFacturacion::CLIENTE_INFO, json_encode($json), $id_factura);
    }

}
