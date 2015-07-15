function formatearNumero(numero, separadorMillar, separadorDecimal) {

    numero += "";

    // Variable que contendra el resultado final
    var resultado = "";

    // Si el numero empieza por el valor "-" (numero negativo)
    if (numero[0] == "-")
    {
        // Cogemos el numero eliminando los posibles puntos que tenga, y sin
        // el signo negativo
        if (separadorMillar == ",")
            nuevoNumero = numero.replace(/\,/g, '').substring(1);
        else
            nuevoNumero = numero.replace(/\./g, '').substring(1);
    } else {
        // Cogemos el numero eliminando los posibles puntos que tenga
        if (separadorMillar == ",")
            nuevoNumero = numero.replace(/\,/g, '');
        else
            nuevoNumero = numero.replace(/\./g, '');

    }

    // Si tiene decimales, se los quitamos al numero
    if (numero.indexOf(separadorDecimal) >= 0)
        nuevoNumero = nuevoNumero.substring(0, nuevoNumero.indexOf(separadorDecimal));

    // Ponemos un punto cada 3 caracteres
    for (var j, i = nuevoNumero.length - 1, j = 0; i >= 0; i--, j++)
        resultado = nuevoNumero.charAt(i) + ((j > 0) && (j % 3 == 0) ? separadorMillar : "") + resultado;

    // Si tiene decimales, se lo añadimos al numero una vez forateado con
    // los separadores de miles
    if (numero.indexOf(separadorDecimal) >= 0)
        resultado += numero.substring(numero.indexOf(separadorDecimal));

    if (numero[0] == "-")
    {
        // Devolvemos el valor añadiendo al inicio el signo negativo
        return "-" + resultado;
    } else {
        return resultado;
    }
}


function soloNumeros(e, decimal) {
    var keynum = window.event ? window.event.keyCode : e.which;
    tecla = String.fromCharCode(keynum).toLowerCase();
    letras = "0123456789" + decimal;

    if (window.event.keyCode == 8 || (window.event.keyCode >= 96 && window.event.keyCode <= 105))
        return true;
    if (letras.indexOf(tecla) == -1) {
        return false;
    }
}

function validarEmail(email) {
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return (expr.test(email))
}
