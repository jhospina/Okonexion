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

        <?php
        //FORMULARIO DE RECUPERACIÖN DE CONTRASEÑA
        if ($response == "recovery"):
            ?>

            <div class="contact-form-wrapper" style="margin-bottom:150px;">
                <form id="form" method="post" action="https://appsthergo.com/upanel/public/recovery">

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
                    else {
                        jQuery("#contra").val("");
                        jQuery("#contra2").val("");
                        jQuery("#msj-error").slideToggle();
                    }




                });

            </script>

        <?php endif; ?>


        <?php get_footer(); ?>