<?php

//VERIFICA EL PERIODO DE PRUEBA DEL USUARIO
if (Auth::user()) {
    if (Auth::user()->estado == User::ESTADO_PERIODO_PRUEBA) {
        if (!Util::calcularDiferenciaFechas(Util::obtenerTiempoActual(), Auth::user()->fin_suscripcion)) {
            Auth::user()->estado = User::ESTADO_PRUEBA_FINALIZADA;
            Auth::user()->save();
            Notificacion::crear(Notificacion::TIPO_SUSCRIPCION_PRUEBA_FINALIZADA);
        }
    }
    
    if (Auth::user()->estado == User::ESTADO_SUSCRIPCION_VIGENTE) {
         if (!Util::calcularDiferenciaFechas(Util::obtenerTiempoActual(), Auth::user()->fin_suscripcion)) {
            Auth::user()->estado = User::ESTADO_SUSCRIPCION_CADUCADA;
            Auth::user()->save();
            Notificacion::crear(Notificacion::TIPO_SUSCRIPCION_CADUCADA,null,URL::to("fact/suscripcion/plan"));
        }
    }

    if (Auth::user()->tipo == User::USUARIO_REGULAR)
        define("TIEMPO_SUSCRIPCION", Fecha::calcularDiferencia(Util::obtenerTiempoActual(), Auth::user()->fin_suscripcion));
}



/*
if (!Util::esConexionSegura()) {    
    exit("It's Connection is not secure");
}*/
        
