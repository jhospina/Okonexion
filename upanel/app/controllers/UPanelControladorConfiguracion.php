<?php

class UPanelControladorConfiguracion extends \BaseController {

    function vista_general() {
        if (!Auth::check()) {
            return User::login();
        }
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_ADMIN)))
            return $acceso;

        return View::make("usuarios/tipo/admin/config/general");
    }

    function vista_servicios() {
        if (!Auth::check()) {
            return User::login();
        }
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_ADMIN)))
            return $acceso;

        $servicios = Servicio::orderBy("id", "ASC")->paginate(10);

        return View::make("usuarios/tipo/admin/config/servicios")->with("servicios", $servicios);
    }

    function vista_suscripcion() {
        if (!Auth::check()) {
            return User::login();
        }
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_ADMIN)))
            return $acceso;

        $monedas = Monedas::listado();

        if (!isset($_GET["coin"]))
            return Redirect::to("config/suscripcion?coin=" .  key($monedas));

        return View::make("usuarios/tipo/admin/config/suscripcion")->with("monedas", $monedas);
    }

    function vista_facturacion() {
        if (!Auth::check()) {
            return User::login();
        }
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_ADMIN)))
            return $acceso;

        return View::make("usuarios/tipo/admin/config/facturacion");
    }

    function post_guardar() {
        $data = Input::all();

        if (!ConfigInstancia::validar($data)) {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, trans("config.general.seccion.error.validacion"), 2));
        }
        ConfigInstancia::registrar($data);

        return Redirect::back()->withInput()->with(User::mensaje("exito", null, trans("config.general.post.exito"), 2));
    }

    function ajax_subirLogo() {

        $output = [];

        if (!Request::ajax())
            return json_encode($output);

        $logo = Input::get("idConfigLogo");


        $extension = strtolower(Input::file($logo)->getClientOriginalExtension());
        $size = Input::file($logo)->getSize();

        $path = 'usuarios/uploads/' . Auth::user()->id . "/";
        $archivo = date("YmdGis") . "." . $extension;

        list($width, $height, $type, $attr) = getimagesize(Input::file($logo));


        //Verifica si las dimensiones de la imagen son correctas
        if (intval($width) != 300 && intval($height) != 50) {
            $output = ['error' => trans("config.general.seccion.logotipo.op.error.1", array("w" => $width, "h" => $height))];
            return json_encode($output);
        }


        Input::file($logo)->move($path, $archivo);
        $configLogo = Instancia::obtenerMetadato($logo);

        if (!is_null($configLogo)) {
            //Elimina la imagen anterior si existe
            if (File::exists(str_replace(URL::to("") . "/", "", $configLogo->valor))) {
                File::delete(str_replace(URL::to("") . "/", "", $configLogo->valor));
            }
        }

        if (!Instancia::actualizarMetadato($logo, URL::to($path . $archivo))) {
            $output = ['error' => trans("otros.error_solicitud")];
            return json_encode($output);
        }

        return json_encode($output);
    }

    function ajax_eliminarLogo() {
        $output = [];

        if (!Request::ajax())
            return json_encode($output);

        $logo = Input::get("idConfigLogo");

        if (!Instancia::existeMetadato($logo))
            return json_encode($output);

        if (!Instancia::eliminarMetadato($logo)) {
            $output = ['error' => trans("otros.error_solicitud")];
            return json_encode($output);
        }

        return json_encode($output);
    }

}
