<?php
$data_mensaje = User::verificarPerfil();

if (Auth::user()->estado == User::ESTADO_PRUEBA_FINALIZADA && !Request::is("fact/*"))
    $data_mensaje = User::mensaje("info", "text-center", "<span class='glyphicon glyphicon-exclamation-sign'></span> " . trans("msj.suscripcion.periodo_prueba.culminado", array("aqui" => "<a href='" . URL::to("fact/suscripcion/plan") . "'>Aqui</a>")));

$data_mensaje = User::verificarUsoEspacio();


foreach ($data_mensaje as $index => $valor) {
    ${$index} = $valor;
}
?>
@include("interfaz/mensaje/index",array("id_mensaje"=>1,"param_mensaje"=>"msj-header"))
