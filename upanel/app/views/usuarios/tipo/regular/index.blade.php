@extends('interfaz/plantilla')

@section("titulo") UPanel @stop

@section("contenido") 

@if(IDCookies::existe(IDCookies::MSJ_INICIAL_PERIODO_PRUEBA))
@if(Cookie::get(IDCookies::MSJ_INICIAL_PERIODO_PRUEBA))
@include(Util::RUTA_MENSAJE_MODAL,array("titulo"=>trans("msj.".IDCookies::MSJ_INICIAL_PERIODO_PRUEBA.".titulo",array("num"=>Instancia::obtenerValorMetadato(ConfigInstancia::periodoPrueba_numero_dias))),"mensaje"=>trans("msj.".IDCookies::MSJ_INICIAL_PERIODO_PRUEBA.".descripcion",array("tiempo"=>Util::calcularDiferenciaFechas(Util::obtenerTiempoActual(),Auth::user()->fin_suscripcion)))))

<script>
    $("#btn-entendido").click(function () {
        jQuery.ajax({
            type: "POST",
            url: "{{URL::to('cookies/set')}}",
            data: {IDCookie: "{{IDCookies::MSJ_INICIAL_PERIODO_PRUEBA}}", valor: false}, success: function (response) {
                
            }}, "html");
        });

</script> 

@endif
@endif

@stop