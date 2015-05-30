<?php

class HasherPro {

    /** Genera un codigo y lo retorna
     * 
     * @return type
     */
    static function generarCodigo() {
        $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //posibles caracteres a usar
        $numerodeletras = 50; //numero de letras para generar el texto
        $codigo = ""; //variable para almacenar la cadena generada
        for ($i = 0; $i < $numerodeletras; $i++) {
            $codigo .= substr($caracteres, rand(0, strlen($caracteres)), 1); /* Extraemos 1 caracter de los caracteres 
              entre el rango 0 a Numero de letras que tiene la cadena */
        }
        return $codigo;
    }

    /** Crear un hash codigo y lo almacena con una llave indicada y lo retorna
     * 
     * @param type $llave La llave para almacenar la clave
     * @return type
     */
    static function crear($llave) {
        $hash = HasherPro::generarCodigo();
        return (User::agregarMetaDato($llave, $hash)) ? $hash : null;
    }

    /** Verifica si un hash es igual al que se ha almacenado indicado por su llave, si es verdadero efectua y desactiva el uso del hash
     * 
     * @param type $hash El codigo Hash a comparar
     * @param type $llave La llave con la que fue almacenada el hash
     * @return type
     */
    static function verificar($hash, $llave) {
        $hash_almacenado = User::obtenerValorMetadato($llave);

        if ($hash == $hash_almacenado) {
            User::eliminarMetadato($llave);
            return true;
        } else
            return false;
    }

}
