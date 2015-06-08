<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */


// Para validar los datos de inicio de sesión.
Route::post('login', 'ControladorAcceso@iniciarSesion');

Route::get("login", function() {
    return Redirect::to("https://appsthergo.com" . User::CONFIG_URL_LOGIN . "?response=refused");
    /*
      if (isset($_SERVER["HTTP_REFERER"])) {
      if (strpos($_SERVER["HTTP_REFERER"], "upanel") === false)
      return(Util::esConexionSegura()) ? Redirect::to("https://" . Util::obtenerDominioDeUrl($_SERVER["HTTP_REFERER"]) . User::CONFIG_URL_LOGIN . "?response=refused") : Redirect::to("http://" . Util::obtenerDominioDeUrl($_SERVER["HTTP_REFERER"]) . User::CONFIG_URL_LOGIN . "?response=refused");
      else
      return (Util::esConexionSegura()) ? Redirect::to("https://" . $_SERVER["SERVER_NAME"] . User::CONFIG_URL_LOGIN) : Redirect::to("http://" . $_SERVER["SERVER_NAME"] . User::CONFIG_URL_LOGIN . "?response=refused");
      } else
      return (Util::esConexionSegura()) ? Redirect::to("https://" . $_SERVER["SERVER_NAME"] . User::CONFIG_URL_LOGIN) : Redirect::to("http://" . $_SERVER["SERVER_NAME"] . User::CONFIG_URL_LOGIN . "?response=refused");
     */
});


//Para activar una cuenta de usuario al confirmar su correo electronico
Route::get('activar/{id}/{codigo}', 'ControladorAcceso@activarCuenta');
//Recibe una solicitud para reestablecer la contraseña
Route::post('recuperar-contrasena', 'ControladorAcceso@recuperarContrasena');
//Para reestablecer una contrasena
Route::get('recovery/{id}/{codigo}', 'ControladorAcceso@recoveryForm');
//Para reestablecer una contrasena
Route::post('recovery', 'ControladorAcceso@recovery');
//Rutas de envios de Correo
Route::get('correo/{usuario}/activacion', 'ControladorAcceso@enviarActivacion');
//Controlador Usuarios
Route::resource('usuario', 'UPanelControladorUsuario');


// Nos indica que las rutas que están dentro de él sólo serán mostradas si antes el usuario se ha autenticado.
Route::group(array('before' => 'auth'), function() {
    aplicacion_desarrollo();
    aplicacion_construir();
    aplicacion_ajax();
    aplicacion_comp();
    ayuda_soporte();
    configuracion();
    usuario();
    usuario_ajax();
    usuario_opciones();
    usuario_opciones_ajax();
    tipoContenido_Encuestas();
    tipoContenido_Institucional();
    tipoContenido_Noticias();
    tipoContenido_PQR();
    cookies();
    notificaciones();
    servicios();
    facturacion();
    pdf();

    control_usuarios();
    control_instancias();
});


//******************************************************************************
//******************************************************************************
// RUTAS DE CONEXIÓN DE LA APP AL SERVIDOR OKONEXION*********************************
//******************************************************************************
//******************************************************************************
//Las apps se conectan para obtener informacion
Route::post("app/descargar/noticias", "ControladorApp@descargar_noticias");
Route::post("app/descargar/institucional", "ControladorApp@descargar_institucional");
Route::post("app/descargar/encuestas/vigente", "ControladorApp@descargar_encuestas_vigente");
Route::post("app/descargar/encuestas/archivadas", "ControladorApp@descargar_encuestas_archivadas");
Route::post("app/enviar/encuestas/respuesta", "ControladorApp@enviar_encuestas_respuesta");
//PQR
Route::post("app/enviar/pqr", "ControladorApp@enviarPqr");
Route::post("app/recibir/pqr", "ControladorApp@recibirPqr");

//Las apps se conectan para cargar imagenes
Route::get("usuarios/uploads/{usuario}/{imagen}/{mime_type}", "ControladorApp@cargarImagen");

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function aplicacion_construir() {
    //APLICACION----------------------------------------------------------------
    Route::get('aplicacion/basico', 'UPanelControladorAplicacion@basico');
    Route::post("aplicacion/basico", "UPanelControladorAplicacion@guardarBasico");

    Route::get('aplicacion/apariencia', 'UPanelControladorAplicacion@apariencia');
    Route::post("aplicacion/apariencia", "UPanelControladorAplicacion@guardarApariencia");

    Route::get('aplicacion/textos', 'UPanelControladorAplicacion@textos');
    Route::post("aplicacion/textos", "UPanelControladorAplicacion@guardarTextos");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function aplicacion_desarrollo() {
    //SOPORTE GENERAL Y ADMIN
    Route::get('aplicacion/desarrollo/cola', 'UPanelControladorAplicacion@colaDesarrollo');
    Route::get('aplicacion/desarrollo/historial', 'UPanelControladorAplicacion@historialDesarrollo');

    //USUARIO REGULAR
    Route::get('aplicacion/desarrollo', 'UPanelControladorAplicacion@desarrollo');
    Route::post('aplicacion/desarrollo', 'UPanelControladorAplicacion@enviarDesarrollo');
    Route::post('aplicacion/desarrollo/iniciar/actualizacion', 'UPanelControladorAplicacion@iniciarActualizacion');
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
function aplicacion_comp() {
    Route::get('aplicacion/versiones', 'UPanelControladorAplicacion@vista_versiones');
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function aplicacion_ajax() {
    Route::post("aplicacion/ajax/guardarIconoMenu", "UPanelControladorAplicacion@ajax_guardarIconoMenu");
    Route::post("aplicacion/ajax/eliminarIconoMenu", "UPanelControladorAplicacion@ajax_eliminarIconoMenu");
    Route::post("aplicacion/ajax/guardarLogo", "UPanelControladorAplicacion@ajax_guardarLogo");
    Route::post("aplicacion/ajax/eliminarLogo", "UPanelControladorAplicacion@ajax_eliminarLogo");
    Route::post("aplicacion/ajax/desarrollo/estado/iniciar", "UPanelControladorAplicacion@ajax_desarrolloEstadoIniciar");
    Route::post("aplicacion/ajax/desarrollo/estado/terminar", "UPanelControladorAplicacion@ajax_desarrolloEstadoTerminar");
    Route::post("aplicacion/ajax/desarrollo/informe/diseno", "UPanelControladorAplicacion@ajax_desarrolloInformeDiseno");
    Route::post('aplicacion/ajax/upload/app', 'UPanelControladorAplicacion@ajax_subirApp');

    //DESCARGAS DE DISEÑOS
    //ANDROID
    Route::get("aplicacion/ajax/desarrollo/descargar/disenoApp/android", "UPanelControladorAplicacion@blank_descargarDisenoAndroid");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function usuario() {
    Route::get("/", "UPanelControladorPresentacion@index");
    Route::get('logout', 'ControladorAcceso@cerrarSesion');
    Route::get("cambiar-contrasena", "UPanelControladorUsuario@cambiarContrasenaForm");
    Route::post("cambiar-contrasena", "UPanelControladorUsuario@cambiarContrasenaPost");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function usuario_ajax() {
    Route::post("usuario/ajax/actualizar/", "UPanelControladorUsuario@ajax_actualizar");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function control_usuarios() {
    Route::get("control/usuarios/", "UPanelControladorUsuario@index_listado");
    Route::post("usuario/create", "UPanelControladorUsuario@create_store");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function control_instancias() {
    Route::get("instancias/", "UPanelControladorInstancias@index");
    Route::get("instancias/crear", "UPanelControladorInstancias@vista_crear");
    Route::get("instancias/{id}", "UPanelControladorInstancias@vista_ver");
    Route::get("instancias/{id}/editar", "UPanelControladorInstancias@vista_editar");
    Route::post("instancias/crear", "UPanelControladorInstancias@post_crear");
    Route::post("instancias/{id}/editar", "UPanelControladorInstancias@post_editar");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function usuario_opciones() {
    
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function usuario_opciones_ajax() {
    Route::post("usuario/opciones/idioma/set", "UPanelControladorUsuario@cambiarIdioma");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function ayuda_soporte() {
    Route::resource('soporte', 'UPanelControladorSoporte');
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function servicios() {
    Route::get('servicios/agregar', 'UPanelControladorServicios@vista_agregar');
    Route::post('servicios/post/agregar', 'UPanelControladorServicios@post_ordenarServicios');
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function configuracion() {
    Route::get("config/general", "UPanelControladorConfiguracion@vista_general");
    Route::get("config/suscripcion", "UPanelControladorConfiguracion@vista_suscripcion");
    Route::get("config/facturacion", "UPanelControladorConfiguracion@vista_facturacion");
    Route::get("config/servicios", "UPanelControladorConfiguracion@vista_servicios");

    Route::post("config/post/guardar", "UPanelControladorConfiguracion@post_guardar");

    Route::post("config/ajax/subir/logo", "UPanelControladorConfiguracion@ajax_subirLogo");
    Route::post("config/ajax/eliminar/logo", "UPanelControladorConfiguracion@ajax_eliminarLogo");
    Route::post("config/ajax/agregar/servicio", "UPanelControladorServicios@ajax_agregar");
    Route::post("config/ajax/editar/servicio", "UPanelControladorServicios@ajax_editar");
    Route::post("config/ajax/obtener/servicio", "UPanelControladorServicios@ajax_obtener");
    Route::post("config/ajax/estado/servicio", "UPanelControladorServicios@ajax_cambiarEstado");
    Route::post("config/ajax/subir/imagen/servicio", "UPanelControladorServicios@ajax_subirImagen");
    Route::post("config/ajax/eliminar/imagen/servicio", "UPanelControladorServicios@ajax_eliminarImagen");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function cookies() {
    Route::post("cookies/set", function() {
        if (Request::ajax()) {
            $cookie = Cookie::make(Input::get("IDCookie"), Input::get("valor"), IDCookies::duracion(Input::get("IDCookie")));
            $response = Response::json(array());
            $response->headers->setCookie($cookie);

            return $response;
        }
    });
}

function facturacion() {
    //PROCESO DE SUSCRIPCION
    Route::get("fact/suscripcion/plan", "UPanelControladorFacturacion@vista_suscripcion_planes");
    Route::get("fact/suscripcion/ciclo/{plan}", "UPanelControladorFacturacion@vista_suscripcion_ciclos");
    //Facturacion y Pagos
    Route::post("fact/orden/pago", "UPanelControladorFacturacion@vistaPost_ordenPago");
    Route::get("fact/orden/pago", "UPanelControladorFacturacion@vistaPost_ordenPago");
    Route::post("fact/orden/pago/procesar/", "UPanelControladorFacturacion@post_ordenPagoProcesar");
    //USUARIO
    Route::get("fact/mis-facturas", "UPanelControladorFacturacion@vista_misFacturas");
    Route::get("fact/facturas", "UPanelControladorFacturacion@vista_facturas");
    //Ver factura
    Route::get("fact/factura/{id}", "UPanelControladorFacturacion@vista_verFactura");
}

function notificaciones() {
    Route::post("nots/ajax/set/visto", "UPanelControladorNotificaciones@ajax_establecerVisto");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function pdf() {
    //Ver
    Route::get("pdf/ver/factura/{id}", "UPanelControladorFacturacion@pdf_factura");
    //Descargar
    Route::get("pdf/descargar/factura/{id}", "UPanelControladorFacturacion@pdf_descargarFactura");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function tipoContenido_Institucional() {
    Route::get("aplicacion/administrar/institucional", "UPanelControladorContenidoInstitucional@institucional");
    //AGREGAR
    Route::get("aplicacion/administrar/institucional/agregar", "UPanelControladorContenidoInstitucional@institucional_vistaAgregar");
    //EDITAR 
    Route::get("aplicacion/administrar/institucional/editar/{id_inst}", "UPanelControladorContenidoInstitucional@institucional_vistaEditar");
    //Publicar 
    Route::post("aplicacion/administrar/institucional/publicar", "UPanelControladorContenidoInstitucional@institucional_publicar");
    //Guardar 
    Route::post("aplicacion/administrar/institucional/guardar", "UPanelControladorContenidoInstitucional@institucional_guardar");

    /*     * AJAX* */
    //ELIMINAR 
    Route::post("aplicacion/administrar/institucional/ajax/eliminar/institucional", "UPanelControladorContenidoInstitucional@ajax_institucional_eliminarInstitucional");
    //GUARDAR ORDEN 
    Route::post("aplicacion/administrar/institucional/ajax/guardar/orden", "UPanelControladorContenidoInstitucional@ajax_institucional_guardarOrden");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function tipoContenido_Noticias() {
    Route::get("aplicacion/administrar/noticias", "UPanelControladorContenidoNoticias@noticias");
    //AGREGAR
    Route::get("aplicacion/administrar/noticias/agregar", "UPanelControladorContenidoNoticias@noticias_vistaAgregar");
    //EDITAR
    Route::get("aplicacion/administrar/noticias/editar/{id_noticia}", "UPanelControladorContenidoNoticias@noticias_vistaEditar");
    //CATEGORIAS
    Route::get("aplicacion/administrar/noticias/categorias", "UPanelControladorContenidoNoticias@noticias_categorias");
    //Publicar
    Route::post("aplicacion/administrar/noticias/publicar", "UPanelControladorContenidoNoticias@noticias_publicar");
    //Guardar
    Route::post("aplicacion/administrar/noticias/guardar", "UPanelControladorContenidoNoticias@noticias_guardar");

    //AJAX
    //CATEGORIA - AGREGAR
    Route::post("aplicacion/administrar/noticias/ajax/agregar/categoria", "UPanelControladorContenidoNoticias@ajax_noticias_agregarCategoria");
    //CATEGORIA - EDITAR
    Route::post("aplicacion/administrar/noticias/ajax/editar/categoria", "UPanelControladorContenidoNoticias@ajax_noticias_editarCategoria");
    //CATEGORIA - Eliminar
    Route::post("aplicacion/administrar/noticias/ajax/eliminar/categoria", "UPanelControladorContenidoNoticias@ajax_noticias_eliminarCategoria");
    //ELIMINAR
    Route::post("aplicacion/administrar/noticias/ajax/eliminar/noticia", "UPanelControladorContenidoNoticias@ajax_noticias_eliminarNoticia");

    Route::post("aplicacion/administrar/noticias/ajax/subir/imagen", "UPanelControladorContenidoNoticias@ajax_noticias_subirImagen");
    Route::post("aplicacion/administrar/noticias/ajax/eliminar/imagen", "UPanelControladorContenidoNoticias@ajax_noticias_eliminarImagen");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function tipoContenido_Encuestas() {
    Route::get("aplicacion/administrar/encuestas", "UPanelControladorContenidoEncuestas@index");
    // AGREGAR 
    Route::get("aplicacion/administrar/encuestas/agregar", "UPanelControladorContenidoEncuestas@vista_agregar");
    // EDITAR 
    Route::get("aplicacion/administrar/encuestas/editar/{id}", "UPanelControladorContenidoEncuestas@vista_editar");
    // HISTORICO 
    Route::get("aplicacion/administrar/encuestas/historico/{id}", "UPanelControladorContenidoEncuestas@vista_historico");
    // Publicar 
    Route::post("aplicacion/administrar/encuestas/publicar", "UPanelControladorContenidoEncuestas@publicar");
    // Guardar 
    Route::post("aplicacion/administrar/encuestas/guardar", "UPanelControladorContenidoEncuestas@guardar");

    /*     * AJAX* */
    //ELIMINAR
    Route::post("aplicacion/administrar/encuesta/ajax/eliminar/encuesta", "UPanelControladorContenidoEncuestas@ajax_eliminar_encuesta");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

function tipoContenido_PQR() {
    Route::get("aplicacion/administrar/pqr", "UPanelControladorContenidoPQR@index");
    //REVISAR
    Route::get("aplicacion/administrar/pqr/{id_pqr}/revisar/", "UPanelControladorContenidoPQR@vista_revisar");
    //ENVIAR RESPUESTA
    Route::post("aplicacion/administrar/pqr/{id_pqr}/revisar/", "UPanelControladorContenidoPQR@enviar_respuesta");
}

//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************
//***************************************************************************

