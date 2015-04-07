<?php

class UPanelControladorUsuario extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        return View::make("usuarios/perfil/index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {

        $user = new User;

        // Obtenemos la data enviada por el usuario
        $data = Input::all();

        $data["contrasena"] = $data["password"];

        if ($user->registrar($data)) {
            $correo = new Correo;
            //Envia un mensaje de confirmación con un codigo al correo electronico, para validar la cuenta de usuario
            $correo->enviarConfirmacion(array("nombre" => $data["nombre1"] . " " . $data["nombre2"], "email" => $data["email"], "contrasena" => $data["contrasena"]), $user->id);
            return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/crear-cuenta/?response=success&email=" . $data["email"]);
        } else
            return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/crear-cuenta/?response=error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {

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
            if (strlen($errores = $user->validar($data)) > 0)
                return Redirect::route('usuario.edit', $id)->withInput()->with(User::mensaje("error", "",$errores, 2));
            elseif ($user->registrar($data))
                return Redirect::route('usuario.index')->with(User::mensaje("exito", null, "Tus datos han sido actualizados. ¡Muchas gracias!", 2));
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

    public function cambiarContrasenaForm() {
        return View::make("usuarios/perfil/cambiar-contrasena");
    }

    public function cambiarContrasenaPost() {

        $data = Input::all();

        if (strlen($data["contra-nueva"]) <= 5)
            return Redirect::to('cambiar-contrasena')->with(User::mensaje("error", null, "La contraseña nueva debe contener como minimo 6 caracteres", 2));

        if ($data["contra-nueva"] != $data["contra-nueva-rep"])
            return Redirect::to('cambiar-contrasena')->with(User::mensaje("error", null, "La contraseña nueva no coinciden en la repetición", 2));

        if (User::cambiarContrasena($data["contra-actual"], $data["contra-nueva"]))
            return Redirect::Route('usuario.index')->with(User::mensaje("exito", null, "¡Bien hecho! Tu contraseña ha sido cambiada con exito ", 2));
        else
            return Redirect::to('cambiar-contrasena')->with(User::mensaje("error", null, "La contraseña actual ingresada es erronea", 2));
    }
    
    
    public function cambiarIdioma(){
        User::actualizarMetadato(OpcionesUsuario::OP_IDIOMA,Input::get("idioma"));
    }
    

}
