<?php

Class MensajeTicket extends Eloquent {

    public $timestamps = false;
    protected $table = 'mensajes_soporte';
    protected $fillable = array('id_usuario', 'id_ticket', 'mensaje',"url_adjunto", "fecha");

    public function ticket() {
        return $this->belongsTo('Ticket');
    }

    public function user() {
        return $this->belongsTo('User', "id_usuario");
    }

    public function getFechaAttribute($value) {
        $meses = array("01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre");

        $ft = explode(" ", $value);

        $fecha = explode("-", $ft[0]);

        return $fecha[2] . " de " . $meses[$fecha[1]] . " del " . $fecha[0] . " (" . $ft[1] . ")";
    }

}
