<?php

class HtmlControl{
    
   
    /** Retorna un atributo html para indicar un chequeo (Checkbox) desde un valor booleano php
     * 
     * @param type $bool El valor booleano en PHP
     * @return type
     */
    public static function setCheck($bool){
        return ($bool)?"checked":"";
    }
}

