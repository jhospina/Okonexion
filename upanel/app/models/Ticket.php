<?php

Class Ticket extends Eloquent {

    protected $table = 'tickets_soporte';
    protected $fillable = array('usuario_cliente', 'usuario_soporte', 'tipo', 'asunto', 'mensaje', "url_adjunto", "estado", "fecha");

    //********************************************************
    //TIPOS DE SOPORTE**************************************
    //********************************************************

    const TIPO_GENERAL = "SG";
    const TIPO_COMERCIAL = "SC";
    const TIPO_FACTURACION = "SF";
    const TIPO_TECNICO = "ST";
    //********************************************************
    //ESTADOS DEL TICKET**************************************
    //********************************************************

    const ESTADO_ABIERTO = "AB";
    const ESTADO_RESPONDIDO = "RE";
    const ESTADO_PROCESANDO = "PR";
    const ESTADO_ENVIADO = "EN";
    const ESTADO_CERRADO = "CE";

    public function validar($data) {
        $errores = "";

        if ($data["tipo"] == "Elegir")
            $errores.="<li>Debes elegir el tipo de soporte que necesitas</li>";

        //Nombre
        if (strlen($data["asunto"]) <= 1)
            $errores.="<li>Debes escribir un asunto</li>";

        if (strlen($data["mensaje"]) <= 1)
            $errores.="<li>Debes escribir un mensaje para que podamos ayudarte</li>";

        if (strlen($errores) > 0)
            return "<ul>" . $errores . "</ul>";
        else
            return $errores;
    }

    public function getStamp() {
        list($Mili, $bot) = explode(" ", microtime());
        $DM = substr(strval($Mili), 2, 4);
        return strval(date("Y") . date("m") . date("d") . date("H") . date("i") . date("s") . $DM);
    }

    public static function getTableName() {
        $ticket = new Ticket;
        return $ticket->table;
    }

    //Filtra los resultado de un listado de tickets
    public function filtrar() {

        $paginacion = 50;

        if (isset($_GET["estado"])) {
            if ($_GET["estado"] != "TO")
                $estado = $_GET["estado"];
            else
                $estado = null;
        }
        else {
            $estado = null;
        }
        if (isset($_GET["buscar"]))
            $buscar = $_GET["buscar"];
        else
            $buscar = null;

        if (isset($_GET["tipo"])) {
            if ($_GET["tipo"] != "TO")
                $tipo = $_GET["tipo"];
            else
                $tipo = null;
        } else {
            $tipo = null;
        }

        if ($estado != null || $tipo != null || $buscar != null) {

            if ($buscar != null && strlen($buscar) > 0) {

                $tickets = Ticket::where('id', 'LIKE', '%' . $buscar . '%')->paginate($paginacion);
                if (count($tickets) == 0) {
                    $usuarios = User::where('nombres', 'LIKE', '%' . $buscar . '%')->paginate($paginacion);

                    foreach ($usuarios as $usuario) {
                        if ($estado != null && $tipo != null) {
                            $tickets = Ticket::where("usuario_cliente", "=", $usuario->id)
                                    ->where("estado", "=", $estado)
                                    ->where("tipo", "=", $tipo)
                                    ->orderBy("fecha", "desc")
                                    ->paginate($paginacion);
                        } elseif ($estado != null) {
                            $tickets = Ticket::where("usuario_cliente", "=", $usuario->id)
                                    ->where("estado", "=", $estado)
                                    ->orderBy("fecha", "desc")
                                    ->paginate($paginacion);
                        } elseif ($tipo != null) {
                            $tickets = Ticket::where("usuario_cliente", "=", $usuario->id)
                                    ->where("tipo", "=", $tipo)
                                    ->orderBy("fecha", "desc")
                                    ->paginate($paginacion);
                        }
                    }
                }
            } elseif ($estado != null && $tipo != null) {
                $tickets = Ticket::where("estado", "=", $estado)->where("tipo", "=", $tipo)->orderBy("fecha", "desc")->paginate($paginacion);
            } elseif ($estado != null) {
                $tickets = Ticket::where("estado", "=", $estado)->orderBy("fecha", "desc")->paginate($paginacion);
            } elseif ($tipo != null) {
                $tickets = Ticket::where("tipo", "=", $tipo)->orderBy("fecha", "desc")->paginate($paginacion);
            }
        } else {

            $tickets = Ticket::orderBy("fecha", "desc")->paginate($paginacion);
        }

        return $tickets;
    }

    //****************************************************
    //RELACIONES CON OTROS MODELOS***************************
    //****************************************************

    public function user() {
        return $this->belongsTo('User', "usuario_cliente");
    }

    public function mensajes() {
        return $this->hasMany("MensajeTicket", "id_ticket", "id");
    }

    //****************************************************
    //MODIFICACION DE ATRIBUTOS***************************
    //****************************************************

    public function getEstadoAttribute($value) {
        $estados = array(Ticket::ESTADO_ABIERTO => "Abierto", Ticket::ESTADO_RESPONDIDO => "Respondido", Ticket::ESTADO_PROCESANDO => "Procesando", Ticket::ESTADO_ENVIADO => "Enviado", Ticket::ESTADO_CERRADO => "Cerrado");
        return $estados[$value];
    }

    public static function obtenerNombreEstado($valor) {
        $estados = array(Ticket::ESTADO_ABIERTO => "Abierto", Ticket::ESTADO_RESPONDIDO => "Respondido", Ticket::ESTADO_PROCESANDO => "Procesando", Ticket::ESTADO_ENVIADO => "Enviado", Ticket::ESTADO_CERRADO => "Cerrado");
        return $estados[$valor];
    }

    public function getFechaAttribute($value) {
        $meses = array("01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre");

        $ft = explode(" ", $value);

        $fecha = explode("-", $ft[0]);

        return $fecha[2] . " de " . $meses[$fecha[1]] . " del " . $fecha[0] . " (" . $ft[1] . ")";
    }

    public function getTipoAttribute($value) {
        $tipos = array(Ticket::TIPO_GENERAL => "General", Ticket::TIPO_COMERCIAL => "Comercial", Ticket::TIPO_FACTURACION => "Facturación", Ticket::TIPO_TECNICO => "Técnico");
        if (!is_null($value))
            return $tipos[$value];
        else
            return null;
    }

    public static function obtenerNombreTipo($valor) {
        $tipos = array(Ticket::TIPO_GENERAL => "General", Ticket::TIPO_COMERCIAL => "Comercial", Ticket::TIPO_FACTURACION => "Facturación", Ticket::TIPO_TECNICO => "Técnico");
        if (!is_null($valor))
            return $tipos[$valor];
        else
            return null;
    }

}
