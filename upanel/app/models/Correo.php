<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Correo extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait,
        RemindableTrait;

    //Envia un mensaje a un correo electronico con un codigo de confirmación para la activación de una cuenta de usuario
    function enviarConfirmacion($data, $id_usuario) {

        //Genera un codigo aleatorio que servira como codigo de confirmación para activar la cuenta del usuario. 
        $codigo = $this->generarCodigo();

        $this->almacenarCodigo($codigo, $id_usuario);


        $data["codigo"] = $codigo;
        $data["id_usuario"] = $id_usuario;

        return Mail::send('emails.confirmacion', $data, function ($message) use ($data) {
                    $message->subject('Confirmación de correo electrónico');
                    $message->to($data["email"]);
                });
    }

    function enviarActivacion($id_usuario) {

        //Genera un codigo aleatorio que servira como codigo de confirmación para activar la cuenta del usuario. 
        $codigo = $this->generarCodigo();

        $this->almacenarCodigo($codigo, $id_usuario);

        $user = User::find($id_usuario);

        $data["nombre"] = $user->nombres;
        $data["codigo"] = $codigo;
        $data["id_usuario"] = $id_usuario;
        $data["email"] = $user->email;

        return Mail::send('emails.activacion', $data, function ($message) use ($data) {
                    $message->subject('Activación de tu cuenta');
                    $message->to($data["email"]);
                });
    }

    //Envia un correo electronico a un usuario nuevo dandole bienvenido a okonexion
    function enviarBienvenida($id_usuario) {
        $user = User::find($id_usuario);

        $data = array("email" => $user->email, "nombre" => $user->nombres);

        return Mail::send('emails.bienvenida', $data, function ($message) use ($data) {
                    $message->subject('¡Bienvenido a Okonexion!');
                    $message->to($data["email"]);
                });
    }

    function enviarRecuperacion($id_usuario) {
        $user = User::find($id_usuario);

        $codigo = $this->generarCodigo();
        $this->almacenarCodigo($codigo, $id_usuario);
        $data = array("email" => $user->email, "nombre" => $user->nombres, "codigo" => $codigo, "id_usuario" => $id_usuario);

        return Mail::send('emails.recuperacion_contrasena', $data, function ($message) use ($data) {
                    $message->subject('Recuperación de contraseña');
                    $message->to($data["email"]);
                });
    }

    //Genera un codigo aleatorio
    function generarCodigo() {
        $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //posibles caracteres a usar
        $numerodeletras = 20; //numero de letras para generar el texto
        $codigo = ""; //variable para almacenar la cadena generada
        for ($i = 0; $i < $numerodeletras; $i++) {
            $codigo .= substr($caracteres, rand(0, strlen($caracteres)), 1); /* Extraemos 1 caracter de los caracteres 
              entre el rango 0 a Numero de letras que tiene la cadena */
        }
        return $codigo;
    }

    //Registra el codigo de confirmación de correo del usuario
    function almacenarCodigo($codigo, $id_usuario) {
        $user = User::find($id_usuario);
        $user->cod_ver_email = $codigo;
        $user->update();
    }

    function enviar($asunto, $mensaje, $id_usuario) {

        $user = User::find($id_usuario);

        $data["asunto"] = $asunto;
        $data["email"] = $user->email;

        return Mail::queue('emails.plantilla', array("mensaje"=>$mensaje), function ($message) use ($data) {
                    $message->subject($data["asunto"]);
                    $message->to($data["email"]);
                });
    }

}
