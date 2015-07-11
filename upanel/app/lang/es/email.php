<?php

return array(
    "hola" => "Hola :nombre",
    "no_responder" => "¡NO RESPONDAS ESTE CORREO!",
    "activacion.msj_01" => "Nos has pedido que enviemos nuevamente el enlace de activación de tu cuenta. Para activar tu cuenta en Appthergo.com haz clic <a href='" . URL::to(""). "activar/:id_usuario/:codigo'>aquí</a> o tambien puedes copiar y pegar el siguiente enlace",
    "bienvenida.titular" => "¡BIENVENIDO A APPSTHERGO.COM!",
    "confirmacion.msj" => "Bienvenido a Appsthergo.com, gracias por registrarte. Tu nueva cuenta ha sido creada satisfactoriamente, pero necesita ser activada.</br></br> Para activar tu nueva cuenta en Appsthergo.com haz clic <a href='" . URL::to(""). "activar/:id_usuario/:codigo'>aquí</a> o tambien puedes copiar y pegar el siguiente enlace:",
    "recuperacion.msj_01" => "Hemos recibido una solicitud para restablecer tu contraseña para ingresar en el panel de usuarios de Appthergo.com. Si no has solicitado esto por favor ignora este mensaje.",
    "recuperacion.msj_02" => "Para reestablecer tu contraseña ingresa en el siguiente enlace o copialo y pegalo en la URL del navegador:",
    "soporte.tickets.crear" => "<p>Estimado :nombre</p>" .
    "<p>Gracias por contactar con nuestro equipo de soporte. Un ticket de soporte ha sido abierto por tu solicitud. Serás notificado por email cuando se haga una respuesta. Los detalles de tu ticket se muestran acontinuación:</p>" .
    "<p>-------------------------------------<br/>" .
    "<b>Ticket ID:</b> :id_ticket<br/>" .
    "<b>Tipo de soporte:</b> :tipo_ticket<br/>" .
    "<b>Asunto:</b> :asunto_ticket<br/>" .
    "<b>Estado:</b> :estado_ticket<br/>" .
    "<b>Fecha y hora de apertura:</b> :fecha_apertura<br/>" .
    "<b>Responder Ticket en:</b> :link<br/>" .
    "-------------------------------------</p>",
    "soporte.ticket.respondido" => "<p>Estimado :nombre</p>" .
    "<p>Tu ticket ha sido respondido por nuestro equipo de soporte: <br/>" .
    "------------------------------------------------------------<br/>" .
    ":mensaje <br/>" .
    "------------------------------------------------------------" . "</p>" .
    "<p>Para responder este ticket ingresa a :link</p>",
    "app.dep.aplicacion_en_desarrollo" => "<p>Hola :nombre</p>" .
    "<p>Tenemos noticias para ti. Tu aplicación <b>:app_nombre</b> ha empezado su fase de desarrollo y muy pronto estará disponible para que empieces a utilizarla. Te avisaremos cuando la fase de desarrollo haya concluido y tu aplicación esté terminada.</p>" .
    "<p><b>Hora de inicio: </b>:fecha_inicio</p>",
    "app.dep.aplicacion_terminada" => "<p>Hola :nombre</p>" .
    "<p>Tenemos muy buenas noticias para ti. Tu aplicación <b>:app_nombre</b> ha finalizado su fase de desarrollo y ya se encuentra disponible para que lo descargues, lo distribuyas y lo utilices a tu gusto.</p>" .
    "<p><b>Hora de inicio: </b>:fecha_inicio</p>" .
    "<p><b>Hora de finalización: </b>:fecha_finalizacion</p></br>" .
    "<p>Ingresa a :link para descargarlo.</p>" .
    "<p>:plataformas</p>",
    "asunto.confirmacion" => "Confirmación de correo electrónico",
    "asunto.activacion" => "Activación de tu cuenta",
    "asunto.bienvenida" => "¡Bienvenido a Appthergo!",
    "asunto.recuperacion" => "Recuperación de contraseña",
    "asunto.tu_pqr_respondido" => "¡Tu :tipo_pqr #:num ha sido contestada!",
    "pqr.respuesta" => "<p>Hola :nombre</p>" .
    "<p>Tu :tipo_pqr con el número :num ha sido constestado por el equipo administrativo de :app: <br/>" .
    "------------------------------------------------------------<br/><br/>" .
    ":mensaje <br/><br/>" .
    "------------------------------------------------------------" . "<br/></p>" .
    "<p>Para más detalles, conectate desde la aplicación movil de :app.</p>",
    "app.dep.aplicacion_en_desarrollo" => "Aplicación en desarrollo",
    //****************************************************************
    //FACTURA GENERADA AUTOMATICAMETE POR SUSCRIPCION
    //****************************************************************
    "asunto.factura.generada" => "Factura generada automaticamente",
    "mensaje.factura.generada" =>
    "<p><b>Estimado cliente:</b> :nombre</p>" .
    "<p>Este correo es para informarle que se ha generado automáticamente un factura el día :fecha, debido a que su suscripción esta próxima a vencer. Para continuar utilizando nuestros servicios debe cancelar esta factura antes de su vencimiento.</p>" .
    "<p>Estos son los datos de la factura generada:<p/>" .
    "<p><b>Factura N:</b> :id_factura<br/>" .
    "<b>Monto total:</b> :total_factura<br/>" .
    "<b>Fecha de vencimiento:</b> :venc_factura</p>" .
    "<p>Para pagar esta factura debe iniciar sesión en su cuenta de usuario e ingresar a la sección de facturación. Tambien puede ingresar directamente haciendo clic <a href=':link_factura'>AQUÍ</a> o copiando y pegando el siguiente:<br/><br/> <a href=':link_factura'>:link_factura</a></p>" .
    "<p>Saludos</p>",
    //****************************************************************
    //AVISO DE CADUCIDAD DE PRUEBA
    //****************************************************************
    "asunto.aviso.caducidad.prueba"=>"Tu periodo prueba esta proxima a vencer",
    "mensaje.aviso.caducidad.prueba"=>"<p>Hola :nombre</p>".
    "<div style='text-align:center;'><h2>¡Te faltan :tiempo!</h2></div>".
    "<p>Este mensaje es para informarte que el periodo de prueba para seguir utilizando nuestros servicios esta a punto de culminar. Te invitamos a que te suscribas antes de que se agote el tiempo y asi evitar cortes en el servicio.</p><br/>".
    "<br/><div style='text-align:center;'><a href=':link' style='-moz-box-shadow:inset 0px 1px 3px 0px #91b8b3;
	-webkit-box-shadow:inset 0px 1px 3px 0px #91b8b3;
	box-shadow:inset 0px 1px 3px 0px #91b8b3;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #768d87), color-stop(1, #6c7c7c));
	background:-moz-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:-webkit-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:-o-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:-ms-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:linear-gradient(to bottom, #768d87 5%, #6c7c7c 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#768d87', endColorstr='#6c7c7c',GradientType=0);
	background-color:#768d87;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
	border:1px solid #566963;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:20px;
	font-weight:bold;
        width:200px;
	padding:11px 23px;
	text-decoration:none;
	text-shadow:0px -1px 0px #2b665e;'>SUSCRIBIRSE</a></div>",
        
    //****************************************************************
    //AVISO DE PRUEBA FINALIZADO
    //****************************************************************
    "asunto.aviso.prueba.finalizada"=>"¡Tu periodo prueba ha finalizado!",
    "mensaje.aviso.prueba.finalizada"=>"<p>Hola :nombre</p>".
    "<div style='text-align:center;'><h2>¡Tu prueba ha finalizado!</h2></div>".
    "<p>Este mensaje es para informarte que el periodo de prueba para seguir utilizando nuestros servicios ha terminado. Te invitamos a que te suscribas para que continues utilizando nuestros servicios.</p><br/>".
    "<br/><div style='text-align:center;'><a href=':link' style='-moz-box-shadow:inset 0px 1px 3px 0px #91b8b3;
	-webkit-box-shadow:inset 0px 1px 3px 0px #91b8b3;
	box-shadow:inset 0px 1px 3px 0px #91b8b3;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #768d87), color-stop(1, #6c7c7c));
	background:-moz-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:-webkit-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:-o-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:-ms-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:linear-gradient(to bottom, #768d87 5%, #6c7c7c 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#768d87', endColorstr='#6c7c7c',GradientType=0);
	background-color:#768d87;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
	border:1px solid #566963;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:20px;
	font-weight:bold;
        width:200px;
	padding:11px 23px;
	text-decoration:none;
	text-shadow:0px -1px 0px #2b665e;'>SUSCRIBIRSE</a></div>",
    //****************************************************************
    //AVISO DE CADUCIDAD DE SUSCRIPCIÓN
    //****************************************************************
    "asunto.aviso.caducidad.suscripcion"=>"Tu periodo prueba esta proxima a vencer",
    "mensaje.aviso.caducidad.suscripcion"=>"<p>Hola :nombre</p>".
    "<div style='text-align:center;'><h2>¡Tu suscripción finaliza en :tiempo!</h2></div>".
    "<p>Este mensaje es para informarte que el periodo de suscripción en el que te encuentras esta proximo a terminar. Te invitamos a que te suscribas antes de que se agote el tiempo y asi evitar cortes en el servicio.</p><br/>".
    "<br/><div style='text-align:center;'><a href=':link' style='-moz-box-shadow:inset 0px 1px 3px 0px #91b8b3;
	-webkit-box-shadow:inset 0px 1px 3px 0px #91b8b3;
	box-shadow:inset 0px 1px 3px 0px #91b8b3;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #768d87), color-stop(1, #6c7c7c));
	background:-moz-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:-webkit-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:-o-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:-ms-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:linear-gradient(to bottom, #768d87 5%, #6c7c7c 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#768d87', endColorstr='#6c7c7c',GradientType=0);
	background-color:#768d87;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
	border:1px solid #566963;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:20px;
	font-weight:bold;
        width:200px;
	padding:11px 23px;
	text-decoration:none;
	text-shadow:0px -1px 0px #2b665e;'>SUSCRIBIRSE</a></div>",
    //****************************************************************
    //AVISO DE SUSCRIPCIÓN FINALIZADA
    //****************************************************************
    "asunto.aviso.suscripcion.finalizada"=>"¡Tu suscripción ha finalizado!",
    "mensaje.aviso.suscripcion.finalizada"=>"<p>Hola :nombre</p>".
    "<div style='text-align:center;'><h2>¡Tu prueba ha finalizado!</h2></div>".
    "<p>Este mensaje es para informarte que el periodo de suscripción para seguir utilizando nuestros servicios ha terminado. Te invitamos a renovar tu suscripción para que continues utilizando nuestros servicios.</p><br/>".
    "<br/><div style='text-align:center;'><a href=':link' style='-moz-box-shadow:inset 0px 1px 3px 0px #91b8b3;
	-webkit-box-shadow:inset 0px 1px 3px 0px #91b8b3;
	box-shadow:inset 0px 1px 3px 0px #91b8b3;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #768d87), color-stop(1, #6c7c7c));
	background:-moz-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:-webkit-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:-o-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:-ms-linear-gradient(top, #768d87 5%, #6c7c7c 100%);
	background:linear-gradient(to bottom, #768d87 5%, #6c7c7c 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#768d87', endColorstr='#6c7c7c',GradientType=0);
	background-color:#768d87;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
	border:1px solid #566963;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:20px;
	font-weight:bold;
        width:200px;
	padding:11px 23px;
	text-decoration:none;
	text-shadow:0px -1px 0px #2b665e;'>SUSCRIBIRSE</a></div>",
    
); 
