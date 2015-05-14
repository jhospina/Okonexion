<?php
/**
 * Template Name: Recovery
 */
get_header();
if (!isset($_GET["response"]))
    $response = null;
else
    $response = $_GET["response"];
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php if ($response == "send"): ?>

            <header class="page-header">
                <h1 class="blog-title">¡SOLICITUD ENVIADA!</h1>
            </header>
            <div style="padding: 20px;margin-bottom: 20px;font-size: 15pt;">
                <p>Hemos enviado un mensaje a tu correo electrónico <b><?php echo $_GET["email"]; ?></b> con un enlace para que puedas reestablecer tu contraseña. ¡Revísalo cuanto antes!</p>
            </div>
        <?php endif; ?>



        <?php if ($response == "fail"): ?>

            <div style="color: red;padding: 10px;border: 1px red dashed;"><p>El correo electrónico <b><?php echo $_GET["email"]; ?></b> ingresado no pertenece a ninguna cuenta en Webox.</p></div>

        <?php endif; ?>



        <?php if ($response == "inactive"): ?>

            <div style="color: orangered;padding: 10px;border: 1px orangered dashed;">
                <div style="text-align: center;font-size: 17pt;"><b>¡ATENCIÓN!</b></div>
                <p>No podemos ayudarte a reestablecer la contraseña de esta cuenta 
                    asociada al correo electrónico <b><?php echo $_GET["email"]; ?></b> porque esta cuenta no ha sido activada. Para activarla se debe 
                    confirmar el correo electrónico con el que fue registrado. Si eres el dueño de esta cuenta te invitamos a revisar tu correo.    <a href="http://<?php echo $_SERVER["SERVER_NAME"] ?>/upanel/public/correo/<?php echo $_GET["user"]; ?>/activacion">¿Reenviar enlace de activación?</p> </p></div>

        <?php endif; ?>


        <?php
        //FORMULARIO DE RECUPERACIÖN DE CONTRASEÑA
        if ($response == "recovery"):
            ?>

            <div class="contact-form-wrapper" style="margin-bottom:150px;">
                <form id="form" method="post" action="http://okonexion.com/upanel/public/recovery">

                    <h2>¡YA PUEDES REESTABLECER TU CONTRASEÑA!</h2>
                    <div class="msj-error" id="msj-error" style="display:none;"></div>
                    <div class="grids" style="max-width: 500px;margin: auto;">
                        <p class="grid-12">
                            <label for="contra">Ingresa una contraseña:</label>
                            <input type="password" name="contra" id="contra" value="">
                        </p>
                        <p class="grid-12">
                            <label for="contra2">Repite la contraseña:</label>
                            <input type="password" name="contra2" id="contra2" value="">
                        </p>
                        <input type="hidden" name="user" value="<?php echo $_GET["user"]; ?>">
                        <input type="hidden" name="code" value="<?php echo $_GET["code"]; ?>">
                        <p class="buttons">
                            <input style="color: #fff;font-weight: bold;text-align: center;text-transform: uppercase; line-height: 1;-moz-transition: all 0.35s;-o-transition: all 0.35s;-webkit-transition: all 0.35s;transition: all 0.35s;background-color: #00b7e5;" type="button" id="submitted" class="contact-form-button" name="submitted" value="REESTABLECER CONTRASEÑA">
                        </p>
                    </div>
                </form>
            </div>

            <script>

                jQuery("#submitted").click(function () {
                    var contra = jQuery("#contra").val();
                    var contra2 = jQuery("#contra2").val();
                    var val = true;
                    
                    jQuery("#msj-error").hide();
                    jQuery("#msj-error").html("");

                    if (contra.length >= 6) {
                        if (contra != contra2) {
                            val = false;
                            jQuery("#msj-error").html("<li>Las contraseñas no coinciden</li>");
                        }
                    } else {
                        val = false;
                        jQuery("#msj-error").html("La contraseña debe contener al menos 6 caracteres");
                    }

                    if (val)
                        jQuery("#form").submit();
                    else{
                        jQuery("#contra").val("");
                        jQuery("#contra2").val("");
                        jQuery("#msj-error").slideToggle();
                    }




                });

            </script>

        <?php endif; ?>

<?php if ($response != "send" && $response != "recovery"): ?>

            <div class="contact-form-wrapper" style="margin-bottom:150px;">
                <form id="form" method="post" action="http://okonexion.com/upanel/public/recuperar-contrasena">

                    <h2>¿Olvidastes tu contraseña?</h2>
                    <div class="msj-error" id="msj-error" style="display:none;"></div>
                    <div class="grids" style="max-width: 500px;margin: auto;">
                        <p class="grid-12">
                            <label for="email">Ingresa tu correo electrónico:</label>
                            <input type="text" name="email" id="email" value="">
                                 <input type="hidden" name="url" value="<?php echo ($_SERVER['HTTPS'])?"https://":"http://"; echo $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];?>"/>
               
                        </p>
                        <p class="buttons">
                            <input style="color: #fff;font-weight: bold;text-align: center;text-transform: uppercase; line-height: 1;-moz-transition: all 0.35s;-o-transition: all 0.35s;-webkit-transition: all 0.35s;transition: all 0.35s;background-color: #00b7e5;" type="button" id="submitted" class="contact-form-button" name="submitted" value="Enviar Solicitud">
                        </p>
                    </div>
                </form>
            </div>

            <script>

                jQuery("#submitted").click(function () {
                    var email = jQuery("#email").val();
                    var val = true;

                    if (!validarEmail(email)) {
                        jQuery("#msj-error").html("El correo electrónico ingresado es invalido");
                        jQuery("#msj-error").slideToggle();
                        val = false;
                    }

                    if (val)
                        jQuery("#form").submit();



                });

                function validarEmail(email) {
                    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    if (expr.test(email))
                        return true;
                    else
                        return false;
                }
            </script>


        <?php endif; ?>

<?php get_footer(); ?>