<?php

Class Instancia extends Eloquent {

    protected $table = 'instancias';
    protected $fillable = array("empresa", "nit", "web", "email", "nombre_contacto", "pais", "region", "ciudad", "direccion", "telefono");

    const ESTADO_EN_ESPERA = "ES";
    const ESTADO_PRUEBA = "EP";
    const ESTADO_VIGENTE = "EV";
    const ESTADO_CADUCADO = "EC";

    public function validar($data) {

        $errores = "";

        foreach ($data as $index => $value) {
            if (in_array($index, $this->fillable)) {
                if (strlen($value) < 3) {
                    $errores.="<li>" . trans("instancias.info.error.llenar_campos") . "</li>";
                    break;
                }
            }
        }

        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL))
            $errores.="<li>" . trans("instancias.info.error.email") . "</li>";

        if (strlen($errores) > 0)
            return "<ul>" . $errores . "</ul>";
        else
            return $errores;
    }

    public function registrar($data, $id_admin = null) {

        $this->fill($data);

        if (isset($data["codigo_postal"]))
            $this->codigo_postal = $data["codigo_postal"];
        if (isset($data["celular"]))
            $this->celular = $data["celular"];

        $fecha = new Fecha(Util::obtenerTiempoActual());
        if (!is_null($id_admin)) {
            $this->estado = Instancia::ESTADO_PRUEBA;
            $this->fin_suscripcion = $fecha->agregarSemanas(1);
            $this->id_administrador = $id_admin; 
        }

        return $this->save();
    }

}
