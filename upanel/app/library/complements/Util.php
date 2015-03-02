<?php

class Util {

    //Elimina la plularidad de una palabra
    static function eliminarPluralidad($palabra) {

        if ($palabra[strlen($palabra) - 2] == "e" && $palabra[strlen($palabra) - 1] == "s")
            return substr($palabra, 0, strlen($palabra) - 2);

        if ($palabra[strlen($palabra) - 1] == "s")
            return substr($palabra, 0, strlen($palabra) - 1);
    }

}
?>

