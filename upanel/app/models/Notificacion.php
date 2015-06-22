<?php

Class Notificacion extends Eloquent {

    protected $table = "notificaciones";

    /**
     * TIPOS DE NOTIFICACIONES
     */
    const TIPO_SUSCRIPCION_PRUEBA_FINALIZADA = "SUPF";
    const TIPO_SUSCRIPCION_REALIZADA = "SURE";
    const TIPO_FACTURACION_AUTO_SUSCRIPCION = "FACS";

    /** crear una notificacion de usuario
     * 
     * @param string $tipo El tipo de notificacion
     * @param int $usuario [Opcional] El usuario al que se le notificara, si no se define se utilizara el de la sesion actual
     * @param int $link [Opcional] A que se debe la notificacion
     * @return boolean
     */
    static function crear($tipo, $usuario = null, $link = null) {
        $not = new Notificacion;

        if (!is_null($usuario)) {
            $not->id_usuario = $usuario;
            $user = User::find($usuario);
            $instancia = $user->instancia;
        } else {
            $not->id_usuario = Auth::user()->id;
            $instancia = Auth::user()->instancia;
        }

        if (!is_null($link))
            $not->link = $link;

        $not->instancia = $instancia;
        $not->tipo = $tipo;
        $not->visto = false;
        return $not->save();
    }

    /** Establece una notificacion como vista, dada por su id
     * 
     * @param type $id_not El id de la notificacion
     * @return boolean
     */
    static function visto($id_not = null) {

        if (is_null($id_not))
            return Notificacion::where("id_usuario", Auth::user()->id)->update(array("visto" => true));

        $not = Notificacion::find($id_not);
        if (is_null($not))
            return;
        $not->visto = true;
        return $not->save();
    }

    /** Retorna una descripcion en forma de mensaje de la notificacion dirijida al usuario
     * 
     * @param type $tipo El tipo de notificacion
     * @param type $params Datos opciones
     * @return String El mensaje
     */
    static function descripcion($tipo, $params = null) {
        $msj = array(
            Notificacion::TIPO_SUSCRIPCION_PRUEBA_FINALIZADA => trans("nots.suscripcion.prueba.finalizada"),
            Notificacion::TIPO_SUSCRIPCION_REALIZADA => trans("nots.suscripcion.pago.realizado", array("link" => "#")),
            Notificacion::TIPO_FACTURACION_AUTO_SUSCRIPCION => trans("nots.facturacion.auto.suscripcion")
        );
        return $msj[$tipo];
    }

    static function icono($tipo) {
        $msj = array(
            Notificacion::TIPO_SUSCRIPCION_PRUEBA_FINALIZADA => '<span class="glyphicon glyphicon-exclamation-sign"></span>',
            Notificacion::TIPO_SUSCRIPCION_REALIZADA => '<span class="glyphicon glyphicon-thumbs-up"></span>',
            Notificacion::TIPO_FACTURACION_AUTO_SUSCRIPCION => '<span class="glyphicon glyphicon-list-alt"></span>',
        );
        return $msj[$tipo];
    }

    /** Obtiene una cantidad de notificaciones
     * 
     * @param type $cantidad
     * @param type $id_usuario
     * @return type
     */
    static function obtener($cantidad, $id_usuario = null) {
        if (is_null($id_usuario))
            $id_usuario = Auth::user()->id;
        $nots = Notificacion::where("id_usuario", $id_usuario)->orderBy("id", "DESC")->take($cantidad)->get();
        return (count($nots) > 0) ? $nots : null;
    }

    /** Obtiene un lista enlazada con las notificaciones no vistas por el usuario
     * 
     * @param type $id_usuario
     * @return type
     */
    static function noVistas($id_usuario = null) {
        if (is_null($id_usuario))
            $id_usuario = Auth::user()->id;
        $nots = Notificacion::where("id_usuario", $id_usuario)->where("visto", false)->orderBy("id", "DESC")->get();
        return (count($nots) > 0) ? $nots : null;
    }

    static function plantilla($not) {
        $html = "<li class='not-item'";
        if (!is_null($not->link))
            $html .="onClick='location.href=\"" . $not->link . "\";'";
        $html .="><p>";
        $html.=Notificacion::icono($not->tipo) . " " . Notificacion::descripcion($not->tipo);
        $html.="</p><span class='fecha-not'><span class='glyphicon glyphicon-time'></span> " . trans("otros.info.hace") . " " . Util::calcularDiferenciaFechas($not->created_at, Util::obtenerTiempoActual()) . "</span></li>";
        return $html;
    }

}
