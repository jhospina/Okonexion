<?php

namespace Appsthergo\API;

class Util {

    /** Get the Current URI
     * 
     * @return string The URL
     */
    public static function getUrlCurrent() {
        $url = Util::UrlCreate($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        return $url;
    }

    /** Create a URL
     * 
     * @param type $url
     * @return type
     */
    static function UrlCreate($url) {
        return($_SERVER['HTTPS']) ? "https://" . $url : "http://" . $url;
    }

    /** Filtra una url obteniendo unicamente la url, sin datos enviados por Get. 
     * 
     * @param type $url
     */
    static function UrlFiltrate($url) {
        if (strpos($url, "?") !== false) {
            $parts = explode("?", $url);
            return $parts[0];
        } else
            return $url;
    }

}
