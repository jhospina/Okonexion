<ul class="nav nav-pills">
  <li role="presentation" class="{{(Request::is('aplicacion/*/estadisticas'))?"active":""}}"><a href="{{URL::to("aplicacion/".$app->id."/estadisticas")}}">{{trans("otros.info.resumen")}}</a></li>
  <li role="presentation" class="{{(Request::is('aplicacion/*/estadisticas/usuarios'))?"active":""}}"><a href="{{URL::to("aplicacion/".$app->id."/estadisticas/usuarios")}}">{{trans("app.estadisticas.titulo.base.usuarios")}} </a></li>
</ul>