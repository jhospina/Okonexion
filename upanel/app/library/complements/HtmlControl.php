<?php

class HtmlControl {

    /** Retorna un atributo html para indicar un chequeo (Checkbox) desde un valor booleano php
     * 
     * @param type $bool El valor booleano en PHP
     * @return type
     */
    public static function setCheck($bool) {
        return ($bool) ? "checked" : "";
    }

    public static function crearEnlacesDesdeTexto($texto) {
        //Expresión regular que usaremos como filtro
        $reg_ex = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

        //Comprobamos si hay alguna url en el texto
        if (preg_match($reg_ex, $texto, $url)) {
            //Convertimos en formato url  
            $resultado_texto = preg_replace($reg_ex, '<a target="blank" href="'.$url[0].'">'.$url[0].'</a> ', $texto);
        } else {
            //Dejamos igual el texto al no haber ninguna url  
            $resultado_texto = $texto;
        }
        return $resultado_texto;
    }

}
