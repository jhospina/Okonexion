<?php
/**
 * Template Name: Login
 */
if (!isset($_GET["response"]))
    $response = null;
else
    $response = $_GET["response"];

//Inicio de sesion automatico si el usuario ha recordado sus credenciales de acceso
if ($response == null)
    header("Location: http://" . $_SERVER["SERVER_NAME"] . "/upanel/public/");


get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php if ($response == "confirmation"): ?>

            <header class="page-header">
                <h1 class="blog-title">¡MUCHAS GRACIAS!</h1>
                <h2>Tu cuenta ha sido activada</h2>
            </header>

        <?php endif; ?>


        <?php if ($response == "recovery"): ?>

            <header class="page-header">
                <h1 class="blog-title">¡TU CONTRASEÑA HA SIDO REESTRABLECIDA!</h1>
                <h2>Ya puedes iniciar sesión con tu nueva contraseña</h2>
            </header>

        <?php endif; ?>


        <?php if ($response == "fail-password"): ?>
            <div style="color: red;padding: 10px;border: 1px red dashed;">
                <p style="font-size: 15pt;">Lo sentimos, hubo un error al tratar de reestablecer tu contraseña, inténtalo de nuevo más tarde.</p>
            </div>


        <?php endif; ?>


        <?php if ($response == "fail"): ?>
            <div style="color: red;padding: 10px;border: 1px red dashed;">
                <p style="font-size: 15pt;">El correo electrónico o la contraseña ingresada son incorrectas</p>
            </div>

        <?php endif; ?>


        <?php if ($response == "fail-confirmation"): ?>
            <div style="color: red;padding: 10px;border: 1px red dashed;">
                <p style="font-size: 15pt;">Lo sentimos, no puedes ingresar a esta cuenta porque no ha sido activada. 
                    Para activarla debes confirmar tu correo electrónico ingresando en el enlace que te enviamos a tu correo <b><?php echo $_GET["email"]; ?></b> al momento del registro.<br/>
                    <a href="http://<?php echo $_SERVER["SERVER_NAME"] ?>/upanel/public/correo/<?php echo $_GET["user"]; ?>/activacion">¿Reenviar enlace de activación?</p>
            </div>

        <?php endif; ?>


        <?php if ($response == "send-confirmation"): ?>
            <div style="color: white;
                 padding: 8px;
                 border: 1px rgb(0, 94, 0) solid;
                 background: rgba(0, 128, 0, 0.8);
                 -webkit-border-radius: 5px;
                 -moz-border-radius: 5px;
                 border-radius: 5px;">
                <p style="font-size: 15pt;">Hemos enviado un mensaje a tu correo electrónico <b><?php echo $_GET["email"]; ?></b> con un enlace para activar tu cuenta.</br> ¡Revisalo por favor!</p>
            </div>

        <?php endif; ?>

        <?php if ($response == "logout"): ?>
            <div style="color: white;
                 padding: 8px;
                 border: 1px rgb(0, 94, 0) solid;
                 background: rgba(0, 128, 0, 0.8);
                 -webkit-border-radius: 5px;
                 -moz-border-radius: 5px;
                 border-radius: 5px;">
                <p style="font-size: 15pt;">¡Ha cerrado sesión con exito!</p>
            </div>

        <?php endif; ?>

        <div class="contact-form-wrapper" style="margin-bottom:150px;">
            <form id="form_cuenta" method="post" action="http://okonexion.com/upanel/public/login">

                <h2>Inicia sesión con tu cuenta</h2>
                <div class="grids" style="max-width: 500px;margin: auto;">
                    <p class="grid-12">
                        <label for="email">Correo Electrónico</label>
                        <input type="text" name="email" id="email" value="<?php echo (isset($_GET["email"])) ? $_GET["email"] : ""; ?>">
                    </p>
                    <p class="grid-12">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" name="contrasena" id="contrasena" value="">
                    </p>
                    <p class="grid-6">
                        <input type="checkbox" name="recordarme"/> Recordarme
                    </p>
                    <p class="grid-6" style="text-align: right;">
                        <a href="<?php echo esc_url(get_permalink(get_page_by_title('Recuperar contraseña'))); ?>">¿Olvidaste tu contraseña?</a>
                    </p>
                         <input type="hidden" name="url" value="<?php echo ($_SERVER['HTTPS'])?"https://":"http://"; echo $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];?>"/>
                    <p class="buttons">
                        <input type="submit" id="submitted" class="contact-form-button" name="submitted" value="Iniciar Sesión">
                    </p>
                </div>
            </form>
        </div>



        <?php get_footer(); ?>
