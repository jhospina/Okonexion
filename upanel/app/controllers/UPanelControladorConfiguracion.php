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

        $logo = ConfigInstancia::visual_logo;


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
        $configLogo = Instancia::obtenerMetadato(ConfigInstancia::visual_logo);

        if (!is_null($configLogo)) {
            //Elimina la imagen anterior si existe
            if (File::exists(str_replace(URL::to("") . "/", "", $configLogo->valor))) {
                File::delete(str_replace(URL::to("") . "/", "", $configLogo->valor));
            }
        }

        if (!Instancia::actualizarMetadato(ConfigInstancia::visual_logo, URL::to($path . $archivo))) {
            $output = ['error' => trans("otros.error_solicitud")];
            return json_encode($output);
        }

        return json_encode($output);
    }

    function ajax_eliminarLogo() {
        $output = [];

        if (!Request::ajax())
            return json_encode($output);

        if (!Instancia::eliminarMetadato(ConfigInstancia::visual_logo)) {
            $output = ['error' => trans("otros.error_solicitud")];
            return json_encode($output);
        }

        return json_encode($output);
    }

}
