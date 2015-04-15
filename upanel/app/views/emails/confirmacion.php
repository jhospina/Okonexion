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
                        <p>Hola <?php echo trans("email.hola",array("nombre"=>$nombre)) ?></p>
                        <p><?php echo trans("email.confirmacion.msj_01",array("id_usuario"=>$id_usuario,"codigo"=>$codigo)); ?></p>
                        <p>http://<?php echo $_SERVER["SERVER_NAME"] . "/upanel/public/activar/" . $id_usuario . "/".$codigo; ?></p>
                        <p><?php echo trans("email.confirmacion.msj_02"); ?></p>
                        <p><?php echo trans("email.confirmacion.msj_03",array("email"=>$email,"contrasena"=>$contrasena)); ?></p>
                        </div>
                </td></tr>
        </table>
    </body>
</html>