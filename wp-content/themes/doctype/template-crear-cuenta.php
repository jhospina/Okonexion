<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


/**
 * Template Name: Crear cuenta
 */
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php
        require "appsthergo/Appsthergo.php";

        use Appsthergo\Appsthergo as Appsthergo;

$apps = new Appsthergo("JHSJDJSI", "ES");
        $apps->printFormCreateUser(get_the_title(), esc_url(get_permalink(get_page_by_title('Ingresar'))));
        ?>

    </main>
</div>

<?php get_footer(); ?>
