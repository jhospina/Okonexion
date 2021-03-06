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

    public function vista_versiones($id) {
        if (!Auth::check()) {
            return User::login();
        }

        $app = Aplicacion::find($id);

        if (Auth::user()->tipo == User::USUARIO_REGULAR) {
            if ($app->id_usuario != Auth::user()->id)
                return Redirect::to("");
        }

        if (!User::esSuper() && $app->getIdInstancia() != Auth::user()->instancia)
            return Redirect::to("");


        if (!is_null($app)) {
            $versiones = ProcesoApp::where("id_aplicacion", $app->id)->whereNotNull("fecha_finalizacion")->orderBy("id", "DESC")->paginate(30);
            return View::make("usuarios/tipo/regular/app/versiones")->with("app", $app)->with("versiones", $versiones);
        }
        return Redirect::to("");
    }

    public function vista_estadisticas($id) {

        if (!Auth::check()) {
            return User::login();
        }

        $tipo_suscripcion = User::obtenerValorMetadato(UsuarioMetadato::SUSCRIPCION_TIPO);
        if ($tipo_suscripcion != ConfigInstancia::suscripcion_tipo_plata && $tipo_suscripcion != ConfigInstancia::suscripcion_tipo_oro)
            return Redirect::to("");


        $app = Aplicacion::find($id);

        if (Auth::user()->tipo == User::USUARIO_REGULAR) {
            if ($app->id_usuario != Auth::user()->id)
                return Redirect::to("");
        }

        //Obtiene el numero de instalaciones
        $instalaciones = EstadisticasApp::obtenerTotalInstalaciones($app->id);
        $instalaciones_hoy = EstadisticasApp::obtenerNumeroInstalacionesPorDia($app->id);

        list($ejeX_instal, $stat_instalaciones) = EstadisticasApp::obtenerValoresGrafica_Instalaciones($app, $instalaciones_hoy);
        list($ejeX_act, $stat_actividad) = EstadisticasApp::obtenerValoresGrafica_Actividad($app);

        return View::make("usuarios/tipo/regular/app/estadisticas/index")
                        ->with("app", $app)
                        ->with("instalaciones", $instalaciones)
                        ->with("instalaciones_hoy", $instalaciones_hoy)
                        ->with("actividad", $stat_actividad[count($stat_actividad) - 1])
                        ->with("stat_instalaciones", $stat_instalaciones)
                        ->with("ejeX_instal", $ejeX_instal)
                        ->with("stat_actividad", $stat_actividad)
                        ->with("ejeX_act", $ejeX_act)
        ;
    }

    public static function vista_estadisticas_usuarios($id) {

        if (!Auth::check()) {
            return User::login();
        }

        $tipo_suscripcion = User::obtenerValorMetadato(UsuarioMetadato::SUSCRIPCION_TIPO);

        //Unicamente para plan ORO
        if ($tipo_suscripcion != ConfigInstancia::suscripcion_tipo_oro)
            return Redirect::to("");

        $app = Aplicacion::find($id);

        if (Auth::user()->tipo == User::USUARIO_REGULAR) {
            if ($app->id_usuario != Auth::user()->id)
                return Redirect::to("");
        }

        $edades = DatosUsuarioApp::histograma($app->id, DatosUsuarioApp::EDAD, trans("otros.time.anos"));
        $generos = DatosUsuarioApp::histograma($app->id, DatosUsuarioApp::GENERO);
        $aficiones = DatosUsuarioApp::histograma($app->id, DatosUsuarioApp::AFICIONES_CT . "_%");

        return View::make("usuarios/tipo/regular/app/estadisticas/base-usuarios")
                        ->with("app", $app)
                        ->with("edades", $edades)
                        ->with("generos", $generos)
                        ->with("aficiones", $aficiones);
        ;
    }

    public static function vista_estadisticas_seg_especifico($id) {

        if (!Auth::check()) {
            return User::login();
        }

        $app = Aplicacion::find($id);

        $tipo_suscripcion = User::obtenerValorMetadato(UsuarioMetadato::SUSCRIPCION_TIPO);

        //Unicamente para plan ORO
        if ($tipo_suscripcion != ConfigInstancia::suscripcion_tipo_oro)
            return Redirect::to("");

        if (Auth::user()->tipo == User::USUARIO_REGULAR) {
            if ($app->id_usuario != Auth::user()->id)
                return Redirect::to("");
        }


        $view = View::make("usuarios/tipo/regular/app/estadisticas/seguimiento")
                ->with("app", $app);

        $tipos_contenidos = TipoContenido::obtenerTiposContenidoDelDiseno($app->diseno);

        for ($i = 0; $i < count($tipos_contenidos); $i++) {
            ${"act_" . $tipos_contenidos[$i]} = EstadisticasApp::obtenerActividadEspecificaPorDia($app->id, $tipos_contenidos[$i]);
            $view->with("act_" . $tipos_contenidos[$i], ${"act_" . $tipos_contenidos[$i]});
        }

        $view->with("tipos_contenidos", $tipos_contenidos);

        return $view;
    }

    public function vista_listado() {
        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::invalidarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        if (User::esSuper())
            $apps = Aplicacion::join('usuarios', 'aplicaciones.id_usuario', '=', 'usuarios.id')->select("aplicaciones.id", "aplicaciones.nombre", "aplicaciones.estado", "usuarios.nombres", "usuarios.apellidos", "usuarios.id as id_usuario")->orderBy("aplicaciones.id", "DESC")->paginate(30);
        else
            $apps = Aplicacion::join('usuarios', 'aplicaciones.id_usuario', '=', 'usuarios.id')->where("usuarios.instancia", Auth::user()->instancia)->orderBy("aplicaciones.id", "DESC")->paginate(30);



        return View::make("usuarios/tipo/soporteGeneral/app/index")->with("apps", $apps);
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
        if (strlen($data["mockup"]) != 3) {
            $errores.="<li>" . trans("app.config.info.diseno.error") . "</li>";
        }

        if (strlen($errores) > 0) {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, trans("app.config.info.verificar_errores") . "<ul>" . $errores . "</ul>", 3));
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

        $response = AppDesing::guardarConfigDiseno($data, $app);


        if (is_array($response))
            return Redirect::back()->withInput()->with(User::mensaje("error", null, $response[0], 3));


        //Redirige a la seccion de textos
        if (is_bool($response) && $response == true) {

            $app->estado = Aplicacion::ESTADO_ESTABLECIENTO_TEXTOS; //Estableciendo textos

            @$app->save();

            return Redirect::to("aplicacion/textos")->with(User::mensaje("exito", null, trans("app.config.post.exito"), 2));
        } else {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, trans("otros.error_solicitud"), 3));
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

                $output["id"] = $index;
                $output["url"] = URL::to($path . $archivo);

                break;
            }
        }

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

        $app = Aplicacion::obtener();

        $extension = strtolower(Input::file($logo)->getClientOriginalExtension());
        $size = Input::file($logo)->getSize();

        $path = 'usuarios/uploads/' . Auth::user()->id . "/";
        $archivo = date("YmdGis") . "." . $extension;


        list($width, $height, $type, $attr) = getimagesize(Input::file($logo));


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


        if ($height != $width) {
            $imagen = new Imagen(URL::to($path . $archivo));
            $imagen->setCalidad(9);
            $imagen->crearCopia(256, 256, null, $imagen->getRuta(), true);
        }
        if (!$app->save()) {
            $output = ['error' => trans("otros.error_solicitud")];
            return json_encode($output);
        }

        $output["url"] = URL::to($path . $archivo);

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

        $app = Aplicacion::find($proceso->id_aplicacion);

        $json = json_decode($proceso->json_config, true);

        $datos = array();

        $datos[Aplicacion::configNombreApp] = $json[Aplicacion::configNombreApp];
        $datos[Aplicacion::configLogoApp] = $json[Aplicacion::configLogoApp];
        $datos[Aplicacion::configdisenoApp] = $json[Aplicacion::configdisenoApp];
        $datos[Aplicacion::configKeyApp] = $json[Aplicacion::configKeyApp];

        return json_encode($datos);
    }

    public function ajax_plataformas() {
        $output = array();
        $output["plataformas"] = "";

        $data = Input::all();


        list($android, $ios, $windows) = AppDesing::obtenerDisponibilidadPlataformas($data["mockup"]);

        if ($android)
            $output["plataformas"].="<span data-select='false' data-plataforma='" . AppDesing::PLATAFORMA_ANDROID . "' class='img-plataform tooltip-right' rel='tooltip' title='Android'><img id='plat-android' src='" . URL::to("assets/img/android.png") . "' /></span>";
        if ($ios)
            $output["plataformas"].="<span data-select='false' data-plataforma='" . AppDesing::PLATAFORMA_IOS . "' class='img-plataform tooltip-right' rel='tooltip' title='IOS Iphone'><img id='plat-ios' src='" . URL::to("assets/img/ios.png") . "' /></span>";
        if ($windows)
            $output["plataformas"].="<span data-select='false' data-plataforma='" . AppDesing::PLATAFORMA_WINDOW . "' class='img-plataform tooltip-right' rel='tooltip' title='Windows Phone'><img id='plat-windows' src='" . URL::to("assets/img/windows.png") . "' /></span>";


        return json_encode($output);
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

        $nombreZip = $app->nombre . ".zip";
        //Ruta de la archivo zip
        $archivoZIP = public_path("usuarios/downloads/" . $nombreZip);

        //Crea un archivo zip
        $zip = new ZipArchive;
        $zip->open($nombreZip, ZipArchive::CREATE);

        $config = json_decode($proceso->json_config, true);

        AppDesing::prepararArchivosParaAndroid($zip, $app, $config, $ruta_android);


        $zip->close();

        //Mueve la el archivo zip a la ruta final
        rename($nombreZip, $archivoZIP);

        //Descarga el archivo zip con la imaggenes
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$nombreZip\"\n");

        $fp = fopen("$archivoZIP", "r");
        fpassthru($fp);


        //************************************************************
        //ELIMINA TODOS LOS ARCHIVOS DEL BUFFER
        //************************************************************

        ArchivosCTR::borrarArchivos($ruta_android);
    }

}
