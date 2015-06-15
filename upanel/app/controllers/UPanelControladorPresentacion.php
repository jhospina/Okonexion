<?php

class UPanelControladorPresentacion extends Controller {

    function index() {

        if (!Auth::check()) {
            return User::login();
        }

        if (Auth::user()->tipo == User::USUARIO_REGULAR)
        //USUARIO REGULAR
            return $this->indexRegular();
        //USUARIO SOPORTE GENERAL
        if (Auth::user()->tipo == User::USUARIO_SOPORTE_GENERAL)
            return $this->indexSoporteGeneral();
        //USUARIO ADMIN
        if (Auth::user()->tipo == User::USUARIO_ADMIN)
            return $this->indexAdministrador();
    }

    //Panel principal para el usuario regular
    function indexRegular() {


        $tickets = Ticket::where("estado", "!=", Ticket::ESTADO_CERRADO)->where("usuario_cliente", Auth::user()->id)->get();
        $totalTickets = count(Ticket::where("usuario_cliente", Auth::user()->id)->get());

        $facturas = Facturacion::where("id_usuario", Auth::user()->id)->where("estado", "!=", Facturacion::ESTADO_PAGADO)->orderBy("id", "DESC")->get();

        $factServicios = MetaFacturacion::join('facturacion', 'facturacionMetadatos.id_factura', '=', 'facturacion.id')->where("facturacion.id_usuario", Auth::user()->id)->where("facturacion.estado", Facturacion::ESTADO_PAGADO)->where("facturacionMetadatos.valor", "LIKE", Servicio::CONFIG_NOMBRE . "%")->orderBy('facturacionMetadatos.id', "DESC")->get();

        $totalServicios = 0;
        $serviciosProcesados = 0;

        foreach ($factServicios as $fact) {
            $id_servicio = intval(str_replace(Servicio::CONFIG_NOMBRE, "", $fact->valor));
            $procesado = Util::convertirIntToBoolean(Facturacion::obtenerValorMetadato(MetaFacturacion::PRODUCTO_PROCESADO . $id_servicio, $fact->id));

            if ($procesado)
                $serviciosProcesados++;
            $totalServicios++;
        }

        return View::make("usuarios/tipo/regular/index")->with("tickets", $tickets)->with("facturas", $facturas)->with("totalTickets", $totalTickets)->with("totalServicios",$totalServicios)->with("serviciosProcesados",$serviciosProcesados);
    }

    function indexSoporteGeneral() {
        return View::make("usuarios/tipo/soporteGeneral/index");
    }

    function indexAdministrador() {
        return View::make("usuarios/tipo/admin/index");
    }

}
