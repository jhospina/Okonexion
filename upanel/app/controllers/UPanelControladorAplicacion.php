<?php

class UPanelControladorAplicacion extends Controller {
    /*     * *************************************************************************
     *  RETORNO DE VISTAS ********************************************************************
     * ************************************************************************ */

    public function basico() {

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;


        $app = Aplicacion::obtener();
        return View::make("usuarios/tipo/regular/app/construccion/basico")->with("app", $app);
    }

    public function apariencia() {

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $app = Aplicacion::obtener();

        if (Aplicacion::existe())
            return View::make("usuarios/tipo/regular/app/construccion/disenos/" . $app->diseno . "/apariencia")->with("app", $app);
        else
            return Redirect::to("aplicacion/basico");
    }

    public function desarrollo() {

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $app = Aplicacion::obtener();

        if (Aplicacion::existe())
            if (Aplicacion::validarEstadoParaEntrarSeccionDesarrollo($app->estado))
                return View::make("usuarios/tipo/regular/app/construccion/desarrollo")->with("app", $app);
            else
                return Redirect::to("aplicacion/apariencia");
        else
            return Redirect::to("aplicacion/apariencia");
    }

    public function colaDesarrollo() {
        //Invalida el acceso para el usuario Regular
        if (!is_null($acceso = User::invalidarAcceso(User::USUARIO_REGULAR)))
            return $acceso;


        $colaDesarrollo = ProcesoApp::where("actividad", "=", ProcesoApp::ACTIVIDAD_CONSTRUIR)->orderBy("id", "ASC")->paginate(30);

        return View::make("usuarios/tipo/soporteGeneral/app/colaDesarrollo")->with("colaDesarrollo", $colaDesarrollo);
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
            $errores.="<li>Debes escribir un nombre para tu aplicación.</li>";
        }
        if (strlen($data["mockup"]) != 2) {
            $errores.="<li>Debes escoger una plantilla de diseño para tu aplicación.</li>";
        }

        if (strlen($errores) > 0) {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, "<p>Verifica los siguientes errores:</p><ul>" . $errores . "</ul>", 3));
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
                return Redirect::to("aplicacion/apariencia")->withInput()->with(User::mensaje("exito", null, "Los datos básicos de tu aplicación han sido guardados. Tu aplicación ha pasado a estar en estado \"En diseño\". Esto significa que has iniciado el proceso de diseño de tu aplicación.", 2));
            } else {
                return Redirect::back()->withInput()->with(User::mensaje("error", null, "Hubo un error al tratar de procesar tu solicitud. Por favor intentalo de nuevo.", 3));
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

        if (is_bool($response) && $response == true)
            return Redirect::to("aplicacion/desarrollo")->with(User::mensaje("exito", null, "La apariencia de tu aplicación han sido establecida. Ya estas listo para enviar tu aplicación a desarrollo.", 2));
    }

    public function enviarDesarrollo() {

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $app = Aplicacion::obtener();
        if ($app->estado == Aplicacion::ESTADO_LISTA_PARA_ENVIAR) { //Lista para desarrollo
            $app->estado = Aplicacion::ESTADO_EN_COLA_PARA_DESARROLLO; // En Cola para desarrollo
            if (@$app->save()) {

                ConfiguracionApp::predeterminarValoresVacios($app->id);

                $proceso = new ProcesoApp();
                $proceso->id_aplicacion = $app->id;
                $proceso->actividad = ProcesoApp::ACTIVIDAD_CONSTRUIR; // Construccion
                $proceso->fecha_creacion = User::obtenerTiempoActual();
                $proceso->json_config = ConfiguracionApp::obtenerJSON($app->id);
                $proceso->save();

                /* FUNCTION COLOCADA TEMPORALMENTE********************* */
                AppDesing::prepararRequisitosDeAdministracion($app);
                /*                 * *************************************************** */

                return Redirect::to("aplicacion/desarrollo")->with(User::mensaje("exito", null, "¡Muy bien! Tu aplicación se ha puesto en cola para ser desarrollada. Te enviaremos un correo electrónico para avisarte cuando empiece a desarrollarse y cuando este totalmente terminada.", 3));
            } else {
                return Redirect::back()->withInput()->with(User::mensaje("error", null, "Hubo un error al tratar de procesar tu solicitud. Por favor intentalo de nuevo.", 3));
            }
        } else {
            return Redirect::back()->withInput()->with(User::mensaje("error", null, "Hubo un error al tratar de procesar tu solicitud. Por favor intentalo de nuevo.", 3));
        }
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
                    $output = ['error' => "Hubo un error al subir la imagen. intentalo de nuevo."];
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
            $output = ['error' => "Las dimensiones de la imagen son incorrectas (" . $width . "x" . $height . "). Recuerda que deben ser iguales."];
            return json_encode($output);
        }

        if (intval($width) < 256 && intval($height) < 256) {
            $output = ['error' => "El tamaño de la imagen deben ser mayores o iguales a 256x256. Tu imagen subida es de " . $width . "x" . $height];
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
            $output = ['error' => "Hubo un error al subir la imagen. intentalo de nuevo."];
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

        $mensaje = "<p>Hola " . $usuario->nombres . "</p>" .
                "<p>Tenemos muy buenas noticias para ti. Tu aplicación <b>" . $app->nombre . "</b> ha empezado su fase de desarrollo y muy pronto estará disponible para que empieces a utilizarla. Te avisaremos cuando la fase de desarrollo haya concluido y tu aplicación esté terminada.</p>" .
                "<p><b>Hora de inicio: </b>" . $proceso->fecha_inicio . "</p>";
        $correo->enviar("¡Tu aplicación ha empezado ha desarrollarse!", $mensaje, $usuario->id);

        return json_encode(array("atendido_por" => Auth::user()->nombres, "fecha_inicio" => $proceso->fecha_inicio));
    }

    public function ajax_desarrolloEstadoTerminar() {
        $id_aplicacion = Input::get("id_aplicacion");
        $id_proceso = Input::get("id_proceso");

        $proceso = ProcesoApp::find($id_proceso);
        $proceso->fecha_finalizacion = User::obtenerTiempoActual();
        $proceso->save();

        $app = Aplicacion::find($id_aplicacion);
        $app->estado = Aplicacion::ESTADO_TERMINADA;
        //$app->save();

        return "true";
    }

    public function ajax_desarrolloInformeDiseno() {

        if (!Auth::check()) {
            return;
        }

        $id_proceso = Input::get("id_proceso");   
        $colores = AppDesing::coloresFondo();
        $proceso = ProcesoApp::find($id_proceso);
       

        $json = json_decode($proceso->json_config);
        $json_nuevo = [];
        //CONVIERTE LAS PROPIEDADES DE COLOR DE HEXADECIMAL A UN VALOR NOMINAL (Ejemplo: c30) 
        //Que indica el color correspondiente dentro de la aplicacion
        foreach ($json as $propiedad => $valor) {
            if (strpos($propiedad, "color") !== false) {
                if ($indice = array_search($valor, $colores))
                    $json_nuevo[$propiedad] = $valor . " (c" . (intval($indice) + 1) . ")";
                else
                    $json_nuevo[$propiedad] = $valor;
            }else {
                $json_nuevo[$propiedad] = $valor;
            }
        }

        return json_encode($json_nuevo);
    }

    //Descarga el logo de la aplicacion en tamaños de 256,128,114,56 pixeles complimidos en un zip
    public function blank_desarrolloDescargarLogoApp() {
        //Usuario autenticado
        if (!Auth::check())
            return;

        //Invalida el acceso para el usuario Regular
        if (!is_null($acceso = User::invalidarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $imagen = $_GET["imagen"];
        $id_aplicacion = $_GET["id_app"];
        $id_proceso = $_GET["id_p"];
        $ruta = public_path("usuarios/downloads/" . Auth::user()->id . "");


        //Crear la carpeta de descargas del usuario si este no existe...
        if (!file_exists($ruta))
            mkdir($ruta);

        $nombreZip = "logoApp" . $id_aplicacion . "_" . $id_proceso . ".zip";
        //Ruta de la archivo zip
        $archivoZIP = public_path("usuarios/downloads/" . $nombreZip);

        //Crea un archivo zip
        $zip = new ZipArchive;
        $zip->open($nombreZip, ZipArchive::CREATE);

        // Obtener los nuevos tamaños
        list($ancho, $alto) = getimagesize($imagen);
        $desc = explode(".", $imagen);
        $ext = end($desc); //Obtiene la extension de la imagen

        switch ($ext) {
            case "jpg":
                $origen = imagecreatefromjpeg($imagen);
                break;
            case "jpeg":
                $origen = imagecreatefromjpeg($imagen);
                break;
            case "png":
                $origen = imagecreatefrompng($imagen);
                break;
            case "gif":
                $origen = imagecreatefromgif($imagen);
                break;
        }


        $dims = Aplicacion::$dim_logos;

        for ($i = 0; $i < count($dims); $i++) {
            // Crea la imagen dimensionada
            $nombreImagen = "icon-" . $dims[$i] . ".png";
            $imagen = imagecreatetruecolor($dims[$i], $dims[$i]);
            imagecopyresized($imagen, $origen, 0, 0, 0, 0, $dims[$i], $dims[$i], $ancho, $alto);
            imagepng($imagen, $ruta . "/" . $nombreImagen);
            //Agrega la imagen de 256 al archivo sip
            $zip->addFile($ruta . "/" . $nombreImagen, $nombreImagen);
        }

        //IMAGEN DE LOADING
        $zip->addFile($ruta . "/" . "icon-" . $dims[0] . ".png", "loading-logo.png");
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
