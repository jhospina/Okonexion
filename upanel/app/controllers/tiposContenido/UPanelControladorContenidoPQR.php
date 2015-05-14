<?php

class UPanelControladorContenidoPQR extends Controller {

    public function index() {
        
        if (!Auth::check()){
            return User::login();
        }
        
        if (!Aplicacion::existe())
            return Redirect::to("/");
        $app = Aplicacion::obtener();
        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        $peticiones = Contenido_PQR::obtenerPQR(Contenido_PQR::tipo(Contenido_PQR::nombre_peticion));
        $quejas = Contenido_PQR::obtenerPQR(Contenido_PQR::tipo(Contenido_PQR::nombre_queja));
        $reclamos = Contenido_PQR::obtenerPQR(Contenido_PQR::tipo(Contenido_PQR::nombre_reclamo));
        $sugerencias = Contenido_PQR::obtenerPQR(Contenido_PQR::tipo(Contenido_PQR::nombre_sugerencia));

        return View::make("usuarios/tipo/regular/app/administracion/pqr/index")->with("app", $app)
                        ->with("peticiones", $peticiones)
                        ->with("quejas", $quejas)
                        ->with("reclamos", $reclamos)
                        ->with("sugerencias", $sugerencias);
    }

    public function vista_revisar($id_pqr) {
        
        if (!Auth::check()){
            return User::login();
        }
        
        if (!Aplicacion::existe())
            return Redirect::to("/");
        $app = Aplicacion::obtener();
        if (!Aplicacion::estaTerminada($app->estado))
            return Redirect::to("/");

        $pqr = ContenidoApp::find($id_pqr);
        $discusion = Contenido_PQR::obtenerDiscusion($id_pqr);
        return View::make("usuarios/tipo/regular/app/administracion/pqr/revisar")->with("app", $app)->with("pqr", $pqr)->with("discusion", $discusion);
    }

    public function enviar_respuesta() {
        if (!Auth::check()){
            return User::login();
        }
        
        $data = Input::all();

        $pqrOr = ContenidoApp::find($data["id_pqr"]);

        $pqr = new ContenidoApp;
        $pqr->id_aplicacion = $pqrOr->id;
        $pqr->id_usuario = $pqrOr->id_usuario;
        $pqr->titulo = $pqrOr->titulo;
        $pqr->contenido = $data["respuesta"];
        $pqr->tipo = $pqrOr->tipo;
        $pqr->estado = ContenidoApp::ESTADO_PUBLICO;
        $pqr->contenido_padre = $pqrOr->id;
        

                
        
        $pqr->save();
        
        $app=Aplicacion::obtener();

        ContenidoApp::agregarMetaDato($pqr->id, Contenido_PQR::configUsuario, Auth::user()->id, Auth::user()->id);
   
        /**
         * ENVIA UN CORREO NOTFICANDO AL USUARIO DE LA REPSUESTA DE SU PQR
         */
        
        $email=new Correo();
        $email->enviarUsuarioApp($app->nombre,Contenido_PQR::cliente_obtenerEmail($pqrOr->id),
                trans("email.asunto.tu_pqr_respondido",array("tipo_pqr"=> Contenido_PQR::obtenerNombreTipo($pqrOr->tipo),"num"=>$data["id_pqr"])), 
                trans("email.pqr.respuesta",array("nombre"=>Contenido_PQR::cliente_obtenerNombre($pqrOr->id),"num"=>$pqrOr->id,"tipo_pqr"=>Contenido_PQR::obtenerNombreTipo($pqrOr->tipo),"app"=>$app->nombre,"mensaje"=>$data["respuesta"])));
        
        return Redirect::to("aplicacion/administrar/pqr/".$data["id_pqr"]."/revisar/")->with(User::mensaje("exito", null, "¡" . trans("otros.info.mensaje_enviado")."!", 2));
    }

}
