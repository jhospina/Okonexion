<?php

class MetPayU {

    const pais = "CO";
    //CODIGOS DE METODOS DE PAGO;
    const METODO_PAGO_PSE = 254;
    const METODO_PAGO_ACH_DEBIT = 26;
    const METODO_PAGO_DINERS = 253;
    const METODO_PAGO_MASTERCARD = 251;
    const METODO_PAGO_AMEX = 252;
    const METODO_PAGO_EFECTY = 37;
    const METODO_PAGO_BALOTO = 35;
    const METODO_PAGO_VISA = 250;
    const METODO_PAGO_CASH_ON_DELIVERY = 27;
    const METODO_PAGO_BANK_REFERENCED = 36;
    //***************************************
    //TIPOS DE DOCUMENTOS
    //***************************************
    const TIPO_DOCUMENTO_CEDULA_CIUDADANIA = "CC";
    const TIPO_DOCUMENTO_CEDULA_EXTRAJERA = "CE";
    const TIPO_DOCUMENTO_NIT_EMPRESA = "NIT";
    const TIPO_DOCUMENTO_PASAPORTE = "PP";
    const TIPO_DOCUMENTO_TARJETA_DE_IDENTIDAD = "TI";
    const TIPO_DOCUMENTO_ID_UNICO_CLIENTE = "IDC";
    const TIPO_DOCUMENTO_NUMERO_MOVIL = "CEL";
    const TIPO_DOCUMENTO_IDENTIFICACION_EXTRANJETA = "DE";
    // UTIL -------
    const PREFIJO_REF = "test";
    const URL_RESPUESTA_TBANCARIA = "";

    var $account_id;

    function __construct() {
        PayU::$apiKey = Instancia::obtenerValorMetadato(ConfigInstancia::fact_payu_apiKey);
        PayU::$apiLogin = Instancia::obtenerValorMetadato(ConfigInstancia::fact_payu_apiLogin);
        PayU::$merchantId = Instancia::obtenerValorMetadato(ConfigInstancia::fact_payu_merchantId);
        PayU::$language = Idioma::actual();
        PayU::$isTest = Util::convertirIntToBoolean(Instancia::obtenerValorMetadato(ConfigInstancia::fact_payu_sandbox));
        $this->account_id = Instancia::obtenerValorMetadato(ConfigInstancia::fact_payu_accountId);

        Environment::setPaymentsCustomUrl("https://stg.api.payulatam.com/payments-api/4.0/service.cgi");
        Environment::setReportsCustomUrl("https://stg.api.payulatam.com/reports-api/4.0/service.cgi");
        Environment::setSubscriptionsCustomUrl("https://stg.api.payulatam.com/payments-api/rest/v4.3/");
    }

    /** Obtiene un array con codigos de identificación de cada uno de los metodos pagos disponibles
     * 
     * @return type
     */
    function obtenerMetodosPagosActivos() {
        try {
            $mets = array();
            $array = PayUPayments::getPaymentMethods();
            $payment_methods = $array->paymentMethods;
            foreach ($payment_methods as $payment_method) {
                if ($payment_method->country == self::pais) {
                    $mets[] = $payment_method->id;
                }
            }
        } catch (PayUException $e) {
            return array();
        }
        return $mets;
    }

    function obtenerBancosActivos() {
        try {
            //Ingrese aquí el nombre del medio de pago
            $parameters = array(
                PayUParameters::ACCOUNT_ID => $this->account_id,
                PayUParameters::PAYMENT_METHOD => PaymentMethods::PSE,
                PayUParameters::COUNTRY => self::pais,
            );
            $array = PayUPayments::getPSEBanks($parameters);
        } catch (PayUException $e) {
            return array();
        }

        return $array->banks;
    }

    /** Procesa el pago por tarjeta de credito
     * 
     * @param array $parameters
     * @param type $factura
     * @return type
     */
    function procesarPagoTCredito($parameters, $factura) {
        $parameters[PayUParameters::ACCOUNT_ID] = $this->account_id;
        try {
            $response = PayUPayments::doAuthorizationAndCapture($parameters);
            if ($response) {
                if ($response->transactionResponse->state == MetPayUTransactionCode::APPROVED) {
                    //Datos de transaccion
                    $infoValidacion = array(
                        MetaFacturacion::METODO_PAGO => $parameters[PayUParameters::PAYMENT_METHOD],
                        MetaFacturacion::TRANSACCION_ID => $response->transactionResponse->transactionId,
                        MetaFacturacion::TRANSACCION_ID_ORDEN => $response->transactionResponse->orderId,
                        MetaFacturacion::TRANSACCION_CODIGO_TRAZABILIDAD => $response->transactionResponse->trazabilityCode,
                        MetaFacturacion::TRANSACCION_CODIGO_AUTORIZACION => $response->transactionResponse->authorizationCode,
                        MetaFacturacion::TRANSACCION_FECHA_OPERACION => $response->transactionResponse->operationDate);

                    Facturacion::validarPago($factura, $infoValidacion, Facturacion::GATEWAY_PAYU);
                    return Redirect::to("fact/mis-facturas")->with(User::mensaje("exito", null, trans("fact.orden.pago.msj.exito", array("num" => $factura->id)), 2));
                } else {
                    return Redirect::back()->with(User::mensaje("error", null, $this->getDescripcionRespuestaTransaccion($response->transactionResponse->responseCode), 2));
                }
            } else {
                return Redirect::back()->with(User::mensaje("advertencia", null, trans("fact.payu.error.exception"), 2));
            }
        } catch (PayUException $e) {
            if ($e->payUCode == PayUErrorCodes::JSON_DESERIALIZATION_ERROR)
                return Redirect::back()->with(User::mensaje("advertencia", null, trans("fact.payu.error.exception"), 2));
            if ($e->payUCode == PayUErrorCodes::API_ERROR)
                return Redirect::back()->with(User::mensaje("error", null, $e->getMessage(), 2));
            if ($e->payUCode == PayUErrorCodes::INVALID_PARAMETERS)
                return Redirect::back()->with(User::mensaje("advertencia", null, trans("fact.payu.error.exception"), 2));
            return Redirect::back()->with(User::mensaje("advertencia", null, trans("fact.orden.pago.informacion.tc.error.invalido"), 2));
        }
    }

    function procesarPagoTBancaria($parameters, $factura) {
        $parameters[PayUParameters::ACCOUNT_ID] = $this->account_id;
        //Página de respuesta a la cual será redirigido el pagador.     
        $parameters[PayUParameters::RESPONSE_URL] = "http://www.test.com/response";
        try {
            $response = PayUPayments::doAuthorizationAndCapture($parameters);
            print_r($response);
            exit();
            if ($response) {

                if ($response->transactionResponse->state)
                    if ($response->transactionResponse->state == "PENDING") {
                        $response->transactionResponse->pendingReason;
                        $response->transactionResponse->extraParameters->BANK_URL;
                        
                        //COMPLETAR UNA VEZ QUE LA EMPRESA ESTE LEGALMENTE CONSTITUIDA Y SE TENGA UNA CUENTA DE PAYU ACTIVA
                        //**************************************************************************
                        //**************************************************************************
                        //**************************************************************************
                        //**************************************************************************
                        //**************************************************************************
                        //**************************************************************************
                        //**************************************************************************
                        //**************************************************************************
                        //**************************************************************************
                        //**************************************************************************
                        
                        
                        
                    }
                $response->transactionResponse->responseCode;
            }
        } catch (PayUException $e) {
            if ($e->payUCode == PayUErrorCodes::JSON_DESERIALIZATION_ERROR)
                return Redirect::back()->with(User::mensaje("advertencia", null, trans("fact.payu.error.exception"), 2));
            if ($e->payUCode == PayUErrorCodes::API_ERROR)
                return Redirect::back()->with(User::mensaje("error", null, $e->getMessage(), 2));
            if ($e->payUCode == PayUErrorCodes::INVALID_PARAMETERS)
                return Redirect::back()->with(User::mensaje("advertencia", null, trans("fact.payu.error.exception"), 2));
            return Redirect::back()->with(User::mensaje("advertencia", null, trans("fact.orden.pago.informacion.tc.error.invalido"), 2));
        }
    }

    /* Obtiene un array con los tipos de documentos permitidos
     * 
     * @return type
     */

    static function getTiposDocumentos() {
        $class = new ReflectionClass(__CLASS__);
        $docs = array();
        foreach ($class->getConstants() as $index => $value) {
            if (strpos($index, "TIPO_DOCUMENTO_") !== false) {
                $docs[$index] = $value;
            }
        }
        return $docs;
    }

    function getDescripcionRespuestaTransaccion($codigo) {
        return trans("payu.error.trans" . $codigo);
    }

    static function getDescripcionTipoDocumento($tipo) {
        return trans("payu.codigo.tipo.documento." . $tipo);
    }

}
