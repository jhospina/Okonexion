<?php

class UPanelControladorServicios extends Controller {

    function ajax_agregar() {
        if (!Request::ajax())
            return;

        $output = [];
        $data = Input::all();
        $servicio = new Servicio();

        if (!Servicio::validar($data))
            return json_encode(array("error" => "error"));

        if ($servicio->registrar($data)) {
            $output[Servicio::COL_NOMBRE] = $servicio->nombre;
            $output[Servicio::COL_COSTO] = Monedas::nomenclatura(Monedas::actual(), $data[Servicio::COL_COSTO]);
            $output[Servicio::COL_ID] = $servicio->id;
            $output[Servicio::COL_ESTADO] = $servicio->estado;
        }



        return json_encode($output);
    }

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
