<?php

class Idioma {

    const LANG_ES = "es";
    const LANG_EN = "en";
    const PATH_ICON = "assets/img/idiomas/";

    static function listado() {
        $class = new ReflectionClass("Idioma");
        $idiomas = array();
        foreach ($class->getConstants() as $index => $value) {
            if (strpos($index, "LANG_") !== false) {
                $idiomas[$index] = $value;
            }
        }
        return $idiomas;
    }

    static function actual() {
        return (is_null($idioma = User::obtenerValorMetadato(UsuarioMetadato::OP_IDIOMA))) ? OP_IDIOMA : $idioma;
    }

}
