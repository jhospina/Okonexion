<?php

class UPanelControladorInstancias extends Controller {

    public function index() {
        if (!Auth::check())
            return User::login();

        //Acceso solo al super admin
        if (!User::esSuperAdmin())
            return User::login();

        $instancias = Instancia::orderBy("id", "desc")->paginate(30);

        return View::make("usuarios/tipo/superadmin/instancias/index")->with("instancias", $instancias);
    }

    public function vista_crear() {
        if (!Auth::check())
            return User::login();

        //Acceso solo al super admin
        if (!User::esSuperAdmin())
            return User::login();

        return View::make("usuarios/tipo/superadmin/instancias/crear");
    }

    public function post_crear() {
        $data = Input::all();

        $instancia = new Instancia();

        if (strlen($errores = $instancia->validar($data)) > 0)
            return Redirect::to('instancias/crear')->withInput()->with(User::mensaje("error", "", $errores, 2));

        $data_cuenta["nombres"] = $data["nombres"];
        $data_cuenta["apellidos"] = $data["apellidos"];
        $data_cuenta["email"] = $data["email_cuenta"];
        $data_cuenta["password"] = $data["password"];
        $data_cuenta["password2"] = $data["password2"];
        $data_cuenta["tipo"] = User::USUARIO_ADMIN;
        $data_cuenta["email_confirmado"] = 1;


        $user = new User;

        if (strlen($errores = $user->validar($data)) > 0)
            return Redirect::to('instancias/crear')->withInput()->with(User::mensaje("error", "", $errores, 2));

        if ($user->registrar($data_cuenta)) {
            $instancia->registrar($data, $user->id);

            $user->instancia = $instancia->id;
            $user->save();

            return Redirect::to('instancias/')->with(User::mensaje("exito", "", trans("instancias.post.exito.crear"), 2));
        } else {
            return Redirect::to('instancias/crear')->withInput()->with(User::mensaje("error", "", trans("instancias.info.error.email.existe"), 2));
        }
    }

    public function vista_ver($id) {

        if (!Auth::check())
            return User::login();

        //Acceso solo al super admin
        if (!User::esSuperAdmin())
            return User::login();

        $instancia = Instancia::find($id);

        if (is_null($instancia))
            return Redirect::to("instancias");

        $admin = User::find($instancia->id_administrador);

        return View::make("usuarios/tipo/superadmin/instancias/ver")->with("instancia", $instancia)->with("admin", $admin);
    }

    public function vista_editar($id) {
        if (!Auth::check())
            return User::login();

        //Acceso solo al super admin
        if (!User::esSuperAdmin())
            return User::login();

        $instancia = Instancia::find($id);

        if (is_null($instancia))
            return Redirect::to("instancias");

        $admin = User::find($instancia->id_administrador);


        return View::make("usuarios/tipo/superadmin/instancias/editar")->with("instancia", $instancia)->with("admin", $admin);
    }

    public function post_editar($id) {

        if (!Auth::check())
            return User::login();

        //Acceso solo al super admin
        if (!User::esSuperAdmin())
            return User::login();

        $instancia = Instancia::find($id);

        if (is_null($instancia))
            return Redirect::to("instancias");

        $data = Input::all();
        if (strlen($errores = $instancia->validar($data)) > 0)
            return Redirect::to('instancias/'.$id.'/editar')->withInput()->with(User::mensaje("error", "", $errores, 2));

        if ($instancia->registrar($data))
            return Redirect::to('instancias/')->with(User::mensaje("exito", "", trans("instancias.post.exito.editar"), 2));
        else
            return Redirect::to('instancias/'.$id.'/editar')->with(User::mensaje("info", "", trans("otros.error_solicitud"), 2));
    }

}
