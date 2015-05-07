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
            <tr><td><h1><?php echo $nombreApp;  ?></h1></td></tr>
            <tr><td>
                    <div id=":vx" class="a3s" style="overflow: hidden;">
                    <?php echo $mensaje;  ?>   
                    </div>
                </td></tr>
                    <tr><td>
                      <?php echo trans("email.no_responder"); ?>
                </td></tr>
        </table>
    </body>
</html>