<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
 * Template Name: Login
 */
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        require "appsthergo/Appsthergo.php";

        use Appsthergo\Appsthergo as Appsthergo;

$apps = new Appsthergo("JHSJDJSI", "ES");
        $apps->printFormLogin();
        ?>

    </main>
</div>


<?php get_footer(); ?>
