<?php

class UPanelControladorServicios extends Controller {

    function vista_misServicios() {

        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;


        $consulta = MetaFacturacion::where("id_usuario", Auth::user()->id)->where("valor", "LIKE", Servicio::CONFIG_NOMBRE . "%")->groupBy('valor')->get();

        return View::make("usuarios/tipo/regular/servicios/index")->with("consulta", $consulta);
    }

    function vista_agregar() {
        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;


        $servicios = Servicio::listado();
        $hash = HasherPro::crear(UsuarioMetadato::HASH_CREAR_FACTURA);

        return View::make("usuarios/tipo/regular/servicios/agregar")->with("servicios", $servicios)->with("hash", $hash);
    }

    function vista_servicios() {
        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::invalidarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        if (User::esSuperAdmin())
            $factServicios = MetaFacturacion::join('facturacion', 'facturacionMetadatos.id_factura', '=', 'facturacion.id')->where("facturacion.estado", Facturacion::ESTADO_PAGADO)->where("facturacion.instancia", Auth::user()->instancia)->where("facturacionMetadatos.valor", "LIKE", Servicio::CONFIG_NOMBRE . "%")->orderBy('facturacionMetadatos.id', "DESC")->paginate(60);
        else
            $factServicios = MetaFacturacion::join('facturacion', 'facturacionMetadatos.id_factura', '=', 'facturacion.id')->where("facturacion.estado", Facturacion::ESTADO_PAGADO)->where("facturacionMetadatos.valor", "LIKE", Servicio::CONFIG_NOMBRE . "%")->orderBy('facturacionMetadatos.id', "DESC")->paginate(60);

        return View::make("usuarios/tipo/admin/servicios/index")->with("factServicios", $factServicios);
    }

    function post_ordenarServicios() {

        if (!Auth::check()) {
            return User::login();
        }

        //Valida el acceso solo para el usuario Regular
        if (!is_null($acceso = User::validarAcceso(User::USUARIO_REGULAR)))
            return $acceso;

        $data = Input::all();

        if (isset($data[UsuarioMetadato::HASH_CREAR_FACTURA])) {

            //Verifica el hash de creacion y lo efectua una unica vez
            if (HasherPro::Verificar($data[UsuarioMetadato::HASH_CREAR_FACTURA], UsuarioMetadato::HASH_CREAR_FACTURA)) {
                unset($data[UsuarioMetadato::HASH_CREAR_FACTURA]);
                $id_factura = Facturacion::nueva(Instancia::obtenerValorMetadato(ConfigInstancia::fact_impuestos_iva));
                Facturacion::agregarMetadato(MetaFacturacion::MONEDA_ID, Monedas::actual(), $id_factura);
                User::agregarMetaDato(UsuarioMetadato::FACTURACION_ID_PROCESO, $id_factura);
                Facturacion::generarJSONCliente($id_factura);

                foreach ($data as $index => $servicio) {
                    $servicioData = array();
                    $servicioData[MetaFacturacion::PRODUCTO_ID] = $servicio;
                    $servicioData[MetaFacturacion::PRODUCTO_DESCUENTO] = 0;
                    $servicioData[MetaFacturacion::PRODUCTO_VALOR] = Instancia::obtenerValorMetadato(Servicio::CONFIG_COSTO . Monedas::actual() . str_replace(Servicio::CONFIG_NOMBRE, "", $servicio));
                    if (!Facturacion::agregarProducto($id_factura, $servicioData))
                        return Redirect::back()->with(User::mensaje("error", null, trans("otros.error_solicitud"), 2));
                }

                return Redirect::to("fact/orden/pago");
            }
        }

        return Redirect::back()->with(User::mensaje("error", null, trans("otros.error_solicitud"), 2));
    }

    function ajax_procesar() {
        if (!Request::ajax())
            return;

        $output = [];
        $data = Input::all();
        $id_factura = $data["id_factura"];
        $id_servicio = $data["id_servicio"];
        $factura = Facturacion::find($id_factura);

        Facturacion::actualizarMetadato(MetaFacturacion::PRODUCTO_PROCESADO . $id_servicio, Util::convertirBooleanToInt(true), $id_factura);
        Facturacion::agregarMetadato(MetaFacturacion::PRODUCTO_OBSERVACIONES . $id_servicio, str_replace("\n", "<br/>", $data["observaciones"]), $id_factura, $factura->id_usuario);

        return json_encode($output);
    }

    function ajax_obtenerObservacion() {

        if (!Request::ajax())
            return;

        $output = [];
        $data = Input::all();

        $id_factura = $data["id_factura"];
        $id_servicio = $data["id_servicio"];

        $observaciones = Facturacion::obtenerValorMetadato(MetaFacturacion::PRODUCTO_OBSERVACIONES . $id_servicio, $id_factura);
        $output["observacion"] = $observaciones;
        return json_encode($output);
    }

    /** Agrega un nuevo servicio
     * 
     * @return type
     */
    function ajax_agregar() {
        if (!Request::ajax())
            return;

        $output = [];
        $data = Input::all();
        $servicio = new Servicio();

        $idioma = User::obtenerValorMetadato(UsuarioMetadato::OP_IDIOMA);


        if (!Servicio::validar($data))
            return json_encode(array("error" => "error"));

        if ($servicio->registrar($data)) {

            $nombre = json_decode($data[Servicio::CONFIG_NOMBRE], true);
            $descripcion = json_decode($data[Servicio::CONFIG_DESCRIPCION], true);
            $costo = json_decode($data[Servicio::CONFIG_COSTO], true);
            $imagen = $data[Servicio::CONFIG_IMAGEN];

            foreach ($nombre as $clave => $valor)
                $servicio->setNombre($valor, $clave);

            foreach ($descripcion as $clave => $valor)
                $servicio->setNombre($valor, $clave);

            foreach ($costo as $clave => $valor) {
                $moneda = substr($clave, strpos($clave, "-") + 1, strlen($clave));
                $servicio->setCosto(Monedas::desformatearNumero($moneda, $valor), $clave);
            }

            if (strlen($imagen) > 0) {
                $servicio->setImagen($imagen, Servicio::CONFIG_IMAGEN);
            }

            $moneda = Monedas::actual();

            $output[Servicio::CONFIG_NOMBRE] = $nombre[Servicio::CONFIG_NOMBRE . Idioma::actual()];
            $output[Servicio::CONFIG_COSTO] = Monedas::nomenclatura($moneda, $costo[Servicio::CONFIG_COSTO . $moneda]);
            $output[Servicio::COL_ID] = $servicio->id;
            $output[Servicio::COL_ESTADO] = $servicio->estado;
        }


        return json_encode($output);
    }

    function ajax_editar() {
        if (!Request::ajax())
            return;

        $output = [];
        $data = Input::all();
        $servicio = Servicio::find($data[Servicio::COL_ID]);

        $idioma = User::obtenerValorMetadato(UsuarioMetadato::OP_IDIOMA);


        if (!Servicio::validar($data))
            return json_encode(array("error" => "error"));


        $nombre = json_decode($data[Servicio::CONFIG_NOMBRE], true);
        $descripcion = json_decode($data[Servicio::CONFIG_DESCRIPCION], true);
        $costo = json_decode($data[Servicio::CONFIG_COSTO], true);
        $imagen = $data[Servicio::CONFIG_IMAGEN];

        foreach ($nombre as $clave => $valor)
            $servicio->setNombre($valor, $clave);

        foreach ($descripcion as $clave => $valor)
            $servicio->setNombre($valor, $clave);

        foreach ($costo as $clave => $valor) {
            $moneda = substr($clave, strpos($clave, "-") + 1, strlen($clave));
            $servicio->setCosto(Monedas::desformatearNumero($moneda, $valor), $clave);
        }

        if (strlen($imagen) > 0) {
            $servicio->setImagen($imagen, Servicio::CONFIG_IMAGEN);
        }

        $moneda = Monedas::actual();

        $output[Servicio::CONFIG_NOMBRE] = $nombre[Servicio::CONFIG_NOMBRE . Idioma::actual()];
        $output[Servicio::CONFIG_COSTO] = Monedas::nomenclatura($moneda, $costo[Servicio::CONFIG_COSTO . $moneda]);

        return json_encode($output);
    }

    function ajax_obtener() {
        if (!Request::ajax())
            return;

        $output = [];
        $data = Input::all();

        $servicio = Servicio::find($data[Servicio::COL_ID]);

        $metas = ConfigInstancia::where("clave", "LIKE", "servicio_%" . $data[Servicio::COL_ID])->get();

        foreach ($metas as $meta) {

            //Elimina el id del servicio de la cadena clave
            $clave = substr($meta->clave, 0, strlen($meta->clave) - 1);

            //Formatea los numeros de costo
            if (strpos($clave, "costo") !== false) {
                $moneda = substr($clave, strpos($clave, "-") + 1, strlen($clave));
                $valor = Monedas::formatearNumero($moneda, $meta->valor);
            } else {
                $valor = $meta->valor;
            }

            $output[$clave] = $valor;
        }

        return json_encode($output);
    }

    /** Cambia el estado de un servicio
     * 
     * @return type
     */
    function ajax_cambiarEstado() {
        if (!Request::ajax())
            return;

        $data = Input::all();

        $servicio = Servicio::find($data[Servicio::COL_ID]);
        $servicio->estado = $data[Servicio::COL_ESTADO];
        if (!$servicio->save()) {
            return json_encode(array("error" => "error"));
        } else {
            return json_encode(array());
        }
    }

    function ajax_subirImagen() {
        $output = [];

        if (!Request::ajax())
            return json_encode($output);

        $imagen = Servicio::CONFIG_IMAGEN . "-upload";

        $extension = strtolower(Input::file($imagen)->getClientOriginalExtension());
        $size = Input::file($imagen)->getSize();

        $path = 'usuarios/uploads/' . Auth::user()->id . "/";
        $archivo = date("YmdGis") . "." . $extension;

        Input::file($imagen)->move($path, $archivo);

        $output[Servicio::CONFIG_IMAGEN] = URL::to($path . $archivo);

        return json_encode($output);
    }

    function ajax_eliminarImagen() {
        $output = [];

        if (!Request::ajax())
            return json_encode($output);

        $imagen = Input::get(Servicio::CONFIG_IMAGEN);

        if (strlen($imagen) == 0)
            return json_encode($output);

        ConfigInstancia::where("valor", $imagen)->delete();

        //Elimna la imagen del servidor
        if (File::exists(Util::convertirUrlPath($imagen)))
            File::delete(Util::convertirUrlPath($imagen));

        return json_encode($output);
    }

}
