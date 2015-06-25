<?php

// Set default language.
// It should matchs with $localesAllowed array.

define('DEFAULT_LANG', Idioma::LANG_ES);

// Set default language if lang session do not exits. 

if (!Session::has('locale'))
    Session::put('locale', DEFAULT_LANG);

//Optiene el idioma configurado para el usuario
$idioma = User::obtenerValorMetadato(UsuarioMetadato::OP_IDIOMA);

if (!is_null($idioma)) {
    Session::put('locale', $idioma);
} else {
    $idioma = (isset($_GET["lang_get"])) ? $_GET["lang_get"] : null;
    if (!is_null($idioma)) {
        Session::put('locale', strtolower($idioma));
    } else {
        Session::put('locale', DEFAULT_LANG);
    }
}


//Indica globalmente el idioma del usuario
define("OP_IDIOMA", Session::get('locale'));

// Overwrite locale in /app/config/app.php file.
// Make translation

App::setLocale(Session::get('locale'));
