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
        $meses = Util::obtenerNombreMeses();
        $ft = explode(" ", $value);
        $fecha = explode("-", $ft[0]);
        
        return trans("otros.fecha.formato_01",array("dia"=>$fecha[2],"mes"=>$meses[$fecha[1]],"ano"=>$fecha[0],"hora"=>$ft[1]));
    }

}
