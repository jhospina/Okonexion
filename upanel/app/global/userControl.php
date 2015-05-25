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
}
        
