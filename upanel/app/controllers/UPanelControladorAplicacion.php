<?php

class UPanelControladorAplicacion extends Controller {
    /*     * *************************************************************************
     *  RETORNO DE VISTAS ********************************************************************
     * ************************************************************************ */

    public function basico() {

        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;


        $app = Aplicacion::obtener();
        return View::make("usuarios/tipo/regular/app/construccion/basico")->with("app", $app);
    }

    public function apariencia() {

        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $app = Aplicacion::obtener();

        if (Aplicacion::existe())
            return View::make("usuarios/tipo/regular/app/construccion/disenos/" . $app->diseno . "/apariencia")->with("app", $app);
        else
            return Redirect::to("aplicacion/basico");
    }

    public function textos() {

        if (!Auth::check()) {
            return User::login();
        }


        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $app = Aplicacion::obtener();

        if (Aplicacion::existe())
            return View::make("usuarios/tipo/regular/app/construccion/textos")->with("app", $app);
        else
            return Redirect::to("aplicacion/basico");
    }

    public function desarrollo() {

        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $app = Aplicacion::obtener();

        if (!is_null($app))
            if (Aplicacion::validarEstadoParaEntrarSeccionDesarrollo($app->estado))
                return View::make("usuarios/tipo/regular/app/construccion/desarrollo")->with("app", $app);
            else
                return Redirect::to("aplicacion/apariencia");
        else
            return Redirect::to("aplicacion/apariencia");
    }

    public function vista_versiones() {
        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $app = Aplicacion::obtener();

        if (!is_null($app)) {
            $versiones = ProcesoApp::where("id_aplicacion", $app->id)->whereNotNull("fecha_finalizacion")->orderBy("id", "DESC")->paginate(30);
            return View::make("usuarios/tipo/regular/app/versiones")->with("app", $app)->with("versiones",$versiones);
        }
        return Redirect::to("");
    }

    public function colaDesarrollo() {

        if (!Auth::check()) {
            return User::login();
        }

        //Invalida el acceso para el usuario Regular
        if (!is_null($acceso = User::invalidarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        if (Auth::user()->instancia != User::PARAM_INSTANCIA_SUPER_ADMIN) {
            return User::login();
        }


        $colaDesarrollo = ProcesoApp::where("fecha_finalizacion", null)->orderBy("id", "ASC")->paginate(30);

        return View::make("usuarios/tipo/soporteGeneral/app/colaDesarrollo")->with("colaDesarrollo", $colaDesarrollo);
    }

    public function historialDesarrollo() {

        if (!Auth::check()) {
            return User::login();
        }

        //Invalida el acceso para el usuario Regular
        if (!is_null($acceso = User::invalidarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        if (User::esSuperAdmin() || Auth::user()->instancia == User::PARAM_INSTANCIA_SUPER_ADMIN)
            $historial = ProcesoApp::whereNotNull("fecha_finalizacion")->orderBy("id", "DESC")->paginate(30);
        else
            $historial = ProcesoApp::where("instancia", Auth::user()->instancia)->whereNotNull("fecha_finalizacion")->orderBy("id", "DESC")->paginate(30);


        return View::make("usuarios/tipo/soporteGeneral/app/historialDesarrollo")->with("historial", $historial);
    }

    /*     * ***************************************************************************
     *  LLAMADOS POST ********************************************************************
     * ************************************************************************ */

    public function guardarBasico() {

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $data = Input::all();

        $errores = "";

        if (strlen($data["nombre"]) <= 1) {
            $errores.="<li>" . trans("app.config.info.nombre.error") . "</li>";
        }
        if (strlen($data["mockup"]) != 2) {
            $errores.="<li>" . trans("app.config.info.diseno.error") . "</li>";
        }

        if (strlen($errores) > 0) {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, "<p>" . trans("app.config.info.verificar_errores") . "</p><ul>" . $errores . "</ul>", 3));
        } else {


            //Retorna null si el usuario no tiene una aplicacion
            if (is_null($app = Aplicacion::obtener())) {
                $app = new Aplicacion();
                $app->key_app = $app->generarKeyApp();
            }

            $app->id_usuario = Auth::user()->id;
            $app->nombre = $data["nombre"];
            $app->diseno = $data["mockup"];

            if (@$app->save()) {
                return Redirect::to("aplicacion/apariencia")->withInput()->with(User::mensaje("exito", null, trans("app.config.db.post.exito"), 2));
            } else {
                return Redirect::back()->withInput()->with(User::mensaje("error", null, trans("otros.error_solicitud"), 3));
            }
        }
    }

    public function guardarApariencia() {

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $data = Input::all();
        $app = Aplicacion::obtener();

        if (is_array($response = AppDesing::guardarConfigDiseno($data, $app)))
            return Redirect::back()->withInput()->with(User::mensaje("error", null, $response[0], 3));

        //Redirige a la seccion de textos
        if (is_bool($response) && $response == true) {

            $app->estado = Aplicacion::ESTADO_ESTABLECIENTO_TEXTOS; //Estableciendo textos

            @$app->save();

            return Redirect::to("aplicacion/textos")->with(User::mensaje("exito", null, trans("app.config.post.exito"), 2));
        }
    }

    public function guardarTextos() {

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $data = Input::all();
        $app = Aplicacion::obtener();

        if (is_string($response = AppDesing::guardarConfigTextos($data, $app)))
            return Redirect::back()->withInput()->with(User::mensaje("error", null, $response, 3));

        //Redirige a la seccion de textos
        if (is_bool($response) && $response == true) {

            $app->estado = Aplicacion::ESTADO_LISTA_PARA_ENVIAR;

            @$app->save();
            return Redirect::to("aplicacion/desarrollo")->with(User::mensaje("exito", null, trans("app.config.post.exito"), 2));
        }
    }

    public function enviarDesarrollo() {

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $app = Aplicacion::obtener();
        if ($app->estado == Aplicacion::ESTADO_LISTA_PARA_ENVIAR || Aplicacion::ESTADO_EN_PROCESO_DE_ACTUALIZACION) { //Lista para desarrollo
            $app->estado = Aplicacion::ESTADO_EN_COLA_PARA_DESARROLLO; // En Cola para desarrollo
            if (@$app->save()) {

                ConfiguracionApp::predeterminarValoresVacios($app->id);

                $version = ProcesoApp::obtenerNumeroVersion($app->id);

                $proceso = new ProcesoApp();
                $proceso->id_aplicacion = $app->id;
                if ($version == 0)
                    $proceso->actividad = ProcesoApp::ACTIVIDAD_CONSTRUIR; // Construccion
                else
                    $proceso->actividad = ProcesoApp::ACTIVIDAD_ACTUALIZAR; // Construccion

                $proceso->fecha_creacion = User::obtenerTiempoActual();
                $proceso->json_config = ConfiguracionApp::obtenerJSON($app->id);
                $proceso->instancia = Auth::user()->instancia;
                $proceso->save();

                /* FUNCTION COLOCADA TEMPORALMENTE********************* */
                AppDesing::prepararRequisitosDeAdministracion($app);
                /*                 * *************************************************** */

                return Redirect::to("aplicacion/desarrollo")->with(User::mensaje("exito", null, trans("app.config.dep.post.exito"), 3));
            } else {
                return Redirect::back()->withInput()->with(User::mensaje("error", null, trans("otros.error_solicitud"), 3));
            }
        } else {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, trans("otros.error_solicitud"), 3));
        }
    }

    public function iniciarActualizacion() {

        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;


        if (Aplicacion::existe()) {
            $app = Aplicacion::obtener();
            $app->estado = Aplicacion::ESTADO_EN_PROCESO_DE_ACTUALIZACION;
            $app->save();
            return Redirect::to("aplicacion/basico");
        }

        return Redirect::to("");
    }

    /*     * ***************************************************************************
     *  LLAMADOS AJAX ********************************************************************
     * ************************************************************************ */

    /**
     * Guarda una imagen como una de las configuraciones de aplicacion
     *
     * Obtiene los datos traidos por POST y guarda la imagen en la carpeta Uploads del usuario
     * y almacena la URL en la base de datos
     *
     * @param 
     * @return json_encode
     * @throws Exception Llamado unicamente por POST
     */
    public function ajax_guardarIconoMenu() {

        $data = Input::all();
        $config = new ConfiguracionApp;

        $app = Aplicacion::obtener();

        $output = [];

        foreach ($data as $index => $imagen) {
            if (strpos($index, "iconoMenu") !== false) {

                $extension = strtolower(Input::file($index)->getClientOriginalExtension());
                $size = Input::file($index)->getSize();

                $path = 'usuarios/uploads/' . Auth::user()->id . "/";
                $archivo = Util::obtenerTimeStamp() . "." . $extension;

                Input::file($index)->move($path, $archivo);

                if (ConfiguracionApp::existeConfig($index)) {
                    $config = ConfiguracionApp::find(ConfiguracionApp::obtenerIdConfigDesdeClave($index));



                    //Elimina la imagen anterior si existe
                    if (File::exists(str_replace(URL::to("") . "/", "", $config->valor))) {
                        File::delete(str_replace(URL::to("") . "/", "", $config->valor));
                    }
                }

                $config->id_aplicacion = $app->id;
                $config->clave = $index;
                $config->valor = URL::to($path . $archivo);
                if (!$config->save()) {
                    $output = ['error' => trans("otros.error_solicitud")];
                }

                break;
            }
        }

        //

        return json_encode($output);
    }

    /**
     * Elimina una imagen como una de las configuraciones de aplicacion
     *
     * Obtiene los datos traidos por POST y elimina la imagen y la opcion de configuracion de 
     * una de las opciones del menu principal
     *
     * @param 
     * @return json_encode
     * @throws Exception Llamado unicamente por POST
     */
    public function ajax_eliminarIconoMenu() {

        $idIcono = Input::get("idIcono");

        $config = ConfiguracionApp::find(ConfiguracionApp::obtenerIdConfigDesdeClave($idIcono));

        //Elimina la imagen anterior si existe
        if (File::exists(str_replace(URL::to("") . "/", "", $config->valor))) {
            File::delete(str_replace(URL::to("") . "/", "", $config->valor));
            $config->delete();
        }
    }

    public function ajax_guardarLogo() {

        $logo = "logoApp";

        $output = [];

        $config = new ConfiguracionApp;

        $app = Aplicacion::obtener();

        $extension = strtolower(Input::file($logo)->getClientOriginalExtension());
        $size = Input::file($logo)->getSize();

        $path = 'usuarios/uploads/' . Auth::user()->id . "/";
        $archivo = date("YmdGis") . "." . $extension;


        list($width, $height, $type, $attr) = getimagesize(Input::file($logo));

        //Verifica si las dimensiones de la imagen son iguales
        if (intval($width) != intval($height)) {
            $output = ['error' => trans("app.config.imagen.error01", array("w" => $width, "h" => $height))];
            return json_encode($output);
        }

        if (intval($width) < 256 && intval($height) < 256) {
            $output = ['error' => trans("app.config.imagen.error02", array("width_objetivo" => 256, "height_objetivo" => 256, "width" => $width, "height" => $height))];
            return json_encode($output);
        }

        Input::file($logo)->move($path, $archivo);

        if (!is_null($app->url_logo)) {
            //Elimina la imagen anterior si existe
            if (File::exists(str_replace(URL::to("") . "/", "", $app->url_logo))) {
                File::delete(str_replace(URL::to("") . "/", "", $app->url_logo));
            }
        }

        $app->url_logo = URL::to($path . $archivo);
        if (!$app->save()) {
            $output = ['error' => trans("otros.error_solicitud")];
            return json_encode($output);
        }

        return json_encode($output);
    }

    public function ajax_eliminarLogo() {

        $app = Aplicacion::obtener();
        //Elimina la imagen anterior si existe
        if (File::exists(Util::convertirUrlPath($app->url_logo))) {
            File::delete(Util::convertirUrlPath($app->url_logo));
            $app->url_logo = null;
            $app->save();
        }
    }

    public function ajax_desarrolloEstadoIniciar() {
        $id_aplicacion = Input::get("id_aplicacion");
        $id_proceso = Input::get("id_proceso");

        $proceso = ProcesoApp::find($id_proceso);
        $proceso->fecha_inicio = User::obtenerTiempoActual();
        $proceso->atendido_por = Auth::user()->id;
        $proceso->save();

        $app = Aplicacion::find($id_aplicacion);
        $app->estado = Aplicacion::ESTADO_EN_DESARROLLO;
        $app->save();

        $usuario = $app->user;

        $correo = new Correo();

        $mensaje = trans("email.app.dep.aplicacion_en_desarrollo", array("nombre" => $usuario->nombres, "app_nombre" => $app->nombre, "fecha_inicio" => $proceso->fecha_inicio));

        $correo->enviar(trans("app.config.dep.email.asunto.aplicacion_en_desarrollo"), $mensaje, $usuario->id);

        return json_encode(array("atendido_por" => Auth::user()->nombres, "fecha_inicio" => $proceso->fecha_inicio));
    }

    public function ajax_desarrolloEstadoTerminar() {
        $id_proceso = Input::get("id_proceso");
        $android = Input::get("android");
        $windows = Input::get("windows");
        $iphone = Input::get("iphone");
        $observacion = Input::get("observacion");

        $proceso = ProcesoApp::find($id_proceso);
        $proceso->fecha_finalizacion = User::obtenerTiempoActual();
        $proceso->observaciones = $observacion;


        $app = Aplicacion::find($proceso->id_aplicacion);
        $app->estado = Aplicacion::ESTADO_TERMINADA;

        $plats = "";

        if (strlen($android) > 0) {
            $app->url_android = URL::to($android);
            $proceso->url_android = URL::to($android);
            $plats.="<img src='" . URL::to("assets/img/android.png") . "'/>";
        }
        if (strlen($windows) > 0) {
            $app->url_windows = URL::to($windows);
            $proceso->url_windows = URL::to($windows);
            $plats.="<img src='" . URL::to("assets/img/windows.png") . "'/>";
        }
        if (strlen($iphone) > 0) {
            $app->url_iphone = URL::to($iphone);
            $proceso->url_iphone = URL::to($iphone);
            $plats.="<img src='" . URL::to("assets/img/ios.png") . "'/>";
        }

        $app->save();
        $proceso->save();

        $usuario = User::find($app->id_usuario);

        $output = [];

        /**
         *  ENVIA UN CORREO AL USUARIO INDICANDO QUE SU APLICACIÓN ESTA LISTA
         */
        $params = array("nombre" => $usuario->nombres,
            "app_nombre" => $app->nombre,
            "fecha_inicio" => $proceso->fecha_inicio,
            "fecha_finalizacion" => $proceso->fecha_finalizacion,
            "plataformas" => $plats,
            "link" => URL::to("aplicacion/desarrollo"));

        $correo = new Correo();
        $mensaje = trans("email.app.dep.aplicacion_terminada", $params);
        $correo->enviar(trans("app.config.dep.email.asunto.aplicacion_terminada", array("nombreApp" => $app->nombre)), $mensaje, $usuario->id);



        return json_encode($output);
    }

    public function ajax_subirApp() {

        $id_proceso = Input::get("id_proceso");
        $ID = Input::get("plataforma");


        $output = [];

        $proceso = ProcesoApp::find($id_proceso);

        $app = Aplicacion::find($proceso->id_aplicacion);


        $extension = strtolower(Input::file($ID)->getClientOriginalExtension());
        //$size = Input::file($ID)->getSize();
        $path = 'usuarios/uploads/' . $app->id_usuario . '/app/' . $id_proceso . "/";
        $apk = $app->nombre . "_" . $ID . "." . $extension;
        Input::file($ID)->move($path, $apk);

        $output["path"] = $path . $apk;
        $output["nombreApp"] = $app->nombre;


        return json_encode($output);
    }

    public function ajax_desarrolloInformeDiseno() {

        if (!Auth::check()) {
            return;
        }

        $id_proceso = Input::get("id_proceso");
        $proceso = ProcesoApp::find($id_proceso);

        $json = json_decode($proceso->json_config, true);

        $datos = array();

        $datos[Aplicacion::configNombreApp] = $json[Aplicacion::configNombreApp];
        $datos[Aplicacion::configLogoApp] = $json[Aplicacion::configLogoApp];
        $datos[Aplicacion::configdisenoApp] = $json[Aplicacion::configdisenoApp];
        $datos[Aplicacion::configKeyApp] = $json[Aplicacion::configKeyApp];

        return json_encode($datos);
    }

    /** Descarga un archivo comprimido zip, con todos los archivos necesarios para el diseño de la aplicación en android
     * 
     * @return type
     */
    public function blank_descargarDisenoAndroid() {
        //Usuario autenticado
        if (!Auth::check())
            return;

        //Invalida el acceso para el usuario Regular
        if (!is_null($acceso = User::invalidarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $id_aplicacion = $_GET["id_app"];
        $id_proceso = $_GET["id_p"];


        $app = Aplicacion::find($id_aplicacion);
        $proceso = ProcesoApp::find($id_proceso);

        $ruta = public_path("usuarios/downloads/" . Auth::user()->id . "");

        $ruta_android = $ruta . "/android" . $id_proceso . "/";

        //Crear la carpeta de descargas del usuario si este no existe...
        if (!file_exists($ruta))
            mkdir($ruta);

        //Crea una carpeta para crear los archivos necesarios para android
        if (!file_exists($ruta_android))
            mkdir($ruta_android);

        $nombreZip = $app->nombre . $id_aplicacion . "_android.zip";
        //Ruta de la archivo zip
        $archivoZIP = public_path("usuarios/downloads/" . $nombreZip);

        //Crea un archivo zip
        $zip = new ZipArchive;
        $zip->open($nombreZip, ZipArchive::CREATE);

        //Obtiene el contenido config java del usuario en android
        $java = AppDesing::plantillaConfigAndroid($app, $proceso->json_config);
        //Crear el archivo java de configuracion de android del usuario
        $ruta_javaConfig = $ruta_android . "AppConfig.java";
        $fp = fopen($ruta_javaConfig, "c");
        fwrite($fp, $java);
        fclose($fp);

        //Añade el archivo config de Java para Android
        $zip->addFile($ruta_javaConfig, "app/src/main/java/libreria/sistema/AppConfig.java");

        $config = json_decode($proceso->json_config, true);

        AppDesing::prepararArchivosParaAndroid($zip, $app, $config, $ruta . "/");

        //Carga el logo de la aplicaciòn
        $logoApp = new Imagen($config[Aplicacion::configLogoApp]);

        $logoApp->crearCopia(192, 192, "ic_launcherx192", $ruta . "/");
        $zip->addFile($ruta . "/" . "ic_launcherx192." . $logoApp->getExtension(), "app/src/main/res/mipmap-xxxhdpi/ic_launcher.png");
        //mipmap-xxhdpi
        $logoApp->crearCopia(144, 144, "ic_launcherx144", $ruta . "/");
        $zip->addFile($ruta . "/" . "ic_launcherx144." . $logoApp->getExtension(), "app/src/main/res/mipmap-xxhdpi/ic_launcher.png");
        //mipmap-xhdpi
        $logoApp->crearCopia(96, 96, "ic_launcherx96", $ruta . "/");
        $zip->addFile($ruta . "/" . "ic_launcherx96." . $logoApp->getExtension(), "app/src/main/res/mipmap-xhdpi/ic_launcher.png");
        //mipmap-mdpi
        $logoApp->crearCopia(48, 48, "ic_launcherx48", $ruta . "/");
        $zip->addFile($ruta . "/" . "ic_launcherx48." . $logoApp->getExtension(), "app/src/main/res/mipmap-mdpi/ic_launcher.png");
        //mipmap-hdpi
        $logoApp->crearCopia(72, 72, "ic_launcherx72", $ruta . "/");
        $zip->addFile($ruta . "/" . "ic_launcherx72." . $logoApp->getExtension(), "app/src/main/res/mipmap-hdpi/ic_launcher.png");

        $zip->close();

        //Mueve la el archivo zip a la ruta final
        rename($nombreZip, $archivoZIP);

        //Descarga el archivo zip con la imaggenes
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$nombreZip\"\n");

        $fp = fopen("$archivoZIP", "r");
        fpassthru($fp);
    }

}
