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
    const TIPO_ESPECIAL = "ES";
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

        if ($data["tipo"] == trans("otros.elegir"))
            $errores.="<li>" . trans("menu_ayuda.soporte.tickets.crear.info.tipo_soporte.error") . "</li>";

        //Nombre
        if (strlen($data["asunto"]) <= 1)
            $errores.="<li>" . trans("menu_ayuda.soporte.tickets.crear.info.asunto.error") . "</li>";

        if (strlen($data["mensaje"]) <= 1)
            $errores.="<li>" . trans("menu_ayuda.soporte.tickets.crear.info.mensaje.error") . "</li>";

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

        $paginacion = 30;

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

                $tickets = Ticket::where('id', 'LIKE', '%' . $buscar . '%')->where("instancia", Auth::user()->instancia)->where("tipo", "!=", Ticket::TIPO_ESPECIAL)->get();
                if (count($tickets) == 0) {
                    $usuarios = User::where('nombres', 'LIKE', '%' . $buscar . '%')->where("instancia", Auth::user()->instancia)->where("tipo", "!=", Ticket::TIPO_ESPECIAL)->get();

                    foreach ($usuarios as $usuario) {
                        if ($estado != null && $tipo != null) {
                            $tickets = Ticket::where("usuario_cliente", "=", $usuario->id)
                                    ->where("estado", "=", $estado)
                                    ->where("tipo", "=", $tipo)
                                    ->where("instancia", Auth::user()->instancia)->where("tipo", "!=", Ticket::TIPO_ESPECIAL)
                                    ->orderBy("fecha", "desc")
                                    ->paginate($paginacion);
                        } elseif ($estado != null) {
                            $tickets = Ticket::where("usuario_cliente", "=", $usuario->id)
                                    ->where("estado", "=", $estado)
                                    ->where("instancia", Auth::user()->instancia)->where("tipo", "!=", Ticket::TIPO_ESPECIAL)
                                    ->orderBy("fecha", "desc")
                                    ->paginate($paginacion);
                        } elseif ($tipo != null) {
                            $tickets = Ticket::where("usuario_cliente", "=", $usuario->id)
                                    ->where("tipo", "=", $tipo)
                                    ->where("instancia", Auth::user()->instancia)->where("tipo", "!=", Ticket::TIPO_ESPECIAL)
                                    ->orderBy("fecha", "desc")
                                    ->paginate($paginacion);
                        }

                        if (is_null($estado) && is_null($tipo)) {
                            $tickets = Ticket::where("usuario_cliente", "=", $usuario->id)
                                    ->where("instancia", Auth::user()->instancia)->where("tipo", "!=", Ticket::TIPO_ESPECIAL)
                                    ->orderBy("fecha", "desc")
                                    ->paginate($paginacion);
                        }
                    }
                }
            } elseif ($estado != null && $tipo != null) {
                $tickets = Ticket::where("estado", "=", $estado)->where("tipo", "=", $tipo)->where("instancia", Auth::user()->instancia)->where("tipo", "!=", Ticket::TIPO_ESPECIAL)->orderBy("fecha", "desc")->paginate($paginacion);
            } elseif ($estado != null) {
                $tickets = Ticket::where("estado", "=", $estado)->where("instancia", Auth::user()->instancia)->where("tipo", "!=", Ticket::TIPO_ESPECIAL)->orderBy("fecha", "desc")->paginate($paginacion);
            } elseif ($tipo != null) {
                $tickets = Ticket::where("tipo", "=", $tipo)->where("instancia", Auth::user()->instancia)->where("tipo", "!=", Ticket::TIPO_ESPECIAL)->orderBy("fecha", "desc")->paginate($paginacion);
            }
        } else {
            $tickets = Ticket::where("instancia", Auth::user()->instancia)->where("tipo", "!=", Ticket::TIPO_ESPECIAL)->orderBy("fecha", "desc")->paginate($paginacion);
        }

        return $tickets;
    }

    //Filtra los resultado de un listado de tickets
    public function filtrar_super() {
        $paginacion = 30;
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

                $tickets = Ticket::where('id', 'LIKE', '%' . $buscar . '%')->get();
                if (count($tickets) == 0) {
                    $usuarios = User::where('nombres', 'LIKE', '%' . $buscar . '%')->get();

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

                        if (is_null($estado) && is_null($tipo)) {
                            $tickets = Ticket::where("usuario_cliente", "=", $usuario->id)
                                    
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

    public static function obtenerEstados() {
        return array(Ticket::ESTADO_ABIERTO => trans("atributos.estado.ticket.abierto"),
            Ticket::ESTADO_RESPONDIDO => trans("atributos.estado.ticket.respondido"),
            Ticket::ESTADO_PROCESANDO => trans("atributos.estado.ticket.procesando"),
            Ticket::ESTADO_ENVIADO => trans("atributos.estado.ticket.enviado"),
            Ticket::ESTADO_CERRADO => trans("atributos.estado.ticket.cerrado"));
    }

    public static function obtenerTipos() {
        return array(Ticket::TIPO_GENERAL => trans("atributos.tipo.ticket.general")
            , Ticket::TIPO_COMERCIAL => trans("atributos.tipo.ticket.comercial")
            , Ticket::TIPO_FACTURACION => trans("atributos.tipo.ticket.facturacion")
            , Ticket::TIPO_TECNICO => trans("atributos.tipo.ticket.tecnico")
            , Ticket::TIPO_ESPECIAL => trans("atributos.estado.ticket.especial"));
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
        $estados = Ticket::obtenerEstados();
        return $estados[$value];
    }

    public static function obtenerNombreEstado($valor) {
        $estados = Ticket::obtenerEstados();
        return $estados[$valor];
    }

    public function getFechaAttribute($value) {
        $meses = Util::obtenerNombreMeses();

        $ft = explode(" ", $value);

        $fecha = explode("-", $ft[0]);

        return trans("otros.fecha.formato_01", array("dia" => $fecha[2], "mes" => $meses[$fecha[1]], "ano" => $fecha[0], "hora" => $ft[1]));
    }

    public function getTipoAttribute($value) {
        $tipos = Ticket::obtenerTipos();
        if (!is_null($value))
            return $tipos[$value];
        else
            return null;
    }

    public static function obtenerNombreTipo($valor) {
        $tipos = Ticket::obtenerTipos();
        if (!is_null($valor))
            return $tipos[$valor];
        else
            return null;
    }

}
