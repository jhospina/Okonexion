$(function () {
//PAGOS POR TARJETA DE CREDITO
    $(btn_pagar + "-tcredito").click(function () {
        if (verificar()) {
            if (verificarTC()) {
                if (verificarInfoPagador())
                {
                    jQuery(btn_pagar + "-tcredito").attr("disabled", "disabled");
                    jQuery(btn_pagar + "-tcredito").html(btn_msj_verificando);
                    $("#modal-proceso").modal({show: true, backdrop: "static"});
                    actualizarDatos(id_form + "-tcredito", btn_pagar + "-tcredito");
                } else {
                    $("#msj-error-tc").show();
                    $("#msj-error-tc").html(msj_error_infoPagador);
                }
            }
            else {
                $("#msj-error-tc").show();
                $("#msj-error-tc").html(msj_error_tc);
            }
        } else {
            $("#msj-error-fact").show();
        }
    });

//PAGOS POR PSE
    $(btn_pagar + "-pse").click(function () {
        $("#msj-error-fact").hide();
        $("#msj-error-tc").hide();
        // Call our token request function
        if (verificar()) {
            if (verificarDatosPSE())
            {
                alert("SISTEMA DE PAGOS PSE AUN NO IMPLEMENTADO");
                return;
                jQuery(btn_pagar).attr("disabled", "disabled");
                jQuery(btn_pagar).html(btn_msj_verificando);
                $("#modal-proceso").modal({show: true, backdrop: "static"});
                actualizarDatos(id_form + "-pse", btn_pagar + "-pse");
            } else {
                $("#msj-error-pse").show();
                $("#msj-error-pse").html(msj_error_pse);
            }
        }
        else {
            $("#msj-error-fact").show();
        }
    });

});




function verificarInfoPagador() {
    if ($("#check_datos_fact").val() == "1")
        return true;

    var val = true;
    if ($("#cp_dni").val().length < 3) {
        val = false;
        $("#cp_dni").focus();
    }
    if ($("#cp_nombre").val().length < 3) {
        val = false;
        $("#cp_nombre").focus();
    }

    if ($("#cp_email").val().length < 3 || !validarEmail($("#cp_email").val())) {
        val = false;
        $("#cp_email").focus();
    }

    if ($("#cp_region").val().length < 3) {
        val = false;
        $("#cp_region").focus();
    }

    if ($("#cp_ciudad").val().length < 3) {
        val = false;
        $("#cp_ciudad").focus();
    }
    if ($("#cp_direccion").val().length < 5) {
        val = false;
        $("#cp_direccion").focus();
    }

    if ($("#cp_telefono").val().length < 6) {
        val = false;
        $("#cp_telefono").focus();
    }

    return val;

}


function verificarDatosPSE() {
    var val = true;
    if ($("#pse_numDoc").val().length < 3) {
        val = false;
        $("#pse_numDoc").focus();
    }
    if ($("#pse_nombre").val().length < 3) {
        val = false;
        $("#pse_nombre").focus();
    }

    if ($("#pse_email").val().length < 3 || !validarEmail($("#pse_email").val())) {
        val = false;
        $("#pse_email").focus();
    }

    if ($("#pse_telefono").val().length < 3) {
        val = false;
        $("#pse_telefono").focus();
    }

    return val;
}