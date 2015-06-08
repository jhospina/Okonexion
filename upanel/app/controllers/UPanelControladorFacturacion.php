<?php

class UPanelControladorFacturacion extends \BaseController {

    function vista_suscripcion_planes() {

        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        return View::make("usuarios/tipo/regular/facturacion/suscripcion/planes");
    }

    function vista_suscripcion_ciclos($plan) {
        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $hash = HasherPro::crear(UsuarioMetadato::HASH_CREAR_FACTURA);
        return View::make("usuarios/tipo/regular/facturacion/suscripcion/ciclos")->with("plan", $plan)->with("hash", $hash);
    }

    function vistaPost_ordenPago() {
        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $data = Input::all();

        if (isset($data[UsuarioMetadato::HASH_CREAR_FACTURA])) {

            //Verifica el hash de creacion y lo efectua una unica vez
            if (HasherPro::Verificar($data[UsuarioMetadato::HASH_CREAR_FACTURA], UsuarioMetadato::HASH_CREAR_FACTURA)) {
                $id_factura = Facturacion::nueva(Instancia::obtenerValorMetadato(ConfigInstancia::fact_impuestos_iva));
                Facturacion::agregarMetadato(MetaFacturacion::MONEDA_ID, $data[MetaFacturacion::MONEDA_ID], $id_factura);
                User::agregarMetaDato(UsuarioMetadato::FACTURACION_ID_PROCESO, $id_factura);
                Facturacion::agregarProducto($id_factura, $data);
                Facturacion::generarJSONCliente($id_factura);
            }
        }

        if (isset($data[UsuarioMetadato::FACTURACION_ID_PROCESO])) {
            User::agregarMetaDato(UsuarioMetadato::FACTURACION_ID_PROCESO, $id_factura);
        }

        $factura = Facturacion::find(User::obtenerValorMetadato(UsuarioMetadato::FACTURACION_ID_PROCESO));

        if (is_null($factura))
            return Redirect::to("/")->with(User::mensaje("error", null, trans("fact.ordenPago.error.sin_factura"), 2));


        return View::make("usuarios/tipo/regular/facturacion/ordenPago")->with("factura", $factura);
    }

    function post_ordenPagoProcesar() {
        $data = Input::all();

        if (!isset($data["token"]))
            return;

        //Twocheckout::username('appsthergo');
        //Twocheckout::password('Jhospina92');
        Twocheckout::privateKey(Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_privateKey));
        Twocheckout::sellerId(Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_idSeller));
        Twocheckout::sandbox(Util::convertirIntToBoolean(Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_sandbox)));

        $factura = Facturacion::find(User::obtenerValorMetadato(UsuarioMetadato::FACTURACION_ID_PROCESO));

        try {
            $charge = Twocheckout_Charge::auth(array(
                        "merchantOrderId" => $factura->id,
                        "token" => $data['token'],
                        "currency" => Facturacion::obtenerValorMetadato(MetaFacturacion::MONEDA_ID, $factura->id),
                        "total" => $factura->total,
                        "billingAddr" => array(
                            "name" => Auth::user()->nombres . " " . Auth::user()->apellidos,
                            "addrLine1" => Auth::user()->direccion,
                            "city" => Auth::user()->ciudad,
                            "state" => Auth::user()->region,
                            "zipCode" => '000000',
                            "country" => Auth::user()->pais,
                            "email" => Auth::user()->email,
                            "phoneNumber" => Auth::user()->telefono,
                        )
            ));
            //Si la transaccion ha sido aprobada
            if ($charge['response']['responseCode'] == 'APPROVED') {
                Facturacion::validarPago($factura, $charge['response']['orderNumber'], Facturacion::TIPOPAGO_TARJETA_CREDITO_ATRAVES_2CHECKOUTS);
                return Redirect::to("")->with(User::mensaje("exito", null, "<span class='glyphicon glyphicon-ok'></span> " . trans("fact.orden.pago.msj.exito", array("num" => $factura->id)), 2));
            }
        } catch (Twocheckout_Error $e) {
            return Redirect::back()->with(User::mensaje("error", null, "<span class='glyphicon glyphicon-remove-circle'></span> " . trans("fact.orden.pago.informacion.tc.error.proceso"), 2));
            //Si ha producido un error 
        }
    }

    function vista_misFacturas() {
        $facturas = Facturacion::where("id_usuario", Auth::user()->id)->orderBy("id", "DESC")->paginate(30);
        return View::make("usuarios/tipo/regular/facturacion/facturas/index")->with("facturas", $facturas);
    }

    function vista_facturas() {

        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::invalidarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        if (User::esSuperAdmin() || Auth::user()->instancia == User::PARAM_INSTANCIA_SUPER_ADMIN)
            $facturas = Facturacion::orderBy("id", "DESC")->paginate(30);
        else
            $facturas = Facturacion::where("instancia", Auth::user()->instancia)->orderBy("id", "DESC")->paginate(30);
        return View::make("usuarios/tipo/admin/facturacion/facturas/index")->with("facturas", $facturas);
    }

    function vista_verFactura($id) {
        if (!Auth::check()) {
            return User::login();
        }

        $factura = Facturacion::find($id);

        if ($factura->id_usuario != Auth::user()->id && Auth::user()->tipo == User::USUARIO_REGULAR)
            return Redirect::to("");

        return View::make("usuarios/general/facturacion/facturas/ver")->with("factura", $factura);
    }

    function pdf_factura($id) {
        if (!Auth::check()) {
            return User::login();
        }

        $factura = Facturacion::find($id);

        if ($factura->id_usuario != Auth::user()->id && Auth::user()->tipo == User::USUARIO_REGULAR)
            return Redirect::to("");

        return PDF::load($this->htmlFactura($factura), 'A4', 'portrait')->show();
    }

    function pdf_descargarFactura($id) {
        if (!Auth::check()) {
            return User::login();
        }

        $factura = Facturacion::find($id);

        if ($factura->id_usuario != Auth::user()->id && Auth::user()->tipo == User::USUARIO_REGULAR)
            return Redirect::to("");

        return PDF::load($this->htmlFactura($factura), 'A4', 'portrait')->download(str_replace(" ", "", trans("fact.col.numero.factura") . " " . $factura->id));
    }

    function htmlFactura($factura) {

        if (is_null($logoPlat = Instancia::obtenerValorMetadato(ConfigInstancia::visual_logo_facturacion))) {
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
            $html .="       " . Monedas::simbolo($moneda) . "" . $valor_real . " " . $moneda . "";
            $html .="    </td>";
            $html .="  </tr>";

            if ($descuento_producto > 0) {

                $html .=" <tr>";
                $html .="    <td>";
                $html .="        <i><span class='glyphicon glyphicon-ok'></span> <b>" . trans('otros.info.descuento') . " " . $descuento_producto . "%</b> - " . trans('fact.producto.id.' . $id_producto) . "</i>";
                $html .="    </td>";
                $html .= "     <td style='text-align:right;'>";
                $html .="        -" . Monedas::simbolo($moneda) . "" . $valor_descontado . " " . $moneda . "";
                $html .="   </td>";
                $html .="   </tr>";
            }

        endforeach;

        $html .="<tr>";
        $html .="     <td style='text-align:right;'>" . trans('fact.orden.tu.comprar.subtotal') . "</td><td style='text-align:right;'>" . Monedas::simbolo($moneda) . "" . $subtotal . " " . $moneda . "</td>";
        $html .="  </tr>";
        $html .="  <tr>";

        $valor_iva = ($iva / 100) * $subtotal;
        $valor_total = $valor_iva + $subtotal;

        if ($iva > 0) {
            $html .=" <td style='text-align:right;'>" . trans('fact.orden.tu.compra.iva') . " (" . $iva . "%)</td><td style='text-align:right;'>" . Monedas::simbolo($moneda) . "" . $valor_iva . " " . $moneda . "</td>";
        }
        $html .= " </tr>";
        $html .= " <tr><td style='text-align:right;background:gainsboro;' style='font-size: 13pt;'><b>" . trans('fact.info.total') . "</b></td><td style='text-align:right;' style='font-size: 13pt;'><b>" . Monedas::simbolo($moneda) . " " . $valor_total . " " . $moneda . "</b></div> ";
        $html .= "  </table>";

        $html.="<h3>" . trans("fact.factura.transaccion.titulo") . "</h3>";

        $html .= " <table style='width:100%;border:1px black solid;' cellpadding='5'>";
        $html .= " <tr style='background:gainsboro;'><th>" . Util::descodificarTexto(Util::convertirMayusculas(trans('fact.factura.transaccion.fecha'))) . "</th>";
        $html .= "   <th>" . Util::convertirMayusculas(trans('fact.factura.transaccion.metodo')) . "</th>";
        $html .= "  <th>" . Util::descodificarTexto(Util::convertirMayusculas(trans('fact.factura.transaccion.id'))) . "</th>";
        $html .= "  <th>" . trans('fact.info.total') . "</th>";
        $html .= "   </tr>";

        if (!is_null($factura->tipo_pago)) {
            $html .= "<tr>";
            $html .= "    <td>" . Fecha::formatear(Facturacion::obtenerValorMetadato(MetaFacturacion::FECHA_PAGO, $factura->id)) . "</td>";
            $html .= "   <td>" . Facturacion::tipo($factura->tipo_pago) . "</td>";
            $html .= "    <td>" . Facturacion::obtenerValorMetadato(MetaFacturacion::TRANSACCION_ID, $factura->id) . "</td>";
            $html .= "    <td>" . Monedas::simbolo($moneda) . " " . $valor_total . " " . $moneda . "</td>";
            $html .= "  </tr>";
        }
        $html .= "  </table>";


        $html.= '</body></html>';

        return $html;
    }

}
