<?php

return array(
    "hola" => "Hola :nombre",
    "no_responder" => "¡NO RESPONDAS ESTE CORREO!",
    "activacion.msj_01" => "Nos has pedido que enviemos nuevamente el enlace de activación de tu cuenta. Para activar tu cuenta en Okonexion haz clic <a href='http://" . $_SERVER["SERVER_NAME"] . "/upanel/public/activar/:id_usuario/:codigo'>aquí</a> o tambien puedes copiar y pegar el siguiente enlace",
    "bienvenida.titular" => "¡BIENVENIDO A OKONEXION!",
    "confirmacion.msj_01" => "Bienvenido a Okonexion, gracias por registrarte. Tu nueva cuenta ha sido creada satisfactoriamente, pero necesita ser activada.</br></br> Para activar tu nueva cuenta en Okonexion haz clic <a href='http://" . $_SERVER["SERVER_NAME"] . "/upanel/public/activar/:id_usuario/:codigo'>aquí</a> o tambien puedes copiar y pegar el siguiente enlace",
    "confirmacion.msj_02" => "Recuerda que tus datos para iniciar sesión en Okonexion son los siguientes:",
    "confirmacion.msj_03" => "Dirección de Email: :email<br>Contraseña: :contrasena",
    "recuperacion.msj_01" => "Hemos recibido una solicitud para restablecer tu contraseña para ingresar en el panel de usuarios de Okonexion. Si no has solicitado esto por favor ignora este mensaje.",
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
    "<p><b>Hora de inicio: </b>:fecha_inicio</p>".
    "<p><b>Hora de finalización: </b>:fecha_finalizacion</p></br>".
    "<p>Ingresa a :link para descargarlo.</p>".
    "<p>:plataformas</p>",
    "asunto.confirmacion" => "Confirmación de correo electrónico",
    "asunto.activacion" => "Activación de tu cuenta",
    "asunto.bienvenida" => "¡Bienvenido a Okonexion!",
    "asunto.recuperacion" => "Recuperación de contraseña",
    "asunto.tu_pqr_respondido" => "¡Tu :tipo_pqr #:num ha sido contestada!",
    "pqr.respuesta" => "<p>Hola :nombre</p>" .
    "<p>Tu :tipo_pqr con el número :num ha sido constestado por el equipo administrativo de :app: <br/>" .
    "------------------------------------------------------------<br/><br/>" .
    ":mensaje <br/><br/>" .
    "------------------------------------------------------------" . "<br/></p>" .
    "<p>Para más detalles, conectate desde la aplicación movil de :app.</p>",
    "app.dep.aplicacion_en_desarrollo"
);
