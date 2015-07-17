<?php

class UPanelControladorFacturacion extends \BaseController {

    function vista_suscripcion_planes() {

        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $hash = HasherPro::crear(UsuarioMetadato::HASH_CREAR_FACTURA);

        if (strlen($hash) == 0) {
            return Redirect::to("fact/suscripcion/plan");
        }

        return View::make("usuarios/tipo/regular/facturacion/suscripcion/planes")->with("hash", $hash);
    }

    function vista_suscripcion_ciclos($plan) {
        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $hash = HasherPro::crear(UsuarioMetadato::HASH_CREAR_FACTURA);

        if (strlen($hash) == 0) {
            return Redirect::to("fact/suscripcion/ciclo/" . $plan);
        }

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
                $id_factura = Facturacion::nueva();
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

    function post_ordenPagoProcesar_TCredito_2Checkout() {
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
                Facturacion::validarPago($factura, array(MetaFacturacion::TRANSACCION_ID => $charge['response']['orderNumber']), Facturacion::GATEWAY_2CHECKOUT);
                return Redirect::to("fact/mis-facturas")->with(User::mensaje("exito", null, trans("fact.orden.pago.msj.exito", array("num" => $factura->id)), 2));
            }
        } catch (Twocheckout_Error $e) {
            return Redirect::back()->with(User::mensaje("error", null, trans("fact.orden.pago.informacion.tc.error.proceso"), 2));
            //Si ha producido un error 
        }
    }

    function post_ordenPagoProcesar_TCredito_PayU() {

        $data = Input::all();
        $sandBox = Util::convertirIntToBoolean(Instancia::obtenerValorMetadato(ConfigInstancia::fact_payu_sandbox));

        $factura = Facturacion::find(User::obtenerValorMetadato(UsuarioMetadato::FACTURACION_ID_PROCESO));
        $productos = Facturacion::obtenerProductos($factura->id);
        $descripcion = "";
        foreach ($productos as $producto) {
            $desProducto = (strpos($producto[MetaFacturacion::PRODUCTO_ID], Servicio::CONFIG_NOMBRE) !== false) ? Servicio::obtenerNombre($producto[MetaFacturacion::PRODUCTO_ID]) : trans("fact.producto.id." . $producto[MetaFacturacion::PRODUCTO_ID]);
            $descripcion.="[" . $desProducto . "]";
        }

        $parameters = array(
            //Ingrese aquí el código de referencia.
            PayUParameters::REFERENCE_CODE => MetPayU::PREFIJO_REF . $factura->id,
            //Ingrese aquí la descripción.
            PayUParameters::DESCRIPTION => $descripcion,
            // -- Valores --
            //Ingrese aquí el valor.        
            PayUParameters::VALUE => $factura->total,
            //Ingrese aquí la moneda.
            PayUParameters::CURRENCY => Monedas::COP,
            // -- Comprador 
            //Ingrese aquí el nombre del comprador.
            PayUParameters::BUYER_NAME => Auth::user()->nombres . " " . Auth::user()->apellidos,
            //Ingrese aquí el email del comprador.
            PayUParameters::BUYER_EMAIL => Auth::user()->email,
            //Ingrese aquí el teléfono de contacto del comprador.
            PayUParameters::BUYER_CONTACT_PHONE => Auth::user()->telefono,
            //Ingrese aquí el documento de contacto del comprador.
            PayUParameters::BUYER_DNI => Auth::user()->dni,
            //Ingrese aquí la dirección del comprador.
            PayUParameters::BUYER_STREET => Auth::user()->direccion,
            PayUParameters::BUYER_STREET_2 => "",
            PayUParameters::BUYER_CITY => Auth::user()->ciudad,
            PayUParameters::BUYER_STATE => Auth::user()->region,
            PayUParameters::BUYER_COUNTRY => "CO",
            PayUParameters::BUYER_POSTAL_CODE => "000000",
            PayUParameters::BUYER_PHONE => Auth::user()->celular);

        //Los mismos datos de facturacion
        if (Util::convertirIntToBoolean(intval($data["check_datos_fact"]))) {
            // -- pagador --
            $parameters[PayUParameters::PAYER_NAME] = ($sandBox) ? "APPROVED" : Auth::user()->nombres . " " . Auth::user()->apellidos;
            //$parameters[PayUParameters::PAYER_NAME] = 
            $parameters[PayUParameters::PAYER_EMAIL] = Auth::user()->email;
            $parameters[PayUParameters::PAYER_CONTACT_PHONE] = Auth::user()->telefono;
            $parameters[PayUParameters::PAYER_DNI] = Auth::user()->dni;
            $parameters[PayUParameters::PAYER_STREET] = Auth::user()->direccion;
            $parameters[PayUParameters::PAYER_CITY] = Auth::user()->ciudad;
            $parameters[PayUParameters::PAYER_STATE] = Auth::user()->region;
            $parameters[PayUParameters::PAYER_COUNTRY] = "CO"; //Colombia
            $parameters[PayUParameters::PAYER_POSTAL_CODE] = "0000000000";
            $parameters[PayUParameters::PAYER_PHONE] = Auth::user()->celular;
        } else {
            $parameters[PayUParameters::PAYER_NAME] = ($sandBox) ? "APPROVED" : $data["cp_nombre"];
            $parameters[PayUParameters::PAYER_EMAIL] = $data["cp_email"];
            $parameters[PayUParameters::PAYER_CONTACT_PHONE] = $data["cp_telefono"];
            $parameters[PayUParameters::PAYER_DNI] = $data["cp_dni"];
            $parameters[PayUParameters::PAYER_STREET] = $data["cp_direccion"];
            $parameters[PayUParameters::PAYER_CITY] = $data["cp_ciudad"];
            $parameters[PayUParameters::PAYER_STATE] = $data["cp_estado"];
            $parameters[PayUParameters::PAYER_COUNTRY] = "CO"; //Colombia
            $parameters[PayUParameters::PAYER_POSTAL_CODE] = "0000000000";
            $parameters[PayUParameters::PAYER_PHONE] = $data["cp_telefono"];
        }

        // -- Datos de la tarjeta de crédito -- 
        $parameters[PayUParameters::CREDIT_CARD_NUMBER] = $data["ccNo"];
        $parameters[PayUParameters::CREDIT_CARD_EXPIRATION_DATE] = $data["ano-exp"] . "/" . $data["mes-exp"];
        $parameters[PayUParameters::CREDIT_CARD_SECURITY_CODE] = $data["cvv"];

        switch ($data["fraqTC"]) {
            case "visa":
                $parameters[PayUParameters::PAYMENT_METHOD] = PaymentMethods::VISA;
                break;
            case "master":
                $parameters[PayUParameters::PAYMENT_METHOD] = PaymentMethods::MASTERCARD;
                break;
            case "amex":
                $parameters[PayUParameters::PAYMENT_METHOD] = PaymentMethods::AMEX;
                break;
            case "diners":
                $parameters[PayUParameters::PAYMENT_METHOD] = PaymentMethods::DINERS;
                break;
        }
        //NUmero de cuotas
        $parameters[PayUParameters::INSTALLMENTS_NUMBER] = $data["num_cuotas"];

        //Ingrese aquí el nombre del pais.
        $parameters[PayUParameters::COUNTRY] = PayUCountries::CO;
        //Session id del device.
        $parameters[PayUParameters::DEVICE_SESSION_ID] = Session::getId();
        //IP del pagadador
        $parameters[PayUParameters::IP_ADDRESS] = Util::obtenerDireccionIP();
        //Cookie de la sesión actual.
        $parameters[PayUParameters::PAYER_COOKIE] = Session::getId();

        $parameters[PayUParameters::USER_AGENT] = $_SERVER['HTTP_USER_AGENT'];

        $payu = new MetPayU();
        return $payu->procesarPagoTCredito($parameters, $factura);
    }

    function post_ordenPagoProcesar_TBancaria_PayU() {

        $data = Input::all();

        $factura = Facturacion::find(User::obtenerValorMetadato(UsuarioMetadato::FACTURACION_ID_PROCESO));
        $productos = Facturacion::obtenerProductos($factura->id);
        $descripcion = "";
        foreach ($productos as $producto) {
            $desProducto = (strpos($producto[MetaFacturacion::PRODUCTO_ID], Servicio::CONFIG_NOMBRE) !== false) ? Servicio::obtenerNombre($producto[MetaFacturacion::PRODUCTO_ID]) : trans("fact.producto.id." . $producto[MetaFacturacion::PRODUCTO_ID]);
            $descripcion.="[" . $desProducto . "]";
        }

        $parameters = array(
            //Ingrese aquí el código de referencia.
            PayUParameters::REFERENCE_CODE => MetPayU::PREFIJO_REF . $factura->id,
            //Ingrese aquí la descripción.
            PayUParameters::DESCRIPTION => $descripcion,
            // -- Valores --
            //Ingrese aquí el valor.        
            PayUParameters::VALUE => $factura->total,
            //Ingrese aquí la moneda.
            PayUParameters::CURRENCY => Monedas::COP,
            //Ingrese aquí el email del comprador.
            PayUParameters::BUYER_EMAIL => Auth::user()->email,
            //Ingrese aquí el nombre del pagador.
            PayUParameters::PAYER_NAME => $data["pse_nombre"],
            //Ingrese aquí el email del pagador.
            PayUParameters::PAYER_EMAIL => $data["pse_email"],
            //Ingrese aquí el teléfono de contacto del pagador.
            PayUParameters::PAYER_CONTACT_PHONE => $data["pse_telefono"],
            // -- infarmación obligatoria para PSE --
            //Ingrese aquí el código pse del banco.
            PayUParameters::PSE_FINANCIAL_INSTITUTION_CODE => $data["pse_banco"],
            //Ingrese aquí el tipo de persona (N natural o J jurídica)
            PayUParameters::PAYER_PERSON_TYPE => $data["pse_persona"],
            //Ingrese aquí el documento de contacto del pagador.
            PayUParameters::PAYER_DNI => $data["pse_numDoc"],
            //Ingrese aquí el tipo de documento del pagador: CC, CE, NIT, TI, PP,IDC, CEL, RC, DE.
            PayUParameters::PAYER_DOCUMENT_TYPE => $data["pse_tipoDocumento"],
            //Ingrese aquí el nombre del método de pago
            PayUParameters::PAYMENT_METHOD => PaymentMethods::PSE);
        //Ingrese aquí el nombre del pais.
        //Session id del device.
        $parameters[PayUParameters::DEVICE_SESSION_ID] = Session::getId();
        //IP del pagadador
        $parameters[PayUParameters::IP_ADDRESS] = Util::obtenerDireccionIP();
        //Cookie de la sesión actual.
        $parameters[PayUParameters::PAYER_COOKIE] = Session::getId();

        $parameters[PayUParameters::USER_AGENT] = $_SERVER['HTTP_USER_AGENT'];

        $payu = new MetPayU();
        return $payu->procesarPagoTBancaria($parameters, $factura);
    }

    function post_ordenPagoProcesar_efectivo_PayU() {

        $data = Input::all();

        $factura = Facturacion::find(User::obtenerValorMetadato(UsuarioMetadato::FACTURACION_ID_PROCESO));
        $productos = Facturacion::obtenerProductos($factura->id);
        $descripcion = "";
        foreach ($productos as $producto) {
            $desProducto = (strpos($producto[MetaFacturacion::PRODUCTO_ID], Servicio::CONFIG_NOMBRE) !== false) ? Servicio::obtenerNombre($producto[MetaFacturacion::PRODUCTO_ID]) : trans("fact.producto.id." . $producto[MetaFacturacion::PRODUCTO_ID]);
            $descripcion.="[" . $desProducto . "]";
        }

        $parameters = array(
            PayUParameters::REFERENCE_CODE => MetPayU::PREFIJO_REF . $factura->id,
            PayUParameters::DESCRIPTION => $descripcion,
            PayUParameters::VALUE => $factura->total,
            PayUParameters::CURRENCY => Monedas::COP,
            PayUParameters::BUYER_EMAIL => Auth::user()->email,
            PayUParameters::PAYER_NAME => Auth::user()->nombres . " " . Auth::user()->apellidos,
            PayUParameters::PAYER_DNI => Auth::user()->dni);
        $parameters[PayUParameters::PAYMENT_METHOD] = ($data["puntorec"] == "baloto") ? PaymentMethods::BALOTO : PaymentMethods::EFECTY;
        $parameters[PayUParameters::COUNTRY] = PayUCountries::CO;

        $fecha = new Fecha(Util::obtenerTiempoActual());
        //Agrega 3 dias a la fecha de expiracion
        $parameters[PayUParameters::EXPIRATION_DATE] = str_replace(" ", "T", $fecha->agregarDias(3));
        $parameters[PayUParameters::DEVICE_SESSION_ID] = Session::getId();
        $parameters[PayUParameters::IP_ADDRESS] = Util::obtenerDireccionIP();
        $parameters[PayUParameters::USER_AGENT] = $_SERVER['HTTP_USER_AGENT'];
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
