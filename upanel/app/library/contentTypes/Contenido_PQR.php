<?php

class Contenido_PQR {

    const nombre = "pqr";
    const icono = "glyphicon-info-sign";
    const nombre_peticion = "peticion";
    const nombre_queja = "queja";
    const nombre_reclamo = "reclamo";
    const nombre_sugerencia = "sugerencia";
    const configNombre = "nombre";
    const configEmail = "email";
    const configUsuario = "usuario";

    /** Obtiene el nombre por defecto de este tipo de contenido
     * 
     * @return type
     */
    static function nombreDefecto() {
        return trans("app.tipo.contenido.pqr");
    }

    static function obtenerNombreTipo($tipo) {
        switch ($tipo) {
            case Contenido_PQR::tipo(Contenido_PQR::nombre_peticion):
                return trans("app.admin.pqr.info.peticion");
            case Contenido_PQR::tipo(Contenido_PQR::nombre_queja):
                return trans("app.admin.pqr.info.queja");
            case Contenido_PQR::tipo(Contenido_PQR::nombre_reclamo):
                return trans("app.admin.pqr.info.reclamo");
            case Contenido_PQR::tipo(Contenido_PQR::nombre_sugerencia):
                return trans("app.admin.pqr.info.sugerencia");
        }
    }

    static function tipo($nombre_tipo) {
        return Contenido_PQR::nombre . "_" . $nombre_tipo;
    }


    static function obtenerPQR($tipo) {
        return ContenidoApp::where("tipo", $tipo)->where("id_usuario", Auth::user()->id)->where("contenido_padre", null)->orderBy("id", "desc")->paginate(7);
    }

    static function cliente_obtenerNombre($id_pqr) {
        return ContenidoApp::obtenerValorMetadato($id_pqr, Contenido_PQR::configNombre);
    }

    static function cliente_obtenerEmail($id_pqr) {
        return ContenidoApp::obtenerValorMetadato($id_pqr, Contenido_PQR::configEmail);
    }

    static function obtenerUltimaRespuesta($id_pqr) {
        $discusion = Contenido_PQR::obtenerDiscusion($id_pqr);

        if (is_null($discusion))
            return trans("otros.user.usuario");

        foreach ($discusion as $hilo) {
            return Contenido_PQR::obtenerUsuarioPqr($hilo->id);
        }
    }

    /** Indica que usuario es propietario del mensaje de un pqr
     * 
     * @param type $id_pqr
     * @return type
     */
    static function obtenerUsuarioPqr($id_pqr) {
        $user = ContenidoApp::obtenerValorMetadato($id_pqr, Contenido_PQR::configUsuario);
        if (ctype_digit($user))
            return trans("otros.user.soporte");
        else
            return trans("otros.user.usuario");
        return $user;
    }

    /** Obtiene un array objeto con toda la discusión entre el cliente y soporte de un PQR
     * 
     * @param type $id_pqr
     */
    static function obtenerDiscusion($id_pqr) {
        $discusion = ContenidoApp::where("contenido_padre", "=", $id_pqr)->orderBy("id", "desc")->get();
        return (count($discusion) > 0) ? $discusion : null;
    }

    /** Obtiene una cadena que indica el intervalo de tiempo de la ultima actualizacion del pqr
     * 
     * @param type $id_pqr
     * @param type $fecha_pqr
     * @return type
     */
    static function obtenerUltimaActualizacion($id_pqr, $fecha_pqr) {
        //Obtiene la ultima actualización 
        if (is_null($discusion = Contenido_PQR::obtenerDiscusion($id_pqr)))
            $tiempo = Util::calcularDiferenciaFechas($fecha_pqr, Util::obtenerTiempoActual());
        else {
            foreach ($discusion as $hilo) {
                $tiempo = Util::calcularDiferenciaFechas($hilo->created_at, Util::obtenerTiempoActual());
                break;
            }
        }

        return $tiempo;
    }

    
    
    static function registrar($id_app, $id_usuario, $usuario, $nombre, $email, $asunto, $descripcion, $tipo,$id_padre) {
        $pqr = new ContenidoApp;
        $pqr->id_aplicacion = $id_app;
        $pqr->id_usuario = $id_usuario;
        $pqr->titulo = $asunto;
        $pqr->contenido = $descripcion;
        $pqr->tipo = $tipo;
        $pqr->estado = ContenidoApp::ESTADO_PUBLICO;
        if(intval($id_padre)>0)
            $pqr->contenido_padre=$id_padre;
        $pqr->save();

        ContenidoApp::agregarMetaDato($pqr->id, Contenido_PQR::configNombre, $nombre, $id_usuario);
        ContenidoApp::agregarMetaDato($pqr->id, Contenido_PQR::configEmail, $email, $id_usuario);
        ContenidoApp::agregarMetaDato($pqr->id, Contenido_PQR::configUsuario, $usuario, $id_usuario);

        $json["id_pqr"] = $pqr->id;
        $json["fecha"] = Util::obtenerTiempoActual(false);
            
        
         return Aplicacion::prepararDatosParaApp($json);
    }
    
    static function obtenerPrqUsuario($ids_pqr) {

        $data = array();

        for ($i = 0; $i < count($ids_pqr); $i++) {
            $data_discusion=array();
            $discusion = Contenido_PQR::obtenerDiscusion($ids_pqr[$i]);
            if (!is_null($discusion)) {
                $n=0;
                foreach($discusion as $hilo){
                    $data_discusion["id".$n]=  $hilo->id;
                    $data_discusion["usuario".$n]= ContenidoApp::obtenerValorMetadato($hilo->id,  Contenido_PQR::configUsuario);
                    $data_discusion["fecha".$n]=$hilo->created_at;
                    $data_discusion["mensaje".$n]=$hilo->contenido;
                    $n++;
                }                 
                $data["id_pqr".$ids_pqr[$i]]=$data_discusion;
                
            }else{
                $data["id_pqr".$ids_pqr[$i]]=null;
            }
        }
        
          return Aplicacion::prepararDatosParaApp($data);
    }

}
