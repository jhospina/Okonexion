<?php

class UPanelControladorUsuario extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        if (!Auth::check()) {
            return User::login();
        }

        return View::make("usuarios/perfil/index")->with("usuario", Auth::user());
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

        if (!is_null($acceso = User::invalidarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        return View::make("usuarios/tipo/admin/usuarios/crear");
    }

    public function create_store() {
        $data = Input::all();
        $data["instancia"] = Auth::user()->instancia;

        $user = new User;

        if (strlen($errores = $user->validar($data)) > 0)
            return Redirect::route('usuario.create')->withInput()->with(User::mensaje("error", "", $errores, 2));
        elseif ($user->registrar($data)) {

            if ($data["email_confirmado"] == 0) {
                $correo = new Correo;
                $codigo = $correo->generarCodigo();
                $correo->almacenarCodigo($codigo, $user->id);

                $mensaje = "<p>" . trans("email.hola", array("nombre" => $user->nombres)) . "</p>" .
                        "<p>" . trans("email.confirmacion.msj", array("id_usuario" => $user->id, "codigo" => $codigo)) . "</p>" .
                        "<p>" . trans("otros.dominio") . "/upanel/public/activar/" . $user->id . "/" . $codigo . "</p>";
                //Envia un mensaje de confirmación con un codigo al correo electronico, para validar la cuenta de usuario
                $correo->enviar(trans("email.asunto.confirmacion"), $mensaje, $user->id);
            }
            return Redirect::to('control/usuarios')->with(User::mensaje("exito", null, trans("menu_usuario.crear.usuario.post.exito"), 2));
        }
    }

    /**
     * Recibe las peticiones de registro de usuarios
     *
     * @return Response
     */
    public function store() {

        $user = new User;

        // Obtenemos la data enviada por el usuario
        $data = Input::all();

        $data["contrasena"] = $data["password"];


        //*******************************************
        //CONTROL DE CAPTCHA
        //*******************************************

        $secretKey = "6LcHXwoTAAAAAB7yNDSlmBq6MDOud6Ycd35bOIj1";
        $reCaptcha = new ReCaptcha($secretKey);
        // si se detecta la respuesta como enviada
        if (!$data["g-recaptcha-response"])
            return Redirect::to(Util::filtrarUrl($data["url"]) . "?response=error-captcha");

        $response = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"], $data["g-recaptcha-response"]);

        //Valida el captcha
        if ($response == null || !$response->success)
            return Redirect::to(Util::filtrarUrl($data["url"]) . "?response=error-captcha");

        //REALIZA EL REGISTRO DEL USUARIO
        if ($user->registrar($data)) {

            $ip = Util::obtenerDireccionIP();
            $pais = Paises::obtenerPaisDesdeIP($ip, "name");
            //Obtiene la moneda asignado para el pais del usuario
            $moneda = Monedas::asignacionPais($pais);
            //Asigna la moneda al usuario
            User::agregarMetaDato(UsuarioMetadato::OP_MONEDA, $moneda, $user->id);

            if ($user->estado == User::ESTADO_PERIODO_PRUEBA) {
                Notificacion::crear(Notificacion::TIPO_PRUEBA_MSJ_INICIAL, $user->id);
            }

            //Envia un mensaje de confirmación con un codigo al correo electronico, para validar la cuenta de usuario
            $correo = new Correo;
            $codigo = $correo->generarCodigo();
            $correo->almacenarCodigo($codigo, $user->id);

            $mensaje = "<p>" . trans("email.hola", array("nombre" => $user->nombres)) . "</p>" .
                    "<p>" . trans("email.confirmacion.msj", array("id_usuario" => $user->id, "codigo" => $codigo)) . "</p>" .
                    "<p>" . trans("otros.dominio") . "/upanel/public/activar/" . $user->id . "/" . $codigo . "</p>";
            //Envia un mensaje de confirmación con un codigo al correo electronico, para validar la cuenta de usuario
            $correo->enviar(trans("email.asunto.confirmacion"), $mensaje, $user->id);
            return Redirect::to(Util::filtrarUrl($data["url"]) . "?response=success&email=" . $data["email"]);
        } else
            return Redirect::to(Util::filtrarUrl($data["url"]) . "?response=error");
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

        //Invalida el acceso para el usuario Regular
        if (!is_null($acceso = User::invalidarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $usuario = User::find($id);

        return View::make("usuarios/perfil/index")->with("usuario", $usuario);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {

        if (!Auth::check()) {
            return User::login();
        }

        if ($id != Auth::user()->id)
            App::abort(404);

        $usuario = User::find($id);
        if (is_null($usuario)) {
            App::abort(404);
        }

        return View::make("usuarios/perfil/editar")->with('usuario', $usuario);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {

        //Permite que solo el usuario logueado se ha editado por el mismo
        if ($id == Auth::user()->id) {

            $user = User::find($id);

            // Obtenemos la data enviada por el usuario
            $data = Input::all();

            //Si los datos enviados tienen errores
            if (strlen($errores = $user->validar($data)) > 0) {
                return Redirect::route('usuario.edit', $id)->withInput()->with(User::mensaje("error", "", $errores, 2));
            } elseif ($user->registrar($data, true))
                return Redirect::route('usuario.index')->with(User::mensaje("exito", null, trans("menu_usuario.mi_perfil.editar.post.exito"), 2));
            else
                return Redirect::route('usuario.edit', $id)->withInput()->with(User::mensaje("error", "", trans("otros.error_solicitud"), 2));
        }

        return Redirect::route('usuario.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        
    }

    /**
     *  MUESTRA EL LISTADO DE USUARIOS EN UNA VISTA
     */
    public function index_listado() {

        if (!Auth::check()) {
            return User::login();
        }

        //Invalida el acceso para el usuario Regular
        if (!is_null($acceso = User::invalidarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        //Admin
        if (Auth::user()->tipo == User::USUARIO_ADMIN)
            $usuarios = User::where("id", "!=", Auth::user()->id)->where("instancia", Auth::user()->instancia)->where("instancia", Auth::user()->instancia)->orderBy("id", "DESC")->paginate(30);
        //SuperAdmin
        if (User::esSuperAdmin())
            $usuarios = User::where("id", "!=", Auth::user()->id)->orderBy("id", "DESC")->paginate(30);
        //Soporte general
        if (Auth::user()->tipo == User::USUARIO_SOPORTE_GENERAL)
            $usuarios = User::where("id", "!=", Auth::user()->id)->where("tipo", User::USUARIO_REGULAR)->where("instancia", Auth::user()->instancia)->orderBy("id", "DESC")->paginate(30);

        return View::make("usuarios/tipo/admin/usuarios/index")->with("usuarios", $usuarios);
    }

    public function cambiarContrasenaForm() {
        if (!Auth::check()) {
            return User::login();
        }
        return View::make("usuarios/perfil/cambiar-contrasena");
    }

    public function cambiarContrasenaPost() {


        $data = Input::all();

        if (strlen($data["contra-nueva"]) <= 5)
            return Redirect::to('cambiar-contrasena')->with(User::mensaje("error", null, trans("menu_usuario.cambiar_contrasena.post.error01"), 2));

        if ($data["contra-nueva"] != $data["contra-nueva-rep"])
            return Redirect::to('cambiar-contrasena')->with(User::mensaje("error", null, trans("menu_usuario.cambiar_contrasena.post.error02"), 2));

        if (User::cambiarContrasena($data["contra-actual"], $data["contra-nueva"]))
            return Redirect::Route('usuario.index')->with(User::mensaje("exito", null, trans("menu_usuario.cambiar_contrasena.post.exito"), 2));
        else
            return Redirect::to('cambiar-contrasena')->with(User::mensaje("error", null, trans("menu_usuario.cambiar_contrasena.post.error03"), 2));
    }

    public function cambiarIdioma() {
        User::actualizarMetadato(UsuarioMetadato::OP_IDIOMA, Input::get("idioma"));
    }

    //*******************************************************
    //AJAX***************************************************
    //*******************************************************+

    public function ajax_actualizar() {

        if (!Request::ajax())
            return;

        if (!Auth::check())
            return;

        $data = Input::all();

        $user = User::find(Auth::user()->id);
        $user->registrar($data, true);
        return json_encode(array());
    }

}
