<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
    </head>
    <body>

        <table style="max-width: 840px;
               margin: 0 auto;
               background-color: #fff;
               font-family: Arial,Helvetica,Sans-Serif;
               font-size: 12px;
               line-height: normal;
               color: #000;">
            <tr><td>
                    <img style="width: 25%;" src="http://<?php echo $_SERVER["SERVER_NAME"]; ?>/wp-content/uploads/2015/01/logo1.png"/>
                </td></tr>
            <tr><td>
                    <div id=":vx" class="a3s" style="overflow: hidden;">
                        <p>Hola <?php echo $nombre; ?></p>
                        <p>Bienvenido a Okonexion, gracias por registrarte. Tu nueva cuenta ha sido creada satisfactoriamente, pero necesita ser activada.</br></br> Para activar tu nueva cuenta en Okonexion haz clic <a href="http://<?php echo $_SERVER["SERVER_NAME"] . "/upanel/public/activar/" . $id_usuario . "/".$codigo; ?>">aquí</a> o tambien puedes copiar y pegar el siguiente enlace</p>
                        <p>http://<?php echo $_SERVER["SERVER_NAME"] . "/upanel/public/activar/" . $id_usuario . "/".$codigo; ?></p>
                        <p>Recuerda que tus datos para iniciar sesión en Okonexion son los siguientes:</p>
                        <p>Dirección de Email: <a href="mailto:<?php echo $email; ?>" target="_blank"><?php echo $email; ?></a><br>Contraseña: <?php echo $contrasena; ?></p>
                        </div>
                </td></tr>
        </table>
    </body>
</html>