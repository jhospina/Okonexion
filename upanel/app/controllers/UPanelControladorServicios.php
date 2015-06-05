<?php

class UPanelControladorServicios extends Controller {

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

        return json_encode($output);
    }

}
