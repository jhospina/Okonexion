<?php

class OpcionesUsuario{  
    //LISTADO DE OPCIONES DE USUARIO
    const OP_IDIOMA = "op_idioma";
    const SUSCRIPCION_TIPO="suscripcion_tipo";
    
    /** Obtiene un array de con el nombre de las opciones del usuario
     * 
     * @return type
     */
    static function obtenerNombreOpciones(){
         $class= new ReflectionClass("OpcionesUsuario");
         return  $class->getConstants();
    }
   
    //Obtiene un array con el valor de las opciones predeterminada
    static function obtenerDefinicionOpcionesPredeterminada(){
        $opciones[OpcionesUsuario::OP_IDIOMA]="es"; // Idioma español
        return $opciones;
    }
    
}