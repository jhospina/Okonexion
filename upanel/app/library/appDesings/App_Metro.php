<?php

class App_Metro {

    const sigla = "ME"; // La sigla que representa el diseño en la base de datos

    public static $tipos_contenidos = array(Contenido_Institucional::nombre, Contenido_Noticias::nombre, Contenido_Encuestas::nombre, Contenido_PQR::nombre); // Tipos de contenidos
    public static $configDefecto = array("iconoMenu1" => "Predeterminado", "iconoMenu2" => "Predeterminado", "iconoMenu3" => "Predeterminado", "iconoMenu4" => "Predeterminado");
    //Ids de configuracion que no se deben guardar cuando se establesca el diseño. Su guardado es especial y se realiza por ajax. 
    public static $configExcepciones=array("iconoMenu1","iconoMenu2","iconoMenu3","iconoMenu4","logoApp");
    
    //********************************************************
    //DATOS DE CONFIGURACION DEL DISEÑO***********************
    //********************************************************

    const colorBarraApp = "colorBarraApp"; //(c#) Indica el color de la barra de la aplicaciòn
    const colorNombreApp = "colorNombreApp"; //[rgb(0,0,0)] Indica el color del nombre del a aplicacion
    const alineacionNombre = "alineacionNombre"; //Indica  la alineación del nombre de la aplicación en la barra de la aplicación
    const mostrarNombre = "mostrarNombre"; //Indica como mostrar el nombre, si solo es texto o con el logo de la aplicacion
    const txt_menuBtn_1 = "txt_menuBtn_1"; //Título de la opción #1 del botón del menú principal de la aplicación.
    const txt_menuBtn_2 = "txt_menuBtn_2"; //Título de la opción #2 del botón del menú principal de la aplicación.
    const txt_menuBtn_3 = "txt_menuBtn_3"; //Título de la opción #3 del botón del menú principal de la aplicación.
    const txt_menuBtn_4 = "txt_menuBtn_4"; //Título de la opción #4 del botón del menú principal de la aplicación.
    const colorFondoMenuBt_1 = "colorFondoMenuBt_1"; // (c#) Indica el color del fondo del botón #1 del menú principal
    const colorFondoMenuBt_2 = "colorFondoMenuBt_2"; // (c#) Indica el color del fondo del botón #2 del menú principal
    const colorFondoMenuBt_3 = "colorFondoMenuBt_3"; // (c#) Indica el color del fondo del botón #3 del menú principal
    const colorFondoMenuBt_4 = "colorFondoMenuBt_4"; // (c#) Indica el color del fondo del botón #4 del menú principal
    const txt_menuBtn_1_color = "txt_menuBtn_1_color"; // [rgb(0,0,0)] Indica el color del texto del botón de la opción #1 del menú principal.
    const txt_menuBtn_2_color = "txt_menuBtn_2_color"; // [rgb(0,0,0)] Indica el color del texto del botón de la opción #2 del menú principal.
    const txt_menuBtn_3_color = "txt_menuBtn_3_color"; // [rgb(0,0,0)] Indica el color del texto del botón de la opción #3 del menú principal.
    const txt_menuBtn_4_color = "txt_menuBtn_4_color"; // [rgb(0,0,0)] Indica el color del texto del botón de la opción #4 del menú principal.
    const iconoMenu1 = "iconoMenu1"; //Almacena la URL de la imagen a utilizar como icono de la opción #1 del botón del menú principal de la aplicación
    const iconoMenu2 = "iconoMenu2"; //Almacena la URL de la imagen a utilizar como icono de la opción #2 del botón del menú principal de la aplicación
    const iconoMenu3 = "iconoMenu3"; //Almacena la URL de la imagen a utilizar como icono de la opción #3 del botón del menú principal de la aplicación
    const iconoMenu4 = "iconoMenu4"; //Almacena la URL de la imagen a utilizar como icono de la opción #4 del botón del menú principal de la aplicación

    /**
     * Obtiene el nombre dado por el usuario al tipo de contenido, desde la config del diseño
     * 
     * @param string $tipoContenido El nombre del tipo de contenido
     * @return string El nombre del tipo de contenido dado por el usuario
     * */

    public static function obtenerNombreTipoContenidoPorUsuario($tipoContenido) {
        switch ($tipoContenido) {
            case Contenido_Institucional::nombre:
                return ConfiguracionApp::obtenerValorConfig((string) App_Metro::txt_menuBtn_1);
                break;
            case Contenido_Noticias::nombre:
                return ConfiguracionApp::obtenerValorConfig((string) App_Metro::txt_menuBtn_2);
                break;
            case Contenido_Encuestas::nombre:
                return ConfiguracionApp::obtenerValorConfig((string) App_Metro::txt_menuBtn_3);
                break;
            case Contenido_PQR::nombre:
                return ConfiguracionApp::obtenerValorConfig((string) App_Metro::txt_menuBtn_4);
                break;
        }
    }

    /** Guarda los datos de configuracion del diseño establecidos en la base de datos
     * 
     * @param array $data Los datos enviados por el usuario mediante formulario
     * @param Aplicacion $app Objeto de la aplicacion del usuario 
     * @return boolean|array Retorna true en caso de exito, de lo contraro un array con un mensaje de error
     */
    public static function guardarConfigDiseno($data, $app) {

        $errores = [];

        if (is_null($app->url_logo))
            return $errores[0] = "Sube una imagen que sea tu logo de aplicación. Es importante el logo porque representa tu aplicación.";

        foreach ($data as $clave => $valor) {
            if (!in_array($clave, App_Metro::$configExcepciones)) {
                $config = new ConfiguracionApp;

                ConfiguracionApp::eliminarConfigDesdeClave($clave);

                $config->id_aplicacion = $app->id;
                $config->clave = $clave;
                
                if(Util::esColorRGB($valor))
                    $valor=Util::rgb2hex ($valor);
                
                $config->valor = $valor;
                $config->save();
            }
        }

        $app->estado = Aplicacion::ESTADO_LISTA_PARA_ENVIAR; //Listo para enviar
        @$app->save();

        return true;
    }

    /** Prepara los requisitos de administracion de los tipos de contenido
     * 
     * @param Aplicacion $app El objeto del controlador de aplicacion del usuario 
     */
    public static function prepararRequisitosDeAdministracion($app) {
        Contenido_Noticias::prepararRequisitos($app);
    }

    /** Obtiene los datos JSON de los tipos de contenidos asociados al diseño Metro.
     * 
     * @param Int $id_app Id de la aplicacion
     * @return JSON
     */
    public static function cargarDatosJson($id_app) {
        $data = [];
        $data[Contenido_Institucional::nombre] = Contenido_Institucional::cargarDatosJson($id_app);
        $data[Contenido_Noticias::nombre] = Contenido_Noticias::cargarDatosJson($id_app);
        return json_encode($data);
    }

}
