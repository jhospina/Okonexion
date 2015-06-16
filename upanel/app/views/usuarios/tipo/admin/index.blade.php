@extends('interfaz/plantilla')

@section("titulo") {{trans("interfaz.menu.principal.inicio")}} @stop

@section("contenido") 


<div class="col-lg-12">

    {{-- INFO RAPIDO: VENTAS TOTALES --}}

    @include("interfaz/util/infobox",
    array("infobox_color"=>"green",
    "infobox_icon"=>"glyphicon-globe",
    "infobox_cant"=>$totalVentas,
    "infobox_label"=>$moneda,
    "infobox_descripcion"=>trans("pres.ar.ventas.foot")
    ))
    
    {{-- INFO RAPIDO: APLICACIONES (COLA/TOTAL)--}}

    @include("interfaz/util/infobox",
    array("infobox_color"=>"#357ebd",
    "infobox_icon"=>"glyphicon-phone",
    "infobox_cant"=>$cant_colaD."(".$cant_apps.")",
    "infobox_label"=>trans("pres.ar.aplicaciones"),
    "infobox_descripcion"=>trans("pres.ar.aplicaciones.foot"),
    "infobox_link_info"=>URL::to("aplicaciones")))


    {{-- INFO RAPIDO: SERVICIOS--}}

    @include("interfaz/util/infobox",
    array("infobox_color"=>"sienna",
    "infobox_icon"=>"glyphicon-flash",
    "infobox_cant"=>$serviciosNoProcesados."(".$totalServicios.")",
    "infobox_label"=>trans("pres.ar.servicios"),
    "infobox_descripcion"=>trans("pres.ar.servicios.foot"),
    "infobox_link_info"=>URL::to("control/servicios")))

    {{-- INFO RAPIDO: USUARIOS--}}

    @include("interfaz/util/infobox",
    array("infobox_color"=>"firebrick",
    "infobox_icon"=>"glyphicon-user",
    "infobox_cant"=>$cant_usuarios_suscritos."(".$total_usuarios.")",
    "infobox_label"=>trans("pres.ar.usuarios"),
    "infobox_descripcion"=>trans("pres.ar.usuarios.foot"),
    "infobox_link_info"=>URL::to("control/usuarios"),
    "infobox_link_foot"=>URL::to("usuario/create")))
    
    
    {{-- INFO RAPIDO: FACTURACION (SIN PAGAR/TOTAL)--}}

    @include("interfaz/util/infobox",
    array("infobox_color"=>"teal",
    "infobox_icon"=>"glyphicon-list-alt",
    "infobox_cant"=>$cant_factSinPagar."(".$total_facturas.")",
    "infobox_label"=>trans("pres.ar.facturacion"),
    "infobox_descripcion"=>trans("pres.ar.facturacion.foot"),
    "infobox_link_info"=>URL::to("fact/facturas")))


    {{-- INFO RAPIDO: TICKETS--}}

    @include("interfaz/util/infobox",
    array("infobox_color"=>"#eea236",
    "infobox_icon"=>"glyphicon-comment",
    "infobox_cant"=>$cant_ticketsAbierto."(".$total_tickets.")",
    "infobox_label"=>trans("pres.ar.tickets"),
    "infobox_descripcion"=>trans("pres.ar.tickets.foot"),
    "infobox_link_info"=>URL::to("soporte")))




</div>

@stop
