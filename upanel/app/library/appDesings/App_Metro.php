<?php

class App_Metro {

    const sigla = "ME"; // La sigla que representa el diseño en la base de datos
    const package_default = "AppsthergoAppName";

    public static $tipos_contenidos = array(Contenido_Institucional::nombre, Contenido_Noticias::nombre, Contenido_Encuestas::nombre, Contenido_PQR::nombre); // Tipos de contenidos
    public static $configDefecto = array("iconoMenu1" => "Predeterminado", "iconoMenu2" => "Predeterminado", "iconoMenu3" => "Predeterminado", "iconoMenu4" => "Predeterminado");
    //Ids de configuracion que no se deben guardar cuando se establesca el diseño. Su guardado es especial y se realiza por ajax. 
    public static $configExcepciones = array("iconoMenu1", "iconoMenu2", "iconoMenu3", "iconoMenu4", "logoApp");

    const PATH_PROJECT_ANDROID = "assets/appscore/instytul/metro/android/";
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
    //********************************************************
    //CONFIGURACIÓN DE TEXTOS*********************************
    //********************************************************
    const txt_info_titulo_sin_conexion = "txt_info_titulo_sin_conexion";
    const txt_info_descripcion_sin_conexion = "txt_info_descripcion_sin_conexion";
    const txt_info_cargando = "txt_info_cargando";
    const txt_info_fecha_publicacion = "txt_info_fecha_publicacion";
    const txt_info_no_hay_contenido = "txt_info_no_hay_contenido";
    const txt_info_buscando_encuesta_vigente = "txt_info_buscando_encuesta_vigente";
    const txt_info_boton_enviar = "txt_info_boton_enviar";
    const txt_info_enviando = "txt_info_enviando";
    const txt_info_respuesta_enviada = "txt_info_respuesta_enviada";
    const txt_info_ver_resultados = "txt_info_ver_resultados";
    const txt_info_encabezado_encuesta_vigente = "txt_info_encabezado_encuesta_vigente";
    const txt_info_encabezado_responde_encuesta = "txt_info_encabezado_responde_encuesta";
    const txt_info_resultados = "txt_info_resultados";
    const txt_info_selecciona_respuesta = "txt_info_selecciona_respuesta";
    const txt_info_historial_encuestas = "txt_info_historial_encuestas";
    const txt_info_no_hay_contenido_vuelve_mas_tarde = "txt_info_no_hay_contenido_vuelve_mas_tarde";
    const txt_info_pqr_peticiones = "txt_info_pqr_peticiones";
    const txt_info_pqr_quejas = "txt_info_pqr_quejas";
    const txt_info_pqr_reclamos = "txt_info_pqr_reclamos";
    const txt_info_pqr_sugerencias = "txt_info_pqr_sugerencias";
    const txt_info_btn_crear_peticion = "txt_info_btn_crear_peticion";
    const txt_info_crear_queja = "txt_info_crear_queja";
    const txt_info_btn_crear_reclamo = "txt_info_btn_crear_reclamo";
    const txt_info_btn_crear_sugerencia = "txt_info_btn_crear_sugerencia";
    const txt_info_editText_nombre = "txt_info_editText_nombre";
    const txt_info_editText_email = "txt_info_editText_email";
    const txt_info_editText_asunto = "txt_info_editText_asunto";
    const txt_info_editText_descripcion_peticion = "txt_info_editText_descripcion_peticion";
    const txt_info_editText_descripcion_queja = "txt_info_editText_descripcion_queja";
    const txt_info_editText_descripcion_reclamo = "txt_info_editText_descripcion_reclamo";
    const txt_info_editText_descripcion_sugerencia = "txt_info_editText_descripcion_sugerencia";
    const txt_info_error = "txt_info_error";
    const txt_info_nombre_error = "txt_info_nombre_error";
    const txt_info_email_error = "txt_info_email_error";
    const txt_info_asunto_error = "txt_info_asunto_error";
    const txt_info_descripcion_error = "txt_info_descripcion_error";
    const txt_info_peticion_enviada = "txt_info_peticion_enviada";
    const txt_info_queja_enviada = "txt_info_queja_enviada";
    const txt_info_reclamo_enviada = "txt_info_reclamo_enviada";
    const txt_info_sugerencia_enviada = "txt_info_sugerencia_enviada";
    const txt_info_informacion_enviada = "txt_info_informacion_enviada";
    const txt_info_btn_aceptar = "txt_info_btn_aceptar";
    const txt_info_mis_peticiones = "txt_info_mis_peticiones";
    const txt_info_mis_quejas = "txt_info_mis_quejas";
    const txt_info_mis_reclamos = "txt_info_mis_reclamos";
    const txt_info_mis_sugerencias = "txt_info_mis_sugerencias";
    const txt_info_peticion = "txt_info_peticion";
    const txt_info_queja = "txt_info_queja";
    const txt_info_reclamo = "txt_info_reclamo";
    const txt_info_sugerencia = "txt_info_sugerencia";
    const txt_info_asunto = "txt_info_asunto";
    const txt_info_nombre = "txt_info_nombre";
    const txt_info_email = "txt_info_email";
    const txt_info_enviar_respuesta = "txt_info_enviar_respuesta";
    const txt_info_escribir_respuesta = "txt_info_escribir_respuesta";
    const txt_info_escribe_tu_respuesta = "txt_info_escribe_tu_respuesta";
    const txt_info_usuario_soporte = "txt_info_usuario_soporte";
    const txt_info_usuario = "txt_info_usuario";
    //********************************************************
    //HABILITACION DE PLATAFORMAS*****************************
    //********************************************************
    const PLAT_ANDROID = true;
    const PLAT_IOS_IPHONE = true;
    const PLAT_WINDOWS = true;

    /** Retorna un array de 3 elementos indicando la disponibidad del diseño en la plataformas (Android, IOS, Windows)
     * 
     * @return type
     */
    static function plataformas() {
        $class = new ReflectionClass("App_Metro");

        $plats = array();

        foreach ($class->getConstants() as $index => $indicador) {
            if (strpos($index, "PLAT_") !== false)
                $plats[] = $indicador;
        }

        return $plats;
    }

    /** Obtiene la descripción basica del diseño a
     *  
     * @return type
     */
    public static function descripcion() {
        return trans("app.di.me.descripcion");
    }

    /** Obtiene un string con la plantilla de codigo Java para la configuracion del diseño en android
     * 
     * @param type $config
     */
    public static function plantillaConfigAndroid($config) {
        $datos = json_decode($config, true);

        $java_config = "";

        foreach ($datos as $indice => $valor) {
            $java_config.="public static String $indice=\"$valor\";\n";
        }
        return "package libreria.sistema;\npublic class AppConfig {\n" . $java_config . "}";
    }

    /** Prepara los archivos para el diseño en Android
     * 
     * @param type $zip
     * @param type $config
     */
    public static function prepararArchivosParaAndroid($zip, $config, $ruta) {

        $exclusion = array("app.xml", "AppConfig.java");

        //Dimensiones de imagen para android
        $dim_web = 512;
        $dim_xxxhdpi = 192;
        $dim_xxhdpi = 144;
        $dim_xhdpi = 96;
        $dim_hdpi = 72;
        $dim_mdpi = 48;

        //Nombre especial del app para la identificacion de paquetes
        $nombreAppOrigin = str_replace(" ", "", trim($config[Aplicacion::configNombreApp]));
        $nombreApp = strtolower($nombreAppOrigin);
        
       
        //************************************************************
        //CREA LA ESTRUCTURA DE CARPETAS DEL PROYECTO ANDROID
        //************************************************************
        $projectDir = ArchivosCTR::obtenerListadoDir(App_Metro::PATH_PROJECT_ANDROID);

        for ($i = 0; $i < count($projectDir); $i++) {
            $dir = str_replace(strtolower(App_Metro::package_default), $nombreApp, str_replace(App_Metro::PATH_PROJECT_ANDROID, $ruta, $projectDir[$i]));
            if (!file_exists($dir))
                mkdir($dir);
        }


        //************************************************************
        //CREAR LOS ARCHIVOS DEL PROYECTO*********
        //************************************************************

        $projectAndroid = ArchivosCTR::obtenerListadoArchivos(App_Metro::PATH_PROJECT_ANDROID);



        for ($i = 0; $i < count($projectAndroid); $i++) {

            if (in_array(Util::extraerNombreArchivo($projectAndroid[$i]) . "." . Util::extraerExtensionArchivo($projectAndroid[$i]), $exclusion)) {
                continue;
            }

            $ruta_dest = str_replace(strtolower(App_Metro::package_default), $nombreApp, str_replace(App_Metro::PATH_PROJECT_ANDROID, $ruta, $projectAndroid[$i]));
            if (Imagen::pertenece($projectAndroid[$i])) {
                copy($projectAndroid[$i], $ruta_dest);
                continue;
            }

            if (strpos($projectAndroid[$i], "Instytul_Metro") !== false) {
                $ruta_dest = str_replace(strtolower(App_Metro::package_default), $nombreApp, str_replace(App_Metro::PATH_PROJECT_ANDROID, $ruta, str_replace("Instytul_Metro", $nombreAppOrigin, $projectAndroid[$i])));
                copy($projectAndroid[$i], $ruta_dest);
                continue;
            }

            ArchivosCTR::buscarReemplazarTexto(array(strtolower(App_Metro::package_default), App_Metro::package_default,"Instytul_Metro"), array($nombreApp, $nombreAppOrigin,$nombreAppOrigin), $projectAndroid[$i], $ruta_dest, true);
        }

        //************************************************************
        //AÑADE TODOS LOS ARCHIVOS AL ZIP*********
        //************************************************************
        $projectAndroidZip = ArchivosCTR::obtenerListadoArchivos($ruta);
        for ($i = 0; $i < count($projectAndroidZip); $i++) {
            $zip->addFile($projectAndroidZip[$i], str_replace($ruta, "", $projectAndroidZip[$i]));
        }


        //************************************************************
        //ARCHIVO DE CONFIGURACION DE LA APLICACION
        //************************************************************
        //Obtiene el contenido config java del usuario en android
        $java = App_Metro::plantillaConfigAndroid(json_encode($config));
        //Crear el archivo java de configuracion de android del usuario
        $ruta_javaConfig = $ruta . "app/src/main/java/libreria/sistema/AppConfig.java";
        $fp = fopen($ruta_javaConfig, "c");
        fwrite($fp, $java);
        fclose($fp);

        //Añade el archivo config de Java para Android
        $zip->addFile($ruta_javaConfig, "app/src/main/java/libreria/sistema/AppConfig.java");


        //************************************************************
        //ICONOS DEL MENU ********************************************
        //************************************************************
        //Crea los iconos necesarios de la aplicacion
        $iconos = array(App_Metro::iconoMenu1, App_Metro::iconoMenu2, App_Metro::iconoMenu3, App_Metro::iconoMenu4);

        for ($i = 1; $i <= count($iconos); $i++) {
            //Valida si el icono existe
            if (filter_var($config[$iconos[$i - 1]], FILTER_VALIDATE_URL)) {

                $URL = $config[$iconos[$i - 1]];
                $imagen = new Imagen($URL);
                $imagen->setCalidad(9);

                $imagen->crearCopia($dim_web, $dim_web, "img_menu_btn_" . $i . "-webx$dim_web", $ruta . "app/src/main/");
                $zip->addFile($ruta . "app/src/main/" . "img_menu_btn_" . $i . "-webx$dim_web." . $imagen->getExtension(), "app/src/main/img_menu_btn_" . $i . "-web.png");
                //mipmap-xxxhdpi
                $imagen->crearCopia($dim_xxxhdpi, $dim_xxxhdpi, "img_menu_btn_" . $i . "x$dim_xxxhdpi", $ruta . "app/src/main/");
                $zip->addFile($ruta . "app/src/main/" . "img_menu_btn_" . $i . "x$dim_xxxhdpi." . $imagen->getExtension(), "app/src/main/res/mipmap-xxxhdpi/img_menu_btn_" . $i . ".png");
                //mipmap-xxhdpi
                $imagen->crearCopia($dim_xxhdpi, $dim_xxhdpi, "img_menu_btn_" . $i . "x$dim_xxhdpi", $ruta . "app/src/main/");
                $zip->addFile($ruta . "app/src/main/" . "img_menu_btn_" . $i . "x$dim_xxhdpi." . $imagen->getExtension(), "app/src/main/res/mipmap-xxhdpi/img_menu_btn_" . $i . ".png");
                //mipmap-xhdpi
                $imagen->crearCopia($dim_xhdpi, $dim_xhdpi, "img_menu_btn_" . $i . "x$dim_xhdpi", $ruta . "app/src/main/");
                $zip->addFile($ruta . "app/src/main/" . "img_menu_btn_" . $i . "x$dim_xhdpi." . $imagen->getExtension(), "app/src/main/res/mipmap-xhdpi/img_menu_btn_" . $i . ".png");
                //mipmap-hdpi
                $imagen->crearCopia($dim_hdpi, $dim_hdpi, "img_menu_btn_" . $i . "x$dim_hdpi", $ruta . "app/src/main/");
                $zip->addFile($ruta . "app/src/main/" . "img_menu_btn_" . $i . "x$dim_hdpi." . $imagen->getExtension(), "app/src/main/res/mipmap-hdpi/img_menu_btn_" . $i . ".png");
                //mipmap-mdpi
                $imagen->crearCopia($dim_mdpi, $dim_mdpi, "img_menu_btn_" . $i . "x$dim_mdpi", $ruta . "app/src/main/");
                $zip->addFile($ruta . "app/src/main/" . "img_menu_btn_" . $i . "x$dim_mdpi." . $imagen->getExtension(), "app/src/main/res/mipmap-mdpi/img_menu_btn_" . $i . ".png");
            } else {
                $zip->addFile(public_path("assets/img/desing/" . App_Metro::sigla . "/img_menu_btn_" . $i . "-web.png"), "app/src/main/img_menu_btn_" . $i . "-web.png");
                //mipmap-xxxhdpi
                $zip->addFile(public_path("assets/img/desing/" . App_Metro::sigla . "/mipmap-xxxhdpi/img_menu_btn_" . $i . ".png"), "app/src/main/res/mipmap-xxxhdpi/img_menu_btn_" . $i . ".png");
                //mipmap-xxhdpi
                $zip->addFile(public_path("assets/img/desing/" . App_Metro::sigla . "/mipmap-xxhdpi/img_menu_btn_" . $i . ".png"), "app/src/main/res/mipmap-xxhdpi/img_menu_btn_" . $i . ".png");
                //mipmap-xhdpi
                $zip->addFile(public_path("assets/img/desing/" . App_Metro::sigla . "/mipmap-xhdpi/img_menu_btn_" . $i . ".png"), "app/src/main/res/mipmap-xhdpi/img_menu_btn_" . $i . ".png");
                //mipmap-hdpi
                $zip->addFile(public_path("assets/img/desing/" . App_Metro::sigla . "/mipmap-hdpi/img_menu_btn_" . $i . ".png"), "app/src/main/res/mipmap-hdpi/img_menu_btn_" . $i . ".png");
                //mipmap-mdpi
                $zip->addFile(public_path("assets/img/desing/" . App_Metro::sigla . "/mipmap-mdpi/img_menu_btn_" . $i . ".png"), "app/src/main/res/mipmap-mdpi/img_menu_btn_" . $i . ".png");
            }
        }


        //************************************************************
        //LOGO DE LA APLICACIÓN ********************************************
        //************************************************************
        //Carga el logo de la aplicaciòn
        $logoApp = new Imagen($config[Aplicacion::configLogoApp]);
        $logoApp->setCalidad(9);

        $ruta_logo = $ruta . "app/src/main/res/";

        $logoApp->crearCopia(192, 192, "ic_launcher", $ruta_logo . "mipmap-xxxhdpi/", false);
        $zip->addFile($ruta_logo . "mipmap-xxxhdpi/" . "ic_launcher." . $logoApp->getExtension(), "app/src/main/res/mipmap-xxxhdpi/ic_launcher.png");
        //mipmap-xxhdpi
        $logoApp->crearCopia(144, 144, "ic_launcher", $ruta_logo . "mipmap-xxhdpi/", false);
        $zip->addFile($ruta_logo . "mipmap-xxhdpi/" . "ic_launcher." . $logoApp->getExtension(), "app/src/main/res/mipmap-xxhdpi/ic_launcher.png");
        //mipmap-xhdpi
        $logoApp->crearCopia(96, 96, "ic_launcher", $ruta_logo . "mipmap-xhdpi/", false);
        $zip->addFile($ruta_logo . "mipmap-xhdpi/" . "ic_launcher." . $logoApp->getExtension(), "app/src/main/res/mipmap-xhdpi/ic_launcher.png");
        //mipmap-mdpi
        $logoApp->crearCopia(48, 48, "ic_launcher", $ruta_logo . "mipmap-mdpi/", false);
        $zip->addFile($ruta_logo . "mipmap-mdpi/" . "ic_launcher." . $logoApp->getExtension(), "app/src/main/res/mipmap-mdpi/ic_launcher.png");
        //mipmap-hdpi
        $logoApp->crearCopia(72, 72, "ic_launcher", $ruta_logo . "mipmap-hdpi/", false);
        $zip->addFile($ruta_logo . "mipmap-hdpi/" . "ic_launcher." . $logoApp->getExtension(), "app/src/main/res/mipmap-hdpi/ic_launcher.png");



        //************************************************************
        //APP.XML - NOMBRE DE LA APLICACION ********************************************
        //************************************************************
        //Crear un archivo XML que le dara el nombre a la aplicacion en android
        $fp = fopen($ruta . "app/src/main/" . "res/values/app.xml", "c");
        fwrite($fp, "<resources><string name=\"nombreApp\">" . $config[Aplicacion::configNombreApp] . "</string></resources>");
        fclose($fp);

        //Agrega el archiv al archivo comprimido
        $zip->addFile($ruta . "app/src/main/" . "res/values/app.xml", "app/src/main/res/values/app.xml");
    }

    /** Obtiene un array con los nombres claves de los textos info del diseno App
     * 
     * @return type
     */
    public static function obtenerNombresTextoInfo() {
        $class = new ReflectionClass("App_Metro");

        $claves = array();

        foreach ($class->getConstants() as $index => $value) {
            if (strpos($index, "txt_info_") !== false)
                $claves[$index] = $value;
        }

        return $claves;
    }

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

        $errores = array();

        if (is_null($app->url_logo)) {
            $errores[0] = trans("app.config.info.logo.error");
        }

        if (count($errores) > 0)
            return $errores;



        foreach ($data as $clave => $valor) {
            if (!in_array($clave, App_Metro::$configExcepciones)) {
                $config = new ConfiguracionApp;

                ConfiguracionApp::eliminarConfigDesdeClave($clave);

                $config->id_aplicacion = $app->id;
                $config->clave = $clave;

                if (Util::esColorRGB($valor))
                    $valor = Util::rgb2hex($valor);

                $config->valor = $valor;
                $config->save();
            }
        }


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
