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
                        <p>Hemos recibido una solicitud para restablecer tu contraseña para ingresar en el panel de usuarios de Okonexion. Si no has solicitado esto por favor ignora este mensaje.</p>
                            <p>Para reestablecer tu contraseña ingresa en el siguiente enlace o copialo y pegalo en la URL del navegador:</br>
                        <p>http://<?php echo $_SERVER["SERVER_NAME"] . "/upanel/public/recovery/" . $id_usuario . "/".$codigo; ?></p>
                        </div>
                    </div>
                </td></tr>
        </table>
    </body>
</html>