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
            $id_factura = Facturacion::nueva();
            Facturacion::agregarMetadato(MetaFacturacion::MONEDA_ID, $data[MetaFacturacion::MONEDA_ID], $id_factura);
            User::agregarMetaDato(UsuarioMetadato::FACTURACION_ID_PROCESO, $id_factura);
            Facturacion::agregarProducto($id_factura, $data);
        }

        $factura = Facturacion::find(User::obtenerValorMetadato(UsuarioMetadato::FACTURACION_ID_PROCESO));

        if (is_null($factura))
            return Redirect::to("/")->with(User::mensaje("error", null, trans("fact.ordenPago.error.sin_factura"), 2));


        return View::make("usuarios/tipo/regular/facturacion/ordenPago")->with("factura", $factura);
    }

}
