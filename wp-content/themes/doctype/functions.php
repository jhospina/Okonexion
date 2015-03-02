<?php
/**
 * StagFramework functions and definitions.
 *
 * @package StagFramework
 *
 */
if ( ! isset( $content_width ) )
	$content_width = 970;

/*
 * Set Retina Cookie
 */
global $is_retina;
(isset($_COOKIE['retina'])) ? $is_retina = true : $is_retina = false;

if( ! function_exists( 'stag_theme_setup' ) ) :

function stag_theme_setup(){

	/* Load translation domain ---------------------------------------------*/
	load_theme_textdomain( 'stag', get_template_directory() . '/languages' );

	$locale = get_locale();

	$locale_file = get_template_directory(). "/languages/$locale.php";
	if( is_readable( $locale_file ) ){
	  require_once( $locale_file );
	}

	/* Register Menus ------------------------------------------------------*/
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'stag' ),
	) );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style( 'framework/assets/css/editor-style.css' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// Switches default core markup for search form, comment form, and comments
	// to output valid HTML5.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	/**
	 * StagFramework specific theme support
	 *
	 * @uses Stagtools
	 * @link http://wordpress.org/plugins/stagtools/
	 */
	add_theme_support( 'stag-portfolio' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	set_post_thumbnail_size( 1170, 396, true );
	add_image_size( 'portfolio-thumbnail', 370, 370, true );
}
endif;
add_action( 'after_setup_theme', 'stag_theme_setup' );


if( ! function_exists('stag_sidebar_init') ) :
/**
* Register widget areas
* @return void
*/
function stag_sidebar_init(){
	register_sidebar(array(
		'name'          => __( 'Footer Widgets', 'stag' ),
		'id'            => 'sidebar-footer',
		'before_widget' => '<aside id="%1$s" class="widget grid-4 %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
		'description'   => __( 'Footer Widgets Area.', 'stag' )
	));

	register_sidebar(array(
		'name'          => __( 'Homepage Sections', 'stag' ),
		'id'            => 'sidebar-homepage',
		'before_widget' => '<section id="%1$s" class="section-homepage %2$s"><div class="inside">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h2 class="section-title">',
		'after_title'   => '</h2>',
		'description'   => __( 'Here you can configure the layout of the Homepage.', 'stag' )
	));
}
endif;
add_action('widgets_init', 'stag_sidebar_init');

if ( !function_exists( 'stag_wp_title' ) ) :
/**
* WordPress Title Filter
*
* @since StagFramework 1.0.1
* @param string $title Default title text for current view.
* @param string $sep Optional separator.
* @return string Filtered title.
*/
function stag_wp_title($title, $sep) {
	// if( function_exists('stag_check_third_party_seo') && !stag_check_third_party_seo() ){
		$title .= get_bloginfo( 'name' );

		$site_desc = get_bloginfo( 'description', 'display' );
		if( $site_desc && ( is_home() || is_front_page() ) ) {
			$title = "$title $sep $site_desc";
		}
	// }
	return $title;
}
endif;
add_filter('wp_title', 'stag_wp_title', 10, 2);


/**
* Enqueues scripts and styles for front end.
* @return void
*/
function stag_scripts_styles(){
	if( !is_admin() ) :

	/* Register Scripts ---------------------------------------------------*/
	wp_register_script( 'stag-custom', get_template_directory_uri().'/assets/js/jquery.custom.js', array( 'jquery' ), STAG_THEME_VERSION, true );
	wp_register_script( 'stag-plugins', get_template_directory_uri().'/assets/js/plugins.js', array( 'jquery' ), STAG_THEME_VERSION, true );
	wp_register_script( 'responsiveSlides', get_template_directory_uri().'/assets/js/jquery.responsiveSlides.js', array( 'jquery' ), '1.54', true );
	wp_register_script( 'mixitup', get_template_directory_uri().'/assets/js/jquery.mixitup.min.js', array( 'jquery' ), '1.5.4', true );


	/* Enqueue Scripts ---------------------------------------------------*/
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'stag-custom' );
	wp_enqueue_script( 'stag-plugins' );

	if( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' ); // loads the javascript required for threaded comments

	if( is_single() && 'portfolio' == get_post_type() ) wp_enqueue_script( 'responsiveSlides' );

	if( is_page_template( 'template-portfolio-filterable.php' ) ) wp_enqueue_script( 'mixitup' );

	wp_localize_script('stag-custom', 'stag', array(
		'ajaxurl'   => admin_url('admin-ajax.php'),
		'menuState' => stag_get_option('general_fixed_navigation')
	));

	/* Enqueue Styles ---------------------------------------------------*/
	wp_enqueue_style( 'stag-style', get_stylesheet_uri(), '', STAG_THEME_VERSION );
	wp_enqueue_style( 'stag-custom-style', get_template_directory_uri().'/assets/css/stag-custom-styles.php', 'stag-style', STAG_THEME_VERSION );
	wp_enqueue_style( 'doctype-fonts', stag_google_font_url(), array(), null );

	endif;
}
add_action( 'wp_enqueue_scripts', 'stag_scripts_styles' );


/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function stag_required_plugins() {
	$plugins = array(

		// This is an example of how to include a plugin from the WordPress Plugin Repository
		array(
			'name' 		=> 'StagTools',
			'slug' 		=> 'stagtools',
			'required' 	=> true,
		),

	);

	tgmpa( $plugins );
}
add_action( 'tgmpa_register', 'stag_required_plugins' );

/**
 * Replaces the excerpt "more" text by a link
 */
function new_excerpt_more( $more ) {
   global $post;
	return ' <a class="moretag" href="'. get_permalink($post->ID) . '">'. __( 'Read More&hellip;', 'stag' ) .'</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );

/**
 * Add post classes to portfolio items for filtering portfolio
 *
 * @since 1.0.1
 * @return array New post class array
 */
function doctype_portfolio_mixitup_class( $classes ) {
	$skills = get_the_terms( get_the_ID(), 'skill' );

	$skill = '';
	if ( $skills ) {
		if ( is_array( $skills ) ) {
			foreach( $skills as $ski ) {
				$skill[] .= $ski->slug;
			}
			$classes[] = implode( $skill, ' ' );
		}
	}
	$classes[] = 'mix';

	return $classes;
}
add_filter( 'post_class', 'doctype_portfolio_mixitup_class' );


/**
 * Add body class for sticky navigation
 *
 * @since 1.0.2
 * @return array New body class array
 */
function sticky_nav_body_class( $classes ) {
	$nav = stag_get_option('general_fixed_navigation');

	if( isset($nav) && $nav == "on" ) {
		$classes[] = 'sticky-nav';
	}

	return $classes;
}
add_filter( 'body_class', 'sticky_nav_body_class' );

function doctype_stag_custom_sidebar_widget_args() {
	return array(
		'before_widget' => '<section id="%1$s" class="section-homepage %2$s"><div class="inside">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h2 class="section-title">',
		'after_title'   => '</h2>'
	);
}
add_filter( 'stag_custom_sidebars_widget_args', 'doctype_stag_custom_sidebar_widget_args' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

include_once ( get_template_directory() . '/framework/stag-framework.php' );
include_once ( get_template_directory() . '/inc/init.php' );
