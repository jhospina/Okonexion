<?php
/**
 * Template Name: Crear cuenta
 */
get_header();


if (!isset($_GET["response"]))
    $response = null;
else
    $response = $_GET["response"];
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <header class="page-header">
            <h1 class="blog-title">  <?php echo ($response == "success") ? "¡ACTIVA TU CUENTA!" : the_title(); ?></h1>
            <?php if (!empty($page_subtitle)) echo "<p class='blog-subtitle'>{$page_subtitle}</p>"; ?>
        </header>

        <?php if ($response == "success"): ?>

            <div style="padding: 20px;margin-bottom: 20px;font-size: 15pt;">
                <p>¡Tu cuenta esta casi lista! Por favor revisa tu correo electrónico <b><?php echo $_GET["email"]; ?></b>, hemos enviado un mensaje 
                    con un enlace, haz clic en ese enlace y así podremos confirmar tu correo electrónico y activar tu cuenta. Luego de esto, 
                    tu cuenta quedara activada y podrás iniciar sesión en <a href="<?php echo esc_url(get_permalink(get_page_by_title('Ingresar'))); ?>">ingresar</a>.</p>
            </div>

        <?php endif; ?>
        
         <?php if ($response == "error"): ?>

            <div style="padding: 20px;margin-bottom: 20px;font-size: 12pt;color:red;">
                <p>El correo eletrónico ingresado ya existe</p>
            </div>

        <?php endif; ?>


        <?php if ($response == null || $response=="error"): ?>

            <div class="contact-form-wrapper" style="margin-bottom:20px;">
                <form id="form_cuenta" method="post" action="http://okonexion.com/upanel/public/usuario">
                    <h2>Para crear una cuenta en Okonexion debes rellenar el siguiente formulario</h2>
                    <div class="grids">
                        <div class="msj-error" id="msj-error">Algunos campos ingresados están incorrectos por favor corrígelos:</br><ul style="margin: 5px;" id="error-description"></ul></div>
                        <p class="grid-6">
                            <label for="nombre1">Primer Nombre</label>
                            <input type="text" name="nombre1" id="nombre1" value="">
                        </p>
                        <p class="grid-6">
                            <label for="nombre2">Segundo Nombre</label>
                            <input type="text" name="nombre2" id="nombre2" value="">
                        </p>
                        <p class="grid-6">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" name="apellidos" id="apellidos" value="">
                        </p>
                        <p class="grid-6">
                            <label for="email">Correo Electrónico</label>
                            <input type="text" name="email" id="email" value="">
                        </p>
                        <p class="grid-6">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" id="password" value="">
                        </p>
                        <p class="grid-6">
                            <label for="password_rep">Repetir Contraseña</label>
                            <input type="password" name="password_rep" id="password_rep" value="">
                        </p>
                        <p class="buttons">
                            <input style="color: #fff;font-weight: bold;text-align: center;text-transform: uppercase; line-height: 1;-moz-transition: all 0.35s;-o-transition: all 0.35s;-webkit-transition: all 0.35s;transition: all 0.35s;background-color: #00b7e5;" type="button" id="submitted" class="contact-form-button" name="submitted" value="Crear Cuenta">
                        </p>
                    </div>
                    <input type="hidden" name="url" value="<?php echo ($_SERVER['HTTPS'])?"https://":"http://"; echo $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];?>"/>
                </form>
            </div>

            <script>

                jQuery("#submitted").click(function () {

                    var val = true;
                    var nombre1 = jQuery("#nombre1").val();
                    var nombre2 = jQuery("#nombre2").val();
                    var apellidos = jQuery("#apellidos").val();
                    var email = jQuery("#email").val();
                    var contra = jQuery("#password").val();
                    var contra_rep = jQuery("#password_rep").val();

                    jQuery("#msj-error").fadeOut();
                    jQuery("#error-description").html("");

                    if (!validarNombre(nombre1)) {
                        val = false;
                        jQuery("#error-description").append("<li>Nombre invalido</li>");
                    }

                    if (!validarNombre(apellidos)) {
                        val = false;
                        jQuery("#error-description").append("<li>Apellidos invalido</li>");
                    }

                    if (!validarEmail(email)) {
                        val = false;
                        jQuery("#error-description").append("<li>Correo electrónico invalido</li>");
                    }

                    if (contra.length < 6) {
                        val = false;
                        jQuery("#error-description").append("<li>Escribe una contraseña de al menos 6 caracteres</li>");
                    }

                    if (contra != contra_rep) {
                        val = false;
                        jQuery("#error-description").append("<li>Las contraseñas deben coincidir</li>");
                    }

                    if (val)
                        jQuery("#form_cuenta").submit();
                    else
                        jQuery("#msj-error").slideToggle();

                });


                //Validar nombre
                function validarNombre(name)
                {

                    if (name.length < 3) {
                        return (false);
                    }
                    var checkOK = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890.ÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú ";
                    var checkStr = name;
                    var allValid = true;
                    for (i = 0; i < checkStr.length; i++) {
                        ch = checkStr.charAt(i);
                        for (j = 0; j < checkOK.length; j++)
                            if (ch == checkOK.charAt(j))
                                break;
                        if (j == checkOK.length) {
                            allValid = false;
                            break;
                        }
                    }
                    if (!allValid) {
                        return (false);
                    }

                    return (true);

                }


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
