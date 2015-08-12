<?php

Class Facturacion extends Eloquent {

    protected $table = 'facturacion';
    public $timestamps = false;

    const ESTADO_SIN_PAGAR = "SP";
    const ESTADO_PAGADO = "PA";
    const ESTADO_VENCIDA = "VE";
    const GATEWAY_2CHECKOUT = "2CH";
    const GATEWAY_PAYU = "PYU";

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
        return trans("atributos.gateway.factura." . $id_tipo);
    }

    /** Crear una nueva factura, con estado SIN PAGAR
     * 
     * @param double $iva [0] $iva El valor del iva, si aplica. 
     * @param int $vencimiento [1] El tiempo de vencimiento para la factura dado en numero de dias
     * @param int $id_factura [null] El id del usuario al que se le creara la factura
     * @return int Retorna el id de la factura creada, de lo contrario retorna Null
     */
    static function nueva($vencimiento = 1, $id_usuario = null) {

        $fecha_creacion = Util::obtenerTiempoActual();

        $fact = new Facturacion();


        if (is_null($id_usuario))
            $id_usuario = Auth::user()->id;

        $usuario = User::find($id_usuario);

        $fact->instancia = $usuario->instancia;

        $fact->id_usuario = $id_usuario;

        $fact->estado = Facturacion::ESTADO_SIN_PAGAR;
        $fact->iva = (Auth::user()->getMoneda() == Monedas::COP) ? Instancia::obtenerValorMetadato(ConfigInstancia::fact_impuestos_iva) : 0;
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
    static function agregarProducto($id_factura, $producto, $id_usuario = null) {

        if (is_null($id_usuario))
            $id_usuario = Auth::user()->id;


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
        Facturacion::agregarMetadato(MetaFacturacion::PRODUCTO_ID . $n, $producto[MetaFacturacion::PRODUCTO_ID], $id_factura, $id_usuario);
        Facturacion::agregarMetadato(MetaFacturacion::PRODUCTO_VALOR . $n, $total_producto, $id_factura, $id_usuario);
        Facturacion::agregarMetadato(MetaFacturacion::PRODUCTO_DESCUENTO . $n, $descuento_producto, $id_factura, $id_usuario);
        Facturacion::agregarMetadato(MetaFacturacion::PRODUCTO_PROCESADO . $n, Util::convertirBooleanToInt(false), $id_factura, $id_usuario);


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

            if (strpos($producto[MetaFacturacion::PRODUCTO_ID], "actualizacion") !== false) {

                //Actualiza la suscripcion a plata
                if ($producto[MetaFacturacion::PRODUCTO_ID] == ConfigInstancia::producto_suscripcion_plata_actualizacion) {
                    User::actualizarMetadato(UsuarioMetadato::SUSCRIPCION_TIPO, ConfigInstancia::suscripcion_tipo_plata);
                    //Actualiza el espacio asignado
                    User::actualizarMetadato(UsuarioMetadato::ESPACIO_DISCO_ASIGNADO, ConfigInstancia::obtenerEspacioEnDiscoPermitidoDeSuscripcion(ConfigInstancia::suscripcion_tipo_plata));
                }
                //Actualiza la suscripcion a oro
                if ($producto[MetaFacturacion::PRODUCTO_ID] == ConfigInstancia::producto_suscripcion_oro_actualizacion) {
                    User::actualizarMetadato(UsuarioMetadato::SUSCRIPCION_TIPO, ConfigInstancia::suscripcion_tipo_oro);
                    //Actualiza el espacio asignado
                    User::actualizarMetadato(UsuarioMetadato::ESPACIO_DISCO_ASIGNADO, ConfigInstancia::obtenerEspacioEnDiscoPermitidoDeSuscripcion(ConfigInstancia::suscripcion_tipo_oro));
                }


                continue;
            }

            //Valida la suscripcion nuevas o de renovacion y las aplica
            if (strpos($producto[MetaFacturacion::PRODUCTO_ID], "suscripcion") !== false) {

                //SI el usuario tiene una suscripcion vigente, se le añade el tiempo si afectar el tiempo de la suscripcion actual
                if (Auth::user()->estado == User::ESTADO_SUSCRIPCION_VIGENTE) {
                    $fecha = new Fecha(Auth::user()->fin_suscripcion);
                    //Crea una notificacion
                    Notificacion::crear(Notificacion::TIPO_SUSCRIPCION_RENOVADA);
                } else {
                    $fecha = new Fecha(Util::obtenerTiempoActual());
                    //Crea una notificacion
                    Notificacion::crear(Notificacion::TIPO_SUSCRIPCION_REALIZADA);
                }

                $ciclo = ConfigInstancia::obtenerCantidadMesesProductosSuscripcion($producto[MetaFacturacion::PRODUCTO_ID]);

                //Asigna el tiempo de suscripcion del usuario
                Auth::user()->fin_suscripcion = $fecha->agregarMeses($ciclo);
                Auth::user()->estado = User::ESTADO_SUSCRIPCION_VIGENTE;
                Auth::user()->save();

                //Asigna al usuario el ciclo de suscripcion
                User::agregarMetaDato(UsuarioMetadato::SUSCRIPCION_CICLO, $ciclo);

                if (strpos($producto[MetaFacturacion::PRODUCTO_ID], ConfigInstancia::suscripcion_tipo_bronce) !== false)
                    User::agregarMetaDato(UsuarioMetadato::SUSCRIPCION_TIPO, ConfigInstancia::suscripcion_tipo_bronce);
                elseif (strpos($producto[MetaFacturacion::PRODUCTO_ID], ConfigInstancia::suscripcion_tipo_plata) !== false)
                    User::agregarMetaDato(UsuarioMetadato::SUSCRIPCION_TIPO, ConfigInstancia::suscripcion_tipo_plata);
                elseif (strpos($producto[MetaFacturacion::PRODUCTO_ID], ConfigInstancia::suscripcion_tipo_oro) !== false)
                    User::agregarMetaDato(UsuarioMetadato::SUSCRIPCION_TIPO, ConfigInstancia::suscripcion_tipo_oro);

                User::actualizarMetadato(UsuarioMetadato::FACTURACION_GEN_AUTO_SUSCRIPCION, Util::convertirBooleanToInt(false));
            }
        }
    }

    /** Valida el pago de una factura
     * 
     * @param Facturacion $factura El objeto de la factura
     * @param Array $infoValidacion Información de validación de la transaccion
     * @param string $tipo_pago El tipo de pago realizado
     */
    static function validarPago($factura, $infoValidacion, $gateway) {
        Facturacion::generarJSONCliente($factura->id);
        //Se almacena el numero de la transaccion arrojada por el servidor de pagos
        foreach ($infoValidacion as $tipo => $info) {
            Facturacion::agregarMetadato($tipo, $info, $factura->id);
        }
        //Se registra la fecha de pago
        Facturacion::agregarMetadato(MetaFacturacion::FECHA_PAGO, Util::obtenerTiempoActual(), $factura->id);
        //Se establece el estado de la factura como pagado
        $factura->estado = Facturacion::ESTADO_PAGADO;
        //Se establece tipo de pago de la factura
        $factura->gateway = $gateway;
        //Se elimina la factura en proceso del cliente
        User::eliminarMetadato(UsuarioMetadato::FACTURACION_ID_PROCESO);
        $factura->save();
        //Se validan y se efectuan los productos pagados por el usuario
        Facturacion::validarProductos($factura->id);
    }

    static function generarJSONCliente($id_factura, $id_usuario = null) {

        if (is_null($id_usuario))
            $usuario = User::find(Auth::user()->id);
        else
            $usuario = User::find($id_usuario);

        $json = array();
        $json["empresa"] = $usuario->empresa;
        $json["numero_identificacion"] = User::obtenerInfoTipoDocumento($usuario->tipo_documento, true) . " " . $usuario->numero_identificacion;
        $json["nombre"] = $usuario->nombres . " " . $usuario->apellidos;
        $json["email"] = $usuario->email;
        $json["direccion"] = $usuario->direccion;
        $json["lugar"] = $usuario->ciudad . ", " . $usuario->region;
        $json["pais"] = Paises::obtenerNombre($usuario->pais);


        //Almacena una copia de los datos del cliente con la que se hizo la factura
        Facturacion::agregarMetadato(MetaFacturacion::CLIENTE_INFO, json_encode($json), $id_factura, $id_usuario);
    }

    static function pdfHtmlFactura($factura) {


        $usuario = User::find($factura->id_usuario);

        if (is_null($logoPlat = Instancia::obtenerValorMetadato(ConfigInstancia::visual_logo_facturacion, $usuario->instancia))) {
            $logoPlat = URL::to("/assets/img/logo-factura.png");
            $esLogoPlat = true;
        } else {
            $esLogoPlat = false;
        }



        $moneda = Facturacion::obtenerValorMetadato(MetaFacturacion::MONEDA_ID, $factura->id);
        $cliente = json_decode(Facturacion::obtenerValorMetadato(MetaFacturacion::CLIENTE_INFO, $factura->id));
        $productos = Facturacion::obtenerProductos($factura->id);
        $subtotal = 0; //Almacenara el subtotal de los productos
        $iva = $factura->iva;


        $html = '<html>';
        $html .= "<head><title>" . trans("fact.col.numero.factura") . " " . $factura->id . "</title></head>";
        $html .= '<body>';

        //ENCABEZADo
        $html .= "<table style='width:100%;border-bottom:1px rgb(229, 229, 229) solid;'><tr><td rowspan='2'><img src='" . $logoPlat . "'/></td>";
        $html .= '<td style="font-size:20pt;">' . trans("fact.col.numero.factura") . " " . $factura->id . '</span></tr>';
        $html .= "<tr><td style='font-size:15pt;font-weight:bold;";
        if ($factura->estado == Facturacion::ESTADO_PAGADO)
            $html .="color:green";
        if ($factura->estado == Facturacion::ESTADO_SIN_PAGAR)
            $html .="color:red";
        if ($factura->estado == Facturacion::ESTADO_VENCIDA)
            $html .="color:orange";
        $html .= "'>";
        $html .= Facturacion::estado($factura->estado) . '</td></tr><tr><td style="padding:20px;"></td><td></td></tr></table>';


        //FECHAS
        $html.="<table style='width:100%;border-bottom:1px rgb(229, 229, 229) solid;margin-bottom:20px;' ><tr><td>";
        $html.="<b>" . Util::descodificarTexto(trans("fact.col.fecha.creacion")) . ":</b> " . Fecha::formatear($factura->fecha_creacion) . "</td></tr><tr><td><b>" . trans("fact.col.fecha.vencimiento") . ":</b> " . Fecha::formatear($factura->fecha_creacion) . "</td></tr></table>";

        //Cliente
        $html.="<table  style='width:100%;' cellpadding='5' cellspacing='20'>";
        $html.="<tr><td style='border-bottom:1px rgb(229, 229, 229) solid;font-size:14pt;'><b>" . trans("fact.factura.info.a.cliente") . "</b></td><td style='border-bottom:1px rgb(229, 229, 229) solid;font-size:14pt;'><b>" . trans("fact.factura.info.pagar.a") . "</b></td></tr>";
        $html.="<tr><td>";
        foreach ($cliente as $indice => $info)
            $html.="<div>" . $info . "</div>";

        $html.="</td><td valign='top'>" . trans("interfaz.nombre") . " (appsthergo.com)</td></tr>";

        $html.="</table>";


        //Productos
        $html.="<table style='width:100%;border:1px black solid;margin-bottom:50px;' cellpadding='5'>";
        $html.="<tr style='background:gainsboro;'><th>" . Util::descodificarTexto(Util::convertirMayusculas(trans('fact.orden.tu.comprar.descripcion'))) . "</th><th style='text-align:right;'>" . Util::convertirMayusculas(trans('fact.orden.tu.comprar.precio')) . "</th></tr>";

        foreach ($productos as $producto):

            $id_producto = $producto[MetaFacturacion::PRODUCTO_ID];
            $valor_producto = $producto[MetaFacturacion::PRODUCTO_VALOR];
            $descuento_producto = $producto[MetaFacturacion::PRODUCTO_DESCUENTO];
//Calcula el valor descontado del producto
            $valor_real = ($valor_producto * 100) / (100 - $descuento_producto);
            $valor_descontado = $valor_real - $valor_producto;

            $subtotal+=$valor_producto;


            $html .="<tr>";
            $html .=" <td>";
            if (strpos($id_producto, Servicio::CONFIG_NOMBRE) !== false)
                $html .="    <span class='glyphicon glyphicon-ok'></span> " . Util::descodificarTexto(Servicio::obtenerNombre($id_producto)) . "";
            else
                $html .="    <span class='glyphicon glyphicon-ok'></span> " . Util::descodificarTexto(trans('fact.producto.id.' . $id_producto)) . "";
            $html .=" </td>";
            $html .="  <td style='text-align:right;'>";
            $html .="       " . Monedas::simbolo($moneda) . "" . Monedas::formatearNumero($moneda, $valor_real) . " " . $moneda . "";
            $html .="    </td>";
            $html .="  </tr>";

            if ($descuento_producto > 0) {

                $html .=" <tr>";
                $html .="    <td>";
                $html .="        <i><span class='glyphicon glyphicon-ok'></span> <b>" . trans('otros.info.descuento') . " " . $descuento_producto . "%</b> - " . Util::descodificarTexto(trans('fact.producto.id.' . $id_producto)) . "</i>";
                $html .="    </td>";
                $html .= "     <td style='text-align:right;'>";
                $html .="        -" . Monedas::simbolo($moneda) . "" . Monedas::formatearNumero($moneda, $valor_descontado) . " " . $moneda . "";
                $html .="   </td>";
                $html .="   </tr>";
            }

        endforeach;

        $html .="<tr>";
        $html .="     <td style='text-align:right;'>" . trans('fact.orden.tu.comprar.subtotal') . "</td><td style='text-align:right;'>" . Monedas::simbolo($moneda) . "" . Monedas::formatearNumero($moneda, $subtotal) . " " . $moneda . "</td>";
        $html .="  </tr>";
        $html .="  <tr>";

        $valor_iva = ($iva / 100) * $subtotal;
        $valor_total = $valor_iva + $subtotal;

        if ($iva > 0) {
            $html .=" <td style='text-align:right;'>" . trans('fact.orden.tu.compra.iva') . " (" . $iva . "%)</td><td style='text-align:right;'>" . Monedas::simbolo($moneda) . "" . Monedas::formatearNumero($moneda, $valor_iva) . " " . $moneda . "</td>";
        }
        $html .= " </tr>";
        $html .= " <tr><td style='text-align:right;background:gainsboro;' style='font-size: 13pt;'><b>" . trans('fact.info.total') . "</b></td><td style='text-align:right;' style='font-size: 13pt;'><b>" . Monedas::simbolo($moneda) . " " . Monedas::formatearNumero($moneda, $valor_total) . " " . $moneda . "</b></div> ";
        $html .= "  </table>";

        $html.="<h3>" . Util::descodificarTexto(trans("fact.factura.transaccion.titulo")) . "</h3>";

        $html .= " <table style='width:100%;border:1px black solid;' cellpadding='5'>";
        $html .= " <tr style='background:gainsboro;'><th>" . Util::descodificarTexto(Util::convertirMayusculas(trans('fact.factura.transaccion.fecha'))) . "</th>";
        $html .= "   <th>" . Util::convertirMayusculas(trans('fact.factura.transaccion.gateway')) . "</th>";
        $html .= "  <th>" . Util::descodificarTexto(Util::convertirMayusculas(trans('fact.factura.transaccion.id'))) . "</th>";
        $html .= "  <th>" . trans('fact.info.total') . "</th>";
        $html .= "   </tr>";

        if (!is_null($factura->gateway)) {
            $html .= "<tr>";
            $html .= "    <td>" . Fecha::formatear(Facturacion::obtenerValorMetadato(MetaFacturacion::FECHA_PAGO, $factura->id)) . "</td>";
            $html .= "   <td>" . Facturacion::tipo($factura->gateway) . "</td>";
            $html .= "    <td>" . Facturacion::obtenerValorMetadato(MetaFacturacion::TRANSACCION_ID, $factura->id) . "</td>";
            $html .= "    <td>" . Monedas::simbolo($moneda) . " " . $valor_total . " " . $moneda . "</td>";
            $html .= "  </tr>";
        }
        $html .= "  </table>";


        $fecha_operacion = Facturacion::obtenerValorMetadato(MetaFacturacion::TRANSACCION_FECHA_OPERACION, $factura->id);

        if (!is_null($fecha_operacion)) {
            $html.="<h3>" . Util::descodificarTexto(trans("fact.factura.info.operacion.titulo")) . "</h3>";

            $html .= " <table style='width:100%;border:1px black solid;' cellpadding='5'>";
            $html .= " <tr style='background:gainsboro;'><th>" . Util::descodificarTexto(Util::convertirMayusculas(trans('fact.factura.transaccion.fecha.operacion'))) . "</th>";
            $html .= "   <th>" . Util::convertirMayusculas(trans('fact.factura.transaccion.metodo.pago')) . "</th>";
            $html .= "  <th>" . Util::descodificarTexto(Util::convertirMayusculas(trans('fact.factura.transaccion.codigo.autorizacion'))) . "</th>";
            $html .= "  <th>" . trans('fact.factura.transaccion.id.orden') . "</th>";
            $html .= "   </tr>";

            if (!is_null($factura->gateway)) {
                $html .= "<tr>";
                $html .= "    <td>" . $fecha_operacion . "</td>";
                $html .= "   <td>" . Facturacion::obtenerValorMetadato(MetaFacturacion::METODO_PAGO, $factura->id) . "</td>";
                $html .= "    <td>" . Facturacion::obtenerValorMetadato(MetaFacturacion::TRANSACCION_CODIGO_AUTORIZACION, $factura->id) . "</td>";
                $html .= "    <td>" . Facturacion::obtenerValorMetadato(MetaFacturacion::TRANSACCION_ID_ORDEN, $factura->id) . "</td>";
                $html .= "  </tr>";
            }
            $html .= "  </table>";
        }


        $html.= '</body></html>';

        return $html;
    }

    /** Calcula el numero de cuotas maxima a escoger de un valor. Utilizado para las cuotas de las tarjetas de credito.
     * 
     * @param type $valor
     * @param type $cantMax
     * @param type $valorCuotaMin
     * @return type
     */
    static function calcularNumeroCuotasMaxima($valor, $cantMax = 24, $valorCuotaMin = 25000) {
        $valorCuota = $valor / $cantMax;

        while ($valorCuota < $valorCuotaMin) {
            $cantMax--;
            $valorCuota = $valor / $cantMax;
        }

        return $cantMax;
    }

}
