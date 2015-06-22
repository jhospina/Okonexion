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
            User::agregarMetaDato(UsuarioMetadato::FACTURACION_ID_PROCESO, $data[UsuarioMetadato::FACTURACION_ID_PROCESO]); 
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
                return Redirect::to("fact/mis-facturas")->with(User::mensaje("exito", null, "<span class='glyphicon glyphicon-ok'></span> " . trans("fact.orden.pago.msj.exito", array("num" => $factura->id)), 2));
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

        return PDF::load(Facturacion::pdfHtmlFactura($factura), 'A4', 'portrait')->show();
    }

    function pdf_descargarFactura($id) {
        if (!Auth::check()) {
            return User::login();
        }

        $factura = Facturacion::find($id);

        if ($factura->id_usuario != Auth::user()->id && Auth::user()->tipo == User::USUARIO_REGULAR)
            return Redirect::to("");

        return PDF::load(Facturacion::pdfHtmlFactura($factura), 'A4', 'portrait')->download(str_replace(" ", "", trans("fact.col.numero.factura") . " " . $factura->id));
    }

}
