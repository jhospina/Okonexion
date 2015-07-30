<?php

class ArchivosCTR {

    /** Borrar todos los archivos y carpetas dentro de un directorio
     * 
     * @param type $dir
     * @return type
     */
    static function borrarArchivos($dir) {
        if (!$dh = @opendir($dir))
            return;
        while (false !== ($current = readdir($dh))) {
            if ($current != '.' && $current != '..') {
                if (!@unlink($dir . '/' . $current))
                    ArchivosCTR::borrarArchivos($dir . '/' . $current);
            }
        }
        closedir($dh);
        @rmdir($dir);
    }

    static function obtenerListadoDir($path) {
        $dirs = array();

        if (strpos($path, "/../") !== false || strpos($path, "/./") !== false) {
            return null;
        }

        if (!$directorio = opendir($path))
            return null;

        while ($archivo = readdir($directorio)) { //obtenemos un archivo y luego otro sucesivamente   
            if (is_dir($dir = $path . $archivo . "/")) {//verificamos si es o no un directorio 
                if (strpos($dir, "/../") !== false || strpos($dir, "/./") !== false) {
                    continue;
                }
                $dirs[] = $dir;

                $dirs_dir = ArchivosCTR::obtenerListadoDir($dir);
                for ($i = 0; $i < count($dirs_dir); $i++) {
                    if (strpos($dirs_dir[$i], "/../") !== false || strpos($dirs_dir[$i], "/./") !== false) {
                        continue;
                    }
                    $dirs[] = $dirs_dir[$i];
                }
            }
        }

        closedir($directorio);

        return $dirs;
    }

    /** Obtiene un array con todos los archivos de un directorio, entrega todos los archivos asi sea que esten dentro de otras carpetas de la ruta indicada
     * 
     * @param type $path Ruta PATH del directorio a listar
     * @return string
     */
    static function obtenerListadoArchivos($path) {
        $archivos = array();
        if (strpos($path, "/../") !== false || strpos($path, "/./") !== false)
            return null;

        if (!$directorio = opendir($path))
            return null;

        while ($archivo = readdir($directorio)) { //obtenemos un archivo y luego otro sucesivamente   
            if (is_dir($dir = $path . $archivo)) {//verificamos si es o no un directorio 
                $archivos_dir = ArchivosCTR::obtenerListadoArchivos($dir . "/");
                for ($i = 0; $i < count($archivos_dir); $i++) {
                    $archivos[] = $archivos_dir[$i];
                }
            } else {
                $archivos[] = $path . $archivo;
            }
        }

        closedir($directorio);

        return $archivos;
    }

    /** Busca y reemplaza un texto dentro de un archivo y realiza una nueva copia con el nuevo contenido
     * 
     * @param type $buscar El texto a buscar
     * @param type $reemplazar El texto a reemplazar
     * @param type $path_archivo La ruta del archivo a analizar
     * @param type $path_dest La ruta del destino de la copia del archivo
     * @param type $copiar_no [false] Indica si se realiza una copia del archivo a un asi si la palabra no fue encontrada
     * @return boolean
     */
    static function buscarReemplazarTexto($buscar, $reemplazar, $path_archivo, $path_dest, $copiar_no = false) {

        $contenido = file_get_contents($path_archivo);

        if (is_array($buscar)) {
            $val = false;
            foreach ($buscar as $indice => $valor) {
                if (strpos($contenido, $valor) !== false) {
                    $val = true;
                }
            }
            if (!$val) {
                if ($copiar_no)
                    copy($path_archivo, $path_dest);
                return false;
            }
        } else {

            //Si no encuentra el texto
            if (!strpos($contenido, $buscar) !== false) {
                if ($copiar_no)
                    copy($path_archivo, $path_dest);
                return false;
            }
        }

        $contenido = str_replace($buscar, $reemplazar, $contenido);
        $fp = fopen($path_dest, "c");

        fwrite($fp, $contenido);
        fclose($fp);

        return true;
    }

    /** Obtiene el peso en Kb de un directorio
     * 
     * @param type $directory
     * @return type
     */
    static function obtenerTamanoDirectorio($directory) {
        $size = 0;
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file) {
            $size+=$file->getSize();
        }
        return $size;
    }

}
