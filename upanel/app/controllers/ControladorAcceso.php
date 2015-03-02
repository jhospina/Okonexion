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
                return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/ingresar/?response=fail-confirmation&user=" . $id_user . "&email=" . Input::get("email"));
            } else
            // Da acceso al Upanel del usuario
                return Redirect::to('/');
        } else
        // En caso de que la autenticación haya fallado manda un mensaje al formulario de login y también regresamos los valores enviados con withInput().
            return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/ingresar/?response=fail&email=" . Input::get("email"));
    }

    function cerrarSesion() {
        Auth::logout();
        return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/ingresar/?response=logout");
    }

    //Activa la cuenta del usuario al confirmar su correo electronico
    function activarCuenta($id, $codigo) {
        $user = User::find($id);

        if (is_null($user)) {
            App::abort(404);
        }

        if ($codigo == $user->cod_ver_email) {
            if ($user->email_confirmado == 0) {

                $user->email_confirmado = 1;
                $user->update();
                $correo = new Correo();
                $correo->enviarBienvenida($id);
                return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/ingresar/?response=confirmation&email=" . $user->email);
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
        $resultado = DB::select('select id,email,email_confirmado from usuarios where email = ?', array($email));
        if (count($resultado) > 0) {
            foreach ($resultado as $usuario) {
                if ($usuario->email_confirmado == 0) {
                    return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/recuperar-contrasena/?response=inactive&user=".$usuario->id."&email=" . $email);
                }
                //Envia un correo con un enlace para la recuperación del a contraseña del usuario
                $correo = new Correo();
                $correo->enviarRecuperacion($usuario->id);
            }
            return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/recuperar-contrasena/?response=send&email=" . $usuario->email);
        } else {
            return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/recuperar-contrasena/?response=fail&email=" . $email);
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
                return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/recuperar-contrasena/?response=recovery&user=" . $id . "&code=" . $codigo);
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

        if (!is_null($user)) {
            if ($codigo == $user->cod_ver_email) {
                $user->password = $contra;
                $user->update();
                return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/ingresar/?response=recovery&email=" . $user->email);
            } else {
                return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/ingresar/?response=fail-password");
            }
        } else {
            return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/ingresar/?response=fail-password");
        }
    }

    function enviarActivacion($id_usuario) {

        $user = User::find($id_usuario);

        $correo = new Correo;
        //Envia un mensaje de confirmación con un codigo al correo electronico, para validar la cuenta de usuario
        $correo->enviarActivacion($id_usuario);

        return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/ingresar/?response=send-confirmation&email=" . $user->email);
    }

}
