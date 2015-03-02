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
                        <p>Nos has pedido que enviemos nuevamente el enlace de activación de tu cuenta. Para activar tu cuenta en Okonexion haz clic <a href="http://<?php echo $_SERVER["SERVER_NAME"] . "/upanel/public/activar/" . $id_usuario . "/".$codigo; ?>">aquí</a> o tambien puedes copiar y pegar el siguiente enlace</p>
                        <p>http://<?php echo $_SERVER["SERVER_NAME"] . "/upanel/public/activar/" . $id_usuario . "/".$codigo; ?></p>
                        </div>
                </td></tr>
        </table>
    </body>
</html>