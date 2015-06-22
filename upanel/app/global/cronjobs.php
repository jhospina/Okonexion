<?php

Event::listen('cron.collectJobs', function() {
    /**
     * CRON: Creación de facturas
     * DESCRIPCION: Creación de facturas anticipadamente para los usuarios suscriptos, la factura se crea con 10 dias de antelacion.
     * EJECUCION: Cada dia
     */
    Cron::add('userGenFacts', '0 0 * * *', function() {
//Obtiene un array de objetos con todos los usuarios de tipo regular
        $usuarios = User::where("tipo", User::USUARIO_REGULAR)->get();

        $prefijo = "suscripcion_valor_";

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
                "link_factura" => "https://appsthergo.com/upanel/public/fact/factura/" . $id_factura
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
            Notificacion::crear(Notificacion::TIPO_FACTURACION_AUTO_SUSCRIPCION, $usuario->id, URL::to("fact/factura/" . $id_factura));
        }

        return "Facturas generadas automaticamente";
    });
});


//$report=Cron::run();

//Cron::setDisablePreventOverlapping(); 

//print_r($report);
//exit();
//Cron::setDatabaseLogging(true);
