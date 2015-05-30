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
            }
        }

        $factura = Facturacion::find(User::obtenerValorMetadato(UsuarioMetadato::FACTURACION_ID_PROCESO));

        if (is_null($factura))
            return Redirect::to("/")->with(User::mensaje("error", null, trans("fact.ordenPago.error.sin_factura"), 2));


        return View::make("usuarios/tipo/regular/facturacion/ordenPago")->with("factura", $factura);
    }

    function post_ordenPagoProcesar() {
        $data = Input::all();

        //Twocheckout::username('appsthergo');
        //Twocheckout::password('Jhospina92');
        Twocheckout::privateKey(Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_privateKey));
        Twocheckout::sellerId(Instancia::obtenerValorMetadato(ConfigInstancia::fact_2checkout_idSeller));
        Twocheckout::sandbox(true);

        try {
            $charge = Twocheckout_Charge::auth(array(
                        "merchantOrderId" => "123",
                        "token" => $data['token'],
                        "currency" => 'USD',
                        "total" => '10.00',
                        "billingAddr" => array(
                            "name" => 'Testing Tester',
                            "addrLine1" => '123 Test St',
                            "city" => 'Columbus',
                            "state" => 'OH',
                            "zipCode" => '43123',
                            "country" => 'USA',
                            "email" => 'example@2co.com',
                            "phoneNumber" => '555-555-5555'
                        )
            ));

            if ($charge['response']['responseCode'] == 'APPROVED') {
                echo "Thanks for your Order!";
                echo "<h3>Return Parameters:</h3>";
                echo "<pre>";
                print_r($charge);
                echo "</pre>";
            }
        } catch (Twocheckout_Error $e) {
            print_r($e->getMessage());
        }
    }

}
