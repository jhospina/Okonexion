<?php

class UPanelControladorSoporte extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        if (!Auth::check()) {
            return User::login();
        }

        if (Auth::user()->tipo == User::USUARIO_REGULAR) {
            $user = User::find(Auth::user()->id);
            $tickets = $user->tickets()->orderBy("fecha", "desc")->paginate(20);
            return View::make("usuarios/tipo/regular/ayuda/soporte/index")->with("tickets", $tickets);
        } else {
            if (isset($_GET["ref"])) {
                if (!(User::esSuperAdmin() || Auth::user()->instancia == User::PARAM_INSTANCIA_SUPER_ADMIN) && $_GET["ref"] == "assist") {
                    $user = User::find(Auth::user()->id);
                    $tickets = $user->tickets()->orderBy("fecha", "desc")->paginate(20);
                    return View::make("usuarios/tipo/soporteGeneral/ayuda/soporte/index_oko")->with("tickets", $tickets);
                }
            }
            $ticket = new Ticket;
            if (User::esSuperAdmin() || Auth::user()->instancia == User::PARAM_INSTANCIA_SUPER_ADMIN)
                $tickets = $ticket->filtrar_super();
            else
                $tickets = $ticket->filtrar();
            return View::make("usuarios/tipo/soporteGeneral/ayuda/soporte/index")->with("tickets", $tickets);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {

        if (!Auth::check()) {
            return User::login();
        }

        if (Auth::user()->tipo == User::USUARIO_REGULAR) {
            $ticket = new Ticket();
            return View::make("usuarios/tipo/regular/ayuda/soporte/crear")->with("ticket", $ticket);
        }

        if (!(User::esSuperAdmin() || Auth::user()->instancia == User::PARAM_INSTANCIA_SUPER_ADMIN)) {
            $ticket = new Ticket();
            return View::make("usuarios/tipo/soporteGeneral/ayuda/soporte/crear")->with("ticket", $ticket);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {



        $permitidos = array("png", "jpg", "jpeg", "pdf", "zip", "gif");

        $data = Input::all();
        $ticket = new Ticket();

//Si los datos enviados reportan errores
        if (strlen($errores = $ticket->validar($data)) > 0)
            return Redirect::route('soporte.create')->withInput()->with(User::mensaje("error", "", $errores, 2));

//Controla el ingreso correcto de un archivo adjunto
        if (Input::hasFile('adjunto')) {
            $extension = strtolower(Input::file('adjunto')->getClientOriginalExtension());
            $size = Input::file('adjunto')->getSize();

            if (!in_array($extension, $permitidos))
                return Redirect::route('soporte.create')->withInput()->with(User::mensaje("error", "", trans("menu_ayuda.soporte.tickets.crear.post.archivo.error01"), 2));

            if ($size > 1000000)
                return Redirect::route('soporte.create')->withInput()->with(User::mensaje("error", "", trans("menu_ayuda.soporte.tickets.crear.post.archivo.error02"), 2));


            $path = 'usuarios/uploads/' . Auth::user()->id . "/";
            $archivo = $ticket->getStamp() . "." . $extension;

            Input::file('adjunto')
                    ->move($path, $archivo);

            $ticket->url_adjunto = URL::to($path . $archivo);
        }

        $ticket->usuario_cliente = Auth::user()->id;
        $ticket->tipo = $data["tipo"];
        $ticket->asunto = $data["asunto"];
        $ticket->mensaje = str_replace("\n", "<br/>", $data["mensaje"]);
        $ticket->fecha = date('Y-m-d H:i:s');
        $ticket->instancia = Auth::user()->instancia;
        if ($ticket->save()) {

            $correo = new Correo;

            $mensaje = trans("email.soporte.tickets.crear", array("nombre" => Auth::user()->nombres, "id_ticket" => $ticket->id, "tipo_ticket" => $ticket->tipo, "asunto_ticket" => $ticket->asunto, "fecha_apertura" => $ticket->fecha, "link" => "<a href='" . URL::to("soporte/" . $ticket->id) . "'>" . URL::to("soporte/" . $ticket->id) . "</a>"));

            $correo->enviar(trans("menu_ayuda.soporte.tickets.email.asunto"), $mensaje, Auth::user()->id);

            return Redirect::route('soporte.index')->withInput()->with(User::mensaje("exito", "", trans("menu_ayuda.soporte.tickets.crear.post.exito"), 2));
        } else
            return Redirect::route('soporte.create')->withInput()->with(User::mensaje("error", "", trans("otros.error_solicitud"), 2));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {

        if (!Auth::check()) {
            return User::login();
        }

        $ticket = Ticket::find($id);
        $mensajes = $ticket->mensajes()->orderBy('fecha', 'desc')->get();

        if (isset($_GET["ref"])) {
            if (!(User::esSuperAdmin() || Auth::user()->instancia == User::PARAM_INSTANCIA_SUPER_ADMIN) && $_GET["ref"] == "assist") {
                return View::make("usuarios/tipo/soporteGeneral/ayuda/soporte/mostrar_oko")->with("ticket", $ticket)->with("mensajes", $mensajes);
            }
        } else {
            return View::make("usuarios/tipo/regular/ayuda/soporte/mostrar")->with("ticket", $ticket)->with("mensajes", $mensajes);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {

        if (!Auth::check()) {
            return User::login();
        }


        $ticket = Ticket::find($id);

        if (Input::get("action") == "cerrar") {

            $ticket->estado = Ticket::ESTADO_CERRADO;

            if ($ticket->update()) {
                return Redirect::route('soporte.index')->withInput()->with(User::mensaje("info", "", trans("menu_ayuda.soporte.tickets.mostrar.post.info.cerrar") . $id, 2));
            }
        } elseif (Input::get("action") == "mensaje") {

            $permitidos = array("png", "jpg", "jpeg", "pdf", "zip", "gif");

            $mensaje = new MensajeTicket;
            $mensaje->id_ticket = $id;
            $mensaje->id_usuario = Auth::user()->id;
            $mensaje->mensaje = str_replace("\n", "<br/>", Input::get("mensaje"));
            $mensaje->fecha = User::obtenerTiempoActual();
            $ticket->estado = Ticket::ESTADO_ENVIADO;


            if (!isset($_POST["ref"])) {
                //SI el mensaje es enviado por alguiien que no es un clinte, el ticket se le asigna al usuario de soporte que esta atendiendo el ticket
                if (Auth::user()->tipo != User::USUARIO_REGULAR) {
                    $ticket->usuario_soporte = Auth::user()->id;
                    $ticket->estado = Ticket::ESTADO_RESPONDIDO;
                    $correo = new Correo;

                    $mensaje_correo = trans("email.soporte.ticket.respondido", array("nombre" => $ticket->user->nombres, "mensaje" => $mensaje->mensaje, "link" => "<a href='" . URL::to("soporte/" . $ticket->id) . "'>" . URL::to("soporte/" . $ticket->id) . "</a>"));

                    $correo->enviar(trans("otros.sigla.re") . ": " . $ticket->asunto, $mensaje_correo, $ticket->usuario_cliente);
                }
            }


//Controla el ingreso correcto de un archivo adjunto
            if (Input::hasFile('adjunto')) {
                $extension = strtolower(Input:: file('adjunto')->
                                getClientOriginalExtension());
                $size = Input::file('adjunto')->getSize();

                if (!in_array($extension, $permitidos))
                    return Redirect::route('soporte.show', $id)->
                                    withInput()->with(User::mensaje("error", "", trans("menu_ayuda.soporte.tickets.crear.post.archivo.error01"), 2));

                if ($size > 1000000)
                    return Redirect:: route('soporte.show', $id)->withInput()->with(User::mensaje("error", "", trans("menu_ayuda.soporte.tickets.crear.post.archivo.error02"), 2));


                $path = 'usuarios/soporte/adjuntos/' . Auth::user()->id . "/";
                $archivo = $ticket->getStamp() . "." . $extension;

                Input::file('adjunto')
                        ->move($path, $archivo);

                $mensaje->url_adjunto = URL::to($path . $archivo);
            }

            if ($mensaje->save()) {

                $ticket->update();
                if (Auth::user()->tipo == User::USUARIO_REGULAR)
                    return Redirect::route('soporte.index')->withInput()->with(
                                    User::mensaje("exito", "", trans("menu_ayuda.soporte.tickets.mostrar.post.exito01"), 2));
                elseif (isset($_POST["ref"])) {
                    return Redirect::to('soporte?ref='.$_POST["ref"])->withInput()->with(
                                    User::mensaje("exito", "", trans("menu_ayuda.soporte.tickets.mostrar.post.exito02"), 2));
                } else {
                    return Redirect::route('soporte.index')->withInput()->with(
                                    User::mensaje("exito", "", trans("menu_ayuda.soporte.tickets.mostrar.post.exito02"), 2));
                }
            } else
                return Redirect::route('soporte.index')->withInput()->with(User::mensaje("error", "", trans("otros.error_solicitud"), 2));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        
    }

}
