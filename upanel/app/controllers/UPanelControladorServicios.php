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


            foreach ($nombre as $clave => $valor)
                $servicio->setNombre($valor, $clave);

            foreach ($descripcion as $clave => $valor)
                $servicio->setNombre($valor, $clave);

            foreach ($costo as $clave => $valor) {
                $moneda = substr($clave, strpos($clave, "-") + 1, strlen($clave));
                $servicio->setCosto(Monedas::desformatearNumero($moneda, $valor), $clave);
            }

            $moneda = Monedas::actual();

            $output[Servicio::CONFIG_NOMBRE] = $nombre[Servicio::CONFIG_NOMBRE . Idioma::actual()];
            $output[Servicio::CONFIG_COSTO] = Monedas::nomenclatura($moneda, $costo[Servicio::CONFIG_COSTO . $moneda]);
            $output[Servicio::COL_ID] = $servicio->id;
            $output[Servicio::COL_ESTADO] = $servicio->estado;
        }


        return json_encode($output);
    }

    function ajax_obtener() {
        if (!Request::ajax())
            return;

        $output = [];
        $data = Input::all();

        $servicio = Servicio::find($data[Servicio::COL_ID]);

        $metas = ConfigInstancia::where("clave", "LIKE", "servicio_%" . $data[Servicio::COL_ID])->get();

        foreach ($metas as $clave => $valor)
            $output[$clave] = $valor;

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

}
