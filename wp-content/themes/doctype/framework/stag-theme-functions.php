<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function stag_add_option( $name, $value ){
	stag_update_option( $name, $value );
}

function stag_update_option( $name, $value ){
	$stag_values = get_option( 'stag_framework_values' );
	$stag_values[$name] = $value;
	update_option( 'stag_framework_values', $stag_values );
}

function stag_remove_option( $name ){
	$stag_values = get_option( 'stag_framework_values' );
	unset($stag_values[$name]);
	update_option( 'stag_framework_values', $stag_values );
}

function stag_get_option( $name ){
	$stag_values = get_option( 'stag_framework_values' );
	if( @array_key_exists( $name, $stag_values ) ) return stripslashes( $stag_values[$name] );
	return false;
}

function stag_is_theme_activated(){
	global $pagenow;
	if ( is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" )
		return true;
	return false;
}

function stag_add_version_meta() {
	echo '<meta name="generator" content="' . STAG_THEME_NAME . ' ' . STAG_THEME_VERSION . '">'."\n\t";
	echo '<meta name="generator" content="StagFramework ' . STAG_FRAMEWORK_VERSION . '">'."\n";
}
add_action( 'stag_meta_head', 'stag_add_version_meta' );

/*---------------------------------------------------------*/
/* Filters that allow shortcodes in Text widgets
/*---------------------------------------------------------*/
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );

/*---------------------------------------------------------*/
/* Remove generator for security
/*---------------------------------------------------------*/
remove_action( 'wp_head', 'wp_generator' );

add_filter( 'body_class', 'stag_body_class' );
function stag_body_class( $classes ) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

	if($is_lynx) $classes[] = 'lynx';
	elseif($is_gecko) $classes[] = 'gecko';
	elseif($is_opera) $classes[] = 'opera';
	elseif($is_NS4) $classes[] = 'ns4';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) {
		$browser = $_SERVER['HTTP_USER_AGENT'];
		$browser = substr( "$browser", 25, 8);
		if ($browser == "MSIE 7.0"  ) {
			$classes[] = 'ie7';
			$classes[] = 'ie';
		} elseif ($browser == "MSIE 6.0" ) {
			$classes[] = 'ie6';
			$classes[] = 'ie';
		} elseif ($browser == "MSIE 8.0" ) {
			$classes[] = 'ie8';
			$classes[] = 'ie';
		} elseif ($browser == "MSIE 9.0" ) {
			$classes[] = 'ie9';
			$classes[] = 'ie';
		} else {
			$classes[] = 'ie';
		}
	}
	else $classes[] = 'unknown';
	
	if( $is_iphone ) $classes[] = 'iphone';

	return $classes;
}
