<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Correo extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait,
        RemindableTrait;

    const emailFrom = 'appsthergo@gmail.com';

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

    /** Enviar un correo electronico a un usuario
     * 
     * @param String $asunto El asunto del correo
     * @param String $mensaje El mensaje del correo
     * @param Integer $id_usuario El id del usuario a enviarle el correo
     * @param (String|Array) $adjuntos [null] El path del archivo a adjuntar, puede ser un array de paths
     * @param String $plantilla [plantilla1] El nombre de la plantilla a usar para el correo
     * @return boolean
     */
    function enviar($asunto, $mensaje, $id_usuario, $adjuntos = null, $plantilla = "plantilla1") {

        $user = User::find($id_usuario);

        $data["asunto"] = $asunto;
        $data["email"] = $user->email;

        return Mail::queue('emails.' . $plantilla, array("mensaje" => $mensaje), function ($message) use ($data, $adjuntos) {
                    $message->subject($data["asunto"]);
                    $message->to($data["email"]);

                    //Para adjuntar archivos
                    if (!is_null($adjuntos)) {

                        if (is_array($adjuntos)) {
                            for ($i = 0; count($adjuntos); $i++) {
                                $message->attach($adjuntos[$i]);
                            }
                        } else {
                            $message->attach($adjuntos);
                        }
                    }
                });
    }

    function enviarUsuarioApp($from, $email, $asunto, $mensaje) {

        $data["asunto"] = $asunto;
        $data["email"] = $email;
        $data["from"] = $from;

        return Mail::queue('emails.plantillaApp', array("mensaje" => $mensaje, "nombreApp" => $from), function ($message) use ($data) {
                    $message->from(Correo::emailFrom, $data["from"]);
                    $message->subject($data["asunto"]);
                    $message->to($data["email"]);
                });
    }

}
