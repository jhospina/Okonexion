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
    return Redirect::to("http://" . $_SERVER["SERVER_NAME"] . "/ingresar/?response=refused");
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
    Route::get("/", "UPanelControladorPresentacion@index");
    Route::get('logout', 'ControladorAcceso@cerrarSesion');
    Route::get("cambiar-contrasena", "UPanelControladorUsuario@cambiarContrasenaForm");
    Route::post("cambiar-contrasena", "UPanelControladorUsuario@cambiarContrasenaPost");
    Route::resource('soporte', 'UPanelControladorSoporte');


    //APLICACION----------------------------------------------------------------

    Route::get('aplicacion/basico', 'UPanelControladorAplicacion@basico');
    Route::post("aplicacion/basico", "UPanelControladorAplicacion@guardarBasico");

    Route::get('aplicacion/apariencia', 'UPanelControladorAplicacion@apariencia');
    Route::post("aplicacion/apariencia", "UPanelControladorAplicacion@guardarApariencia");

    Route::get('aplicacion/desarrollo', 'UPanelControladorAplicacion@desarrollo');
    Route::post('aplicacion/desarrollo', 'UPanelControladorAplicacion@enviarDesarrollo');

    //SOPORTE GENERAL Y ADMIN

    Route::get('aplicacion/cola-desarrollo', 'UPanelControladorAplicacion@colaDesarrollo');

    //AJAX
    Route::post("aplicacion/ajax/guardarIconoMenu", "UPanelControladorAplicacion@ajax_guardarIconoMenu");
    Route::post("aplicacion/ajax/eliminarIconoMenu", "UPanelControladorAplicacion@ajax_eliminarIconoMenu");
    Route::post("aplicacion/ajax/guardarLogo", "UPanelControladorAplicacion@ajax_guardarLogo");
    Route::post("aplicacion/ajax/eliminarLogo", "UPanelControladorAplicacion@ajax_eliminarLogo");
    Route::post("aplicacion/ajax/desarrollo/estado/iniciar", "UPanelControladorAplicacion@ajax_desarrolloEstadoIniciar");
    Route::post("aplicacion/ajax/desarrollo/estado/terminar", "UPanelControladorAplicacion@ajax_desarrolloEstadoTerminar");
    Route::post("aplicacion/ajax/desarrollo/informe/diseno", "UPanelControladorAplicacion@ajax_desarrolloInformeDiseno");

    //DESCARGAS BLANK
    Route::get("aplicacion/ajax/desarrollo/descargar/logoApp/", "UPanelControladorAplicacion@blank_desarrolloDescargarLogoApp");



    //***************************************************************************
    //*ADMINISTRACIÓN DE CONTENIDOS DE APLICACIÓN********************************
    //***************************************************************************

    /* INSTITUCIONAL********************************** */
    Route::get("aplicacion/administrar/institucional", "UPanelControladorContenidoInstitucional@institucional");
    /* INSTITUCIONAL - AGREGAR */ Route::get("aplicacion/administrar/institucional/agregar", "UPanelControladorContenidoInstitucional@institucional_vistaAgregar");
    /* INSTITUCIONAL - EDITAR */ Route::get("aplicacion/administrar/institucional/editar/{id_inst}", "UPanelControladorContenidoInstitucional@institucional_vistaEditar");
    /* INSTITUCIONAL - Publicar */ Route::post("aplicacion/administrar/institucional/publicar", "UPanelControladorContenidoInstitucional@institucional_publicar");
    /* INSTITUCIONAL - Guardar */ Route::post("aplicacion/administrar/institucional/guardar", "UPanelControladorContenidoInstitucional@institucional_guardar");


    /* NOTICIAS********************************** */
    Route::get("aplicacion/administrar/noticias", "UPanelControladorContenidoNoticias@noticias");
    /* NOTICIAS - AGREGAR */ Route::get("aplicacion/administrar/noticias/agregar", "UPanelControladorContenidoNoticias@noticias_vistaAgregar");
    /* NOTICIAS - EDITAR */ Route::get("aplicacion/administrar/noticias/editar/{id_noticia}", "UPanelControladorContenidoNoticias@noticias_vistaEditar");
    /* NOTICIAS - CATEGORIAS */ Route::get("aplicacion/administrar/noticias/categorias", "UPanelControladorContenidoNoticias@noticias_categorias");
    /* NOTICIAS - Publicar */ Route::post("aplicacion/administrar/noticias/publicar", "UPanelControladorContenidoNoticias@noticias_publicar");
    /* NOTICIAS - Guardar */ Route::post("aplicacion/administrar/noticias/guardar", "UPanelControladorContenidoNoticias@noticias_guardar");

    /* ENCUESTAS************************************** */
    Route::get("aplicacion/administrar/encuestas", "UPanelControladorContenidoEncuestas@index");
    /* ENCUESTAS - AGREGAR */ Route::get("aplicacion/administrar/encuestas/agregar", "UPanelControladorContenidoEncuestas@vista_agregar");
    /* ENCUESTAS - EDITAR */ Route::get("aplicacion/administrar/encuestas/editar/{id}", "UPanelControladorContenidoEncuestas@vista_editar");
    /* ENCUESTAS - HISTORICO */ Route::get("aplicacion/administrar/encuestas/historico/{id}", "UPanelControladorContenidoEncuestas@vista_historico");
    /* ENCUESTAS - Publicar */ Route::post("aplicacion/administrar/encuestas/publicar", "UPanelControladorContenidoEncuestas@publicar");
    /* ENCUESTAS - Guardar */ Route::post("aplicacion/administrar/encuestas/guardar", "UPanelControladorContenidoEncuestas@guardar");

    //AJAX
    /* NOTICIAS - CATEGORIA - AGREGAR */Route::post("aplicacion/administrar/noticias/ajax/agregar/categoria", "UPanelControladorContenidoNoticias@ajax_noticias_agregarCategoria");
    /* NOTICIAS - CATEGORIA - EDITAR */Route::post("aplicacion/administrar/noticias/ajax/editar/categoria", "UPanelControladorContenidoNoticias@ajax_noticias_editarCategoria");
    /* NOTICIAS - CATEGORIA - Eliminar */Route::post("aplicacion/administrar/noticias/ajax/eliminar/categoria", "UPanelControladorContenidoNoticias@ajax_noticias_eliminarCategoria");
    /* NOTICIAS - ELIMINAR */Route::post("aplicacion/administrar/noticias/ajax/eliminar/noticia", "UPanelControladorContenidoNoticias@ajax_noticias_eliminarNoticia");

    /* Institucional - ELIMINAR */Route::post("aplicacion/administrar/institucional/ajax/eliminar/institucional", "UPanelControladorContenidoInstitucional@ajax_institucional_eliminarInstitucional");
    /* Encuesta - ELIMINAR */Route::post("aplicacion/administrar/encuesta/ajax/eliminar/encuesta", "UPanelControladorContenidoEncuestas@ajax_eliminar_encuesta");

    Route::post("aplicacion/administrar/noticias/ajax/subir/imagen", "UPanelControladorContenidoNoticias@ajax_noticias_subirImagen");
    Route::post("aplicacion/administrar/noticias/ajax/eliminar/imagen", "UPanelControladorContenidoNoticias@ajax_noticias_eliminarImagen");
});


//******************************************************************************
//******************************************************************************
// RUTAS DE CONEXIÓN DE LA APP AL SERVIDOR WEBOX*********************************
//******************************************************************************
//******************************************************************************
//Las apps se conectan para obtener informacion
Route::post("app/conectar", "ControladorApp@conectar");
//Las apps se conectan para cargar imagenes
Route::get("usuarios/uploads/{usuario}/{imagen}/{mime_type}", "ControladorApp@cargarImagen");
