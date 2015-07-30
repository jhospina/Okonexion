<?php

Event::listen('cron.collectJobs', function() {
    /**
     * CRON: Creación de facturas
     * DESCRIPCION: Creación de facturas anticipadamente para los usuarios suscriptos, la factura se crea con 10 dias de antelacion.
     * EJECUCION: Una vez al dia
     */
    Cron::add('userGenFacts', '0 0 * * *', function() {
//Obtiene un array de objetos con todos los usuarios de tipo regular
        $usuarios = User::where("tipo", User::USUARIO_REGULAR)->get();

        $prefijo = "suscripcion_valor_";

        $n = 0;
        $ids = array();

        foreach ($usuarios as $usuario) {

            $moneda = Instancia::obtenerValorMetadato(ConfigInstancia::info_moneda, $usuario->instancia);
            $indicadorAuto = Util::convertirIntToBoolean(User::obtenerValorMetadato(UsuarioMetadato::FACTURACION_GEN_AUTO_SUSCRIPCION, $usuario->id));

            //El usuario debe tener la suscricion vigente
            if ($usuario->estado != User::ESTADO_SUSCRIPCION_VIGENTE)
                continue;

            //Ha todos los usuarios que le queden menos de 10 dias de suscripcion
            if (Fecha::difSec(Util::obtenerTiempoActual(), $usuario->fin_suscripcion) > (60 * 60 * 24 * 10))
                continue;

            if ($indicadorAuto || is_null($indicadorAuto))
                continue;

            //Obtiene los datos de suscripcion del usuario
            $tipo_suscripcion = User::obtenerValorMetadato(UsuarioMetadato::SUSCRIPCION_TIPO, $usuario->id);
            $ciclo_suscripcion = User::obtenerValorMetadato(UsuarioMetadato::SUSCRIPCION_CICLO, $usuario->id);

            //Obtiene el descuento aplicado de la instancia
            if (is_null($descuento = Instancia::obtenerValorMetadato($prefijo . $ciclo_suscripcion . "mes_descuento", $usuario->instancia)))
                $descuento = 0;


            //Genera la nueva factura
            $id_factura = Facturacion::nueva(Instancia::obtenerValorMetadato(ConfigInstancia::fact_impuestos_iva, $usuario->instancia), 10, $usuario->id);
            Facturacion::generarJSONCliente($id_factura, $usuario->id);

            $ids[] = $id_factura;

            Facturacion::agregarMetadato(MetaFacturacion::MONEDA_ID, $moneda, $id_factura, $usuario->id);


            $data_producto[MetaFacturacion::PRODUCTO_ID] = $usuario->obtenerProductoIdSuscripcionVigente();
            $data_producto[MetaFacturacion::PRODUCTO_DESCUENTO] = $descuento;
            $data_producto[MetaFacturacion::PRODUCTO_VALOR] = Instancia::obtenerValorMetadato($prefijo . $ciclo_suscripcion . "mes_" . $tipo_suscripcion . "-" . $moneda, $usuario->instancia);


            //Indica que al usuario ya se le ha generado la factura por suscripcion
            User::actualizarMetadato(UsuarioMetadato::FACTURACION_GEN_AUTO_SUSCRIPCION, Util::convertirBooleanToInt(true), $usuario->id);
            //Agrega el producto de suscripcion a la factura con lo datos que lo definen
            Facturacion::agregarProducto($id_factura, $data_producto, $usuario->id);


            $factura = Facturacion::find($id_factura);


            $mensaje = trans("email.mensaje.factura.generada", array(
                "nombre" => $usuario->nombres,
                "fecha" => Fecha::formatear(Util::obtenerTiempoActual()),
                "id_factura" => $id_factura,
                "total_factura" => Monedas::nomenclatura($moneda, Monedas::formatearNumero($moneda, $factura->total)),
                "venc_factura" => $factura->fecha_vencimiento,
                "link_factura" => trans("otros.link_upanel") . "fact/factura/" . $id_factura
            ));


            //Genera un PDF de la factura: Desactivado -> Error desconocido
            /*
              $path = 'usuarios/uploads/' . $usuario->id . "/";
              $archivo = trans("otros.info.factura") . $factura->id . ".pdf";

              $pdfPath = $path . $archivo;


              File::put($pdfPath, PDF::load(Facturacion::pdfHtmlFactura($factura), 'A4', 'portrait')->output());
             */

            //Envia un correo al usuario con la información de la factura
            $correo = new Correo();
            $correo->enviar(trans("email.asunto.factura.generada"), $mensaje, $usuario->id);

            //Crea una notificacion para el usuario
            Notificacion::crear(Notificacion::TIPO_FACTURACION_AUTO_SUSCRIPCION, $usuario->id, trans("otros.link_upanel") . "fact/factura/" . $id_factura);

            $n++;
        }

        return "$n facturas generadas [" . Util::formatearResultadosArray($ids, "|", "(", ")") . "]";
    });

    /**
     * CRON: Vencimiento de facturas
     * DESCRIPCION: Establece como vencidas todas las facturas sin pagar que su fecha de vencimiento hayan culminado
     * EJECUCION: Una vez al dia
     */
    Cron::add("factVencimiento", "0 0 * * *", function() {

        $facturas = Facturacion::where("estado", Facturacion::ESTADO_SIN_PAGAR)->get();

        $n = 0;
        $ids = array();
        foreach ($facturas as $factura) {

            if (Fecha::difSec(Util::obtenerTiempoActual(), $factura->fecha_vencimiento) < 0) {
                $factura->estado = Facturacion::ESTADO_VENCIDA;
                $factura->save();
                $ids[] = $factura->id;
            }
        }

        return "$n facturas vencidas [" . Util::formatearResultadosArray($ids, "|", "(", ")") . "]";
    });

    /**
     * CRON: Seguimiento de suscripcion
     * DESCRIPCION: Notifica al usuario cuando suscripcion esta proxima a vencer
     * EJECUCION: Una vez al dia
     */
    Cron::add("userSegSusc", "0 0 * * *", function() {

        $usuarios = User::where("tipo", User::USUARIO_REGULAR)->get();

        $n = 0;
        $ids = array();

        foreach ($usuarios as $usuario) {

            if (Fecha::difSec(Util::obtenerTiempoActual(), $usuario->fin_suscripcion) > (60 * 60 * 24 * 3))
                continue;

            if ($usuario->estado == User::ESTADO_PERIODO_PRUEBA) {

                //Si la suscripciòn de prueba se ha vencido
                if (!Util::calcularDiferenciaFechas(Util::obtenerTiempoActual(), $usuario->fin_suscripcion) && $usuario->estado == User::ESTADO_PERIODO_PRUEBA) {

                    $n++;
                    $ids[] = $usuario->id;

                    $usuario->estado = User::ESTADO_PRUEBA_FINALIZADA;
                    $usuario->save();
                    Notificacion::crear(Notificacion::TIPO_SUSCRIPCION_PRUEBA_FINALIZADA, $usuario->id);

                    $correo = new Correo();

                    $mensaje = trans("email.mensaje.aviso.prueba.finalizada", array("nombre" => $usuario->nombres,
                        "link" => trans("otros.link_upanel") . "fact/suscripcion/plan"));

                    $correo->enviar(trans("email.asunto.aviso.prueba.finalizada"), $mensaje, $usuario->id);

                    continue;
                }

                $n++;

                $ids[] = $usuario->id;

                Notificacion::crear(Notificacion::TIPO_PRUEBA_AVISO_CADUCIDAD, $usuario->id);

                $correo = new Correo();

                $mensaje = trans("email.mensaje.aviso.caducidad.prueba", array("nombre" => $usuario->nombres,
                    "tiempo" => Fecha::calcularDiferencia(Util::obtenerTiempoActual(), $usuario->fin_suscripcion),
                    "link" => trans("otros.link_upanel") . "fact/suscripcion/plan"));

                $correo->enviar(trans("email.asunto.aviso.caducidad.prueba"), $mensaje, $usuario->id);
            }



            if ($usuario->estado == User::ESTADO_SUSCRIPCION_VIGENTE) {

                //Si la suscripciòn de prueba se ha vencido
                if (!Util::calcularDiferenciaFechas(Util::obtenerTiempoActual(), $usuario->fin_suscripcion) && $usuario->estado == User::ESTADO_SUSCRIPCION_VIGENTE) {

                    $n++;
                    $ids[] = $usuario->id;

                    $usuario->estado = User::ESTADO_SUSCRIPCION_CADUCADA;
                    $usuario->save();
                    Notificacion::crear(Notificacion::TIPO_SUSCRIPCION_CADUCADA, $usuario->id);

                    $correo = new Correo();

                    $mensaje = trans("email.mensaje.aviso.suscripcion.finalizada", array("nombre" => $usuario->nombres,
                        "link" => trans("otros.link_upanel") . "fact/suscripcion/plan"));

                    $correo->enviar(trans("email.asunto.aviso.suscripcion.finalizada"), $mensaje, $usuario->id);

                    continue;
                }

                $n++;
                $ids[] = $usuario->id;
                Notificacion::crear(Notificacion::TIPO_SUSCRIPCION_AVISO_CADUCIDAD, $usuario->id);

                $correo = new Correo();

                $mensaje = trans("email.mensaje.aviso.caducidad.suscripcion", array("nombre" => $usuario->nombres,
                    "tiempo" => Fecha::calcularDiferencia(Util::obtenerTiempoActual(), $usuario->fin_suscripcion),
                    "link" => trans("otros.link_upanel") . "fact/suscripcion/plan"));

                $correo->enviar(trans("email.asunto.aviso.caducidad.suscripcion"), $mensaje, $usuario->id);
            }
        }

        return "$n usuarios informados [" . Util::formatearResultadosArray($ids, "|", "(", ")") . "]";
    });

    /**
     * CRON: Actualización de uso de espacio en disco del usuario
     * DESCRIPCION: Analiza y almacena el uso de espacio en disco de cada usuario
     * EJECUCION: Una vez al dia
     */
    Cron::add("userUsoDisco", "0 0 * * *", function() {

        $usuarios = User::where("tipo", User::USUARIO_REGULAR)->get();

        foreach ($usuarios as $usuario) {

            $pesoTotal = 0;

            //**************************************
            // VERIFICA Y OBTIENE EL PESO EN DISCO DE LA BASE DE DATOS USADA POR EL USUARIO
            //**************************************
            $pesoTotal += strlen(serialize($usuario));
            $pesoTotal += strlen(serialize(UsuarioMetadato::where("id_usuario", $usuario->id)->get()));
            $pesoTotal += strlen(serialize(AppMeta::where("id_usuario", $usuario->id)->get()));
            $pesoTotal += strlen(serialize(MetaContenidoApp::where("id_usuario", $usuario->id)->get()));
            $pesoTotal += strlen(serialize(ContenidoApp::where("id_usuario", $usuario->id)->get()));
            $pesoTotal += strlen(serialize(Aplicacion::where("id_usuario", $usuario->id)->get()));

            //Obtiene el peso de los archivos del usuario
            if (file_exists(public_path("usuarios/uploads/" . $usuario->id))) {
                $pesoTotal+=ArchivosCTR::obtenerTamanoDirectorio(public_path("usuarios/uploads/" . $usuario->id));
            }

            User::actualizarMetadato(UsuarioMetadato::ESPACIO_DISCO_UTILIZADO, $pesoTotal, $usuario->id);
        }
    });
});


//$report=Cron::run();

//Cron::setDisablePreventOverlapping(); 

//print_r($report);
//exit();
//Cron::setDatabaseLogging(true);
