
// Called when token created successfully.
var successCallback = function (data) {

    // Set the token as the value for the token input
    $("#token").val(data.response.token.token);
    $("#modal-proceso").modal({show: true, backdrop: "static"});
    // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
    actualizarDatos(id_form,btn_pagar);
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
            else {
                $("#msj-error-tc").show();
                $("#msj-error-tc").html(msj_error_tc);
            }
        }
        else {
            $("#msj-error-fact").show();
        }

        // Prevent form from submitting
        return false;
    });
    
    
    
$("#mes-exp").change(function () {
    $("#expMonth").val($(this).val());
});
$("#ano-exp").change(function () {
    $("#expYear").val($(this).val());
});

    
});
