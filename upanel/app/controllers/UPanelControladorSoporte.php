<?php

class UPanelControladorSoporte extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {


        if (Auth::user()->tipo == User::USUARIO_REGULAR) {

            $user = User::find(Auth::user()->id);
            $tickets = $user->tickets()->orderBy("fecha", "desc")->paginate(10);
            return View::make("usuarios/tipo/regular/ayuda/soporte/index")->with("tickets", $tickets);
        }

        if (Auth::user()->tipo == User::USUARIO_SOPORTE_GENERAL) {
            $ticket = new Ticket;
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

        if (Auth::user()->tipo == User::USUARIO_REGULAR) {
            $ticket = new Ticket();
            return View::make("usuarios/tipo/regular/ayuda/soporte/crear")->with("ticket", $ticket);
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
                return Redirect::route('soporte.create')->withInput()->with(User::mensaje("error", "", "El tipo de archivo subido no es válido.", 2));

            if ($size > 1000000)
                return Redirect::route('soporte.create')->withInput()->with(User::mensaje("error", "", "El tamaño del archivo adjunto es demasiado grande. Maximo de 1Mb.", 2));


            $path = 'usuarios/uploads/' . Auth::user()->id . "/";
            $archivo = $ticket->getStamp() . "." . $extension;

            Input::file('adjunto')
                    ->move($path, $archivo);

            $ticket->url_adjunto = URL::to($path . $archivo);
        }

        $ticket->usuario_cliente = Auth::user()->id;
        $ticket->tipo = $data["tipo"];
        $ticket->asunto = $data["asunto"];
        $ticket->mensaje = $data["mensaje"];
        $ticket->fecha = date('Y-m-d H:i:s');
        if ($ticket->save()) {

            $correo = new Correo;

            $mensaje = "<p>Estimado " . Auth::user()->nombres . "</p>" .
                    "<p>Gracias por contactar con nuestro equipo de soporte. Un ticket de soporte ha sido abierto por tu solicitud. Serás notificado por email cuando se haga una respuesta. Los detalles de tu ticket se muestran acontinuación:</p>" .
                    "<p>-------------------------------------<br/>" .
                    "<b>Ticket ID:</b> " . $ticket->id . "<br/>" .
                    "<b>Tipo de soporte:</b> " . $ticket->tipo . "<br/>" .
                    "<b>Asunto:</b> " . $ticket->asunto . "<br/>" .
                    "<b>Estado:</b> Abierto<br/>" .
                    "<b>Fecha y hora de apertura:</b> " . $ticket->fecha . "<br/>" .
                    "<b>Responder Ticket en:</b> <a href='" . URL::to("soporte/" . $ticket->id) . "'>" . URL::to("soporte/" . $ticket->id) . "</a><br/>" .
                    "-------------------------------------</p>";

            $correo->enviar("Ticket de soporte abierto", $mensaje, Auth::user()->id);

            return Redirect::route('soporte.index')->withInput()->with(User::mensaje("exito", "", "El ticket ha sido creado con exito. Por favor espera un momento y te contestaremos cuando sea posible. Muchas gracias", 2));
        } else
            return Redirect::route('soporte.create')->withInput()->with(User::mensaje("error", "", "Hubo un error al procesar la solicitud. Intentalo de nuevo.", 2));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $ticket = Ticket::find($id);
        $mensajes = $ticket->mensajes()->orderBy('fecha', 'desc')->get();
        return View::make("usuarios/tipo/regular/ayuda/soporte/mostrar")->with("ticket", $ticket)->with("mensajes", $mensajes);
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

        $ticket = Ticket::find($id);



        if (Input::get("action") == "cerrar") {

            $ticket->estado = "CE";

            if ($ticket->update()) {
                return Redirect::route('soporte.index')->withInput()->with(User::mensaje("info", "", "Has cerrado el ticket #" . $id, 2));
            }
        } elseif (Input::get("action") == "mensaje") {

            $permitidos = array("png", "jpg", "jpeg", "pdf", "zip", "gif");

            $mensaje = new MensajeTicket;
            $mensaje->id_ticket = $id;
            $mensaje->id_usuario = Auth::user()->id;
            $mensaje->mensaje = str_replace("\n", "<br/>", Input::get("mensaje"));
            $mensaje->fecha = User::obtenerTiempoActual();
            $ticket->estado = "EN";


            //SI el mensaje es enviado por alguiien que no es un cliente, el ticket se le asigna al usuario de soporte que esta atendiendo el ticket
            if (Auth::user()->tipo != User::USUARIO_REGULAR) {
                $ticket->usuario_soporte = Auth::user()->id;
                $ticket->estado = User::USUARIO_REGULAR;
                $correo = new Correo;

                $mensaje_correo = "<p>Estimado " . $ticket->user->nombres . "</p>" .
                        "<p>Tu ticket ha sido respondido por nuestro equipo de soporte: <br/>" .
                        "------------------------------------------------------------<br/>" .
                        $mensaje->mensaje . "<br/>" .
                        "------------------------------------------------------------" . "</p>" .
                        "<p>Para responder este ticket ingresa a <a href='" . URL::to("soporte/" . $ticket->id) . "'>" . URL::to("soporte/" . $ticket->id) . "</a></p>";

                $correo->enviar("Re: " . $ticket->asunto, $mensaje_correo, $ticket->usuario_cliente);
            }


            //Controla el ingreso correcto de un archivo adjunto
            if (Input::hasFile('adjunto')) {
                $extension = strtolower(Input:: file('adjunto')->
                                getClientOriginalExtension());
                $size = Input::file('adjunto')->getSize();

                if (!in_array($extension, $permitidos))
                    return Redirect::route('soporte.show', $id)->
                                    withInput()->with(User::mensaje("error", "", "El tipo de archivo subido no es válido.", 2));

                if ($size > 1000000)
                    return Redirect:: route('soporte.show', $id)->withInput()->with(User::mensaje("error", "", "El tamaño del archivo adjunto es demasiado grande. Maximo de 1Mb.", 2));


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
                                    User::mensaje("exito", "", "El mensaje ha sido enviado con exito. Muchas gracias. Pronto te responderemos", 2));
                else
                    return Redirect::route('soporte.index')->withInput()->with(
                                    User::mensaje("exito", "", "El mensaje ha sido enviado con exito.", 2));
            } else
                return Redirect::route('soporte.index')->withInput()->with(User::mensaje("error", "", "Hubo un error al procesar la solicitud. Intentalo de nuevo.", 2));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}
