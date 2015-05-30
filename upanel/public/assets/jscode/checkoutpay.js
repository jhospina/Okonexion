var id_form = "#CCForm";
var btn_pagar = "#btn-pagar";
// Called when token created successfully.
var successCallback = function (data) {

    // Set the token as the value for the token input
    $("#token").val(data.response.token.token);
    $("#modal-proceso").modal("show");
    // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
    actualizarDatos();
};
// Called when token creation fails.
var errorCallback = function (data) {
    if (data.errorCode === 200) {
        tokenRequest();
    } else {
        jQuery(btn_pagar).removeAttr("disabled");
        jQuery(btn_pagar).html(btn_msj_inicial);
        if (data.errorCode == 401) {
            $("#msj-error-tc").show();
            $("#msj-error-tc").html(msj_error_tc_invalido);
        } else {
            $("#msj-error-tc").show();
            $("#msj-error-tc").html(msj_error_tc_descon);
        }
    }
};
var tokenRequest = function () {
    // Setup token request arguments
    var args = {
        sellerId: idSeller,
        publishableKey: publicKeyUser,
        ccNo: $("#ccNo").val(),
        cvv: $("#cvv").val(),
        expMonth: $("#expMonth").val(),
        expYear: $("#expYear").val()
    };
    // Make the token request
    TCO.requestToken(successCallback, errorCallback, args);
};
$(function () {
    // Pull in the public encryption key for our environment
    if (sandbox)
        TCO.loadPubKey('sandbox');
    $(btn_pagar).click(function (e) {

        $("#msj-error-fact").hide();
        $("#msj-error-tc").hide();
        // Call our token request function
        if (verificar()) {
            if (verificarTC()) {
                jQuery(btn_pagar).attr("disabled", "disabled");
                jQuery(btn_pagar).html(btn_msj_verificando);
                tokenRequest();
            }
            else
                $("#msj-error-tc").show();
            $("#msj-error-tc").html(msj_error_tc);
        }
        else {
            $("#msj-error-fact").show();
        }

        // Prevent form from submitting
        return false;
    });
});
//Verifica la información de facturacion del usuario
function verificar() {

    var val = true;
    if ($("#dni").val().length < 3) {
        val = false;
        $("#dni").focus();
    }
    if ($("#empresa").val().length < 3) {
        val = false;
        $("#empresa").focus();
    }
    if ($("#pais")[0].selectedIndex == 0) {
        val = false;
        $("#pais").focus();
    }

    if ($("#region").val().length < 3) {
        val = false;
        $("#region").focus();
    }

    if ($("#ciudad").val().length < 3) {
        val = false;
        $("#ciudad").focus();
    }
    if ($("#direccion").val().length < 5) {
        val = false;
        $("#direccion").focus();
    }

    if ($("#telefono").val().length < 6) {
        val = false;
        $("#telefono").focus();
    }

    return val;
}

//Verifica los datos de la TC
function verificarTC() {

    var val = true;
    if ($("#cvv").val().length < 2) {
        val = false;
        $("#cvv").focus();
    }


    if ($("#mes-exp")[0].selectedIndex == 0) {
        val = false;
        $("#mes-exp").focus();
    }

    if ($("#ano-exp")[0].selectedIndex == 0) {
        val = false;
        $("#ano-exp").focus();
    }

    if ($("#ccNo").val().length < 10) {
        val = false;
        $("#ccNo").focus();
    }

    return val;
}


//Actualiza los datos del usuario via Ajax y envia el formulario para procesar el pago
function actualizarDatos() {
    jQuery.ajax({
        type: "POST",
        url: url_postInfo,
        data: {
            dni: $("#dni").val(),
            empresa: $("#empresa").val(),
            pais: $("#pais").val(),
            region: $("#region").val(),
            ciudad: $("#ciudad").val(),
            direccion: $("#direccion").val(),
            telefono: $("#telefono").val()},
        success: function (data) {
            jQuery(btn_pagar).html(btn_msj_procesando);
            $(id_form).submit();
        }}, "html");
}


$("#mes-exp").change(function () {
    $("#expMonth").val($(this).val());
});
$("#ano-exp").change(function () {
    $("#expYear").val($(this).val());
});




