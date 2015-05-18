<?php

class ControladorAcceso extends Controller {

    //Valida el acceso de login de un usuario
    function iniciarSesion() {

        $userdata = array(
            'email' => Input::get('email'),
            'password' => Input::get('contrasena')
        );

// Validamos los datos y además mandamos como un segundo parámetro la opción de recordar el usuario.
        if (Auth::attempt($userdata, Input::get('recordarme', 0))) {

            //El usuario debe haber confirmado el correo y activado la cuenta antes de poder ingresar
            if (Auth::user()->email_confirmado == 0) {
                $id_user = Auth::user()->id;
                Auth::logout();
                return Redirect::to(Util::filtrarUrl(Input::get('url')) . "?response=fail-confirmation&user=" . $id_user . "&email=" . Input::get("email"));
            } else {
                //Inserta una cookie al usuario para mostrar un mensaje inicial de periodo de prueba
                if (Auth::user()->tipo == User::USUARIO_REGULAR && Auth::user()->estado == User::ESTADO_PERIODO_PRUEBA) {
                    $cookie = Cookie::make(IDCookies::MSJ_INICIAL_PERIODO_PRUEBA, true, IDCookies::duracion(IDCookies::MSJ_INICIAL_PERIODO_PRUEBA));
                    return Redirect::to('/')->withCookie($cookie);
                }

                // Da acceso al Upanel del usuario
                return Redirect::to('/');
            }
        } else
        // En caso de que la autenticación haya fallado manda un mensaje al formulario de login y también regresamos los valores enviados con withInput().
            return Redirect::to(Util::filtrarUrl(Input::get('url')) . "?response=fail&email=" . Input::get("email"));
    }

    function cerrarSesion() {
        $url_origen = User::obtenerValorMetadato(User::META_URL_ORIGEN);
        Auth::logout();
        return Redirect::to(Util::obtenerDominioDeUrl($url_origen) . User::CONFIG_URL_LOGIN . "?response=logout");
    }

    //Activa la cuenta del usuario al confirmar su correo electronico
    function activarCuenta($id, $codigo) {
        $user = User::find($id);

        if (is_null($user)) {
            App::abort(404);
        }

        if ($codigo == $user->cod_ver_email) {
            if ($user->email_confirmado == Util::convertirBooleanToInt(false)) {

                $user->email_confirmado = Util::convertirBooleanToInt(true);
                $user->update();
                $correo = new Correo();
                $correo->enviarBienvenida($id);
                $url_origen = User::obtenerValorMetadato(User::META_URL_ORIGEN, $user->id);
                return Redirect::to(Util::obtenerDominioDeUrl($url_origen) . User::CONFIG_URL_LOGIN . "?response=confirmation&email=" . $user->email);
            } else {
                App::abort(404);
            }
        } else {
            App::abort(404);
        }
    }

    //Recibe una solicitud para reestablecer contraseña
    function recuperarContrasena() {
        $email = Input::get('email');
        $url_origen = Input::get('url');
        $resultado = DB::select('select id,email,email_confirmado from usuarios where email = ?', array($email));
        if (count($resultado) > 0) {
            foreach ($resultado as $usuario) {

                User::agregarMetaDato(User::META_URL_RECOVERY, Util::filtrarUrl($url_origen), $usuario->id);

                if ($usuario->email_confirmado == 0) {
                    return Redirect::to(Util::filtrarUrl($url_origen) . "?response=inactive&user=" . $usuario->id . "&email=" . $email);
                }
                //Envia un correo con un enlace para la recuperación del a contraseña del usuario
                $correo = new Correo();
                $correo->enviarRecuperacion($usuario->id);
            }
            return Redirect::to(Util::filtrarUrl($url_origen) . "?response=send&email=" . $usuario->email);
        } else {
            return Redirect::to(Util::filtrarUrl($url_origen) . "?response=fail&email=" . $email);
        }
    }

    //Verifica la solicitud de recuperación de contraseña y si es valida lo dirije al formulario
    function recoveryForm($id, $codigo) {
        $user = User::find($id);
        if (is_null($user)) {
            App::abort(404);
        }
        if ($codigo == $user->cod_ver_email) {
            if ($user->email_confirmado == 1) {
                $url_recovery = User::obtenerValorMetadato(User::META_URL_RECOVERY, $user->id);
                return Redirect::to($url_recovery . "?response=recovery&user=" . $id . "&code=" . $codigo);
            } else {
                App::abort(404);
            }
        } else {
            App::abort(404);
        }
    }

    function recovery() {
        $id_usuario = Input::get('user');
        $codigo = Input::get('code');
        $contra = Input::get('contra');

        $user = User::find($id_usuario);

        $url_origen = User::obtenerValorMetadato(User::META_URL_ORIGEN, $user->id);
        if (!is_null($user)) {
            if ($codigo == $user->cod_ver_email) {
                $user->password = $contra;
                $user->update();
                return Redirect::to(Util::obtenerDominioDeUrl($url_origen) . User::CONFIG_URL_LOGIN . "?response=recovery&email=" . $user->email);
            } else {
                return Redirect::to(Util::obtenerDominioDeUrl($url_origen) . User::CONFIG_URL_LOGIN . "?response=fail-password");
            }
        } else {
            return Redirect::to(Util::obtenerDominioDeUrl($url_origen) . User::CONFIG_URL_LOGIN . "?response=fail-password");
        }
    }

    function enviarActivacion($id_usuario) {

        $user = User::find($id_usuario);

        $correo = new Correo;
        //Envia un mensaje de confirmación con un codigo al correo electronico, para validar la cuenta de usuario
        $correo->enviarActivacion($id_usuario);
        $url_origen = User::obtenerValorMetadato(User::META_URL_ORIGEN, $user->id);
        return Redirect::to(Util::obtenerDominioDeUrl($url_origen) . User::CONFIG_URL_LOGIN . "?response=send-confirmation&email=" . $user->email);
    }

}
