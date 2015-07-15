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

    if ($("#ccNo").val().length < 13) {
        val = false;
        $("#ccNo").focus();
    }

    return val;
}


//Actualiza los datos del usuario via Ajax y envia el formulario para procesar el pago
function actualizarDatos(id_form, btn_pagar) {
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




