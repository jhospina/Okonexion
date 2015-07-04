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

        return View::make("usuarios/tipo/regular/index")->with("tickets", $tickets)->with("facturas", $facturas)->with("totalTickets", $totalTickets)->with("totalServicios", $totalServicios)->with("serviciosProcesados", $serviciosProcesados);
    }

    function indexSoporteGeneral() {

        //APLICACIONES****************************************************
        if (User::esSuper())
            $cant_apps = count(Aplicacion::where("estado", Aplicacion::ESTADO_TERMINADA)->get());
        else
            $cant_apps = count(Aplicacion::join("usuarios", "aplicaciones.id_usuario", "=", "usuarios.id")->where("aplicaciones.estado", Aplicacion::ESTADO_TERMINADA)->where("usuarios.instancia", Auth::user()->instancia)->get());
        $cant_colaD = ProcesoApp::where("fecha_finalizacion", null)->orderBy("id", "ASC")->count();

        //FACTURACION****************************************************
        if (User::esSuper()) {
            $total_facturas = Facturacion::count();
            $cant_factSinPagar = Facturacion::where("estado", Facturacion::ESTADO_SIN_PAGAR)->orWhere("estado", Facturacion::ESTADO_VENCIDA)->count();
        } else {
            $total_facturas = Facturacion::where("instancia", Auth::user()->instancia)->count();
            $cant_factSinPagar = Facturacion::where("instancia", Auth::user()->instancia)->where("estado", Facturacion::ESTADO_SIN_PAGAR)->orWhere("estado", Facturacion::ESTADO_VENCIDA)->count();
        }

        //SERVICIOS****************************************************
        if (User::esSuper())
            $factServicios = MetaFacturacion::join('facturacion', 'facturacionMetadatos.id_factura', '=', 'facturacion.id')->where("facturacion.estado", Facturacion::ESTADO_PAGADO)->where("facturacionMetadatos.valor", "LIKE", Servicio::CONFIG_NOMBRE . "%")->orderBy('facturacionMetadatos.id', "DESC")->get();
        else
            $factServicios = MetaFacturacion::join('facturacion', 'facturacionMetadatos.id_factura', '=', 'facturacion.id')->where("facturacion.instancia", Auth::user()->instancia)->where("facturacion.estado", Facturacion::ESTADO_PAGADO)->where("facturacionMetadatos.valor", "LIKE", Servicio::CONFIG_NOMBRE . "%")->orderBy('facturacionMetadatos.id', "DESC")->get();

        $totalServicios = Servicio::where("instancia", Auth::user()->instancia)->count();
        $serviciosNoProcesados = 0;

        foreach ($factServicios as $fact) {
            $id_servicio = intval(str_replace(Servicio::CONFIG_NOMBRE, "", $fact->valor));
            $procesado = Util::convertirIntToBoolean(Facturacion::obtenerValorMetadato(MetaFacturacion::PRODUCTO_PROCESADO . $id_servicio, $fact->id));
            if (!$procesado)
                $serviciosNoProcesados++;
        }


        //USUARIOS****************************************************
        if (User::esSuper()) {
            $cant_usuarios_suscritos = User::where("estado", User::ESTADO_SUSCRIPCION_VIGENTE)->where("tipo", User::USUARIO_REGULAR)->count();
            $total_usuarios = User::where("tipo", User::USUARIO_REGULAR)->count();
        } else {
            $cant_usuarios_suscritos = User::where("estado", User::ESTADO_SUSCRIPCION_VIGENTE)->where("instancia", Auth::user()->instancia)->where("tipo", User::USUARIO_REGULAR)->count();
            $total_usuarios = User::where("estado", User::ESTADO_SUSCRIPCION_VIGENTE)->where("instancia", Auth::user()->instancia)->where("tipo", User::USUARIO_REGULAR)->count();
        }

        //TICKETS****************************************************
        if (User::esSuper()) {
            $total_tickets = Ticket::all()->count();
            $cant_ticketsAbierto = Ticket::where("estado", Ticket::ESTADO_ABIERTO)->count();
        } else {
            $total_tickets = Ticket::where("instancia", Auth::user()->instancia)->count();
            $cant_ticketsAbierto = Ticket::where("instancia", Auth::user()->instancia)->where("estado", Ticket::ESTADO_ABIERTO)->count();
        }


        return View::make("usuarios/tipo/soporteGeneral/index")
                        ->with("cant_apps", $cant_apps)
                        ->with("cant_colaD", $cant_colaD)
                        ->with("total_facturas", $total_facturas)
                        ->with("cant_factSinPagar", $cant_factSinPagar)
                        ->with("totalServicios", $totalServicios)
                        ->with("serviciosNoProcesados", $serviciosNoProcesados)
                        ->with("cant_usuarios_suscritos", $cant_usuarios_suscritos)
                        ->with("total_usuarios", $total_usuarios)
                        ->with("total_tickets", $total_tickets)
                        ->with("cant_ticketsAbierto", $cant_ticketsAbierto);
    }

    function indexAdministrador() {

        //VENTAS TOTALES****************************************************
        if (User::esSuper())
            $facturas = Facturacion::where("estado", Facturacion::ESTADO_PAGADO)->get(array("id", "total"));
        else
            $facturas = Facturacion::where("instancia", Auth::user()->instancia)->where("estado", Facturacion::ESTADO_PAGADO)->get(array("id", "total"));

        $totalVentas = 0;
        $moneda = Monedas::actual();

        foreach ($facturas as $factura)
            $totalVentas+=Monedas::convertir(Facturacion::obtenerValorMetadato(MetaFacturacion::MONEDA_ID, $factura->id), $moneda, $factura->total);

        $totalVentas = Monedas::simbolo(Monedas::actual()) . Monedas::formatearNumero($moneda, $totalVentas);

        //APLICACIONES****************************************************
        if (User::esSuper())
            $cant_apps = count(Aplicacion::where("estado", Aplicacion::ESTADO_TERMINADA)->get());
        else
            $cant_apps = count(Aplicacion::join("usuarios", "aplicaciones.id_usuario", "=", "usuarios.id")->where("aplicaciones.estado", Aplicacion::ESTADO_TERMINADA)->where("usuarios.instancia", Auth::user()->instancia)->get());
        $cant_colaD = ProcesoApp::where("fecha_finalizacion", null)->orderBy("id", "ASC")->count();

        //FACTURACION****************************************************
        if (User::esSuper()) {
            $total_facturas = Facturacion::count();
            $cant_factSinPagar = Facturacion::where("estado", Facturacion::ESTADO_SIN_PAGAR)->orWhere("estado", Facturacion::ESTADO_VENCIDA)->count();
        } else {
            $total_facturas = Facturacion::where("instancia", Auth::user()->instancia)->count();
            $cant_factSinPagar = Facturacion::where("instancia", Auth::user()->instancia)->where("estado", Facturacion::ESTADO_SIN_PAGAR)->orWhere("estado", Facturacion::ESTADO_VENCIDA)->count();
        }

        //SERVICIOS****************************************************
        if (User::esSuper())
            $factServicios = MetaFacturacion::join('facturacion', 'facturacionMetadatos.id_factura', '=', 'facturacion.id')->where("facturacion.estado", Facturacion::ESTADO_PAGADO)->where("facturacionMetadatos.valor", "LIKE", Servicio::CONFIG_NOMBRE . "%")->orderBy('facturacionMetadatos.id', "DESC")->get();
        else
            $factServicios = MetaFacturacion::join('facturacion', 'facturacionMetadatos.id_factura', '=', 'facturacion.id')->where("facturacion.instancia", Auth::user()->instancia)->where("facturacion.estado", Facturacion::ESTADO_PAGADO)->where("facturacionMetadatos.valor", "LIKE", Servicio::CONFIG_NOMBRE . "%")->orderBy('facturacionMetadatos.id', "DESC")->get();

        $totalServicios = Servicio::where("instancia", Auth::user()->instancia)->count();
        $serviciosNoProcesados = 0;

        foreach ($factServicios as $fact) {
            $id_servicio = intval(str_replace(Servicio::CONFIG_NOMBRE, "", $fact->valor));
            $procesado = Util::convertirIntToBoolean(Facturacion::obtenerValorMetadato(MetaFacturacion::PRODUCTO_PROCESADO . $id_servicio, $fact->id));
            if (!$procesado)
                $serviciosNoProcesados++;
        }


        //USUARIOS****************************************************
        if (User::esSuper()) {
            $cant_usuarios_suscritos = User::where("estado", User::ESTADO_SUSCRIPCION_VIGENTE)->where("tipo", User::USUARIO_REGULAR)->count();
            $total_usuarios = User::where("tipo", User::USUARIO_REGULAR)->count();
        } else {
            $cant_usuarios_suscritos = User::where("estado", User::ESTADO_SUSCRIPCION_VIGENTE)->where("instancia", Auth::user()->instancia)->where("tipo", User::USUARIO_REGULAR)->count();
            $total_usuarios = User::where("estado", User::ESTADO_SUSCRIPCION_VIGENTE)->where("instancia", Auth::user()->instancia)->where("tipo", User::USUARIO_REGULAR)->count();
        }

        //TICKETS****************************************************
        if (User::esSuper()) {
            $total_tickets = Ticket::all()->count();
            $cant_ticketsAbierto = Ticket::where("estado", Ticket::ESTADO_ABIERTO)->count();
        } else {
            $total_tickets = Ticket::where("instancia", Auth::user()->instancia)->count();
            $cant_ticketsAbierto = Ticket::where("instancia", Auth::user()->instancia)->where("estado", Ticket::ESTADO_ABIERTO)->count();
        }



        return View::make("usuarios/tipo/admin/index")
                        ->with("moneda", $moneda)
                        ->with("totalVentas", $totalVentas)
                        ->with("cant_apps", $cant_apps)
                        ->with("cant_colaD", $cant_colaD)
                        ->with("total_facturas", $total_facturas)
                        ->with("cant_factSinPagar", $cant_factSinPagar)
                        ->with("totalServicios", $totalServicios)
                        ->with("serviciosNoProcesados", $serviciosNoProcesados)
                        ->with("cant_usuarios_suscritos", $cant_usuarios_suscritos)
                        ->with("total_usuarios", $total_usuarios)
                        ->with("total_tickets", $total_tickets)
                        ->with("cant_ticketsAbierto", $cant_ticketsAbierto);
    }

}
