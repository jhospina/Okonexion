<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <main id="main">
 *
 * @package StagFramework
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
       <meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	
	<?php stag_meta_head(); ?>
	
	<title><?php wp_title('|', true, 'right'); ?></title>

	<!-- Prefetch DNS for external resources to speed up loading time -->
	<link rel="dns-prefetch" href="//fonts.googleapis.com">

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<!--[if lt IE 9]>
	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js"></script>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->

<?php stag_head(); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="page" class="hfeed site">
	
	<header id="masthead" class="site-header site-header--fixed" role="banner">

		<div class="inside">
			<div id="navbar" class="navbar">
				<nav id="site-navigation" class="navigation main-navigation" role="navigation">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'menu_class'     => 'primary-menu',
						'menu_id'        => 'primary-menu',
						'container'      => false 
					) );
					?>
				</nav><!-- #site-navigation -->
			</div><!-- #navbar -->
			
			<a class="site-branding" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<span class="logo-container">
					<?php
					if( stag_get_option('general_text_logo') == 'on' ){ ?>
					    <h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
					<?php } elseif( stag_get_option('general_custom_logo') != '' ) { ?>
					    <img data-at2x src="<?php echo stag_get_option('general_custom_logo'); ?>" alt="<?php bloginfo( 'name' ); ?>">
					<?php } else{ ?>
					    <img data-at2x src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.png" alt="<?php bloginfo( 'name' ); ?>">
					<?php }
					?>
				</span>
			</a>
		</div>

	</header><!-- #masthead -->

	<div id="content" class="site-content">
