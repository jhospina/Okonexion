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

        return View::make("usuarios/tipo/regular/index")->with("tickets", $tickets)->with("facturas", $facturas)->with("totalTickets",$totalTickets);
    }

    function indexSoporteGeneral() {
        return View::make("usuarios/tipo/soporteGeneral/index");
    }

    function indexAdministrador() {
        return View::make("usuarios/tipo/admin/index");
    }

}
