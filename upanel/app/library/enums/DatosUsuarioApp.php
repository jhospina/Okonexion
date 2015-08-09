<?php

class DatosUsuarioApp {

    const EDAD = "edad";
    const GENERO = "genero";
    const EMAIL = "email";
    const AFICIONES_CT = "aficiones";

    /** Retorna un histograma en forma de array(Frecuencias, Medidas) 
     * 
     * @param type $id_app El id de la app
     * @param type $tipoDato El tipo de dato de usuario
     * @param type $subfijo Texto posterior en la medida
     * @return type
     */
    static function histograma($id_app, $tipoDato,$subfijo=null) {
        $registros = AppMeta::where("id_app", $id_app)->where("clave", "LIKE", "%_" . $tipoDato)->get();

        $frecuencias = array(); // Almacena la frecuencia de cada medida
        $medidas = array(); //Almacenas las medidas cuantitativas

        foreach ($registros as $reg) {
            $medida=$reg->valor." ".$subfijo;
            
            if (!in_array($medida, $medidas))
                $medidas[] = $medida;

            if (!isset($frecuencias[array_search($medida, $medidas)]))
                $frecuencias[array_search($medida, $medidas)] = 0;

            $frecuencias[array_search($medida, $medidas)]+=1;
        } 
        
       
        return array($medidas,$frecuencias);   
    }

}
